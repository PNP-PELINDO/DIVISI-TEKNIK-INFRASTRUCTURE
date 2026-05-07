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
            $table->date('breakdown_date')->nullable()->after('repair_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_logs', function (Blueprint $table) {
            $table->dropColumn('breakdown_date');
        });
    }
};
