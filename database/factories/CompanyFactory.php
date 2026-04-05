<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyNames = [
            'Baltijas Tehnoloģijas',
            'Rīgas Serviss',
            'Kurzemes Tīkli',
            'Vidzemes Risinājumi',
            'Zemgales Loģistika',
            'Latgales Pakalpojumi',
            'Saules Enerģija',
            'Dzintara Dati',
            'Ziemeļu Sistēmas',
            'Daugavas Transports',
            'Mežaparka Birojs',
            'Pārdaugavas Projekti',
            'Juglas Serviss',
            'Mārupes Tīkli',
            'Ogres Risinājumi',
            'Cēsu Pakalpojumi',
            'Valmieras Loģistika',
            'Liepājas Tehnoloģijas',
            'Jelgavas Serviss',
            'Ventspils Sistēmas',
            'Rēzeknes Dati',
            'Talsu Projekti',
            'Siguldas Risinājumi',
            'Kuldīgas Pakalpojumi',
            'Saldus Loģistika',
            'Bauskas Tīkli',
            'Dobeles Serviss',
            'Aizkraukles Dati',
            'Madonas Risinājumi',
            'Tukuma Projekti',
            'Gulbenes Pakalpojumi',
            'Balvu Loģistika',
            'Alūksnes Serviss',
            'Limbažu Sistēmas',
            'Salaspils Tīkli',
            'Ādažu Dati',
            'Ķekavas Risinājumi',
            'Olaines Pakalpojumi',
            'Jūrmalas Serviss',
            'Rīgas Digitālie Pakalpojumi',
            'Latvijas IT Risinājumi',
            'Baltijas Biznesa Serviss',
            'Digitālās Sistēmas Latvija',
            'Loģistikas Centrs Rīga',
            'Pakalpojumu Grupa Kurzeme',
            'Tehnoloģiju Parks Vidzeme',
            'Zemgales Būve',
            'Latgales Transports',
        ];

        $cities = [
            'Rīga',
            'Daugavpils',
            'Liepāja',
            'Jelgava',
            'Jūrmala',
            'Ventspils',
            'Rēzekne',
            'Valmiera',
            'Ogre',
            'Cēsis',
        ];

        $streets = [
            'Brīvības iela',
            'Raiņa bulvāris',
            'Lāčplēša iela',
            'Kr. Barona iela',
            'Dzirnavu iela',
            'Čaka iela',
            'Valdemāra iela',
            'Maskavas iela',
            'Tērbatas iela',
            'Kalpaka bulvāris',
        ];

        $legalForm = fake()->randomElement(['SIA', 'AS']);
        $city = fake()->randomElement($cities);

        return [
            'name' => $legalForm.' '.fake()->unique()->randomElement($companyNames),
            'registration_no' => fake()->unique()->numerify('###########'),

            'country_code' => 'LV',
            'postal_code' => 'LV-'.fake()->numerify('####'),
            'city' => $city,
            'street' => fake()->randomElement($streets),
            'street_number' => (string) fake()->numberBetween(1, 250),
        ];
    }
}
