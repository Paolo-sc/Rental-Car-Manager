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
            <form id="edit-reservation-form" method="POST" action="" data-add-action="">
                @csrf
                <div class="input-container input-couple">
                    <div class="input-dropdown">
                        <label for="customer-input">Cliente</label>
                        <input type="text" id="customer-input" name="customer" class="form-control"
                            autocomplete="off" placeholder="Cerca cliente...">
                        <input type="hidden" id="customer-id" name="customer_id">
                        <div id="customer-suggestions" class="list-group suggestions"
                            style="position:absolute; z-index:1000; width:100%;"></div>
                    </div>
                    <button type="button" class="btn-primary" id="add-customer-button">Nuovo Cliente</button>
                </div>
                <div class="input-container input-couple">
                    <div class="input-dropdown">
                        <label for="main-driver-input">Conducente primario</label>
                        <input type="text" id="main-driver-input" name="main_driver" class="form-control"
                            autocomplete="off" placeholder="Cerca conducente...">
                        <input type="hidden" id="main-driver-id" name="main_driver_id">
                        <div id="main-driver-suggestions" class="list-group suggestions"
                            style="position:absolute; z-index:1000; width:100%;"></div>
                    </div>
                    <button type="button" class="btn-primary" id="add-driver-button">Nuovo Conducente</button>
                </div>
                <div class="input-container input-couple">
                    <div class="input-dropdown">
                        <label for="vehicle-input">Veicolo</label>
                        <input type="text" id="vehicle-input" name="vehicle" class="form-control" autocomplete="off"
                            placeholder="Cerca veicolo...">
                        <input type="hidden" id="vehicle-id" name="vehicle_id">
                        <div id="vehicle-suggestions" class="list-group suggestions"
                            style="position:absolute; z-index:1000; width:100%;"></div>
                    </div>
                    <button type="button" class="btn-primary" id="add-vehicle-button">Nuovo Veicolo</button>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="start-date">Data Inizio</label>
                        <input type="date" id="start-date" name="start_date" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="end-date">Data Fine</label>
                        <input type="date" id="end-date" name="end_date" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="pickup-time">Orario di Ritiro</label>
                        <input type="time" id="pickup-time" name="pickup_time" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="return-time">Orario di Rientro</label>
                        <input type="time" id="return-time" name="return_time" class="form-control">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="payment_received">Stato Pagamento</label>
                        <select id="payment_received" name="payment_received" class="form-control">
                            <option value="0">Non Pagato</option>
                            <option value="1">Pagato</option>
                        </select>
                    </div>
                    <div class="couple-container">
                        <label for="payment_date">Data di Pagamento</label>
                        <input type="date" id="payment_date" name="payment_date" class="form-control">
                    </div>
                    <div class="couple-container">
                        <label for="reservation-status">Stato Prenotazione</label>
                        <select id="reservation-status" name="reservation_status" class="form-control">
                            <option value="active">Attiva</option>
                            <option value="completed">Completata</option>
                            <option value="canceled">Annullata</option>
                        </select>
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="pickup-location">Luogo di Ritiro</label>
                        <input type="text" id="pickup-location" name="pickup_location" class="form-control"
                            placeholder="Luogo di Ritiro">
                    </div>
                    <div class="couple-container">
                        <label for="return-location">Luogo di Rientro</label>
                        <input type="text" id="return-location" name="return_location" class="form-control"
                            placeholder="Luogo di Rientro">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="daily_rate">Tariffa Giornaliera</label>
                        <input type="number" step="0.01" id="daily_rate" name="daily_rate" class="form-control"
                            placeholder="Tariffa Giornaliera">
                    </div>
                    <div class="couple-container">
                        <label for="subtotal">Subtotale</label>
                        <input type="number" step="0.01" id="subtotal" name="subtotal" class="form-control"
                            placeholder="Subtotale">
                    </div>
                    <div class="couple-container">
                        <label for="discount_amount">Importo Sconto</label>
                        <input type="number" step="0.01" id="discount_amount" name="discount_amount"
                            class="form-control" placeholder="Importo Sconto">
                    </div>
                    <div class="couple-container">
                        <label for="tax_rate">Aliquota Fiscale (%)</label>
                        <input type="number" step="0.01" id="tax_rate" name="tax_rate" class="form-control"
                            placeholder="Aliquota Fiscale">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="deposit_amount">Importo Deposito</label>
                        <input type="number" step="0.01" id="deposit_amount" name="deposit_amount"
                            class="form-control" placeholder="Importo Deposito">
                    </div>
                    <div class="couple-container">
                        <label for="deposit_payment_method">Metodo di Pagamento Deposito</label>
                        <select id="deposit_payment_method" name="deposit_payment_method" class="form-control">
                            <option value="cash">Contanti</option>
                            <option value="credit_card">Carta di Credito</option>
                            <option value="bank_transfer">Bonifico</option>
                            <option value="other">Altro</option>
                        </select>
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="total_amount">Importo Totale</label>
                        <input type="number" step="0.01" id="total_amount" name="total_amount"
                            class="form-control" placeholder="Importo Totale">
                    </div>
                    <div class="couple-container">
                        <label for="total_paid">Importo Totale pagato</label>
                        <input type="number" step="0.01" id="total_paid" name="total_paid" class="form-control"
                            placeholder="Importo Totale">
                    </div>
                    <div class="couple-container">
                        <label for="payment_method">Metodo di Pagamento</label>
                        <select id="payment_method" name="payment_method" class="form-control">
                            <option value="cash">Contanti</option>
                            <option value="credit_card">Carta di Credito</option>
                            <option value="bank_transfer">Bonifico</option>
                            <option value="other">Altro</option>
                        </select>
                    </div>
                    <div class="couple-container">
                        <label for="status">Stato</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active">Attiva</option>
                            <option value="pending">In Attesa</option>
                            <option value="completed">Completata</option>
                            <option value="canceled">Annullata</option>
                        </select>
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="km_included_type">Tipo di kilometraggio</label>
                        <select id="km_included_type" name="km_included_type" class="form-control">
                            <option value="unlimited">Illimitato</option>
                            <option value="limited">Limitato</option>
                        </select>
                    </div>
                    <div class="couple-container">
                        <label for="km_included_value">Chilometri Inclusi</label>
                        <input type="number" step="0.01" id="km_included_value" name="km_included_value"
                            class="form-control" placeholder="Chilometri Inclusi">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="franchise_theft_fire">Franchigia Furto/Incendio</label>
                        <input type="number" step="0.01" id="franchise_theft_fire" name="franchise_theft_fire"
                            class="form-control" placeholder="Franchigia Furto/Incendio">
                    </div>
                    <div class="couple-container">
                        <label for="deductible_damage">Franchigia Danni</label>
                        <input type="number" step="0.01" id="deductible_damage" name="deductible_damage"
                            class="form-control" placeholder="Franchigia Danni">
                    </div>
                    <div class="couple-container">
                        <label for="deductible_rca">Franchigia RCA</label>
                        <input type="number" step="0.01" id="deductible_rca" name="deductible_rca"
                            class="form-control" placeholder="Franchigia RCA">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="special_conditions">Condizioni Speciali</label>
                        <input type="text" id="special_conditions" name="special_conditions" class="form-control"
                            placeholder="Condizioni Speciali">
                    </div>
                    <div class="couple-container">
                        <label for="notes">Note</label>
                        <input type="text" id="notes" name="notes" class="form-control"
                            placeholder="Note">
                    </div>
                </div>
                <div class="input-container input-couple">
                    <div class="couple-container">
                        <label for="customer_signature_status">Firma Cliente</label>
                        <select id="customer_signature_status" name="customer_signature_status" class="form-control">
                            <option value="not signed">Non Firmato</option>
                            <option value="signed">Firmato</option>
                        </select>
                    </div>
                    <div class="couple-container">
                        <label for="signature_date">Data Firma</label>
                        <input type="date" id="signature_date" name="signature_date" class="form-control"
                            placeholder="Data Firma">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-edit-modal">Annulla</button>
            <button type="submit" class="btn-primary" form="edit-reservation-form"
                id="submit-edit-reservation">Aggiungi</button>
        </div>
    </div>
</div>
