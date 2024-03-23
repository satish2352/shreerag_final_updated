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
        Schema::create('purchase_orders_old', function (Blueprint $table) {
                $table->id();
                $table->string('client_name')->nullable();
                $table->string('tax')->nullable();
                $table->string('payment_terms')->nullable();
                $table->string('gst_number')->nullable();
                $table->string('email')->nullable();
                $table->string('invoice_date')->nullable();
                $table->jsonb('items')->nullable();
                $table->string('note')->nullable();
                $table->string('discount')->nullable();
                $table->string('total')->nullable();
                $table->string('status')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('purchase_orders_old');
    }
};