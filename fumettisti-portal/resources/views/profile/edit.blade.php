{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifica Profilo - Fumettisti Portal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Modifica Profilo</h2>
                    <p class="text-muted">Aggiorna le tue informazioni personali</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna al Profilo
                </a>
            </div>

            <!-- Progress Bar Completamento -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Completamento Profilo</h6>
                        <span class="badge bg-primary">{{ $user->profile->completion_percentage ?? 0 }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $user->profile->completion_percentage ?? 0 }}%"></div>
                    </div>
                    <small class="text-muted mt-1">Completa il tuo profilo per aumentare la visibilità</small>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Colonna Sinistra -->
                    <div class="col-lg-8">
                        <!-- Informazioni Base -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Informazioni Personali
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nome Completo *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Numero di Telefono</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $user->profile->phone) }}"
                                               placeholder="+39 123 456 7890">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Il numero di telefono deve essere unico</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">Data di Nascita</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                               id="birth_date" name="birth_date"
                                               value="{{ old('birth_date', $user->profile->birth_date?->format('Y-m-d')) }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="bio" class="form-label">Descrizione</label>
                                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                                  id="bio" name="bio" rows="4" maxlength="1000"
                                                  placeholder="Raccontaci qualcosa di te, delle tue passioni per i fumetti...">{{ old('bio', $user->profile->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            <span id="bio-count">{{ strlen(old('bio', $user->profile->bio ?? '')) }}</span>/1000 caratteri
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informazioni Aziendali -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-building me-2"></i>Informazioni Aziendali
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Indirizzo Società (Opzionale)</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror"
                                              id="company_address" name="company_address" rows="3" maxlength="500"
                                              placeholder="Via Roma 123, 00100 Roma (RM), Italia">{{ old('company_address', $user->profile->company_address) }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Indirizzo della tua attività (se applicabile)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="website" class="form-label">Sito Web</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror"
                                           id="website" name="website"
                                           value="{{ old('website', $user->profile->website) }}"
                                           placeholder="https://www.tuosito.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-share-alt me-2"></i>Social Network
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="facebook" class="form-label">
                                            <i class="fab fa-facebook text-primary me-2"></i>Facebook
                                        </label>
                                        <input type="url" class="form-control @error('social_links.facebook') is-invalid @enderror"
                                               id="facebook" name="social_links[facebook]"
                                               value="{{ old('social_links.facebook', $user->profile->social_links['facebook'] ?? '') }}"
                                               placeholder="https://facebook.com/username">
                                        @error('social_links.facebook')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="instagram" class="form-label">
                                            <i class="fab fa-instagram text-danger me-2"></i>Instagram
                                        </label>
                                        <input type="url" class="form-control @error('social_links.instagram') is-invalid @enderror"
                                               id="instagram" name="social_links[instagram]"
                                               value="{{ old('social_links.instagram', $user->profile->social_links['instagram'] ?? '') }}"
                                               placeholder="https://instagram.com/username">
                                        @error('social_links.instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="twitter" class="form-label">
                                            <i class="fab fa-twitter text-info me-2"></i>Twitter
                                        </label>
                                        <input type="url" class="form-control @error('social_links.twitter') is-invalid @enderror"
                                               id="twitter" name="social_links[twitter]"
                                               value="{{ old('social_links.twitter', $user->profile->social_links['twitter'] ?? '') }}"
                                               placeholder="https://twitter.com/username">
                                        @error('social_links.twitter')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="linkedin" class="form-label">
                                            <i class="fab fa-linkedin text-primary me-2"></i>LinkedIn
                                        </label>
                                        <input type="url" class="form-control @error('social_links.linkedin') is-invalid @enderror"
                                               id="linkedin" name="social_links[linkedin]"
                                               value="{{ old('social_links.linkedin', $user->profile->social_links['linkedin'] ?? '') }}"
                                               placeholder="https://linkedin.com/in/username">
                                        @error('social_links.linkedin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonna Destra -->
                    <div class="col-lg-4">
                        <!-- Avatar -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-camera me-2"></i>Immagine Profilo
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="avatar-upload-container mb-3">
                                    <div class="avatar-preview">
                                        <img src="{{ $user->profile->avatar_url }}" alt="Avatar"
                                             class="rounded-circle" width="150" height="150" id="avatar
