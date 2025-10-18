@extends('layouts.app') 

@section('title', 'Dashboard') 

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}"> 
@endpush
@section('content') 
    <section class="simple-page-content">
        <section class="metrics-cards">
            <div class="content-card metric-card">
                <div class="card-title">Total Revenue</div>
                <div class="card-value">$1,250.00</div>
                <div class="card-trend trending-up">12.5%</div>
                <div class="card-description">Trending up this month</div>
                <div class="card-sub-description">Visitors for the last 6 months</div>
            </div>
            <div class="content-card metric-card">
                <div class="card-title">New Customers</div>
                <div class="card-value">1,234</div>
                <div class="card-trend trending-down">20%</div>
                <div class="card-description">Down 20% this period</div>
                <div class="card-sub-description">Acquisition needs attention</div>
            </div>
            <div class="content-card metric-card">
                <div class="card-title">Active Accounts</div>
                <div class="card-value">45,678</div>
                <div class="card-trend trending-up">12.5%</div>
                <div class="card-description">Strong user retention</div>
                <div class="card-sub-description">Engagement exceed targets</div>
            </div>
            <div class="content-card metric-card">
                <div class="card-title">Growth Rate</div>
                <div class="card-value">4.5%</div>
                <div class="card-trend trending-up">4.5%</div>
                <div class="card-description">Steady performance</div>
                <div class="card-sub-description">Meets growth projections</div>
            </div>
        </section>

        <section class="calendar-card">
            <div class="card-header">
                <div class="header-left">
                    <h2 class="card-title">Calendario Prenotazioni Veicoli</h2>
                    <p class="card-subtitle">Visualizza e gestisci le prenotazioni dei veicoli</p>
                </div>
                <div class="month-filter">
                    <div class="filter-group">
                        <label for="yearSelect">Anno:</label>
                        <select id="yearSelect">
                            <option value="">Tutti</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="monthSelect">Mese:</label>
                        <select id="monthSelect">
                            <option value="">Tutti</option>
                        </select>
                    </div>
                    <button class="filter-reset" id="resetFilter">Reset</button>
                </div>
            </div>

            <div class="card-body">
                <div class="calendar-wrapper">
                    <div class="calendar-container" id="calendar">
                        <div class="calendar-grid" id="calendar-grid">
                            <!-- Header viene generato dinamicamente -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                Scorri orizzontalmente per vedere altri mesi • Clicca su una prenotazione per i dettagli • Usa i filtri per
                navigare rapidamente
            </div>
        </section>

    </section>
@endsection

@push('scripts')
    <script>
        window.calendarDataUrl = "{{ route('calendar.data') }}";
    </script>
    <script src="{{ asset('js/gantt.js') }}"></script>
@endpush
