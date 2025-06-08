<?php

namespace App\Http\Controllers;

use App\Models\Fumetto;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostra la homepage del portale
     */
    public function index()
    {
        // Ultimi fumetti pubblicati
        $latestFumetti = Fumetto::with(['user', 'categories', 'magazine'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        // Fumetti più popolari (per ora ordiniamo per data, poi si potrà aggiungere un sistema di rating)
        $popularFumetti = Fumetto::with(['user', 'categories', 'magazine'])
            ->published()
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Fumettisti più attivi
        $activeFumettisti = User::withCount('fumetti')
            ->having('fumetti_count', '>', 0)
            ->orderBy('fumetti_count', 'desc')
            ->take(6)
            ->get();

        // Statistiche generali
        $stats = [
            'total_fumetti' => Fumetto::published()->count(),
            'total_fumettisti' => User::whereHas('fumetti')->count(),
            'total_users' => User::count(),
        ];

        return view('home', compact('latestFumetti', 'popularFumetti', 'activeFumettisti', 'stats'));
    }
}
