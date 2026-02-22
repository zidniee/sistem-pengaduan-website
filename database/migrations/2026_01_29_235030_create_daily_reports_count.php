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
        Schema::create('daily_reports_count', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer("counter")->default(0);
            $table->timestamps();
        });

        DB::table('daily_reports_count')->insert([
            'id' => 1,
            'counter' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports_count');
    }
};
