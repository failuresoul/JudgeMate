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
        // 1. Create contests table
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create contest_problems pivot table
        Schema::create('contest_problems', function (Blueprint $table) {
            $table->foreignId('contest_id')->constrained('contests')->cascadeOnDelete();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete();
            $table->string('label'); // e.g. A, B, C...
            $table->primary(['contest_id', 'problem_id']);
        });

        // 3. Create contest_participants pivot table
        Schema::create('contest_participants', function (Blueprint $table) {
            $table->foreignId('contest_id')->constrained('contests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->primary(['contest_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_participants');
        Schema::dropIfExists('contest_problems');
        Schema::dropIfExists('contests');
    }
};
