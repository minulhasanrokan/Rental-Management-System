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
        Schema::create('s_m_s_alerts', function (Blueprint $table) {
            $table->id();
            $table->integer('alert_status')->nullable();
            $table->string('user_id')->nullable();
            $table->integer('alert_group')->nullable();
            $table->string('alert_title')->nullable();
            $table->date('alert_date')->nullable();
            $table->text('alert_details')->nullable();
            $table->integer('email_status')->default(0);
            $table->integer('delete_status')->default(0);
            $table->integer('status')->default(1);
            $table->integer('edit_status')->default(0);
            $table->integer('total_sent')->default(0);
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
        Schema::dropIfExists('s_m_s_alerts');
    }
};
