<?php

namespace App\Http\Controllers;

use App\Http\Requests\FumettoStoreRequest;
use App\Http\Requests\FumettoUpdateRequest;
use App\Models\Fumetto;
use App\Models\Category;
use App\Models\Magazine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $query->search($request->search);
        }

        // Filtro per categoria
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtro per anno
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // Filtro per rivista
        if ($request->filled('magazine')) {
            $query->byMagazine($request->magazine);
        }

        $fumetti = $query->paginate(12)->withQueryString();

        // Dati per i filtri
        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::active()->orderBy('name')->get();
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
        $categories = Category::orderBy('name')->get();
        $magazines = Magazine::active()->orderBy('name')->get();

        return view('fumetti.create', compact('categories', 'magazines'));
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
            $imagePath = $request->file('cover_image')->store('cover-images', 'public');
            $data['cover_image'] = $imagePath;
        }

        $fumetto = Fumetto::create($data);

        // Associa le categorie selezionate
        if ($request->filled('categories')) {
            $fumetto->categories()->attach($request->categories);
        }

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', 'Fumetto creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fumetto $fumetto)
    {
        $fumetto->load(['user.profile', 'categories', 'magazine']);

        // Fumetti correlati dello stesso autore
        $relatedFumetti = Fumetto::where('user_id', $fumetto->user_id)
            ->where('id', '!=', $fumetto->id)
            ->published()
            ->take(4)
            ->get();

        return view('fumetti.show', compact('fumetto', 'relatedFumetti'));
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
        // Autorizzazione: solo il proprietario può modificare
        if ($fumetto->user_id !== Auth::id()) {
            abort(403, 'Non sei autorizzato a modificare questo fumetto.');
        }

        $data = $request->validated();

        // Gestione caricamento nuova immagine di copertina
        if ($request->hasFile('cover_image')) {
            // Elimina la vecchia immagine se presente
            if ($fumetto->cover_image && Storage::disk('public')->exists($fumetto->cover_image)) {
                Storage::disk('public')->delete($fumetto->cover_image);
            }

            // Salva la nuova immagine
            $imagePath = $request->file('cover_image')->store('cover-images', 'public');
            $data['cover_image'] = $imagePath;
        }

        $fumetto->update($data);

        // Aggiorna le categorie
        if ($request->filled('categories')) {
            $fumetto->categories()->sync($request->categories);
        } else {
            $fumetto->categories()->detach();
        }

        return redirect()->route('fumetti.show', $fumetto)
            ->with('success', 'Fumetto aggiornato con successo!');
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

        // Elimina l'immagine di copertina se presente
        if ($fumetto->cover_image && Storage::disk('public')->exists($fumetto->cover_image)) {
            Storage::disk('public')->delete($fumetto->cover_image);
        }

        $fumetto->delete();

        return redirect()->route('profile.show')
            ->with('success', 'Fumetto eliminato con successo!');
    }
}
