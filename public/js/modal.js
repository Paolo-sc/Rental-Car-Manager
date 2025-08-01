document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('delete-modal');
    const modalCloseButton = document.getElementById('close-delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const deleteCarName = document.getElementById('delete-car-name');
    const tableBody = document.getElementById('vehiclesTableBody');

    // Event delegation per tutti i bottoni .delete-vehicle-btn, anche quelli aggiunti dopo
    tableBody.addEventListener('click', function(event) {
        const button = event.target.closest('.delete-vehicle-btn');
        if (button) {
            event.preventDefault(); // Evita comportamenti default (es: submit form)
            const brand = button.getAttribute('data-brand');
            const model = button.getAttribute('data-model');
            const licensePlate = button.getAttribute('data-license_plate');
            const action = button.getAttribute('data-action');

            deleteForm.action = action;
            deleteCarName.textContent = brand + ' ' + model + ' (' + licensePlate + ')';

            deleteModal.style.display = 'flex';
        }
    });

    modalCloseButton.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });
    window.addEventListener('click', function(event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });
    //Esc per chiudere il modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            deleteModal.style.display = 'none';
        }
    });
});