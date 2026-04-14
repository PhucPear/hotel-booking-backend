<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create role
        $admin = Role::factory()->create(['name' => 'admin']);
        $user  = Role::factory()->create(['name' => 'user']);
        $staff = Role::factory()->create(['name' => 'staff']);

        // create permission
        $adminPermission = Permission::factory()->create(['name' => 'admin']);
        $manageRooms = Permission::factory()->create(['name' => 'manage_rooms']);
        $manageUsers = Permission::factory()->create(['name' => 'manage_users']);
        $booking     = Permission::factory()->create(['name' => 'booking_handle']);

        // assign permission to role
        $admin->permissions()->attach([
            $adminPermission->id,
        ]);

        $staff->permissions()->attach([
            $manageRooms->id,
            $manageUsers->id,
            $booking->id
        ]);

        $user->permissions()->attach([
            $booking->id
        ]);
    }
}
