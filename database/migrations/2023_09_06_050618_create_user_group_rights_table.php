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
        Schema::create('user_group_rights', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('g_id')->nullable();
            $table->integer('c_id')->nullable();
            $table->integer('r_id')->nullable();
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
        Schema::dropIfExists('user_group_rights');
    }
};
