<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('business_details_id')->nullable();
            $table->unsignedBigInteger('part_item_id')->nullable();
            $table->string('product_description', 500)->nullable();
            $table->decimal('required_quantity', 12, 3)->default(0);
            $table->decimal('available_quantity', 12, 3)->default(0);
            $table->decimal('shortage_quantity', 12, 3)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('rate', 15, 3)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('requisition_id')->references('id')->on('requisition')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};
