<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First AC',
                'description' => "Awarded on a user's first-ever accepted submission",
                'icon_class' => 'bi bi-award',
            ],
            [
                'name' => 'Speed Demon',
                'description' => "Awarded for an accepted submission within 5 minutes of a contest's start time",
                'icon_class' => 'bi bi-lightning-charge-fill',
            ],
            [
                'name' => 'Problem Slayer',
                'description' => 'Awarded once a user reaches 50 or more accepted submissions',
                'icon_class' => 'bi bi-shield-shaded',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                [
                    'description' => $badge['description'],
                    'icon_class' => $badge['icon_class'],
                ]
            );
        }

        $this->command->info('✅  Badges seeded successfully.');
    }
}
