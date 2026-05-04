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
        Schema::create('infrastructures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->string('category'); // equipment, facility, utility
            $table->string('type'); // e.g., 'Gantry Crane', 'Genset'
            $table->string('code_name');
            $table->string('status')->default('available'); // available, breakdown, maintenance
            $table->integer('quantity')->default(1);
            $table->string('image')->nullable();
            
            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes for Performance & Data Integrity
            $table->index(['code_name', 'type', 'status', 'entity_id'], 'infra_search_idx');
            $table->unique(['code_name', 'entity_id'], 'infra_code_entity_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructures');
    }
};
