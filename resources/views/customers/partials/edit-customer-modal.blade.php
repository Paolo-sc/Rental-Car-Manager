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
            <form id="edit-customer-form" method="POST" action="{{ route('customers.add') }}"
                data-add-action="{{ route('customers.add') }}" enctype="multipart/form-data">
                @csrf
                <div class="input-container input-couple">
                    <label for="edit-customer-name">Nome</label>
                    <input type="text" name="first_name" id="edit-customer-name">
                    <label for="edit-customer-lastname">Cognome</label>
                    <input type="text" name="last_name" id="edit-customer-lastname">
                    <label for="edit-customer-company">Ditta</label>
                    <input type="text" name="company_name" id="edit-customer-company">
                </div>
                <div class="input-container input-couple">
                    <label for="edit-customer-taxcode">Codice Fiscale</label>
                    <input type="text" name="tax_code" id="edit-customer-taxcode">
                    <label for="edit-customer-vat">Partita IVA</label>
                    <input type="text" name="vat_number" id="edit-customer-vat">
                </div>
                <div class="input-container input-couple">
                    <label for="edit-customer-email">Email</label>
                    <input type="email" name="email" id="edit-customer-email" required>
                    <label for="edit-customer-phone">Telefono</label>
                    <input type="text" name="phone" id="edit-customer-phone" required>
                    <div class="filter-container">
                        <label for="edit-customer-type">Tipo Cliente</label>
                        <select name="customer_type" id="edit-customer-type">
                            <option value="individual">Individuale</option>
                            <option value="company">Azienda</option>
                        </select>
                    </div>
                </div>
                <div class="input-container">
                    <label for="edit-customer-address">Indirizzo</label>
                    <input type="text" name="address" id="edit-customer-address" required>
                </div>
                <div class="input-container input-couple">
                    <label for="edit-customer-state">Stato</label>
                    <input type="text" name="state" id="edit-customer-state" required>
                    <label for="edit-customer-city">Città</label>
                    <input type="text" name="city" id="edit-customer-city" required>
                    <label for="edit-customer-postalcode">CAP</label>
                    <input type="text" name="postal_code" id="edit-customer-postalcode" required>
                </div>
                <div class="input-container input-couple">
                    <label for="edit-document-number">Numero documento</label>
                    <input type="text" name="document_number" id="edit-document-number">
                    <label for="edit-document-type">Tipo documento</label>
                    <input type="text" name="document_type" id="edit-document-type">
                    <label for="edit-document-expiry">Data di scadenza</label>
                    <input type="date" name="expiry_date" id="edit-document-expiry">
                    <label for="edit-customer-document">Carica il documento</label>
                    <input type="file" name="document" id="edit-customer-document" required>
                </div>
                <div class="input-container">
                    <label for="edit-customer-note">Note</label>
                    <input type="text" name="notes" id="edit-customer-note">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-edit-modal">Annulla</button>
            <button type="submit" class="btn-primary" form="edit-customer-form"
                id="submit-edit-customer">Aggiungi</button>
        </div>
    </div>
</div>
