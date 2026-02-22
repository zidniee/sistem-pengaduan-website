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
        // Index untuk complaint list filtering
        Schema::table('complaints', function (Blueprint $table) {
            // Search optimization
            $table->index(['username'], 'idx_complaints_username');
            $table->index(['ticket'], 'idx_complaints_ticket');
            $table->index(['platform_id'], 'idx_complaints_platform_id');
            
            // Sorting optimization
            $table->index(['created_at'], 'idx_complaints_created_at');
            $table->index(['submitted_at'], 'idx_complaints_submitted_at');
            
            // Composite index untuk pagination + filtering
            $table->index(['platform_id', 'created_at'], 'idx_complaints_platform_created');
        });

        // Index untuk inspections filtering
        Schema::table('inspections', function (Blueprint $table) {
            $table->index(['complaint_id', 'inspected_at'], 'idx_inspections_complaint_inspected');
            $table->index(['new_status'], 'idx_inspections_new_status');
            $table->index(['account_status'], 'idx_inspections_account_status');
        });

        // Index untuk platforms small lookup
        Schema::table('platforms', function (Blueprint $table) {
            $table->index(['name'], 'idx_platforms_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropIndex('idx_complaints_username');
            $table->dropIndex('idx_complaints_ticket');
            $table->dropIndex('idx_complaints_platform_id');
            $table->dropIndex('idx_complaints_created_at');
            $table->dropIndex('idx_complaints_submitted_at');
            $table->dropIndex('idx_complaints_platform_created');
        });

        Schema::table('inspections', function (Blueprint $table) {
            $table->dropIndex('idx_inspections_complaint_inspected');
            $table->dropIndex('idx_inspections_new_status');
            $table->dropIndex('idx_inspections_account_status');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropIndex('idx_platforms_name');
        });
    }
};
