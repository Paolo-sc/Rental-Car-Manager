@extends('layouts.app') {{-- Indica che questa vista estende il layout 'app' --}}

@section('title', 'Veicoli') {{-- Definisce il titolo per questa pagina --}}

@push('styles') {{-- Questa sezione corrisponde a @stack('styles') nel layout --}}
    <link rel="stylesheet" href="{{ asset('css/pages/vehicles.css') }}"> {{-- Include il CSS specifico per la pagina veicoli --}}
@endpush

@section('content')
<div x-data="carCrud()" class="container mx-auto py-4">

    <h1 class="text-2xl font-bold mb-4">Gestione Veicoli</h1>
    <!-- Pulsante aggiungi -->
    <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        + Aggiungi Veicolo
    </button>

    <!-- Tabella veicoli -->
    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr>
                <th class="py-2 px-3 border-b">ID</th>
                <th class="py-2 px-3 border-b">Targa</th>
                <th class="py-2 px-3 border-b">Marca</th>
                <th class="py-2 px-3 border-b">Modello</th>
                <th class="py-2 px-3 border-b">Anno</th>
                <th class="py-2 px-3 border-b">Colore</th>
                <th class="py-2 px-3 border-b">Tipo di carburante</th>
                <th class="py-2 px-3 border-b">Trasmissione</th>
                <th class="py-2 px-3 border-b">Posti</th>
                <th class="py-2 px-3 border-b">VIN</th>
                <th class="py-2 px-3 border-b">Cilindrata</th>
                <th class="py-2 px-3 border-b">Chilometraggio</th>
                <th class="py-2 px-3 border-b">Stato</th>
                <th class="py-2 px-3 border-b">Note</th>
                <th class="py-2 px-3 border-b">Creato il</th>
                <th class="py-2 px-3 border-b">Aggiornato il</th>
                <th class="py-2 px-3 border-b">Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr class="border-t">
                <td class="py-2 px-3">{{ $vehicle->id }}</td>
                <td class="py-2 px-3">{{ $vehicle->license_plate }}</td>
                <td class="py-2 px-3">{{ $vehicle->brand }}</td>
                <td class="py-2 px-3">{{ $vehicle->model }}</td>
                <td class="py-2 px-3">{{ $vehicle->year }}</td>
                <td class="py-2 px-3">{{ $vehicle->color }}</td>
                <td class="py-2 px-3">{{ $vehicle->fuel_type }}</td>
                <td class="py-2 px-3">{{ $vehicle->transmission }}</td>
                <td class="py-2 px-3">{{ $vehicle->seats }}</td>
                <td class="py-2 px-3">{{ $vehicle->vin }}</td>
                <td class="py-2 px-3">{{ $vehicle->engine_size }}</td>
                <td class="py-2 px-3">{{ $vehicle->mileage }}</td>
                <td class="py-2 px-3">{{ $vehicle->status }}</td>
                <td class="py-2 px-3">
                    {{ $vehicle->notes ?? 'Nessuna nota' }}
                </td>
                <td class="py-2 px-3">{{$vehicle->created_at}}</td>
                <td class="py-2 px-3">{{$vehicle->updated_at}}</td>
                <td class="py-2 px-3">
                    <button class="bg-yellow-400 text-white px-2 py-1 rounded mr-2">
                        Modifica
                    </button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded">
                        Elimina
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modale Aggiungi/Modifica -->
    <div x-show="showFormModal" class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded w-full max-w-lg">
            <h2 class="text-xl font-semibold mb-4" x-text="formTitle"></h2>
            <form :action="formAction" method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                @include('vehicles.partials._form')
                <div class="flex justify-end mt-4">
                    <button type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded">Annulla</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Salva
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale Elimina -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Conferma eliminazione</h2>
            <p>Vuoi eliminare il veicolo <span class="font-bold" x-text="deleteCarName"></span>?</p>
            <form :action="'/vehicles/' + deleteCarId" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <div class="flex justify-end">
                    <button type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded">Annulla</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Elimina</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection