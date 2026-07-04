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
        Schema::table('users', function (Blueprint $table) {
            // pending  = registered, awaiting admin approval
            // approved = admin approved, can log in fully
            // rejected = admin rejected the registration
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('password');

            $table->text('rejected_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejected_reason']);
        });
    }
};
