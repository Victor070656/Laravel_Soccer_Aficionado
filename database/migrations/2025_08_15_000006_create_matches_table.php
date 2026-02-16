<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('home_club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('away_club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('season')->nullable();
            $table->integer('matchday')->nullable();
            $table->string('round')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('kick_off');
            $table->string('status')->default('scheduled'); // scheduled, live, half_time, finished, postponed, cancelled
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->integer('home_score_ht')->nullable();
            $table->integer('away_score_ht')->nullable();
            $table->integer('attendance')->nullable();
            $table->string('referee')->nullable();
            $table->timestamps();

            $table->index('kick_off');
            $table->index('status');
            $table->index('season');
            $table->index(['competition_id', 'season', 'matchday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
