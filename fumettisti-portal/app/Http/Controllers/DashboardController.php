<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Statistiche utente
        $myFumetti = $user->fumetti()->count();
        $publishedFumetti = $user->fumetti()->where('is_published', true)->count();
        $draftFumetti = $user->fumetti()->where('is_published', false)->count();

        // Ultimi fumetti dell'utente
        $latestFumetti = $user->fumetti()
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('dashboard.index', compact('user', 'myFumetti', 'publishedFumetti', 'draftFumetti', 'latestFumetti'));
    }
}
