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
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->integer('building_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('tenant_name')->nullable();
            $table->integer('tenant_id')->nullable();
            $table->string('complain_title')->nullable();
            $table->text('complain_details')->nullable();
            $table->text('complain_remarks')->nullable();
            $table->text('tenant_remarks')->nullable();
            $table->integer('process_status')->default(0);
            $table->integer('assign_user')->nullable();
            $table->integer('assign_by')->nullable();
            $table->date('assign_date')->nullable();
            $table->date('complate_date')->nullable();
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
        Schema::dropIfExists('complains');
    }
};
