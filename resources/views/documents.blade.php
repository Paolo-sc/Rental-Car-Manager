@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Documenti') {{-- Definisce il titolo per questa pagina --}}

@section('content') {{-- Questa sezione corrisponde a @yield('content') nel layout --}}
    <section class="simple-page-content">
        <h2>Contenuto della pagina Documenti</h2>
        <p>Consilium captum est, via statuta.</p>
        <p>Factum est, iter non redibit.</p>
        <ul class="document-list">
            <li>Report Vendite</li>
            <li>Bozza Progetto</li>
            <li>Foglio di Calcolo</li>
        </ul>
    </section>
@endsection

@push('scripts')
@endpush