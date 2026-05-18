<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::whereNull('username')->orWhere('username', '')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $user->username = User::generateUniqueUsername($user->name);
                $user->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to undo this without potentially deleting intentionally set usernames
    }
};
