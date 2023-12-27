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
        Schema::create('right_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cat_id');
            $table->string('r_name')->nullable();
            $table->string('r_title')->nullable();
            $table->string('r_action_name')->nullable();
            $table->string('r_route_name')->nullable();
            $table->text('r_details')->nullable();
            $table->string('r_icon')->nullable();
            $table->integer('r_short_order');
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
        Schema::dropIfExists('right_details');
    }
};
