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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->integer('building_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->string('unit_code')->nullable();
            $table->string('unit_title')->nullable();
            $table->text('unit_address')->nullable();
            $table->text('unit_deatils')->nullable();
            $table->string('unit_logo')->nullable();
            $table->string('unit_photo')->nullable();
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
        Schema::dropIfExists('units');
    }
};
