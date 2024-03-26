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
        Schema::create('business_application_processes', function (Blueprint $table) {
            $table->bigIncrements();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_status_id');
            $table->unsignedBigInteger('design_id');
            $table->unsignedBigInteger('design_status_id');
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('production_status_id');
            $table->unsignedBigInteger('store_material_sent_date');
            $table->unsignedBigInteger('store_status_id');
            $table->boolean('is_approve')->default(false);
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
        Schema::dropIfExists('business_application_processes');
    }
};
