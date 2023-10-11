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
        Schema::create('transfer_owners', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id')->nullable();
            $table->integer('building_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('transfer_owner_id')->nullable();
            $table->date('transfer_date')->nullable();
            $table->text('transfer_reason')->nullable();
            $table->integer('table_id')->nullable();
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
        Schema::dropIfExists('transfer_owners');
    }
};
