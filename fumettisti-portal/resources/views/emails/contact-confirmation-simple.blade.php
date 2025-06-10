<h2>Ciao {{ $contact->name }},</h2>

<p>Grazie per averci contattato! Abbiamo ricevuto il tuo messaggio:</p>

<h3>Dettagli:</h3>
<ul>
    <li><strong>Oggetto:</strong> {{ $contact->subject }}</li>
    <li><strong>Data:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</li>
</ul>

<p><strong>Il tuo messaggio:</strong></p>
<blockquote>{{ $contact->message }}</blockquote>

<p>Ti risponderemo al più presto!</p>

<hr>
<p>Fumettisti Portal Team</p>
