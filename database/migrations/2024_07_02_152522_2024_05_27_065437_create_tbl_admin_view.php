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
        Schema::create('admin_view', function (Blueprint $table) {
            $table->id(); // Primary key column
            $table->unsignedBigInteger('business_id');
            $table->string('current_department');
            $table->boolean('is_view')->default(0); // 0 or 1 for false/true
            $table->timestamps(); // includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
