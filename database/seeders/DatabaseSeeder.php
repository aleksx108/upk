<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Occupation;
use App\Models\Personnel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@upk.lv'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Password123'),
                'email_verified_at' => now(),
            ],
        );

        $occupations = Occupation::factory()->count(12)->create();
        $companies = Company::factory()->count(24)->create();

        $companyIds = $companies->modelKeys();
        $occupationIds = $occupations->modelKeys();

        $personnel = Personnel::factory()
            ->count(8)
            ->state(function () {
                $createdAt = fake()->dateTimeBetween(now()->subDays(30), now());

                return [
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            })
            ->create();

        $malePortraits = array_values(array_filter([
            public_path('img/profile_m.jpg'),
            public_path('img/profile_m_2.jpg'),
        ], 'file_exists'));

        $femalePortraits = array_values(array_filter([
            public_path('img/profile_f.jpg'),
            public_path('img/profile_f_2.jpg'),
        ], 'file_exists'));

        $canAttachMedia = Schema::hasTable('media') && (count($malePortraits) + count($femalePortraits) > 0);

        $portraitAssignments = [];

        if ($canAttachMedia) {
            $remainingMalePortraits = $malePortraits;
            $remainingFemalePortraits = $femalePortraits;

            shuffle($remainingMalePortraits);
            shuffle($remainingFemalePortraits);

            $maleTargets = $personnel->where('gender', 'Male')->shuffle()->values();
            $femaleTargets = $personnel->where('gender', 'Female')->shuffle()->values();

            $maleCount = min(count($remainingMalePortraits), $maleTargets->count());
            for ($i = 0; $i < $maleCount; $i++) {
                $portraitAssignments[$maleTargets[$i]->id] = array_pop($remainingMalePortraits);
            }

            $femaleCount = min(count($remainingFemalePortraits), $femaleTargets->count());
            for ($i = 0; $i < $femaleCount; $i++) {
                $portraitAssignments[$femaleTargets[$i]->id] = array_pop($remainingFemalePortraits);
            }
        }

        foreach ($personnel as $person) {
            $workplaceCount = fake()->randomElement([0, 1, 1, 2, 2, 3]);

            $cursor = Carbon::today()
                ->subYears(fake()->numberBetween(1, 10))
                ->subMonths(fake()->numberBetween(0, 11))
                ->startOfDay();

            for ($i = 0; $i < $workplaceCount; $i++) {
                $isLast = $i === $workplaceCount - 1;
                $isCurrent = $isLast && fake()->boolean(70);

                $from = $cursor->copy();

                $durationMonths = fake()->numberBetween(6, 36);
                $to = $from->copy()->addMonths($durationMonths)->subDay();

                if ($to->gt(now())) {
                    $to = now()->subDays(fake()->numberBetween(5, 45))->startOfDay();
                }

                if ($to->lt($from)) {
                    $to = $from->copy()->addMonths(fake()->numberBetween(1, 12))->subDay();
                }

                $person->workplaces()->create([
                    'company_id' => fake()->randomElement($companyIds),
                    'occupation_id' => fake()->randomElement($occupationIds),
                    'from_date' => $from->toDateString(),
                    'to_date' => $isCurrent ? null : $to->toDateString(),
                ]);

                $cursor = ($isCurrent ? now()->startOfDay() : $to->copy())
                    ->addDays(fake()->numberBetween(1, 45));
            }

            if ($canAttachMedia && isset($portraitAssignments[$person->id])) {
                try {
                    $person
                        ->addMedia($portraitAssignments[$person->id])
                        ->preservingOriginal()
                        ->toMediaCollection('portrait_photo');
                } catch (\Throwable $e) {
                    // Ignore media failures in seeding.
                }
            }
        }
    }
}
