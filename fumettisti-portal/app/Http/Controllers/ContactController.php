<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Models\Contact;
use App\Mail\ContactConfirmation;
use App\Mail\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Mostra il form di contatto
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Salva il messaggio e invia le email
     */
    public function store(ContactStoreRequest $request)
    {
        // Salva il messaggio nel database
        /** @var Contact $contact */
        $contact = Contact::create($request->validated());

        try {
            // Invia email di conferma all'utente
            Mail::to($contact->email)->send(new ContactConfirmation($contact));

            // Invia notifica agli amministratori
            $adminEmails = ['admin@fumettistiportal.it', 'info@fumettistiportal.it'];
            foreach ($adminEmails as $adminEmail) {
                Mail::to($adminEmail)->send(new ContactNotification($contact));
            }

            return redirect()->route('contact.success')
                ->with('success', 'Messaggio inviato con successo! Riceverai una conferma via email.');

        } catch (\Exception $e) {
            // Se l'invio email fallisce, salva comunque il messaggio
            return redirect()->route('contact.create')
                ->with('warning', 'Messaggio salvato, ma si è verificato un problema con l\'invio email.')
                ->withInput();
        }
    }

    /**
     * Pagina di successo
     */
    public function success()
    {
        return view('contact.success');
    }

    /**
     * Dashboard admin per gestire i contatti (solo per admin)
     */
    public function index()
    {
        // Nota: qui dovresti aggiungere middleware admin
        $contacts = Contact::latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Mostra dettaglio contatto (solo per admin)
     */
    public function show(Contact $contact)
    {
        // Marca come letto se non lo è già
        if ($contact->status === 'nuovo') {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Aggiorna status del contatto (solo per admin)
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:nuovo,letto,risposto',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($request->status === 'risposto') {
            $contact->markAsReplied($request->admin_notes);
        } else {
            $contact->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);
        }

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Contatto aggiornato con successo!');
    }
}
