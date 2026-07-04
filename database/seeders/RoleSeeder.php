<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * The four application roles:
     *
     *  - Guest        : unauthenticated / anonymous visitor (read-only access)
     *  - Contestant   : registered user who submits solutions
     *  - ProblemSetter: creates and manages contest problems
     *  - Admin        : full platform administration
     */
    public function run(): void
    {
        // Reset cached roles & permissions so a fresh state is used
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'Guest',
            'Contestant',
            'ProblemSetter',
            'Admin',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web']
            );
        }

        $this->command->info('✅  Roles seeded: ' . implode(', ', $roles));
    }
}
