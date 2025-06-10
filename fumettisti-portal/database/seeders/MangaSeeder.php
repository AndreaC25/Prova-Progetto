<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Magazine;
use App\Models\Fumetto;
use App\Models\Review;
use App\Models\Favorite;

class MangaSeeder extends Seeder
{
    public function run()
    {
        // Ottieni utenti esistenti o creane di nuovi
        $users = User::where('is_admin', false)->get();
        if ($users->count() < 5) {
            // Crea utenti aggiuntivi se necessario
            for ($i = 1; $i <= 5; $i++) {
                $user = User::create([
                    'name' => "Mangaka {$i}",
                    'email' => "mangaka{$i}@example.com",
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);

                $user->profile()->create([
                    'bio' => "Artista specializzato in manga e fumetti giapponesi.",
                    'location' => 'Giappone',
                    'is_public' => true
                ]);
            }
            $users = User::where('is_admin', false)->get();
        }

        // Ottieni o crea categoria Manga
        $mangaCategory = Category::firstOrCreate([
            'slug' => 'manga'
        ], [
            'name' => 'Manga',
            'description' => 'Fumetti in stile giapponese',
            'icon' => 'torii-gate',
            'color' => '#ff6b35',
            'is_active' => true
        ]);

        // Ottieni o crea magazine
        $magazine = Magazine::firstOrCreate([
            'name' => 'Weekly Manga Jump'
        ], [
            'description' => 'La rivista manga più famosa al mondo',
            'website' => 'https://manga-jump.com'
        ]);

        // Lista di 20 manga con dati realistici
        $mangaList = [
            [
                'title' => 'Dragon Sphere Chronicles',
                'plot' => 'Un giovane guerriero parte alla ricerca di sette sfere magiche che possono esaudire qualsiasi desiderio. Durante il suo viaggio incontra alleati potenti e nemici temibili in battaglie epiche.',
                'price' => 8.99,
                'image_id' => 1
            ],
            [
                'title' => 'Ninja Storm Academy',
                'plot' => 'In un mondo dove i ninja governano i villaggi nascosti, un giovane orfano sogna di diventare il leader del suo villaggio attraverso determinazione e amicizia.',
                'price' => 7.50,
                'image_id' => 2
            ],
            [
                'title' => 'One Ocean Adventure',
                'plot' => 'Un pirata di gomma naviga i mari alla ricerca del tesoro più grande di tutti i tempi, accompagnato dalla sua ciurma di amici fedeli con poteri straordinari.',
                'price' => 9.99,
                'image_id' => 3
            ],
            [
                'title' => 'Death Chronicle',
                'plot' => 'Uno studente brillante trova un quaderno soprannaturale che può uccidere chiunque scrivendo il suo nome. Una battaglia psicologica tra giustizia e potere inizia.',
                'price' => 12.99,
                'image_id' => 4
            ],
            [
                'title' => 'Titan Slayer',
                'plot' => 'L\'umanità vive rinchiusa dietro enormi mura per proteggersi da giganti divoratori di uomini. Un giovane soldato giura di sterminare tutti i titani.',
                'price' => 11.50,
                'image_id' => 5
            ],
            [
                'title' => 'Spirit Hunter',
                'plot' => 'In un Giappone moderno infestato da demoni, giovani cacciatori usano tecniche antiche e spade speciali per proteggere l\'umanità dagli spiriti maligni.',
                'price' => 10.99,
                'image_id' => 6
            ],
            [
                'title' => 'Hero Academy Plus',
                'plot' => 'In un mondo dove l\'80% della popolazione ha superpoteri, un ragazzo senza quirk sogna di diventare il più grande eroe di tutti i tempi.',
                'price' => 8.50,
                'image_id' => 7
            ],
            [
                'title' => 'Fullmetal Brothers',
                'plot' => 'Due fratelli alchimisti cercano la Pietra Filosofale per riportare i loro corpi alla normalità dopo un esperimento di alchimia andato male.',
                'price' => 13.99,
                'image_id' => 8
            ],
            [
                'title' => 'Hunter X Quest',
                'plot' => 'Un giovane parte alla ricerca del padre scomparso diventando un Hunter, incontrando amici e nemici in un mondo pieno di creature misteriose e poteri nascosti.',
                'price' => 9.50,
                'image_id' => 9
            ],
            [
                'title' => 'Tokyo Phantom',
                'plot' => 'In una Tokyo moderna, creature soprannaturali chiamate ghoul vivono nascosti tra gli umani, nutrendosi di carne umana per sopravvivere.',
                'price' => 11.99,
                'image_id' => 10
            ],
            [
                'title' => 'Sword Masters Online',
                'plot' => 'Migliaia di giocatori rimangono intrappolati in un MMORPG dove la morte nel gioco significa morte nella realtà. Solo completando il gioco possono tornare a casa.',
                'price' => 7.99,
                'image_id' => 11
            ],
            [
                'title' => 'Psychic Mob',
                'plot' => 'Un ragazzo introverso con poteri psichici incredibili cerca di vivere una vita normale mentre impara a controllare le sue abilità soprannaturali.',
                'price' => 8.99,
                'image_id' => 12
            ],
            [
                'title' => 'Promised Escape',
                'plot' => 'Bambini orfani scoprono la terribile verità sul loro orfanotrofio e pianificano una fuga impossibile per salvare se stessi e i loro fratelli.',
                'price' => 10.50,
                'image_id' => 13
            ],
            [
                'title' => 'Fire Brigade 8',
                'plot' => 'In un mondo dove le persone si trasformano spontaneamente in fiamme, una brigata speciale di vigili del fuoco combatte questi fenomeni soprannaturali.',
                'price' => 9.99,
                'image_id' => 14
            ],
            [
                'title' => 'Chain Saw Warrior',
                'plot' => 'Un giovane con poteri demoniaci lavora come cacciatore di demoni pubblico, usando una motosega come arma in battaglie sanguinose e surreali.',
                'price' => 12.50,
                'image_id' => 15
            ],
            [
                'title' => 'Jujutsu Academy',
                'plot' => 'Studenti di una scuola segreta imparano a esorcizzare maledizioni usando tecniche antiche e energia spirituale in battaglie contro spiriti maligni.',
                'price' => 11.99,
                'image_id' => 16
            ],
            [
                'title' => 'Seven Deadly Powers',
                'plot' => 'Un gruppo di cavalieri leggendari con poteri che rappresentano i sette peccati capitali lotta per salvare il regno da una tirannia oppressiva.',
                'price' => 10.99,
                'image_id' => 17
            ],
            [
                'title' => 'Stone Age Revival',
                'plot' => 'Dopo che tutta l\'umanità è stata trasformata in pietra, un genio adolescente si risveglia e cerca di ricostruire la civiltà usando la scienza.',
                'price' => 8.50,
                'image_id' => 18
            ],
            [
                'title' => 'Volleyball Kings',
                'plot' => 'Una squadra di pallavolo liceale con giocatori di bassa statura ma grande determinazione sfida le squadre migliori del Giappone.',
                'price' => 7.99,
                'image_id' => 19
            ],
            [
                'title' => 'Basketball Miracle',
                'plot' => 'Un giocatore di basket con talento nascosto si unisce a una squadra sconosciuta per sfidare la "Generation of Miracles" e diventare il migliore.',
                'price' => 9.50,
                'image_id' => 20
            ]
        ];

        // Crea i manga
        foreach ($mangaList as $index => $mangaData) {
            $user = $users->random();

            $manga = Fumetto::create([
                'title' => $mangaData['title'],
                'plot' => $mangaData['plot'],
                'issue_number' => rand(1, 50),
                'publication_year' => rand(2020, 2024),
                'price' => $mangaData['price'],
                'user_id' => $user->id,
                'magazine_id' => $magazine->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 365)),
                // Aggiungiamo un campo per l'ID immagine per riferimento
                'cover_image' => "https://picsum.photos/300/400?random=" . $mangaData['image_id']
            ]);

            // Associa alla categoria Manga
            $manga->categories()->attach($mangaCategory->id);

            // Aggiungi alcune recensioni casuali
            $reviewersCount = rand(2, 8);
            $availableUsers = $users->where('id', '!=', $manga->user_id)->random($reviewersCount);

            foreach ($availableUsers as $reviewer) {
                Review::create([
                    'user_id' => $reviewer->id,
                    'fumetto_id' => $manga->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->getRandomMangaReview(),
                    'is_approved' => true,
                    'created_at' => now()->subDays(rand(1, 100))
                ]);
            }

            // Aggiungi ai preferiti di alcuni utenti
            $favoritersCount = rand(1, 5);
            $favoriters = $users->where('id', '!=', $manga->user_id)->random($favoritersCount);

            foreach ($favoriters as $favoriter) {
                Favorite::create([
                    'user_id' => $favoriter->id,
                    'fumetto_id' => $manga->id,
                    'created_at' => now()->subDays(rand(1, 200))
                ]);
            }
        }

