<div id="signature-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Firma Contratto</h2>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; gap: 1.5em;">
            <!-- Visualizzazione PDF -->
            <div style="text-align:center;">
                <iframe id="contract-pdf-frame" style="border:1px solid #eee; width:100%; height:350px;" frameborder="0"></iframe>
            </div>
            <!-- Area firma -->
            <div style="text-align:center;">
                <label style="font-weight:bold; margin-bottom:0.5em; display:block;">Firma qui sotto:</label>
                <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas>
                <div style="margin-top:0.5em;">
                    <button type="button" class="btn-secondary" id="clear-signature-btn">Cancella</button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-signature-modal">Annulla</button>
            <button type="button" class="btn-primary" id="save-signature-btn">Salva Firma</button>
        </div>
    </div>
</div>