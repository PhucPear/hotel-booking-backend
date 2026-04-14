<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('qqqqqqqq'),
            'role_id' => Role::where('name', 'admin')->value('id'),
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('qqqqqqqq'),
            'role_id' => Role::where('name', 'user')->value('id'),
        ]);

        User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('qqqqqqqq'),
            'role_id' => Role::where('name', 'staff')->value('id'),
        ]);
    }
}
