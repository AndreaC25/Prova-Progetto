<?php

namespace App\Http\Controllers;

use App\Models\Fumetto;
use App\Models\Category;
use App\Models\Magazine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FumettoController extends Controller
{
    /**
     * Display a listing of fumetti con paginazione
     */
    public function index(Request $request)
    {
        $query = Fumetto::with(['user', 'categories', 'magazine'])
            ->where('is_published', true)
            ->withCount(['reviews', 'favorites']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('plot', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                      ->orderByDesc('reviews_avg_rating');
                break;
            default: // newest
                $query->orderBy('published_at', 'desc');
                break;
        }

        // Paginazione: 10 fumetti per pagina
        $fumetti = $query->paginate(10)->appends($request->query());

        // Aggiungi rating HTML per ogni fumetto
        $fumetti->getCollection()->transform(function ($fumetto) {
            $fumetto->rating_html = $this->generateRatingHtml($fumetto);
            return $fumetto;
        });

        // Ottieni categorie per il filtro
        $categories = Category::where('is_active', true)
            ->withCount(['fumetti' => function($query) {
                $query->where('is_published', true);
            }])
            ->orderByDesc('fumetti_count')
            ->get();

        return view('fumetti.index', compact('fumetti', 'categories'));
    }

    /**
     * Show the form for creating a new fumetto
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::orderBy('name')->get();

        return view('fumetti.create', compact('categories', 'magazines'));
    }

    /**
     * Store a newly created fumetto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'plot' => 'required|string|min:50|max:2000',
            'issue_number' => 'required|integer|min:1',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'price' => 'nullable|numeric|min:0|max:999.99',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $fumetto = new Fumetto($validated);
        $fumetto->user_id = Auth::id();
        $fumetto->is_published = $request->get('is_published', false);
        $fumetto->published_at = $fumetto->is_published ? now() : null;
        $fumetto->save();

        // Attach categories
        $fumetto->categories()->attach($validated['categories']);

        $message = $fumetto->is_published
            ? 'Fumetto pubblicato con successo!'
            : 'Fumetto salvato come bozza.';

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', $message);
    }

    /**
     * Display the specified fumetto
     */
    public function show(Fumetto $fumetto)
    {
        // Check if fumetto is published or belongs to current user
        if (!$fumetto->is_published && (!Auth::check() || $fumetto->user_id !== Auth::id())) {
            abort(404);
        }

        $fumetto->load(['user', 'categories', 'magazine', 'reviews.user']);

        // Fumetti correlati (stessa categoria, escluso quello corrente)
        $relatedFumetti = Fumetto::with(['user', 'categories'])
            ->where('is_published', true)
            ->where('id', '!=', $fumetto->id)
            ->whereHas('categories', function($query) use ($fumetto) {
                $query->whereIn('categories.id', $fumetto->categories->pluck('id'));
            })
            ->withCount(['reviews', 'favorites'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Aggiungi rating HTML
        $relatedFumetti->transform(function ($item) {
            $item->rating_html = $this->generateRatingHtml($item);
            return $item;
        });

        return view('fumetti.show', compact('fumetto', 'relatedFumetti'));
    }

    /**
     * Show the form for editing the specified fumetto
     */
    public function edit(Fumetto $fumetto)
    {
        if ($fumetto->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::orderBy('name')->get();

        return view('fumetti.edit', compact('fumetto', 'categories', 'magazines'));
    }

    /**
     * Update the specified fumetto
     */
    public function update(Request $request, Fumetto $fumetto)
    {
        if ($fumetto->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'plot' => 'required|string|min:50|max:2000',
            'issue_number' => 'required|integer|min:1',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'price' => 'nullable|numeric|min:0|max:999.99',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $fumetto->update($validated);
        $fumetto->categories()->sync($validated['categories']);

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', 'Fumetto aggiornato con successo!');
    }

    /**
     * Remove the specified fumetto
     */
    public function destroy(Fumetto $fumetto)
    {
        if ($fumetto->user_id !== Auth::id()) {
            abort(403);
        }

        $fumetto->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Fumetto eliminato con successo.');
    }

    /**
     * Publish a fumetto
     */
    public function publish(Fumetto $fumetto)
    {
        if ($fumetto->user_id !== Auth::id()) {
            abort(403);
        }

        $fumetto->update([
            'is_published' => true,
            'published_at' => now()
        ]);

        return back()->with('success', 'Fumetto pubblicato con successo!');
    }

    /**
     * Unpublish a fumetto
     */
    public function unpublish(Fumetto $fumetto)
    {
        if ($fumetto->user_id !== Auth::id()) {
            abort(403);
        }

        $fumetto->update([
            'is_published' => false,
            'published_at' => null
        ]);

        return back()->with('success', 'Fumetto rimosso dalla pubblicazione.');
    }

    /**
     * Show user's fumetti dashboard
     */
    public function dashboard()
    {
        $fumetti = Auth::user()->fumetti()
            ->withCount(['reviews', 'favorites'])
            ->latest()
            ->paginate(10);

        return view('fumetti.dashboard', compact('fumetti'));
    }

    /**
     * Genera HTML per le stelle di rating
     */
    private function generateRatingHtml($fumetto)
    {
        $averageRating = $fumetto->reviews()->avg('rating') ?? 0;
        $fullStars = floor($averageRating);
        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

        $html = '';

        // Stelle piene
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star text-warning"></i>';
        }

        // Stella mezza
        if ($hasHalfStar) {
            $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
        }

        // Stelle vuote
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star text-warning"></i>';
        }

        return $html;
    }
}
