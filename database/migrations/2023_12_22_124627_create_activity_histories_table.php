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
        Schema::create('activity_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('right_id')->nullable();
            $table->string('table_name')->nullable();
            $table->integer('table_id')->nullable();
            $table->string('activity')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('search_data')->nullable();
            $table->integer('status')->default(1);
            $table->integer('edit_status')->default(0);
            $table->integer('delete_status')->default(0);
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
        Schema::dropIfExists('activity_histories');
    }
};
