<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organization_id');
            $table->string('customer_po_number');
            $table->string('project_name');
            $table->string('title');
            $table->string('po_validity');
            $table->text('customer_payment_terms')->nullable();
            $table->text('customer_terms_condition')->nullable();
            $table->text('remarks')->nullable();
            $table->string('is_approved_production')->nullable();
            $table->decimal('grand_total_amount', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};
