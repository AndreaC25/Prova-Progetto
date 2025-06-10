<?php

namespace App\Http\Controllers;

use App\Models\Fumetto;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Homepage semplice senza query complesse
        $data = [
            'featured_fumetti' => collect([]),
            'latest_fumetti' => collect([]),
            'top_rated_fumetti' => collect([]),
            'popular_fumetti' => collect([]),
            'featured_artists' => collect([]),
            'categories' => collect([]),
            'stats' => [
                'total_fumetti' => 0,
                'total_artists' => User::count(),
                'total_reviews' => 0,
                'total_categories' => 0
            ]
        ];

        return view('home', $data);
    }
}
