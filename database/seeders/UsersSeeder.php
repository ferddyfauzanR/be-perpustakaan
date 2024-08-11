<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::where('name','owner')->first();

        User::create(
            [
            'name' => 'Owner User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
            ]
        );
    }
}
