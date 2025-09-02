@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Documenti') {{-- Definisce il titolo per questa pagina --}}

@push('styles')
    {{-- Questa sezione corrisponde a @stack('styles') nel layout --}}
    <link rel="stylesheet" href="{{ asset('css/pages/documents.css') }}"> {{-- Include il CSS specifico per la pagina documenti --}}
@endpush

@section('content') {{-- Questa sezione corrisponde a @yield('content') nel layout --}}
    <section class="simple-page-content">
        <h2>Carica file su Google Drive (cartella "autonoleggio")</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('drive.upload.handle') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file">Scegli file:</label>
            <input type="file" name="file" id="file" required>
            <button type="submit" class="btn btn-primary mt-2">Carica</button>
        </form>
    </section>
@endsection

@push('scripts')
@endpush
