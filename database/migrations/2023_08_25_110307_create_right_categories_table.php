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
        Schema::create('right_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->string('c_name')->nullable();
            $table->string('c_title')->nullable();
            $table->string('c_action_name')->nullable();
            $table->text('c_details')->nullable();
            $table->string('c_icon')->nullable();
            $table->integer('short_order');
            $table->integer('status')->default(1);
            $table->integer('delete_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('right_categories');
    }
};
