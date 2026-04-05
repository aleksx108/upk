<?php

namespace Database\Factories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['Male', 'Female', 'Other']);

        $maleFirstNames = [
            'Jānis', 'Pēteris', 'Andris', 'Mārtiņš', 'Rihards', 'Artūrs', 'Edgars', 'Kristaps',
            'Roberts', 'Kārlis', 'Toms', 'Niks', 'Aigars', 'Gatis', 'Raimonds', 'Dainis',
        ];

        $femaleFirstNames = [
            'Anna', 'Laura', 'Elīna', 'Ieva', 'Līga', 'Zane', 'Kristīne', 'Marta',
            'Dace', 'Aija', 'Ilze', 'Inese', 'Madara', 'Sabīne', 'Paulīna', 'Sintija',
        ];

        $maleLastNames = [
            'Bērziņš', 'Kalniņš', 'Ozoliņš', 'Jansons', 'Eglītis', 'Liepiņš', 'Zariņš', 'Vītols',
            'Kļaviņš', 'Pētersons', 'Zālītis', 'Krūmiņš', 'Muižnieks', 'Riekstiņš',
        ];

        $femaleLastNames = [
            'Bērziņa', 'Kalniņa', 'Ozoliņa', 'Jansone', 'Eglīte', 'Liepiņa', 'Zariņa', 'Vītola',
            'Kļaviņa', 'Pētersone', 'Zālīte', 'Krūmiņa', 'Muižniece', 'Riekstiņa',
        ];

        if ($gender === 'Male') {
            $firstName = fake()->randomElement($maleFirstNames);
            $lastName = fake()->randomElement($maleLastNames);
        } elseif ($gender === 'Female') {
            $firstName = fake()->randomElement($femaleFirstNames);
            $lastName = fake()->randomElement($femaleLastNames);
        } else {
            $firstName = fake()->randomElement($maleFirstNames);
            $lastName = fake()->randomElement($maleLastNames);
        }

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

        $streetNo = (string) fake()->numberBetween(1, 250);
        if (fake()->boolean(35)) {
            $streetNo .= '-'.fake()->numberBetween(1, 80);
        }

        static $usedPersonalCodes = [];

        do {
            $birthDate = fake()->dateTimeBetween('-60 years', '-18 years');
            $personalCode = $birthDate->format('dmy').'-'.fake()->numerify('#####');
        } while (isset($usedPersonalCodes[$personalCode]));

        $usedPersonalCodes[$personalCode] = true;

        $domain = fake()->randomElement(['inbox.lv', 'apollo.lv', 'gmail.com', 'outlook.com']);
        $emailLocal = Str::of(Str::ascii($firstName.'.'.$lastName))
            ->lower()
            ->replaceMatches('/[^a-z0-9.]+/', '')
            ->trim('.')
            ->append((string) fake()->unique()->numberBetween(1, 99999))
            ->toString();

        $hrNotes = [
            'Ievadapmācības pabeigtas, noteikti mērķi pārbaudes laikam.',
            'Veiktas ikgadējās snieguma pārrunas; vienošanās par attīstības plānu un KPI.',
            'Nepieciešama papildus apmācība darba aizsardzībā un datu aizsardzībā (GDPR).',
            'Atjaunota amata apraksta versija un saskaņoti pienākumi ar tiešo vadītāju.',
            'Plānots apmācību kurss par klientu apkalpošanas standartu un konfliktsituāciju risināšanu.',
            'Saskaņots atvaļinājuma grafiks; iesniegti nepieciešamie dokumenti personāla lietai.',
            'Fiksētas darba laika uzskaites neatbilstības; nepieciešama precizēšana ar vadītāju.',
            'Sagatavots pielikums darba līgumam par amata maiņu un algu pārskatīšanu.',
            'Pārskatīts pārbaudes laika rezultāts; ieteikums turpināt darba attiecības.',
            'Darbinieks pieteikts obligātajai veselības pārbaudei; gaidāms atzinums.',
        ];

        $note = implode(' ', fake()->randomElements($hrNotes, fake()->numberBetween(1, 3)));

        $phone = '+371 '.fake()->randomElement(['2', '6']).fake()->numerify('#######');

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'personal_code' => $personalCode,
            'gender' => $gender,
            'phone_number' => $phone,
            'email' => $emailLocal.'@'.$domain,
            'birthday_date' => $birthDate,
            'country_code' => 'LV',
            'postal_code' => 'LV-'.fake()->numerify('####'),
            'city' => fake()->randomElement($cities),
            'street' => fake()->randomElement($streets),
            'street_number' => $streetNo,

            'notes' => $note,
        ];
    }
}
