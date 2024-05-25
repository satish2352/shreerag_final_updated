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
        Schema::create('tbl_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id'); 
            $table->unsignedBigInteger('employee_id');
            $table->string('leave_start_date');
            $table->string('leave_end_date');
            $table->string('leave_day');
            $table->string('leave_type');
            $table->text('reason');
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('tbl_leaves');
    }
};