        $this->command->info('✅ Creati 20 manga con successo!');
        $this->command->info('📚 Ogni manga ha recensioni e preferiti casuali');
        $this->command->info('🎨 Immagini collegate tramite Picsum Photos');
    }

    private function getRandomMangaReview()
    {
        $reviews = [
            'Questo manga è assolutamente fantastico! La trama è coinvolgente e i personaggi sono ben sviluppati.',
            'Arte straordinaria e storia emozionante. Non riesco a smettere di leggerlo!',
            'Uno dei migliori manga che abbia mai letto. Altamente raccomandato!',
            'La narrazione è fluida e i combattimenti sono spettacolari. Un must-read!',
            'Personaggi memorabili e una trama che ti tiene incollato. Perfetto!',
            'Il character development è eccezionale. Ogni capitolo è meglio del precedente.',
            'Una storia profonda con messaggi importanti. Non è solo intrattenimento.',
            'L\'arte è dettagliata e la storia è avvincente. Un capolavoro moderno.',
            'Perfetto equilibrio tra azione, comedy e momenti emotivi.',
            'La world-building è incredibile. Un universo ricco e ben costruito.',
            'Ogni personaggio ha la sua personalità unica. Scrittura eccellente.',
            'Non riesco a aspettare il prossimo volume. Una serie imperdibile!',
        ];

        return $reviews[array_rand($reviews)];
    }
}
