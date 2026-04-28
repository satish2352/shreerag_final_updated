<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // business_application_processes — heavily filtered by every department
        Schema::table('business_application_processes', function (Blueprint $table) {
            foreach ([
                'idx_bap_off_canvas_status'    => 'off_canvas_status',
                'idx_bap_dispatch_status'      => 'dispatch_status_id',
                'idx_bap_logistics_status'     => 'logistics_status_id',
                'idx_bap_design_status'        => 'design_status_id',
                'idx_bap_production_status'    => 'production_status_id',
                'idx_bap_store_status'         => 'store_status_id',
                'idx_bap_quality_status'       => 'quality_status_id',
                'idx_bap_business_status'      => 'business_status_id',
            ] as $name => $col) {
                if (!$this->has($table->getTable(), $name)) {
                    $table->index($col, $name);
                }
            }
        });

        // purchase_orders — filtered by quality_status_id in Quality dashboard
        Schema::table('purchase_orders', function (Blueprint $table) {
            foreach ([
                'idx_po_quality_status' => 'quality_status_id',
                'idx_po_security_status'=> 'security_status_id',
            ] as $name => $col) {
                if (Schema::hasColumn('purchase_orders', $col) && !$this->has($table->getTable(), $name)) {
                    $table->index($col, $name);
                }
            }
        });

        // tbl_customer_product_quantity_tracking — filtered by quantity_tracking_status
        Schema::table('tbl_customer_product_quantity_tracking', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_cqt_tracking_status')) {
                $table->index('quantity_tracking_status', 'idx_cqt_tracking_status');
            }
            if (!$this->has($table->getTable(), 'idx_cqt_logistics_status')) {
                $table->index('logistics_list_status', 'idx_cqt_logistics_status');
            }
        });

        // tbl_notification_status — filtered by off_canvas_status + many view flags
        Schema::table('tbl_notification_status', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_notif_off_canvas')) {
                $table->index('off_canvas_status', 'idx_notif_off_canvas');
            }
        });

        // admin_view — already has composite (off_canvas_status, is_view) from prior migration
        // Adding is_deleted separately for the 3-column filter pattern
        Schema::table('admin_view', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_av_status_view_del')) {
                $table->index(['off_canvas_status', 'is_view', 'is_deleted'], 'idx_av_status_view_del');
            }
        });

        // gatepass — filtered by po_tracking_status in Quality/Security dashboards
        Schema::table('gatepass', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_gatepass_tracking_status')) {
                $table->index('po_tracking_status', 'idx_gatepass_tracking_status');
            }
        });

        // login_history — filtered by user_id and created_at for cleanup + list
        Schema::table('login_history', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_lh_user_id')) {
                $table->index('user_id', 'idx_lh_user_id');
            }
            if (!$this->has($table->getTable(), 'idx_lh_created_at')) {
                $table->index('created_at', 'idx_lh_created_at');
            }
        });

        // tbl_item_stock — filtered by part_item_id + quantity > 0
        Schema::table('tbl_item_stock', function (Blueprint $table) {
            if (!$this->has($table->getTable(), 'idx_stock_part_item_qty')) {
                $table->index(['part_item_id', 'quantity'], 'idx_stock_part_item_qty');
            }
        });
    }

    public function down(): void
    {
        $drops = [
            'business_application_processes' => [
                'idx_bap_off_canvas_status', 'idx_bap_dispatch_status', 'idx_bap_logistics_status',
                'idx_bap_design_status', 'idx_bap_production_status', 'idx_bap_store_status',
                'idx_bap_quality_status', 'idx_bap_business_status',
            ],
            'purchase_orders'                        => ['idx_po_quality_status', 'idx_po_security_status'],
            'tbl_customer_product_quantity_tracking' => ['idx_cqt_tracking_status', 'idx_cqt_logistics_status'],
            'tbl_notification_status'                => ['idx_notif_off_canvas'],
            'admin_view'                             => ['idx_av_status_view_del'],
            'gatepass'                               => ['idx_gatepass_tracking_status'],
            'login_history'                          => ['idx_lh_user_id', 'idx_lh_created_at'],
            'tbl_item_stock'                         => ['idx_stock_part_item_qty'],
        ];

        foreach ($drops as $table => $indexes) {
            Schema::table($table, function (Blueprint $blueprint) use ($indexes, $table) {
                foreach ($indexes as $idx) {
                    if ($this->has($table, $idx)) {
                        $blueprint->dropIndex($idx);
                    }
                }
            });
        }
    }

    private function has(string $table, string $indexName): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName])) > 0;
    }
};
