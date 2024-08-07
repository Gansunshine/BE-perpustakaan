<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



use App\Models\Role;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesOwner = Role::where('name', 'owner')->first();

        User::create(
            [
                'name' => 'owner',
                'email' => 'owner@mail.com',
                'role_id' => $rolesOwner->id,
                'password' => Hash::make('password'),
            ]
            );
    }
}
