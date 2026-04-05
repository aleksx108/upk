<?php

namespace Database\Factories;

use App\Models\Occupation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Occupation>
 */
class OccupationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            // IT
            'Programmatūras izstrādātājs',
            'Sistēmu administrators',
            'IT atbalsta speciālists',
            'Tīkla inženieris',
            'DevOps inženieris',
            'Testēšanas inženieris',
            'Datu analītiķis',
            'Biznesa analītiķis',

            // Management
            'Projektu vadītājs',
            'Produkta īpašnieks',
            'Komandas vadītājs',
            'Nodaļas vadītājs',
            'Operāciju vadītājs',
            'Biroja vadītājs',

            // Post office
            'Pasta nodaļas darbinieks',
            'Pasta operators',
            'Pasta nodaļas vadītājs',
            'Sūtījumu šķirotājs',
            'Kurjers',
            'Klientu apkalpošanas speciālists (Pasts)',
        ];

        return [
            'name' => fake()->unique()->randomElement($names),
        ];
    }
}
