<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('type')->default('league'); // league, cup, tournament
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->string('season')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('country');
            $table->index('is_active');
        });

        Schema::create('club_competition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('season')->nullable();
            $table->timestamps();
            $table->unique(['club_id', 'competition_id', 'season']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_competition');
        Schema::dropIfExists('competitions');
    }
};
