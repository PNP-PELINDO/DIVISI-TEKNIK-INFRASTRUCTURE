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
        Schema::create('breakdown_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_id')->constrained()->onDelete('cascade');
            $table->text('issue_detail');
            $table->string('repair_status')->default('reported'); // reported, order_part, on_progress, resolved
            $table->string('vendor_pic')->nullable();
            $table->string('document_proof')->nullable();

            // Technical Timestamps (17+ Dates)
            $table->dateTime('troubleshoot_date')->nullable();
            $table->dateTime('ba_date')->nullable();
            $table->dateTime('work_order_date')->nullable();
            $table->dateTime('pr_po_date')->nullable();
            $table->dateTime('sparepart_date')->nullable();
            $table->dateTime('start_work_date')->nullable();
            $table->dateTime('com_test_date')->nullable();
            $table->dateTime('resolved_date')->nullable();

            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['repair_status', 'infrastructure_id'], 'log_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_logs');
    }
};
