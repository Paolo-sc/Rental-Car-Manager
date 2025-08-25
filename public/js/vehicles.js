let currentPage = 1;
const pageSize = 10;
let totalVehicles = 0;
let vehicleIdToDelete = null;
let searchQuery = "";

// Mostra messaggio di successo
function showSuccess(msg) { alert(msg); }

// Mostra messaggio di errore
function showError(msg) { alert("Errore: " + msg); }

// Mostra il loader
function showLoader() {
    document.getElementById("vehiclesTableSection").style.display = "none";
    document.getElementById("loader-overlay").style.display = "flex";
}

// Nasconde il loader
function hideLoader() {
    document.getElementById("vehiclesTableSection").style.display = "block";
    document.getElementById("loader-overlay").style.display = "none";
}

// Carica i veicoli dalla pagina corrente e stato selezionato
async function loadVehicles(status, page = 1, pageSize = 10,search="") {
    showLoader();
    try {
        let url = '/vehicles/get/' + status + '?page=' + page + '&pageSize=' + pageSize;
        if (search && search.trim() !== "") {
            url += '&search=' + encodeURIComponent(search.trim());
            }
        const response = await fetch(url);
        const data = await response.json();
        totalVehicles = data.total;
        renderTable(data.vehicles);
        renderPaginationControls();
    } catch {
        showError("Impossibile caricare i veicoli. Riprova più tardi.");
        renderTable([]);
        renderPaginationControls();
    } finally {
        hideLoader();
    }
}

// Renderizza la tabella con i veicoli ricevuti
function renderTable(vehicles) {
    const tableBody = document.getElementById("vehiclesTableBody");
    tableBody.innerHTML = ""; // Pulisce il corpo della tabella

    if (!vehicles || vehicles.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="14">Nessun veicolo trovato.</td></tr>';
        return;
    }
    tableBody.innerHTML = vehicles.map(generateRowHtml).join("");
    attachEventListeners();
}

// Genera l'HTML per una riga della tabella
function generateRowHtml(vehicle) {
    return (
        '<tr data-vehicle-id="' +
        vehicle.id +
        '">' +
        "<td>" +
        vehicle.license_plate +
        "</td>" +
        "<td>" +
        vehicle.brand +
        "</td>" +
        "<td>" +
        vehicle.model +
        "</td>" +
        "<td>" +
        vehicle.year +
        "</td>" +
        "<td>" +
        vehicle.color +
        "</td>" +
        "<td>" +
        vehicle.fuel_type +
        "</td>" +
        "<td>" +
        vehicle.transmission +
        "</td>" +
        "<td>" +
        vehicle.seats +
        "</td>" +
        "<td>" +
        vehicle.vin +
        "</td>" +
        "<td>" +
        (vehicle.engine_size || "-") +
        "</td>" +
        "<td>" +
        (vehicle.notes || "Nessuna nota") +
        "</td>" +
        '<td><span class="status-badge status-' +
        vehicle.status.toLowerCase().replace(" ", "-") +
        '">' +
        vehicle.status +
        "</span></td>" +
        "<td>" +
        vehicle.notes +
        "</td>" +
        '<td class="col-actions">' +
        '<div class="action-buttons">' +
        '<button class="btn-secondary edit-vehicle" data-vehicle-id="' +
        vehicle.id +
        '">Modifica</button>' +
        '<button class="btn-danger delete-vehicle" data-vehicle-id="' +
        vehicle.id +
        '" data-brand="' +
        vehicle.brand +
        '" data-model="' +
        vehicle.model +
        '" data-license-plate="' +
        vehicle.license_plate +
        '" data-action="delete">Elimina</button>' +
        "</div>" +
        "</td>" +
        "</tr>"
    );
}

// Attacca gli event listeners ai bottoni di eliminazione
function attachEventListeners() {
    document.querySelectorAll('.delete-vehicle').forEach(button => {
        button.addEventListener('click', function () {
            const vehicleId = button.getAttribute('data-vehicle-id');
            const brand = button.getAttribute('data-brand');
            const model = button.getAttribute('data-model');
            const licensePlate = button.getAttribute('data-license-plate');
            const action = button.getAttribute('data-action');
            openDeleteModal(vehicleId, brand, model, licensePlate, action);
        });
    });
}

