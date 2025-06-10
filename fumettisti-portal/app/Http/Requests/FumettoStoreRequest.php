<?php
// app/Http/Controllers/FumettoController.php

namespace App\Http\Controllers;

use App\Http\Requests\FumettoStoreRequest;
use App\Http\Requests\FumettoUpdateRequest;
use App\Models\Fumetto;
use App\Models\Category;
use App\Models\Magazine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FumettoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fumetto::with(['user', 'categories', 'magazine'])
            ->published()
            ->latest('published_at');

        // Filtro per ricerca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('plot', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro per categoria
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro per anno
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }

        // Filtro per rivista
        if ($request->filled('magazine')) {
            $query->where('magazine_id', $request->magazine);
        }

        // Filtro per autore
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Ordinamento
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'year':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'rating':
                $query->withAvg('approvedReviews', 'rating')
                      ->orderBy('approved_reviews_avg_rating', 'desc');
                break;
            case 'popular':
                $query->withCount('favorites')
                      ->orderBy('favorites_count', 'desc');
                break;
            default:
                $query->latest('published_at');
                break;
        }

        $fumetti = $query->paginate(12)->withQueryString();

        // Dati per i filtri
        $categories = Category::has('fumetti')->orderBy('name')->get();
        $magazines = Magazine::has('fumetti')->orderBy('name')->get();
        $years = Fumetto::published()
            ->selectRaw('DISTINCT publication_year')
            ->orderBy('publication_year', 'desc')
            ->pluck('publication_year');

        return view('fumetti.index', compact('fumetti', 'categories', 'magazines', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verifica limite fumetti per utente
        $userFumettiCount = Auth::user()->fumetti()->count();
        if ($userFumettiCount >= 50) {
            return redirect()->route('profile.show')
                ->with('error', 'Hai raggiunto il limite massimo di 50 fumetti. Contatta il supporto per aumentare il limite.');
        }

        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::active()->orderBy('name')->get();

        // Suggerimenti per il prossimo numero
        $suggestedIssueNumber = Auth::user()->fumetti()->max('issue_number') + 1;

        return view('fumetti.create', compact('categories', 'magazines', 'suggestedIssueNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FumettoStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Gestione caricamento immagine di copertina
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');

            // Genera nome file unico
            $filename = time() . '_' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();

            // Salva con nome personalizzato
            $imagePath = $image->storeAs('cover-images', $filename, 'public');
            $data['cover_image'] = $imagePath;
        }

        // Rimuovi publication_status dai dati (viene gestito nella request)
        unset($data['publication_status']);

        $fumetto = Fumetto::create($data);

        // Associa le categorie selezionate
        if ($request->filled('categories')) {
            $fumetto->categories()->attach($request->input('categories'));
        }

        // Messaggio di successo personalizzato
        $message = 'Fumetto creato con successo!';
        if ($fumetto->is_published) {
            $message = 'Fumetto pubblicato con successo! È ora visibile a tutti gli utenti.';
        } elseif ($fumetto->published_at && $fumetto->published_at > now()) {
            $message = 'Fumetto salvato! Sarà pubblicato automaticamente il ' . $fumetto->published_at->format('d/m/Y H:i');
        } else {
            $message = 'Fumetto salvato come bozza. Potrai pubblicarlo in seguito.';
        }

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Fumetto $fumetto)
    {
        // Se il fumetto non è pubblicato, permetti la visualizzazione solo al proprietario
        if (!$fumetto->is_published && (!Auth::check() || $fumetto->user_id !== Auth::id())) {
            abort(404, 'Fumetto non trovato.');
        }

        $fumetto->load([
            'user.profile',
            'categories',
            'magazine',
            'approvedReviews' => function($query) {
                $query->with('user')->latest()->take(5);
            }
        ]);

        // Fumetti correlati dello stesso autore
        $relatedFumetti = Fumetto::where('user_id', $fumetto->user_id)
            ->where('id', '!=', $fumetto->id)
            ->published()
            ->take(4)
            ->get();

        // Verifica se l'utente ha già recensito
        $userReview = null;
        if (auth()->check()) {
            $userReview = $fumetto->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        // Statistiche recensioni
        $ratingStats = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingStats[$i] = $fumetto->approvedReviews()
                ->where('rating', $i)
                ->count();
        }

        return view('fumetti.show', compact(
            'fumetto',
            'relatedFumetti',
            'userReview',
            'ratingStats'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fumetto $fumetto)
    {
        // Autorizzazione: solo il proprietario può modificare
        if ($fumetto->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a modificare questo fumetto.');
        }

        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::active()->orderBy('name')->get();

        return view('fumetti.edit', compact('fumetto', 'categories', 'magazines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FumettoUpdateRequest $request, Fumetto $fumetto)
    {
        $data = $request->validated();

        // Gestione caricamento nuova immagine di copertina
        if ($request->hasFile('cover_image')) {
            // Elimina la vecchia immagine se presente
            if ($fumetto->cover_image && Storage::disk('public')->exists($fumetto->cover_image)) {
                Storage::disk('public')->delete($fumetto->cover_image);
            }

            // Salva la nuova immagine
            $image = $request->file('cover_image');
            $filename = time() . '_' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('cover-images', $filename, 'public');
            $data['cover_image'] = $imagePath;
        }

        // Rimuovi publication_status dai dati
        unset($data['publication_status']);

        $fumetto->update($data);

        // Aggiorna le categorie
        if ($request->filled('categories')) {
            $fumetto->categories()->sync($request->input('categories'));
        } else {
            $fumetto->categories()->detach();
        }

        // Messaggio personalizzato
        $message = 'Fumetto aggiornato con successo!';
        if ($fumetto->is_published) {
            $message = 'Fumetto aggiornato e pubblicato con successo!';
        } elseif ($fumetto->published_at && $fumetto->published_at > now()) {
            $message = 'Fumetto aggiornato! Sarà pubblicato il ' . $fumetto->published_at->format('d/m/Y H:i');
        }

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fumetto $fumetto)
    {
        // Autorizzazione: solo il proprietario può eliminare
        if ($fumetto->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a eliminare questo fumetto.');
        }

        $fumettoTitle = $fumetto->title;

        // Elimina l'immagine di copertina se presente
        if ($fumetto->cover_image && Storage::disk('public')->exists($fumetto->cover_image)) {
            Storage::disk('public')->delete($fumetto->cover_image);
        }

        $fumetto->delete();

        return redirect()->route('profile.show')
            ->with('success', "Il fumetto \"{$fumettoTitle}\" è stato eliminato con successo.");
    }

    /**
     * Pubblica una bozza
     */
    public function publish(Fumetto $fumetto)
    {
        // Autorizzazione
        if ($fumetto->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a pubblicare questo fumetto.');
        }

        // Verifica che sia una bozza
        if ($fumetto->is_published) {
            return back()->with('info', 'Il fumetto è già pubblicato.');
        }

        $fumetto->update([
            'is_published' => true,
            'published_at' => now()
        ]);

        return back()->with('success', 'Fumetto pubblicato con successo!');
    }

    /**
     * Rimuovi dalla pubblicazione (torna a bozza)
     */
    public function unpublish(Fumetto $fumetto)
    {
        // Autorizzazione
        if ($fumetto->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a modificare questo fumetto.');
        }

        $fumetto->update([
            'is_published' => false,
            'published_at' => null
        ]);

        return back()->with('success', 'Fumetto rimosso dalla pubblicazione.');
    }

    /**
     * Dashboard fumettista
     */
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'fumetti_totali' => $user->fumetti()->count(),
            'fumetti_pubblicati' => $user->fumetti()->published()->count(),
            'fumetti_bozza' => $user->fumetti()->where('is_published', false)->count(),
            'fumetti_programmati' => $user->fumetti()
                ->where('is_published', false)
                ->whereNotNull('published_at')
                ->where('published_at', '>', now())
                ->count(),
            'visualizzazioni_totali' => 0, // Da implementare con tracking
            'recensioni_ricevute' => $user->fumetti()
                ->withCount('approvedReviews')
                ->get()
                ->sum('approved_reviews_count'),
            'rating_medio' => $user->fumetti()
                ->published()
                ->withAvg('approvedReviews', 'rating')
                ->get()
                ->avg('approved_reviews_avg_rating') ?? 0,
        ];

        $recentFumetti = $user->fumetti()
            ->with(['categories', 'magazine'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingPublications = $user->fumetti()
            ->where('is_published', false)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now())
            ->orderBy('published_at')
            ->take(3)
            ->get();

        return view('fumetti.dashboard', compact('stats', 'recentFumetti', 'upcomingPublications'));
    }
}
