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
        Schema::create('user_log_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('ip_address')->nullable();
            $table->dateTime('log_in_time')->nullable();
            $table->dateTime('log_out_time')->nullable();
            $table->dateTime('last_active_time')->nullable();
            $table->integer('log_out_status')->default(0);
            $table->integer('force_status')->default(0);
            $table->integer('delete_status')->default(0);
            $table->integer('status')->default(1);
            $table->integer('edit_status')->default(0);
            $table->integer('add_by')->nullable();
            $table->integer('edit_by')->nullable();
            $table->integer('delete_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_log_histories');
    }
};
