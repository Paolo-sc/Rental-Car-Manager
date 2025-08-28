<div id="edit-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-header-h2">Aggiungi Veicolo</h2>
        </div>
        <div class="modal-body">
            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="edit-vehicle-form" method="POST" action="{{ route('vehicles.add') }}"
            data-add-action="{{ route('vehicles.add') }}">
                @csrf
                <div class="input-container">
                    <label for="edit-vehicle-plate">Targa</label>
                    <input type="text" name="license_plate" id="edit-vehicle-plate" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-brand">Marca</label>
                    <input type="text" name="brand" id="edit-vehicle-brand" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-model">Modello</label>
                    <input type="text" name="model" id="edit-vehicle-model" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-year">Anno</label>
                    <input type="number" name="year" id="edit-vehicle-year" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-color">Colore</label>
                    <input type="text" name="color" id="edit-vehicle-color" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-fuel">Carburante</label>
                    <input type="text" name="fuel_type" id="edit-vehicle-fuel" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-transmission">Trasmissione</label>
                    <input type="text" name="transmission" id="edit-vehicle-transmission" required>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-vin">VIN</label>
                    <input type="text" name="vin" id="edit-vehicle-vin" required>
                </div>
                <div class="input-container" id="mileage-state-container">
                    <label for="edit-vehicle-seats">Posti</label>
                    <input type="number" name="seats" id="edit-vehicle-seats" required>
                    <label for="edit-vehicle-engine-size">Cilindrata</label>
                    <input type="number" name="engine_size" id="edit-vehicle-engine-size" required>
                    <label for="edit-vehicle-mileage">KM</label>
                    <input type="number" name="mileage" id="edit-vehicle-mileage" required>
                    <label for="edit-vehicle-status">Stato</label>
                    <select name="status" id="edit-vehicle-status" required>
                        <option value="Disponibile">Disponibile</option>
                        <option value="Archiviato">Archiviato</option>
                        <option value="Manutenzione">Manutenzione</option>
                    </select>
                </div>
                <div class="input-container">
                    <label for="edit-vehicle-notes">Note</label>
                    <input type="text" name="notes" id="edit-vehicle-notes" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-edit-modal">Annulla</button>
            <button type="submit" class="btn-primary" form="edit-vehicle-form" id="submit-edit-vehicle">Aggiungi</button>
        </div>
    </div>
</div>
