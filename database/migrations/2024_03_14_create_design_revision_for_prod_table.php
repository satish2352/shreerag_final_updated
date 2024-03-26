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
        Schema::create('design_revision_for_prod', function (Blueprint $table) {
            $table->bigIncrements();
            $table->bigIncrements('business_id');
            $table->bigIncrements('design_id');
            $table->bigIncrements('production_id');
            $table->string('reject_reason_prod')->nullable();
            $table->string('remark_by_design');
            $table->string('design_image')->nullable();
            $table->string('bom_image')->nullable();
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
        Schema::dropIfExists('design_revision_for_prod');
    }
};
