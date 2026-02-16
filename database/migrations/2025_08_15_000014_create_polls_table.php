<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('match_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('general'); // general, motm, prediction, gotw
            $table->dateTime('closes_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('total_votes')->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
            $table->index('closes_at');
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->string('image')->nullable();
            $table->integer('votes_count')->default(0);
            $table->timestamps();

            $table->index('poll_id');
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'poll_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
