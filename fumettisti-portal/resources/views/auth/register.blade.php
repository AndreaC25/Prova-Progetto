 @extends('layouts.app')

@section('title', 'Registrati - Fumettisti Portal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="content-container">
                <h2 class="text-center mb-4">Crea il tuo Account</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted">Minimo 8 caratteri</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Conferma Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>REGISTRATI
                    </button>
                </form>

                <div class="text-center">
                    <p>Hai già un account? <a href="{{ route('login') }}" class="text-decoration-none">Accedi qui</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
