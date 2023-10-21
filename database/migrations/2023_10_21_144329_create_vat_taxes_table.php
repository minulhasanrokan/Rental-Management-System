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
        Schema::create('vat_taxes', function (Blueprint $table) {
             $table->id();
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->integer('month_id')->nullable();
            $table->integer('year_id')->nullable();
            $table->integer('add_by')->nullable();
            $table->integer('edit_by')->nullable();
            $table->integer('delete_by')->nullable();
            $table->integer('status')->default(1);
            $table->integer('delete_status')->default(0);
            $table->integer('edit_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_taxes');
    }
};
