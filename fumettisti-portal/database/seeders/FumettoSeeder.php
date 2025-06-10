<?php

namespace Database\Seeders;

use App\Models\Fumetto;
use App\Models\User;
use App\Models\Magazine;
use App\Models\Category;
use Illuminate\Database\Seeder;

class FumettoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $magazines = Magazine::all();
        $categories = Category::all();

        $fumetti = [
            [
                'title' => 'Le Avventure di Capitan Cosmos',
                'issue_number' => 1,
                'publication_year' => 2023,
                'plot' => 'Un eroe spaziale lotta contro le forze del male nell\'universo.',
                'is_published' => true,
                'published_at' => now()->subDays(10)
            ],
            [
                'title' => 'I Misteri di Villa Nera',
                'issue_number' => 3,
                'publication_year' => 2023,
                'plot' => 'Una storia gotica ambientata in una villa misteriosa.',
                'is_published' => true,
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Robot Wars',
                'issue_number' => 12,
                'publication_year' => 2024,
                'plot' => 'In un futuro distopico, i robot si ribellano all\'umanità.',
                'is_published' => true,
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'La Principessa del Regno Perduto',
                'issue_number' => 7,
                'publication_year' => 2024,
                'plot' => 'Una principessa combatte per riconquistare il suo regno.',
                'is_published' => true,
                'published_at' => now()->subDays(1)
            ],
            [
                'title' => 'Scuola di Magia',
                'issue_number' => 2,
                'publication_year' => 2024,
                'plot' => 'Studenti imparano la magia in una scuola speciale.',
                'is_published' => false,
                'published_at' => null
            ]
        ];

        foreach ($fumetti as $index => $fumettoData) {
            $fumetto = Fumetto::create([
                'title' => $fumettoData['title'],
                'issue_number' => $fumettoData['issue_number'],
                'publication_year' => $fumettoData['publication_year'],
                'plot' => $fumettoData['plot'],
                'is_published' => $fumettoData['is_published'],
                'published_at' => $fumettoData['published_at'],
                'user_id' => $users->random()->id,
                'magazine_id' => $magazines->random()->id ?? null
            ]);

            // Associa categorie casuali
            $randomCategories = $categories->random(rand(1, 3));
            $fumetto->categories()->attach($randomCategories->pluck('id'));
        }
    }
}
