@extends('layouts.app')

@section('title', 'Clienti')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/customers.css') }}">
@endpush

@section('content')
    <div class="customers-container">

        <div class="content-card">
            <!-- Header della card -->
            <div class="content-card-header">
                <div>
                    <h1 class="content-title">Tabella dei Clienti</h1>
                    <p class="content-subtitle">Visualizza, aggiungi e gestisci i tuoi clienti.</p>
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
                    <button id="add-customer-button" class="add-btn">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Aggiungi Cliente
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
                        placeholder="Cerca per nome, email o telefono...">

                            <div class="filter-container">
                                <label for="typeFilter">Filtra per:</label>
                                <select id="typeFilter" class="filter-select">
                                    <option value="all">Tutti</option>
                                    <option value="individual">Cliente privato</option>
                                    <option value="company">Azienda</option>
                                </select>
                            </div>

                </div>
                <!-- Messaggio di successo -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Tabella -->
                <div class="table-responsive" id="customersTableSection">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>Codice Fiscale</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th>Indirizzo</th>
                                <th>Ditta</th>
                                <th>Stato</th>
                                <th>Città</th>
                                <th>CAP</th>
                                <th>Partita Iva</th>
                                <th>Documento</th>
                                <th>Note</th>
                                <th class="col-actions">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody">
                            <!-- I clienti attivi verranno caricati qui tramite AJAX -->
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
    @include('customers.partials.delete-customer-modal')
    @include('customers.partials.edit-customer-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/customers.js') }}"></script>
@endpush
