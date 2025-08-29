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
            data-add-action="{{ route('customers.add') }}">
                @csrf

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-edit-modal">Annulla</button>
            <button type="submit" class="btn-primary" form="edit-customer-form" id="submit-edit-customer">Aggiungi</button>
        </div>
    </div>
</div>
