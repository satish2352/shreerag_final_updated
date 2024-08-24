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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->string('type'); // Type of the notification
            $table->text('data'); // Stores notification data as JSON
            $table->timestamp('read_at')->nullable(); // Timestamp for when the notification was read
            $table->morphs('notifiable'); // Adds notifiable_id and notifiable_type columns automatically
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
