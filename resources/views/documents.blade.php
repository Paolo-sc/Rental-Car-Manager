@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Documenti') {{-- Definisce il titolo per questa pagina --}}

@section('content') {{-- Questa sezione corrisponde a @yield('content') nel layout --}}
    <section class="simple-page-content">
        {{-- FORM COMPILAZIONE INVITO REGISTRAZIONE TEMPORANEO --}}
        <h2>Invita un nuovo utente</h2>
        <p>Compila il modulo per inviare un invito a un nuovo utente</p>
        @if ($errors->any())
            <div id="invitation-error-message" class="error-message-container" role="alert" aria-live="assertive">
                <p class="error-text">{{ $errors->first() }}</p>
            </div>
        @endif
        <form name="invitation" method="POST">
            @csrf
            <div class="input-container">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="m@example.com" value="{{ old('email') }}" required>
                @error('email')
                    <span class="input-error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-container">
                <input type="submit" id="invitation-button" name="invite" value="Invita">
            </div>
        </form>
        {{-- MESSAGGIO DI SUCCESSO --}}
        @if (session('warning'))
            <div class="alert alert-warning">
                <p>{{ session('warning') }}</p>
            </div> 
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
@endpush