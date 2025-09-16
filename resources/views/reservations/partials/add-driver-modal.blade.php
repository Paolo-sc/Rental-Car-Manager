<div id="add-driver-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="driver-modal-header">Aggiungi Conducente</h2>
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

            <form id="driver-form" method="POST" action="">
                @csrf
                <h3>Seleziona un conducente esistente</h3>

                <div class="input-container input-couple">
                    <div class="input-dropdown">
                        <label for="driver-input">Conducente</label>
                        <input type="text" id="driver-input" name="driver" class="form-control"
                            autocomplete="off" placeholder="Cerca conducente...">
                        <input type="hidden" id="driver-id" name="driver_id">
                        <div id="driver-suggestions" class="list-group suggestions"
                            style="position:absolute; z-index:1000; width:100%;"></div>
                    </div>
                    <button type="button" class="btn-primary" id="add-existing-driver-button">Aggiungi Conducente</button>
                </div>

                <h3>Oppure crea un nuovo conducente</h3>

                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="first_name">Nome</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="couple-container">
                        <label for="last_name">Cognome</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="phone">Telefono</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                    </div>
                </div>

                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="tax_code">Codice Fiscale</label>
                        <input type="text" id="tax_code" name="tax_code" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="birth_date">Data di Nascita</label>
                        <input type="date" id="birth_date" name="birth_date" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="birth_place">Luogo di Nascita</label>
                        <input type="text" id="birth_place" name="birth_place" class="form-control">
                    </div>
                </div>

                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="driving_license_number">Numero Patente</label>
                        <input type="text" id="driving_license_number" name="driving_license_number" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="driving_license_issue_date">Data Rilascio Patente</label>
                        <input type="date" id="driving_license_issue_date" name="driving_license_issue_date" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="driving_license_expires_at">Data Scadenza Patente</label>
                        <input type="date" id="driving_license_expires_at" name="driving_license_expires_at" class="form-control">
                    </div>
                </div>

                <div class="input-container">
                    <label for="address">Indirizzo</label>
                    <input type="text" id="address" name="address" class="form-control">
                </div>

                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="city">Città</label>
                        <input type="text" id="city" name="city" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="postal_code">CAP</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="country">Nazione</label>
                        <input type="text" id="country" name="country" class="form-control" value="Italy">
                    </div>
                </div>

                <div class="input-container">
                    <label for="notes">Note</label>
                    <input id="notes" name="notes" class="form-control" rows="2">
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-add-driver-modal">Annulla</button>
            <button type="submit" class="btn-primary" form="driver-form" id="save-new-driver-btn">Crea e aggiungi nuovo conducente</button>
        </div>
    </div>
</div>