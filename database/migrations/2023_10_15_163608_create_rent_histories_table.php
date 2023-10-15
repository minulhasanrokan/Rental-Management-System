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
        Schema::create('rent_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id')->nullable();
            $table->integer('rent_id')->nullable();
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
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->integer('add_by')->nullable();
            $table->integer('edit_by')->nullable();
            $table->integer('delete_by')->nullable();
            $table->integer('status')->default(1);
            $table->integer('close_status')->default(0);
            $table->date('start_date')->nullable();
            $table->date('close_date')->nullable();
            $table->integer('update_status')->default(0);
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
        Schema::dropIfExists('rent_histories');
    }
};
