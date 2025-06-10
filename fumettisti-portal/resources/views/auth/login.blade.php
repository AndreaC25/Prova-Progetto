 @extends('layouts.app')

@section('title', 'Accedi - Fumettisti Portal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="content-container">
                <h2 class="text-center mb-4">Accedi al tuo Account</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>ACCEDI
                    </button>
                </form>

                <div class="text-center">
                    <p>Non hai un account? <a href="{{ route('register') }}" class="text-decoration-none">Registrati qui</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
