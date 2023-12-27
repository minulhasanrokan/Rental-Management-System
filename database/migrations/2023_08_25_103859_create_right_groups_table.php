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
        Schema::create('right_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('action_name')->nullable();
            $table->text('details')->nullable();
            $table->string('icon')->nullable();
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
        Schema::dropIfExists('right_groups');
    }
};
