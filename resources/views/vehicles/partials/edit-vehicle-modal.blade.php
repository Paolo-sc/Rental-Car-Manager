<div id="edit-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Vehicle</h2>
            <button class="close-modal">X</button>
        </div>
        <div class="modal-body">
            <form id="edit-vehicle-form">
                @csrf
                <div class="form-group">
                    <label for="edit-vehicle-model">Model</label>
                    <input type="text" name="model" id="edit-vehicle-model" required>
                </div>
                <div class="form-group">
                    <label for="edit-vehicle-year">Year</label>
                    <input type="number" name="year" id="edit-vehicle-year" required>
                </div>
                <div class="form-group">
                    <label for="edit-vehicle-price">Price</label>
                    <input type="number" name="price" id="edit-vehicle-price" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="save-changes">Save Changes</button>
            <button class="cancel">Cancel</button>
        </div>
    </div>
</div>