{{-- resources/views/fumetti/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Aggiungi Nuovo Fumetto - Fumettisti Portal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-plus-circle text-primary me-2"></i>Aggiungi Nuovo Fumetto
                    </h2>
                    <p class="text-muted">Condividi la tua creazione con la community</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Torna al Profilo
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps mb-4">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Info Base</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Copertina</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Dettagli</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Pubblicazione</div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('fumetti.store') }}" method="POST" enctype="multipart/form-data" id="fumetto-form">
                @csrf

                <!-- Step 1: Informazioni Base -->
                <div class="step-content" id="step-1">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informazioni Base
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">
                                        Titolo del Fumetto *
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           placeholder="Es: Le Avventure di SuperEroe"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Scegli un titolo accattivante e memorabile
                                    </small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="issue_number" class="form-label">
                                        Numero Volume/Issue *
                                    </label>
                                    <input type="number"
                                           class="form-control form-control-lg @error('issue_number') is-invalid @enderror"
                                           id="issue_number"
                                           name="issue_number"
                                           value="{{ old('issue_number', 1) }}"
                                           min="1"
                                           max="9999"
                                           required>
                                    @error('issue_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="publication_year" class="form-label">
                                        Anno di Pubblicazione *
                                    </label>
                                    <select class="form-select form-select-lg @error('publication_year') is-invalid @enderror"
                                            id="publication_year"
                                            name="publication_year"
                                            required>
                                        <option value="">Seleziona anno...</option>
                                        @for($year = date('Y'); $year >= 1950; $year--)
                                            <option value="{{ $year }}" {{ old('publication_year', date('Y')) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('publication_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="magazine_id" class="form-label">
                                        Rivista/Casa Editrice
                                    </label>
                                    <select class="form-select form-select-lg @error('magazine_id') is-invalid @enderror"
                                            id="magazine_id"
                                            name="magazine_id">
                                        <option value="">Seleziona rivista...</option>
                                        @foreach($magazines as $magazine)
                                            <option value="{{ $magazine->id }}" {{ old('magazine_id') == $magazine->id ? 'selected' : '' }}>
                                                {{ $magazine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('magazine_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Opzionale - Specifica se pubblicato su una rivista
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Copertina -->
                <div class="step-content" id="step-2" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-image me-2"></i>Copertina del Fumetto
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="upload-area" id="upload-area">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5>Carica Copertina</h5>
                                            <p class="text-muted">
                                                Trascina qui la tua immagine o clicca per selezionarla
                                            </p>
                                            <input type="file"
                                                   class="form-control @error('cover_image') is-invalid @enderror"
                                                   id="cover_image"
                                                   name="cover_image"
                                                   accept="image/*"
                                                   style="display: none;">
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('cover_image').click()">
                                                <i class="fas fa-folder-open me-2"></i>Scegli File
                                            </button>
                                        </div>
                                    </div>
                                    @error('cover_image')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-2">
                                        Formati supportati: JPG, PNG, GIF. Max 5MB.<br>
                                        Dimensioni consigliate: 400x600px (rapporto 2:3)
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <div class="preview-container">
                                        <h6>Anteprima Copertina</h6>
                                        <div class="cover-preview" id="cover-preview">
                                            <div class="preview-placeholder">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                <p class="text-muted mt-2">Nessuna immagine selezionata</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Dettagli -->
                <div class="step-content" id="step-3" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-book-open me-2"></i>Trama e Dettagli
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="plot" class="form-label">
                                    Trama del Fumetto *
                                </label>
                                <textarea class="form-control @error('plot') is-invalid @enderror"
                                          id="plot"
                                          name="plot"
                                          rows="8"
                                          placeholder="Descrivi la trama del tuo fumetto... Cosa succede? Chi sono i protagonisti? Qual è il conflitto principale?"
                                          maxlength="2000"
                                          required>{{ old('plot') }}</textarea>
                                @error('plot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-text text-muted">
                                        Descrivi la storia in modo coinvolgente per attirare i lettori
                                    </small>
                                    <small class="text-muted">
                                        <span id="plot-count">{{ strlen(old('plot', '')) }}</span>/2000 caratteri
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">
                                        Prezzo (€)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number"
                                               class="form-control @error('price') is-invalid @enderror"
                                               id="price"
                                               name="price"
                                               value="{{ old('price') }}"
                                               min="0"
                                               step="0.01"
                                               placeholder="9.99">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Opzionale - Prezzo di vendita suggerito
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Categorie</label>
                                    <div class="categories-grid">
                                        @foreach($categories as $category)
                                            <div class="form-check category-item">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       value="{{ $category->id }}"
                                                       id="category_{{ $category->id }}"
                                                       name="categories[]"
                                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category_{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="form-text text-muted">
                                        Seleziona una o più categorie che descrivono il tuo fumetto
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Pubblicazione -->
                <div class="step-content" id="step-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-rocket me-2"></i>Opzioni di Pubblicazione
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="publication-options">
                                        <div class="form-check publication-option">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="publication_status"
                                                   id="publish_now"
                                                   value="published"
                                                   {{ old('publication_status', 'published') == 'published' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="publish_now">
                                                <div class="option-content">
                                                    <h6><i class="fas fa-globe text-success me-2"></i>Pubblica Ora</h6>
                                                    <p class="text-muted mb-0">Il fumetto sarà immediatamente visibile a tutti gli utenti</p>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="form-check publication-option">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="publication_status"
                                                   id="save_draft"
                                                   value="draft"
                                                   {{ old('publication_status') == 'draft' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="save_draft">
                                                <div class="option-content">
                                                    <h6><i class="fas fa-edit text-warning me-2"></i>Salva come Bozza</h6>
                                                    <p class="text-muted mb-0">Salva il fumetto per modificarlo in seguito. Visibile solo a te</p>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="form-check publication-option">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="publication_status"
                                                   id="schedule_publish"
                                                   value="scheduled"
                                                   {{ old('publication_status') == 'scheduled' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="schedule_publish">
                                                <div class="option-content">
                                                    <h6><i class="fas fa-clock text-info me-2"></i>Programmata</h6>
                                                    <p class="text-muted mb-0">Programma la pubblicazione per una data futura</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="schedule-options mt-3" id="schedule-options" style="display: none;">
                                        <label for="published_at" class="form-label">Data e Ora di Pubblicazione</label>
                                        <input type="datetime-local"
                                               class="form-control @error('published_at') is-invalid @enderror"
                                               id="published_at"
                                               name="published_at"
                                               value="{{ old('published_at') }}"
                                               min="{{ date('Y-m-d\TH:i') }}">
                                        @error('published_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="summary-card">
                                        <h6>Riepilogo</h6>
                                        <div class="summary-item">
                                            <strong>Titolo:</strong>
                                            <span id="summary-title">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong>Numero:</strong>
                                            <span id="summary-issue">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong>Anno:</strong>
                                            <span id="summary-year">-</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong>Copertina:</strong>
                                            <span id="summary-cover">Non caricata</span>
                                        </div>
                                        <div class="summary-item">
                                            <strong>Trama:</strong>
                                            <span id="summary-plot">Non inserita</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation mt-4">
                    <button type="button" class="btn btn-outline-secondary" id="prev-btn" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i>Indietro
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary" id="next-btn">
                            Avanti <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                            <i class="fas fa-save me-2"></i>Salva Fumetto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-css')
<style>
.progress-steps {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 20px;
    position: relative;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 15px;
    left: calc(100% + 10px);
    width: 40px;
    height: 2px;
    background: #dee2e6;
    z-index: 1;
}

.step.active:not(:last-child)::after {
    background: var(--primary-color);
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 5px;
    position: relative;
    z-index: 2;
}

.step.active .step-number {
    background: var(--primary-color);
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.step.active .step-label {
    color: var(--primary-color);
    font-weight: 600;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover,
.upload-area.dragover {
    border-color: var(--primary-color);
    background: #f8f9ff;
}

.cover-preview {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.preview-placeholder {
    text-align: center;
}

.cover-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    max-height: 200px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
}

.category-item {
    margin: 0;
}

.publication-option {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.publication-option:hover {
    border-color: var(--primary-color);
    background: #f8f9ff;
}

.publication-option input:checked + label {
    color: var(--primary-color);
}

.summary-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

@media (max-width: 768px) {
    .progress-steps {
        flex-wrap: wrap;
    }

    .step {
        margin: 10px;
    }

    .step:not(:last-child)::after {
        display: none;
    }
}
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;

    // Navigation
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
        });

        // Show current step
        document.getElementById(`step-${step}`).style.display = 'block';

        // Update progress
        document.querySelectorAll('.step').forEach((stepEl, index) => {
            if (index + 1 <= step) {
                stepEl.classList.add('active');
            } else {
                stepEl.classList.remove('active');
            }
        });

        // Update buttons
        prevBtn.style.display = step > 1 ? 'block' : 'none';
        nextBtn.style.display = step < totalSteps ? 'block' : 'none';
        submitBtn.style.display = step === totalSteps ? 'block' : 'none';

        // Update summary
        if (step === totalSteps) {
            updateSummary();
        }
    }

    function validateStep(step) {
        let isValid = true;
        const stepContent = document.getElementById(`step-${step}`);
        const requiredFields = stepContent.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    function updateSummary() {
        document.getElementById('summary-title').textContent =
            document.getElementById('title').value || '-';
        document.getElementById('summary-issue').textContent =
            document.getElementById('issue_number').value || '-';
        document.getElementById('summary-year').textContent =
            document.getElementById('publication_year').value || '-';
        document.getElementById('summary-cover').textContent =
            document.getElementById('cover_image').files.length > 0 ? 'Caricata' : 'Non caricata';
        document.getElementById('summary-plot').textContent =
            document.getElementById('plot').value ? 'Inserita' : 'Non inserita';
    }

    // Cover image handling
    const coverInput = document.getElementById('cover_image');
    const uploadArea = document.getElementById('upload-area');
    const preview = document.getElementById('cover-preview');

    uploadArea.addEventListener('click', () => coverInput.click());

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            coverInput.files = files;
            handleImagePreview(files[0]);
        }
    });

    coverInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleImagePreview(this.files[0]);
        }
    });

    function handleImagePreview(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    }

    // Character count for plot
    const plotTextarea = document.getElementById('plot');
    const plotCount = document.getElementById('plot-count');

    plotTextarea.addEventListener('input', function() {
        plotCount.textContent = this.value.length;

        if (this.value.length > 1800) {
            plotCount.style.color = '#dc3545';
        } else if (this.value.length > 1500) {
            plotCount.style.color = '#fd7e14';
        } else {
            plotCount.style.color = '#6c757d';
        }
    });

    // Publication options
    document.querySelectorAll('input[name="publication_status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const scheduleOptions = document.getElementById('schedule-options');
            scheduleOptions.style.display = this.value === 'scheduled' ? 'block' : 'none';
        });
    });

    // Form submission
    document.getElementById('fumetto-form').addEventListener('submit', function(e) {
        // Validate all steps before submission
        let isValid = true;
        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                isValid = false;
                break;
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Per favore completa tutti i campi obbligatori');
        }
    });
});
</script>
@endsection
