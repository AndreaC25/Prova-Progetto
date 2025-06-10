@props([
    'fumetto',
    'size' => 'md',
    'class' => ''
])

@php
    $isFavorite = auth()->check() && auth()->user()->favorites()->where('fumetto_id', $fumetto->id)->exists();

    $sizeClasses = [
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg'
    ];

    $buttonClass = $sizeClasses[$size] ?? '';
@endphp

@auth
<form action="{{ route('favorites.toggle', $fumetto) }}"
      method="POST"
      class="favorite-form d-inline">
    @csrf

    <button type="submit"
            class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} {{ $buttonClass }} {{ $class }}">
        <i class="fas fa-heart"></i>
        {{ $isFavorite ? 'Rimuovi' : 'Aggiungi' }}
    </button>
</form>
@else
<button class="btn btn-outline-secondary {{ $buttonClass }} {{ $class }}"
        onclick="alert('Devi effettuare il login per aggiungere ai preferiti')">
    <i class="fas fa-heart"></i>
    Login per Preferiti
</button>
@endauth
