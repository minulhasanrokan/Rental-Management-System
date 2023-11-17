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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->integer('notice_status')->nullable();
            $table->string('user_id')->nullable();
            $table->integer('notice_group')->nullable();
            $table->string('notice_title')->nullable();
            $table->date('notice_date')->nullable();
            $table->text('notice_details')->nullable();
            $table->text('notice_file')->nullable();
            $table->integer('email_status')->default(0);
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
        Schema::dropIfExists('notices');
    }
};
