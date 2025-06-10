<?php

namespace Database\Seeders;

use App\Models\Magazine;
use Illuminate\Database\Seeder;

class MagazineSeeder extends Seeder
{
    public function run(): void
    {
        $magazines = [
            [
                'name' => 'Topolino',
                'country' => 'IT',
                'description' => 'Il più famoso settimanale a fumetti italiano',
                'website' => 'https://www.topolino.it',
                'is_active' => true
            ],
            [
                'name' => 'Dylan Dog',
                'country' => 'IT',
                'description' => 'L\'indagatore dell\'incubo',
                'website' => 'https://www.sergiobonelli.it',
                'is_active' => true
            ],
            [
                'name' => 'Nathan Never',
                'country' => 'IT',
                'description' => 'Fumetto di fantascienza italiana',
                'website' => 'https://www.sergiobonelli.it',
                'is_active' => true
            ],
            [
                'name' => 'Diabolik',
                'country' => 'IT',
                'description' => 'Il re del terrore',
                'website' => 'https://www.diabolik.it',
                'is_active' => true
            ],
            [
                'name' => 'Manga Magazine',
                'country' => 'JP',
                'description' => 'Rivista dedicata ai manga',
                'website' => null,
                'is_active' => true
            ]
        ];

        foreach ($magazines as $magazine) {
            Magazine::create($magazine);
        }
    }
}
