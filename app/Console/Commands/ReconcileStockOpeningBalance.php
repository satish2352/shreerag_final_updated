<?php

namespace App\Console\Commands;

use App\Http\Repository\Organizations\Report\ReportRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * T-2026-060 — Stock Daily Report reconciliation / backfill.
 *
 * The Stock Daily Report's ledger (tbl_part_item.opening_stock + GRN receipts
 * + production issues + delivery challan issues + returnable challan issues +
 * tbl_item_stock_history manual stock movements) will not reconcile with
 * tbl_item_stock.quantity for any part whose stock was adjusted through a
 * path this ledger cannot see or reconstruct (most commonly: manual "Add
 * Stock" / "Edit Stock" entries recorded before the T-2026-060 fix to
 * InventoryRepository, when tbl_item_stock_history either didn't get written
 * at all or was written with the wrong part_item_id).
 *
 * This command does NOT try to guess or fudge which historical event caused
 * the gap — it computes the gap for every part, and (unless --dry-run) seeds
 * exactly ONE explicit, clearly-labelled "Stock Adjustment (opening
 * reconciliation)" tbl_item_stock_history entry per part that needs it, so
 * the report's computed balance reconciles with tbl_item_stock.quantity going
 * forward. It is idempotent — a part that already has an active
 * opening_reconciliation entry is always skipped, so running this command
 * multiple times never seeds duplicate adjustments.
 *
 * Usage:
 *   php artisan stock:reconcile-opening-balance                # all parts
 *   php artisan stock:reconcile-opening-balance --dry-run       # report only, no writes
 *   php artisan stock:reconcile-opening-balance --part=101      # single part
 */
class ReconcileStockOpeningBalance extends Command
{
    protected $signature = 'stock:reconcile-opening-balance
        {--dry-run : Report what would be adjusted without writing anything}
        {--part= : Limit to a single part item id}';

    protected $description = 'Seed one-time "Stock Adjustment (opening reconciliation)" ledger entries so the Stock Daily Report reconciles with tbl_item_stock.quantity';

    /** Amounts smaller than this are treated as float noise, not a real discrepancy. */
    private const EPSILON = 0.001;

    public function handle(ReportRepository $reportRepository): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $onlyPartId = $this->option('part') ? (int) $this->option('part') : null;

        $partIds = $reportRepository->getReconcilablePartItemIds($onlyPartId);

        if ($partIds->isEmpty()) {
            $this->warn('No matching active part items found.');
            return self::SUCCESS;
        }

        $this->info(sprintf(
            '%sReconciling %d part item(s)...',
            $dryRun ? '[DRY RUN] ' : '',
            $partIds->count()
        ));

        $rows = [];
        $adjustedCount = 0;
        $skippedAlreadyReconciled = 0;
        $skippedInSync = 0;
        $skippedNoStockRecord = 0;

        foreach ($partIds as $partId) {
            $result = $reportRepository->computeFullStockLedgerBalance($partId);

            if ($result === null) {
                continue;
            }

            if ($result['actual_quantity'] === null) {
                $skippedNoStockRecord++;
                $rows[] = [
                    $result['part_id'],
                    $this->truncate($result['description']),
                    'n/a',
                    'n/a',
                    'n/a',
                    'skipped: no tbl_item_stock record',
                ];
                continue;
            }

            if ($reportRepository->hasActiveOpeningReconciliation($partId)) {
                $skippedAlreadyReconciled++;
                $rows[] = [
                    $result['part_id'],
                    $this->truncate($result['description']),
                    number_format($result['computed_balance'], 3),
                    number_format($result['actual_quantity'], 3),
                    '-',
                    'skipped: already reconciled',
                ];
                continue;
            }

            $delta = round($result['actual_quantity'] - $result['computed_balance'], 3);

            if (abs($delta) < self::EPSILON) {
                $skippedInSync++;
                continue; // in sync — no console noise for the common case
            }

            $rows[] = [
                $result['part_id'],
                $this->truncate($result['description']),
                number_format($result['computed_balance'], 3),
                number_format($result['actual_quantity'], 3),
                number_format($delta, 3),
                $dryRun ? 'would adjust' : 'adjusted',
            ];

            if (!$dryRun) {
                $remark = sprintf(
                    'Stock Adjustment (opening reconciliation) — computed ledger total %s did not match '
                        . 'tbl_item_stock.quantity %s as of %s. Unrecoverable historical manual stock '
                        . 'movements (e.g. pre-fix "Add Stock" / "Edit Stock" entries) are assumed to '
                        . 'account for the difference. Seeded by stock:reconcile-opening-balance.',
                    number_format($result['computed_balance'], 3),
                    number_format($result['actual_quantity'], 3),
                    now()->toDateTimeString()
                );

                $reportRepository->insertOpeningReconciliationEntry(
                    $partId,
                    $delta,
                    $result['actual_quantity'],
                    $remark
                );

                Log::info('stock:reconcile-opening-balance adjusted part item', [
                    'part_item_id'     => $partId,
                    'description'      => $result['description'],
                    'computed_balance' => $result['computed_balance'],
                    'actual_quantity'  => $result['actual_quantity'],
                    'delta'            => $delta,
                ]);
            }

            $adjustedCount++;
        }

        if (!empty($rows)) {
            $this->table(
                ['Part ID', 'Description', 'Computed Balance', 'Actual Quantity (tbl_item_stock)', 'Delta', 'Action'],
                $rows
            );
        }

        $this->newLine();
        $this->info(sprintf(
            '%s%d adjusted, %d already in sync, %d already reconciled previously, %d skipped (no stock record).',
            $dryRun ? '[DRY RUN] ' : '',
            $adjustedCount,
            $skippedInSync,
            $skippedAlreadyReconciled,
            $skippedNoStockRecord
        ));

        if ($dryRun && $adjustedCount > 0) {
            $this->comment('Re-run without --dry-run to apply these adjustments.');
        }

        return self::SUCCESS;
    }

    private function truncate(?string $value, int $length = 60): string
    {
        $value = (string) $value;
        return mb_strlen($value) > $length ? mb_substr($value, 0, $length - 3) . '...' : $value;
    }
}
