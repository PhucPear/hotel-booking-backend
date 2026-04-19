<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $user  = Role::firstOrCreate(['name' => 'user']);

        // Generate permissions theo REST
        $resources = ['rooms', 'users', 'bookings'];

        $permissions = collect($resources)
            ->flatMap(fn($res) => [
                "view_$res",
                "create_$res",
                "update_$res",
                "delete_$res",
            ]);

        // Insert permission
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // gán permission cho staff
        $staff->permissions()->sync(
            Permission::whereIn('name', [
                'view_rooms',
                'create_rooms',
                'update_rooms',
                'view_bookings',
                'update_bookings',
            ])->pluck('id')
        );

        // Gán permission cho user
        $user->permissions()->sync(
            Permission::whereIn('name', [
                'view_bookings',
                'create_bookings',
                'update_bookings',
                'delete_bookings',
                'view_users',
                'create_users',
                'update_users',
                'delete_users',
            ])->pluck('id')
        );
    }
}
