<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full platform administrator'],
            ['name' => 'Moderator', 'slug' => 'moderator', 'description' => 'Content moderator'],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Regular user'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
