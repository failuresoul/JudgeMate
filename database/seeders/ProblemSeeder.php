<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setter = \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'ProblemSetter'))->first();

        if (!$setter) {
            $setter = \App\Models\User::firstOrCreate(
                ['email' => 'judge@judgemate.test'],
                [
                    'name'     => 'Sample Judge',
                    'username' => 'judge1',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'status'   => 'approved',
                ]
            );
            $setter->assignRole('ProblemSetter');
        }

        $problems = \App\Models\Problem::factory()
            ->count(5)
            ->create(['created_by' => $setter->id]);

        foreach ($problems as $problem) {
            \App\Models\TestCase::create([
                'problem_id'      => $problem->id,
                'input'           => "2\n3 5\n4 6",
                'expected_output' => "8\n10",
                'is_hidden'       => false,
            ]);

            \App\Models\TestCase::create([
                'problem_id'      => $problem->id,
                'input'           => "1\n100 200",
                'expected_output' => "300",
                'is_hidden'       => true,
            ]);
        }
    }
}
