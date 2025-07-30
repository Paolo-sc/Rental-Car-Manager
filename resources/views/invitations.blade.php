@extends('layouts.app'){{-- Indice a che questa vista estende il layout 'app' --}}

@section('title', 'Inviti') {{-- Definisce il titolo per questa pagina --}}

@push('styles') {{-- Questa sezione corrisponde a @stack('styles') nel layout --}}
    <link rel="stylesheet" href="{{ asset('css/pages/invitations.css') }}"> {{-- Include il CSS specifico per la pagina inviti --}}
@endpush

@section('content')
    <section class="simple-page-content">
        <h1>Qui puoi occuparti degli inviti.</h1>
        {{-- FORM COMPILAZIONE INVITO REGISTRAZIONE TEMPORANEO --}}
        <section class="invitaiton-form">
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
                    <input type="email" id="email" name="email" placeholder="m@example.com"
                        value="{{ old('email') }}" required>
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

        @if ($invitations->isEmpty())
            <p>Non sono stati trovati inviti</p>
        @else
            <ul>
                @foreach ($invitations as $invitation)
                    <li>
                        {{ $invitation->email }}
                        {{ $invitation->used ? '(Usato)' : '(Non Usato)' }}
                        {{ $invitation->created_at->format('d/m/Y H:i') }}
                        @if ($invitation->expires_at)
                            Scade il {{ $invitation->expires_at->format('d/m/Y H:i') }}
                            @if ($invitation->expires_at->isPast())
                                <span class="text-danger">(Scaduto)</span>
                            @endif
                        @endif
                        {{ $invitation->token }}
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
