<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed all roles first
        $this->call(RoleSeeder::class);

        // 2. Create a default Admin user for local development
        $admin = User::firstOrCreate(
            ['email' => 'admin@judgemate.test'],
            [
                'name'     => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'status'   => 'approved', // Admin is pre-approved
            ]
        );
        $admin->assignRole('Admin');

        // 3. Create a sample Contestant (Pre-approved)
        $contestant = User::firstOrCreate(
            ['email' => 'contestant@judgemate.test'],
            [
                'name'     => 'Sample Contestant',
                'username' => 'contestant1',
                'password' => Hash::make('password'),
                'status'   => 'approved', // Pre-approved for easy testing
            ]
        );
        $contestant->assignRole('Contestant');

        // 4. Create a sample Judge/ProblemSetter (Pre-approved)
        $setter = User::firstOrCreate(
            ['email' => 'judge@judgemate.test'],
            [
                'name'     => 'Sample Judge',
                'username' => 'judge1',
                'password' => Hash::make('password'),
                'status'   => 'approved', // Pre-approved for easy testing
            ]
        );
        $setter->assignRole('ProblemSetter');

        $this->command->info('✅  Database seeded successfully.');
    }
}
