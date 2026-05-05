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
        Schema::table('breakdown_logs', function (Blueprint $table) {
            if (Schema::hasColumn('breakdown_logs', 'proof_image')) {
                $table->renameColumn('proof_image', 'document_proof');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_logs', function (Blueprint $table) {
            if (Schema::hasColumn('breakdown_logs', 'document_proof')) {
                $table->renameColumn('document_proof', 'proof_image');
            }
        });
    }
};
