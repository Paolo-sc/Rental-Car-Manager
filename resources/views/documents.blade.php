@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Documenti') {{-- Definisce il titolo per questa pagina --}}

@push('styles') {{-- Questa sezione corrisponde a @stack('styles') nel layout --}}
    <link rel="stylesheet" href="{{ asset('css/pages/documents.css') }}"> {{-- Include il CSS specifico per la pagina documenti --}}
@endpush

@section('content') {{-- Questa sezione corrisponde a @yield('content') nel layout --}}
    <section class="simple-page-content">
        <h1>Qui puoi gestire i tuoi documenti.</h1>
        {{-- FORM PER CARICARE UN NUOVO DOCUMENTO --}}
        <section class="document-upload-form">
            <h2>Carica un nuovo documento</h2>
            <p>Compila il modulo per caricare un nuovo documento</p>
            @if ($errors->any())
                <div id="document-error-message" class="error-message-container" role="alert" aria-live="assertive">
                    <p class="error-text">{{ $errors->first() }}</p>
                </div>
            @endif
        </section>
    @endsection

    @push('scripts')
    @endpush
