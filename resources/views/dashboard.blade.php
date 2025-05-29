@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Dashboard') {{-- Definisce il titolo per questa pagina --}}

@section('content') {{-- Questa sezione corrisponde a @yield('content') nel layout --}}
    <section class="simple-page-content">
        <h2>Benvenuto nella tua Dashboard, {{ auth()->user()->full_name ?? 'Utente' }}!</h2>
        <p>Qui potrai visualizzare un riepilogo delle tue attività e metriche principali.</p>

        {{-- INIZIO DEL CODICE DELLE METRICHE--}}
        <section class="metrics-cards">
            <div class="metric-card">
                <div class="card-title">Total Revenue</div>
                <div class="card-value">$1,250.00</div>
                <div class="card-trend trending-up">
                    12.5%
                </div>
                <div class="card-description">Trending up this month</div>
                <div class="card-sub-description">Visitors for the last 6 months</div>
            </div>
            <div class="metric-card">
                <div class="card-title">New Customers</div>
                <div class="card-value">1,234</div>
                <div class="card-trend trending-down">
                    20%
                </div>
                <div class="card-description">Down 20% this period</div>
                <div class="card-sub-description">Acquisition needs attention</div>
            </div>
            <div class="metric-card">
                <div class="card-title">Active Accounts</div>
                <div class="card-value">45,678</div>
                <div class="card-trend trending-up">
                    12.5%
                </div>
                <div class="card-description">Strong user retention</div>
                <div class="card-sub-description">Engagement exceed targets</div>
            </div>
            <div class="metric-card">
                <div class="card-title">Growth Rate</div>
                <div class="card-value">4.5%</div>
                <div class="card-trend trending-up">
                    4.5%
                </div>
                <div class="card-description">Steady performance</div>
                <div class="card-sub-description">Meets growth projections</div>
            </div>
        </section>
        {{-- FINE DEL CODICE DELLE METRICHE --}}

        {{-- Aggiungere altri contenuti specifici della dashboard qui sotto --}}
    </section>
@endsection

@push('scripts')
    {{-- Aggiungere JavaScript per aggiornare dinamicamente questi dati o per grafici --}}
@endpush