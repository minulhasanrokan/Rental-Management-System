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
        Schema::create('system_details', function (Blueprint $table) {
            $table->id();
            $table->string('system_name')->nullable();
            $table->string('system_email')->nullable();
            $table->string('system_mobile')->nullable();
            $table->string('system_title')->nullable();
            $table->text('system_address')->nullable();
            $table->string('system_copy_right')->nullable();
            $table->text('system_deatils')->nullable();
            $table->string('system_logo')->nullable();
            $table->string('system_bg_image')->nullable();
            $table->string('system_favicon')->nullable();
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
        Schema::dropIfExists('system_details');
    }
};
