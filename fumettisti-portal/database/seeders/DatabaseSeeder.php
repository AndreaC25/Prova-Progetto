<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Magazine;
use App\Models\Fumetto;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@fumettistiportal.it',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        // Create Test Users
        $users = [
            [
                'name' => 'Marco Rossi',
                'email' => 'marco@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ],
            [
                'name' => 'Laura Bianchi',
                'email' => 'laura@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ],
            [
                'name' => 'Giuseppe Verde',
                'email' => 'giuseppe@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ],
            [
                'name' => 'Anna Neri',
                'email' => 'anna@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Create profile for each user
            $user->profile()->create([
                'bio' => 'Fumettista appassionato con anni di esperienza nel disegno e nella narrazione.',
                'location' => 'Italia',
                'is_public' => true
            ]);
        }

        // Create Categories
        $categories = [
            [
                'name' => 'Manga',
                'slug' => 'manga',
                'description' => 'Fumetti in stile giapponese',
                'icon' => 'torii-gate',
                'color' => '#ff6b6b'
            ],
            [
                'name' => 'Superhero',
                'slug' => 'superhero',
                'description' => 'Fumetti di supereroi',
                'icon' => 'mask',
                'color' => '#4ecdc4'
            ],
            [
                'name' => 'Fantasy',
                'slug' => 'fantasy',
                'description' => 'Avventure fantastiche',
                'icon' => 'dragon',
                'color' => '#45b7d1'
            ],
            [
                'name' => 'Sci-Fi',
                'slug' => 'sci-fi',
                'description' => 'Fantascienza e futuro',
                'icon' => 'rocket',
                'color' => '#f7b731'
            ],
            [
                'name' => 'Horror',
                'slug' => 'horror',
                'description' => 'Storie di paura e mistero',
                'icon' => 'ghost',
                'color' => '#5f27cd'
            ],
            [
                'name' => 'Graphic Novel',
                'slug' => 'graphic-novel',
                'description' => 'Romanzi a fumetti',
                'icon' => 'book-open',
                'color' => '#00d2d3'
            ],
            [
                'name' => 'Umoristico',
                'slug' => 'umoristico',
                'description' => 'Fumetti divertenti e comici',
                'icon' => 'laugh',
                'color' => '#ff9ff3'
            ],
            [
                'name' => 'Slice of Life',
                'slug' => 'slice-of-life',
                'description' => 'Storie di vita quotidiana',
                'icon' => 'coffee',
                'color' => '#54a0ff'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Magazines
        $magazines = [
            [
                'name' => 'Weekly Shonen Jump',
                'description' => 'La famosa rivista manga giapponese',
                'website' => 'https://www.shonenjump.com'
            ],
            [
                'name' => 'Indie Comics Italia',
                'description' => 'Rivista dedicata ai fumetti indipendenti italiani',
                'website' => 'https://www.indiecomicsitalia.com'
            ],
            [
                'name' => 'Fumetto Digitale',
                'description' => 'Piattaforma per fumetti digitali',
                'website' => 'https://www.fumettodigitale.it'
            ],
            [
                'name' => 'Autoproduzione',
                'description' => 'Per fumetti autoprodotti',
                'website' => null
            ]
        ];

        foreach ($magazines as $magazineData) {
            Magazine::create($magazineData);
        }

        // Get all users except admin for fumetti creation
        $normalUsers = User::where('is_admin', false)->get();
        $allCategories = Category::all();
        $allMagazines = Magazine::all();

        // Create Sample Fumetti
        $fumettiData = [
            [
                'title' => 'Le Avventure di Capitan Pasta',
                'plot' => 'In un mondo dove la pasta è l\'elemento più potente dell\'universo, Capitan Pasta lotta contro il malvagio Dottor Scondito per salvare l\'Italia dai suoi piani diabolici. Con i suoi poteri derivati dalla carbonara perfetta, dovrà affrontare nemici sempre più pericolosi.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 5.99,
                'is_published' => true,
                'published_at' => now()->subDays(10)
            ],
            [
                'title' => 'Samurai della Nebbia',
                'plot' => 'Takeshi è un giovane samurai che vive in un Giappone alternativo dove la nebbia nasconde creature misteriose. Quando il suo villaggio viene attaccato da yokai della nebbia, deve intraprendere un viaggio per trovare la spada leggendaria Kiri-Kiri.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 7.50,
                'is_published' => true,
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Cyber Dreams',
                'plot' => 'Nel 2087, Maya è una hacker che vive nelle periferie di Neo-Tokyo. Quando scopre un complotto che minaccia di cancellare tutti i ricordi dell\'umanità, deve infiltrarsi nella rete neurale globale per salvare il mondo.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 6.99,
                'is_published' => true,
                'published_at' => now()->subDays(15)
            ],
            [
                'title' => 'Il Gatto Filosofo',
                'plot' => 'Micio è un gatto che si pone domande esistenziali profonde mentre osserva la vita quotidiana dei suoi umani. Attraverso i suoi occhi, esploriamo temi come l\'amicizia, l\'amore e il significato della vita.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 4.99,
                'is_published' => true,
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'Squadra Paranormale',
                'plot' => 'Un gruppo di investigatori del paranormale si trova ad affrontare casi sempre più strani nella misteriosa città di Arcanum. Fantasmi, demoni e creature dell\'oltretomba li aspettano ad ogni angolo.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 8.99,
                'is_published' => true,
                'published_at' => now()->subDays(20)
            ],
            [
                'title' => 'La Principessa Guerriera',
                'plot' => 'Aria è una principessa che rifiuta la vita di corte per diventare una guerriera. Con la sua spada magica e il suo drago compagno, protegge il regno dalle forze del male che minacciano la pace.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 7.99,
                'is_published' => true,
                'published_at' => now()->subDays(7)
            ],
            [
                'title' => 'Scuola di Magia Moderna',
                'plot' => 'Emma frequenta una scuola di magia nascosta nel centro di Milano. Tra lezioni di pozioni e incantesimi digitali, deve anche affrontare i tipici problemi adolescenziali, complicati dalla magia.',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 6.50,
                'is_published' => true,
                'published_at' => now()->subDays(12)
            ],
            [
                'title' => 'Robot Sentimentale',
                'plot' => 'R-07 è un robot domestico che sviluppa sentimenti. La sua ricerca per comprendere le emozioni umane lo porta in un viaggio attraverso la città, incontrando persone che gli insegnano cosa significa essere "umani".',
                'issue_number' => 1,
                'publication_year' => 2024,
                'price' => 5.50,
                'is_published' => false
            ]
        ];

        foreach ($fumettiData as $index => $fumettoData) {
            // Assign to random user
            $user = $normalUsers->random();

            $fumetto = Fumetto::create(array_merge($fumettoData, [
                'user_id' => $user->id,
                'magazine_id' => $allMagazines->random()->id
            ]));

            // Assign 1-3 random categories
            $categories = $allCategories->random(rand(1, 3));
            $fumetto->categories()->attach($categories->pluck('id'));

            // Add reviews for published fumetti
            if ($fumetto->is_published) {
                $reviewers = $normalUsers->where('id', '!=', $fumetto->user_id)->random(rand(2, 4));

                foreach ($reviewers as $reviewer) {
                    Review::create([
                        'user_id' => $reviewer->id,
                        'fumetto_id' => $fumetto->id,
                        'rating' => rand(3, 5),
                        'comment' => $this->generateRandomReview(),
                        'is_approved' => true
                    ]);
                }

                // Add favorites
                $favoriters = $normalUsers->where('id', '!=', $fumetto->user_id)->random(rand(1, 3));

                foreach ($favoriters as $favoriter) {
                    Favorite::create([
                        'user_id' => $favoriter->id,
                        'fumetto_id' => $fumetto->id
                    ]);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
    }

    private function generateRandomReview(): string
    {
        $reviews = [
            'Fumetto davvero interessante! La trama è coinvolgente e i personaggi ben caratterizzati.',
            'Ottimi disegni e storia avvincente. Non vedo l\'ora di leggere il prossimo numero.',
            'Bel lavoro! Si vede la passione dell\'autore in ogni vignetta.',
            'Storia originale con un ottimo ritmo narrativo. Consigliato!',
            'I disegni sono fantastici e la storia tiene incollati alle pagine.',
            'Personaggi interessanti e trama ben sviluppata. Molto promettente!',
            'Ottima qualità artistica e narrativa. Un fumetto da non perdere.',
            'Storia emozionante con colpi di scena inaspettati. Bravo l\'autore!',
            'Disegni dettagliati e storia coinvolgente. Aspetto con ansia il seguito.',
            'Fumetto ben fatto con una storia originale e personaggi memorabili.'
        ];

        return $reviews[array_rand($reviews)];
    }
}
