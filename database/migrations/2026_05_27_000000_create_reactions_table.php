<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('target_type', 50);
            $table->string('target_id', 50);
            $table->string('emoji', 20);
            $table->timestamps();

            $table->unique(['user_id', 'target_type', 'target_id']);
            $table->index(['target_type', 'target_id']);
            $table->index('emoji');
        });

        if (Schema::hasTable('likes')) {
            DB::table('likes')
                ->orderBy('id')
                ->chunkById(100, function ($likes) {
                    $rows = $likes->map(function ($like) {
                        return [
                            'user_id' => $like->user_id,
                            'target_type' => match ($like->likeable_type) {
                                \App\Models\Post::class => 'post',
                                \App\Models\Comment::class => 'comment',
                                default => class_basename($like->likeable_type),
                        },
                            'target_id' => (string) $like->likeable_id,
                            'emoji' => '❤️',
                            'created_at' => $like->created_at,
                            'updated_at' => $like->updated_at,
                        ];
                    })->all();

                    DB::table('reactions')->insert($rows);
                }, 'id');
        }

        if (Schema::hasTable('match_reactions')) {
            DB::table('match_reactions')
                ->orderBy('id')
                ->chunkById(100, function ($reactions) {
                    $rows = $reactions->map(function ($reaction) {
                        return [
                            'user_id' => $reaction->user_id,
                            'target_type' => 'match',
                            'target_id' => (string) $reaction->match_id,
                            'emoji' => $reaction->emoji,
                            'created_at' => $reaction->created_at,
                            'updated_at' => $reaction->updated_at,
                        ];
                    })->all();

                    DB::table('reactions')->insert($rows);
                }, 'id');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