// Modal di eliminazione: apri
function openDeleteModal(vehicleId, brand, model, licensePlate, action) {
    const deleteModal = document.getElementById('delete-modal');
    const modalCloseButton = document.getElementById('close-delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const deleteCarName = document.getElementById('delete-car-name');
    vehicleIdToDelete = vehicleId;

    deleteForm.action = action;
    deleteCarName.textContent = brand + ' ' + model + ' (' + licensePlate + ')';

    deleteModal.style.display = 'flex';

    modalCloseButton.addEventListener('click', closeDeleteModal);
    window.addEventListener('click', function (event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
}
//Modal di edit: chiudi
function closeEditModal() {
    const editModal = document.getElementById('edit-modal');
    editModal.style.display = 'none';
}

// Modal di eliminazione: chiudi
function closeDeleteModal() {
    const deleteModal = document.getElementById('delete-modal');
    deleteModal.style.display = 'none';
    vehicleIdToDelete = null;
}

// Elimina un veicolo
async function deleteVehicle(vehicleId) {
    showLoader();
    try {
        const response = await fetch('/vehicles/delete/' + vehicleId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            }
        });
        if (!response.ok) throw new Error("DELETE request failed");
        showSuccess("Veicolo eliminato con successo.");
    } catch {
        showError("Impossibile eliminare il veicolo. Riprova più tardi.");
    } finally {
        // Dopo l'eliminazione, ricarica la pagina corrente con il filtro attivo
        const isChecked = document.getElementById("archivedCheckbox").checked;
        const status = isChecked ? "archived" : "active";
        loadVehicles(status, currentPage, pageSize);
        closeDeleteModal();
        hideLoader();
    }
}

// Pagina e paginazione
function renderPaginationControls() {
    const paginationDiv = document.getElementById("pagination-controls");
    paginationDiv.innerHTML = "";

    const totalPages = Math.ceil(totalVehicles / pageSize);
    if (totalPages <= 1) return;

    // Bottone precedente
    const prevButton = document.createElement("button");
    prevButton.textContent = "Precedente";
    prevButton.disabled = currentPage === 1;
    prevButton.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            onPageChange();
        }
    };
    paginationDiv.appendChild(prevButton);

    // Numeri pagina (max 5 visibili con ... se necessario)
    let start = Math.max(1, currentPage - 2);
    let end = Math.min(totalPages, currentPage + 2);
    if (end - start < 4) {
        if (start === 1) end = Math.min(totalPages, start + 4);
        if (end === totalPages) start = Math.max(1, end - 4);
    }
    if (start > 1) {
        paginationDiv.appendChild(makePageButton(1));
        if (start > 2) paginationDiv.appendChild(makeEllipsis());
    }
    for (let i = start; i <= end; i++) {
        paginationDiv.appendChild(makePageButton(i));
    }
    if (end < totalPages) {
        if (end < totalPages - 1) paginationDiv.appendChild(makeEllipsis());
        paginationDiv.appendChild(makePageButton(totalPages));
    }

    // Bottone successivo
    const nextButton = document.createElement("button");
    nextButton.textContent = "Successiva";
    nextButton.disabled = currentPage === totalPages;
    nextButton.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            onPageChange();
        }
    };
    paginationDiv.appendChild(nextButton);

    function makePageButton(page) {
        const button = document.createElement("button");
        button.textContent = page;
        button.disabled = page === currentPage;
        if (page === currentPage) button.classList.add("selected-page");
        button.onclick = () => {
            currentPage = page;
            onPageChange();
        };
        return button;
    }
    function makeEllipsis() {
        const ellipsis = document.createElement("span");
        ellipsis.classList.add("pagination-ellipsis");
        ellipsis.textContent = " ... ";
        return ellipsis;
    }

    // Aggiungi una classe a tutti i bottoni di paginazione
    const buttons = paginationDiv.querySelectorAll("button");
    buttons.forEach(button => button.classList.add("pagination-button"));
}

// Gestisce cambio pagina o filtro
function onPageChange() {
    const isChecked = document.getElementById("archivedCheckbox").checked;
    loadVehicles(isChecked ? "archived" : "active", currentPage, pageSize, searchQuery);
}

function openAddModal() {
    const addModal = document.getElementById("edit-modal");
    const editModalCloseButton = document.getElementById("close-edit-modal");
    addModal.style.display = 'flex';

    editModalCloseButton.addEventListener("click", closeEditModal);

    window.addEventListener('click', function (event) {
        if (event.target === addModal) {
            closeEditModal();
        }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });
}

// Gestione checkbox e avvio
document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("archivedCheckbox");
    const searchInput = document.getElementById("searchInput");
    const addButton = document.getElementById("addVehicleButton");
    let searchTimeout;

    //Gestione ricerca
    searchInput.addEventListener("input", function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchQuery = searchInput.value;
            currentPage = 1; // Reset della pagina corrente alla ricerca
            onPageChange();
        }, 500); // Ritardo di 500ms per evitare chiamate eccessive
    });
    checkbox.addEventListener("change", function () {
        currentPage = 1;
        onPageChange();
    });

    //Event listner addButton
    addButton.addEventListener("click", openAddModal);

    // Modal delete submit
    document.getElementById("delete-form").onsubmit = async function (e) {
        e.preventDefault();
        if (!vehicleIdToDelete) return;
        await deleteVehicle(vehicleIdToDelete);
    };
    // Refresh button
    document.getElementById("refresh-btn").onclick = function () {
        currentPage = 1;
        onPageChange();
    };
    onPageChange(); // Carica la prima pagina all'avvio
});