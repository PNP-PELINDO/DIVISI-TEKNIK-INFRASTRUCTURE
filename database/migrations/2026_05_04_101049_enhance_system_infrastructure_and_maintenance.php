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
        // 1. Add Indexes and Unique Constraint to Infrastructures
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->index(['code_name', 'type', 'status', 'entity_id'], 'infra_search_idx');
            $table->unique(['code_name', 'entity_id'], 'infra_code_entity_unique');
        });

        // 2. Add Indexes to Breakdown Logs
        Schema::table('breakdown_logs', function (Blueprint $table) {
            $table->index(['repair_status', 'infrastructure_id'], 'log_status_idx');
        });

        // 3. Create Maintenance Schedules table (Preventive Maintenance)
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
        
        Schema::table('breakdown_logs', function (Blueprint $table) {
            $table->dropIndex('log_status_idx');
        });

        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropUnique('infra_code_entity_unique');
            $table->dropIndex('infra_search_idx');
        });
    }
};
