<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MasterAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'admin@u.local',
                'username'=> 'admin'
            ],
            [
                'name' =>  'admin',
                'password' => Hash::make( '0'),
                'role_id'=>1
            ]
        );

        $role = Role::where('name', 'Master Admin')
            ->where('guard_name', 'web')
            ->firstOrFail();

        $user->assignRole($role);
    }
}
