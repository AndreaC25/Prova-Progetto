@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center py-5">
                <h1 class="display-4 mb-4">🎨 Fumettisti Portal</h1>
                <p class="lead mb-4">Il portale dedicato ai fumettisti indipendenti italiani</p>

                @guest
                    <div class="mb-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">Registrati</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Accedi</a>
                    </div>
                @else
                    <div class="alert alert-success">
                        <h4>Benvenuto, {{ Auth::user()->name }}!</h4>
                        <p>Il tuo Fumettisti Portal è pronto!</p>
                        <a href="{{ route('fumetti.create') }}" class="btn btn-success">Crea Fumetto</a>
                    </div>
                @endauth
            </div>

            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5>📚 Fumetti</h5>
                            <p>Pubblica le tue opere</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5>⭐ Recensioni</h5>
                            <p>Ricevi feedback</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5>❤️ Preferiti</h5>
                            <p>Salva i tuoi fumetti preferiti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
