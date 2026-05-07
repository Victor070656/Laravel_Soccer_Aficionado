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
            // Football identity fields
            $table->foreignId('favorite_player_id')->nullable()->after('timezone')->constrained('players')->nullOnDelete();
            $table->string('favorite_coach')->nullable()->after('favorite_player_id');
            $table->string('state')->nullable()->after('country');
            $table->string('football_personality')->nullable()->after('state')->comment('Fan personality type: Fanatic, Analyst, Banter King, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('favorite_player_id');
            $table->dropColumn(['favorite_coach', 'state', 'football_personality']);
        });
    }
};
