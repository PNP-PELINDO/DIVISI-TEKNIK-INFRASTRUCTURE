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
            $table->foreignId('infrastructure_id')->constrained()->cascadeOnDelete();
            $table->text('issue_detail'); // Contoh: MOTOR GANTRY RUSAK
            $table->string('vendor_pic')->nullable(); // Contoh: PT. BIMA
            $table->enum('repair_status', ['reported', 'order_part', 'on_progress', 'resolved'])->default('reported');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
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
