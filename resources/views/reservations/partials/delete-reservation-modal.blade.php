<div id="delete-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Conferma eliminazione</h2>
        </div>
        <div class="modal-body">
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <p>Sei sicuro di voler eliminare la prenotazione <span id="delete-reservation" style="font-weight:600"></span>?
                <p style="color: red; font-weight: bold;">
                    Attenzione: Eliminando questa prenotazione non potrai piu recuperarla!
                </p>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-delete-modal">Annulla</button>
            <button type="submit" class="btn-danger" form="delete-form">Elimina</button>
        </div>
    </div>
</div>
