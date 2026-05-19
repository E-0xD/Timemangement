<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo admin
        User::firstOrCreate(
            ['email' => 'admin@studyflow.app'],
            [
                'name'              => 'Admin User',
                'password'          => Hash::make('password'),
                'role'              => UserRole::Admin,
                'email_verified_at' => now(),
                'school'            => config('site.name'),
            ]
        );

        // Demo student
        User::firstOrCreate(
            ['email' => 'student@studyflow.app'],
            [
                'name'              => 'Jane Student',
                'password'          => Hash::make('password'),
                'role'              => UserRole::Student,
                'email_verified_at' => now(),
                'school'            => 'University of Demo',
                'bio'               => 'A hardworking student using ' . config('site.name') . ' to stay on top of assignments.',
            ]
        );
    }
}
