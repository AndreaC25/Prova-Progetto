<?php

namespace App\Http\Controllers;

use App\Models\Fumetto;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with('fumetto')
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Fumetto $fumetto)
    {
        $user = Auth::user();
        $favorite = $user->favorites()->where('fumetto_id', $fumetto->id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Rimosso dai preferiti';
            $isFavorite = false;
        } else {
            $user->favorites()->create(['fumetto_id' => $fumetto->id]);
            $message = 'Aggiunto ai preferiti';
            $isFavorite = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => $isFavorite,
                'favorites_count' => $fumetto->favorites()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    public function clear()
    {
        Auth::user()->favorites()->delete();

        return back()->with('success', 'Tutti i preferiti sono stati rimossi');
    }
}
