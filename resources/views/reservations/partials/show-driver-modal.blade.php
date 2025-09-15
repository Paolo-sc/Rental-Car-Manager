<div id="driver-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-header-h2">Driver della prenotazione <span id="booking-id"></span></h2>
            <button class="btn-primary" id="add-driver-button">
                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Aggiungi Conducente
            </button>
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
            <ul id="drivers-list">
                <!-- I conducenti della prenotazione verranno caricati qui tramite AJAX -->
            </ul>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn-primary" id="close-driver-modal">Chiudi</button>
        </div>
    </div>
</div>
