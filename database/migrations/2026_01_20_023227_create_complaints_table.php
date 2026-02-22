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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('username');
            $table->integer('platform_id')->constrained('platforms');
            $table->text('description');
            $table->string('account_url')->unique();
            $table->date('submitted_at');
            $table->string('ticket')->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('platform_id')->references('id')->on('platforms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
