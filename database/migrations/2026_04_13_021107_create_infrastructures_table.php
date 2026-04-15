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
            $table->foreignId('entity_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['equipment', 'facility', 'utility']); // Pemisah Kategori
            $table->string('type'); // Contoh: Gantry Luffing Crane, Reach Stacker
            $table->string('code_name')->unique(); // Contoh: GLC-01, GLC-02
            $table->enum('status', ['available', 'breakdown'])->default('available');
            $table->timestamps();
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
