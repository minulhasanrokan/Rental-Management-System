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
        Schema::create('user_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('normal_user_name')->nullable();
            $table->integer('owner_user_type')->nullable();
            $table->integer('tenant_user_type')->nullable();
            $table->integer('employee_user_type')->nullable();
            $table->integer('tenant_user_group')->nullable();
            $table->integer('normal_user_group')->nullable();
            $table->integer('owner_user_group')->nullable();
            $table->integer('employee_user_group')->nullable();
            $table->decimal('session_time', 10, 2)->default(0.00);
            $table->decimal('session_check_time', 10, 2)->default(0.00);
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
        Schema::dropIfExists('user_configs');
    }
};
