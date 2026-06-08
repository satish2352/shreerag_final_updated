<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'is_po_closed')) {
                $table->boolean('is_po_closed')->default(false)->after('is_active');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!$this->hasIndex('purchase_orders', 'idx_po_is_po_closed')) {
                $table->index('is_po_closed', 'idx_po_is_po_closed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if ($this->hasIndex('purchase_orders', 'idx_po_is_po_closed')) {
                $table->dropIndex('idx_po_is_po_closed');
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'is_po_closed')) {
                $table->dropColumn('is_po_closed');
            }
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName])) > 0;
    }
};
