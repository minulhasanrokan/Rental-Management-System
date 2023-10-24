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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date')->nullable();
            $table->time('entry_time')->nullable();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_mobile')->nullable();
            $table->text('visitor_address')->nullable();
            $table->text('visitor_reason')->nullable();
            $table->integer('building_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('tenant_name')->nullable();
            $table->integer('tenant_id')->nullable();
            $table->date('out_date')->nullable();
            $table->time('out_time')->nullable();
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
        Schema::dropIfExists('visitors');
    }
};
