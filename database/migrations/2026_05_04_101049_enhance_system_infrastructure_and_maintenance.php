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
        // 1. Create Maintenance Schedules table (Preventive Maintenance)
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('scheduled_date');
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['scheduled_date', 'status']);
        });

        // 4. Create Status Histories table (Audit Trail for Breakdown Logs)
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breakdown_log_id')->constrained()->onDelete('cascade');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_histories');
        Schema::dropIfExists('maintenance_schedules');
    }
};
