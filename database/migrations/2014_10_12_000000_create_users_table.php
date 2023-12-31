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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('name');
            $table->text('address')->nullable();
            $table->text('details')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->integer('department')->nullable();
            $table->string('assign_department')->nullable();
            $table->integer('designation')->nullable();
            $table->integer('group')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('sex')->nullable();
            $table->integer('blood_group')->nullable();
            $table->string('user_photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('verify_status')->default(0);
            $table->integer('delete_status')->default(0);
            $table->decimal('user_session_time', 10, 2)->default(0.00);
            $table->integer('status')->default(1);
            $table->integer('edit_status')->default(0);
            $table->integer('super_admin_status')->default(0);
            $table->integer('user_type')->default(0);
            $table->string('password');
            $table->integer('password_change_status')->default(1);
            $table->integer('add_by')->nullable();
            $table->integer('edit_by')->nullable();
            $table->integer('delete_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
