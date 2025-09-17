@extends('layouts.app')

@section('title', 'Prenotazioni')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/reservations.css') }}">
@endpush

@section('content')
    <div class="reservation-container">

        <div class="content-card">
            <!-- Header della card -->
            <div class="content-card-header">
                <div>
                    <h1 class="content-title">Tabella delle prenotazioni</h1>
                    <p class="content-subtitle">Visualizza, aggiungi e gestisci la tua prenotazioni.</p>
                </div>
                <div>
                    <button id="refresh-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 2V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M3 12.0001C3.00158 10.2634 3.5056 8.56429 4.45125 7.10764C5.39691 5.651 6.74382 4.49907 8.32951 3.79079C9.9152 3.08252 11.6719 2.84815 13.3879 3.11596C15.1038 3.38377 16.7056 4.14232 18 5.30011L21 8.00011"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 22V16H9" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M21 12C20.9984 13.7367 20.4944 15.4358 19.5487 16.8925C18.6031 18.3491 17.2562 19.501 15.6705 20.2093C14.0848 20.9176 12.3281 21.152 10.6121 20.8841C8.89623 20.6163 7.29445 19.8578 6 18.7L3 16"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button id="add-reservation-button" class="add-btn">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Aggiungi Prenotazione
                    </button>
                </div>
            </div>

            <!-- Body della card -->
            <div class="content-card-body">
                <!-- Barra di ricerca -->
                <div class="search-section">
                    <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M21 20.9999L16.65 16.6499" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <input type="text" id="searchInput" class="search-input"
                        placeholder="Cerca per prenotazione o contratto">
                    <div class="filter-container">
                    </div>
                </div>
                <!-- Messaggio di successo -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Tabella -->
                <div class="table-responsive" id="reservationsTableSection">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Codice prenotazione</th>
                                <th>Cliente</th>
                                <th>Veicolo</th>
                                <th>Data inizio</th>
                                <th>Data fine</th>
                                <th>Orario di ritiro</th>
                                <th>Orario di rientro</th>
                                <th>Deposito</th>
                                <th>Prezzo totale</th>
                                <th>Stato pagamento</th>
                                <th>Stato contratto</th>
                                <th>Stato prenotazione</th>
                                <th>Contratto</th>
                                <th>Conducente</th>
                                <th class="col-actions">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="reservationsTableBody">
                            <!-- I veicoli attivi verranno caricati qui tramite AJAX -->
                        </tbody>
                    </table>
                    <!-- Paginazione -->
                    <div id="pagination-controls"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="loader-overlay" class="loader-overlay">
        <div class="loader-spinner"></div>
    </div>
    @include('reservations.partials.delete-reservation-modal')
    @include('reservations.partials.edit-reservation-modal')
    @include('reservations.partials.signature-modal')
    @include('reservations.partials.show-drivers-modal')
    @include('reservations.partials.add-driver-modal')
    @include('customers.partials.edit-customer-modal')
    @include('vehicles.partials.edit-vehicle-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/reservations.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
@endpush
