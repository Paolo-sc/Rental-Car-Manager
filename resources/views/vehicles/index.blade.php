@extends('layouts.app')

@section('title', 'Veicoli')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/vehicles.css') }}">
@endpush

@section('content')
<div x-data="carCrud()" class="vehicles-container">
    
    <div class="content-card">
        <!-- Header della card -->
        <div class="content-card-header">
            <div>
                <h1 class="content-title">Veicoli</h1>
                <p class="content-subtitle">Visualizza, aggiungi e gestisci la tua flotta di veicoli.</p>
            </div>
            <button>
                + Aggiungi Veicolo
            </button>
        </div>

        <!-- Body della card -->
        <div class="content-card-body">
            <!-- Barra di ricerca -->
            <div class="search-section">
                <input type="text" class="search-input" placeholder="Cerca per targa, marca o modello...">
            </div>

            <!-- Tabella -->
            <div class="vehicles-table-section">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th>Targa</th>
                                <th>Marca</th>
                                <th>Modello</th>
                                <th>Anno</th>
                                <th>Colore</th>
                                <th>Carburante</th>
                                <th>Trasmissione</th>
                                <th>Posti</th>
                                <th>VIN</th>
                                <th>Cilindrata</th>
                                <th>Km</th>
                                <th>Stato</th>
                                <th>Note</th>
                                <th class="col-actions">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td class="col-id">{{ $vehicle->id }}</td>
                                <td>{{ $vehicle->license_plate }}</td>
                                <td>{{ $vehicle->brand }}</td>
                                <td>{{ $vehicle->model }}</td>
                                <td>{{ $vehicle->year }}</td>
                                <td>{{ $vehicle->color }}</td>
                                <td>{{ $vehicle->fuel_type }}</td>
                                <td>{{ $vehicle->transmission }}</td>
                                <td>{{ $vehicle->seats }}</td>
                                <td>{{ $vehicle->vin }}</td>
                                <td>{{ $vehicle->engine_size }}</td>
                                <td>{{ $vehicle->mileage }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $vehicle->status)) }}">
                                        {{ $vehicle->status }}
                                    </span>
                                </td>
                                <td>{{ $vehicle->notes ?? 'Nessuna nota' }}</td>
                                <td class="col-actions">
                                    <div class="action-buttons">
                                        <button class="btn-secondary">Modifica</button>
                                        <button class="btn-danger">Elimina</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aggiungi/Modifica -->
    <div x-show="showFormModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" x-text="formTitle"></h2>
            </div>
            <div class="modal-body">
                <form :action="formAction" method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    @include('vehicles.partials._form')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary">Annulla</button>
                <button type="submit" class="btn-primary">Salva</button>
            </div>
        </div>
    </div>

    <!-- Modal Elimina -->
    <div x-show="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Conferma eliminazione</h2>
            </div>
            <div class="modal-body">
                <p>Vuoi eliminare il veicolo <span style="font-weight: 600;" x-text="deleteCarName"></span>?</p>
                <form :action="'/vehicles/' + deleteCarId" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary">Annulla</button>
                <button type="submit" class="btn-danger">Elimina</button>
            </div>
        </div>
    </div>

</div>
@endsection