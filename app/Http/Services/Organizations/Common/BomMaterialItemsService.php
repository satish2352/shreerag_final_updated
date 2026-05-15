<?php

namespace App\Http\Services\Organizations\Common;

use App\Http\Repository\Organizations\Common\BomMaterialItemsRepository;

class BomMaterialItemsService
{
    protected BomMaterialItemsRepository $repo;

    public function __construct()
    {
        $this->repo = new BomMaterialItemsRepository();
    }

    public function getItems(int $businessDetailsId, int $designId): array
    {
        return $this->repo->getItems($businessDetailsId, $designId);
    }

    /**
     * Return context header data for the BOM modal.
     * Includes MATERIAL INDENT header fields + estimation amount.
     */
    public function getContext(int $businessDetailsId): array
    {
        return $this->repo->getContext($businessDetailsId);
    }

    public function saveItems(
        int $businessId,
        int $businessDetailsId,
        int $designId,
        ?int $estimationId,
        int $userId,
        int $deptRoleId,
        array $items,
        array $deletedIds
    ): array {
        return $this->repo->saveItems(
            $businessId,
            $businessDetailsId,
            $designId,
            $estimationId,
            $userId,
            $deptRoleId,
            $items,
            $deletedIds
        );
    }

    /**
     * Save BOM items for the estimation department AND auto-trigger exceed flow
     * when BOM Final Total exceeds business_details.total_amount.
     *
     * Returns extended response: items, bom_final_total, business_limit,
     * exceed_triggered, message.
     *
     * T-2026-046: Added $trolleyQty so the repository can compute the correct
     * unit-aware BOM total (BomTotalCalculator) instead of naive rate × quantity.
     */
    public function saveItemsWithExceedCheck(
        int $businessId,
        int $businessDetailsId,
        int $designId,
        ?int $estimationId,
        int $userId,
        int $deptRoleId,
        array $items,
        array $deletedIds,
        ?string $exceedReason = null,
        int $trolleyQty = 1
    ): array {
        return $this->repo->saveItemsWithExceedCheck(
            $businessId,
            $businessDetailsId,
            $designId,
            $estimationId,
            $userId,
            $deptRoleId,
            $items,
            $deletedIds,
            $exceedReason,
            $trolleyQty
        );
    }
}
