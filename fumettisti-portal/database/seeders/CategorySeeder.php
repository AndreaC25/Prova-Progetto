<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Azione',
                'description' => 'Fumetti ricchi di combattimenti, avventure e sequenze dinamiche',
                'color' => '#dc3545'
            ],
            [
                'name' => 'Avventura',
                'description' => 'Storie di esplorazioni, viaggi e scoperte emozionanti',
                'color' => '#28a745'
            ],
            [
                'name' => 'Commedia',
                'description' => 'Fumetti umoristici e divertenti per tutti',
                'color' => '#ffc107'
            ],
            [
                'name' => 'Drammatico',
                'description' => 'Storie profonde con tematiche serie e coinvolgenti',
                'color' => '#6c757d'
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Mondi fantastici con magia e creature leggendarie',
                'color' => '#6f42c1'
            ],
            [
                'name' => 'Horror',
                'description' => 'Storie di paura e suspense',
                'color' => '#000000'
            ],
            [
                'name' => 'Fantascienza',
                'description' => 'Futuro, tecnologia e mondi alieni',
                'color' => '#17a2b8'
            ],
            [
                'name' => 'Slice of Life',
                'description' => 'Storie di vita quotidiana',
                'color' => '#fd7e14'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color' => $category['color']
            ]);
        }
    }
}
