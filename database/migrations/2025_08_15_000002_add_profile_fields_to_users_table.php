<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('country')->nullable()->after('avatar');
            $table->string('timezone')->nullable()->after('country');
            $table->integer('points')->default(0)->after('timezone');
            $table->boolean('is_banned')->default(false)->after('points');
            $table->timestamp('banned_at')->nullable()->after('is_banned');
            $table->string('ban_reason')->nullable()->after('banned_at');

            $table->index('points');
            $table->index('is_banned');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'bio',
                'avatar',
                'country',
                'timezone',
                'points',
                'is_banned',
                'banned_at',
                'ban_reason',
            ]);
        });
    }
};
