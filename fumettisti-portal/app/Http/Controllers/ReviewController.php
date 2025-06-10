<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Fumetto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review
     */
    public function store(Request $request, Fumetto $fumetto)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Verifica che l'utente non abbia già recensito
        $existingReview = Review::where('user_id', Auth::id())
            ->where('fumetto_id', $fumetto->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Hai già recensito questo fumetto!');
        }

        Review::create([
            'user_id' => Auth::id(),
            'fumetto_id' => $fumetto->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Recensione aggiunta con successo!');
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a modificare questa recensione.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Recensione aggiornata con successo!');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a eliminare questa recensione.');
        }

        $review->delete();

        return back()->with('success', 'Recensione eliminata con successo!');
    }
}
