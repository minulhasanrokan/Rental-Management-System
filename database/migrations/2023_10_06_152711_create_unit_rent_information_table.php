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
        Schema::create('unit_rent_information', function (Blueprint $table) {
            $table->id();
            $table->integer('building_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->decimal('unit_rent', 10, 2);
            $table->decimal('water_bill', 10, 2);
            $table->decimal('electricity_bill', 10, 2);
            $table->decimal('gas_bill', 10, 2);
            $table->decimal('security_bill', 10, 2);
            $table->decimal('maintenance_bill', 10, 2);
            $table->decimal('service_bill', 10, 2);
            $table->decimal('charity_bill', 10, 2);
            $table->decimal('other_bill', 10, 2);
            $table->integer('add_by')->nullable();
            $table->integer('edit_by')->nullable();
            $table->integer('delete_by')->nullable();
            $table->integer('status')->default(1);
            $table->integer('edit_status')->default(0);
            $table->integer('delete_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_rent_information');
    }
};
