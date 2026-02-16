<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // goal, own_goal, penalty, yellow_card, red_card, substitution, var
            $table->integer('minute');
            $table->integer('extra_minute')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('secondary_player_id')->nullable()->constrained('players')->nullOnDelete(); // for assists, substitutions
            $table->timestamps();

            $table->index('type');
            $table->index('minute');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
