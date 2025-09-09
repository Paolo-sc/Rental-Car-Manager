let currentPage = 1;
const pageSize = 10;
let totalReservations = 0;
let reservationIdToDelete = null;
let searchQuery = "";

// Mostra messaggio di successo
function showSuccess(msg) {
    alert(msg);
}

// Mostra messaggio di errore
function showError(msg) {
    alert("Errore: " + msg);
}

// Mostra il loader
function showLoader() {
    document.getElementById("reservationsTableSection").style.display = "none";
    document.getElementById("loader-overlay").style.display = "flex";
}

// Nasconde il loader
function hideLoader() {
    document.getElementById("reservationsTableSection").style.display = "block";
    document.getElementById("loader-overlay").style.display = "none";
}

// Carica le prenotazioni dalla pagina corrente
async function loadReservations(page = 1, pageSize = 10, search = "") {
    showLoader();
    try {
        let url =
            "/reservations/get/" + "?page=" + page + "&pageSize=" + pageSize;
        if (search && search.trim() !== "") {
            url += "&search=" + encodeURIComponent(search.trim());
        }
        const response = await fetch(url);
        const data = await response.json();
        totalReservations = data.total;
        renderTable(data.reservations);
        renderPaginationControls();
    } catch {
        showError("Impossibile caricare le prenotazioni. Riprova più tardi.");
        renderTable([]);
        renderPaginationControls();
    } finally {
        hideLoader();
    }
}

// Renderizza la tabella con i veicoli ricevuti
function renderTable(reservations) {
    console.log(reservations);
    const tableBody = document.getElementById("reservationsTableBody");
    tableBody.innerHTML = ""; // Pulisce il corpo della tabella

    if (!reservations || reservations.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="14">Nessuna prenotazione trovato.</td></tr>';
        return;
    }
    tableBody.innerHTML = reservations.map(generateRowHtml).join("");
    attachEventListeners();
}

// Genera l'HTML per una riga della tabella
function generateRowHtml(reservation) {
    const customerName = 
        [reservation.customer.first_name, reservation.customer.last_name]
            .filter(Boolean)
            .join(" ") || reservation.customer.company_name || "";
    return (
        '<tr data-booking-code="' +
        reservation.booking_code +
        '">' +
        "<td>" +
        reservation.booking_code +
        "</td>" +
        "<td>" +
        reservation.contract_number +
        "</td>" +
        "<td>" +
        customerName +
        "</td>" +
        "<td>" +
        reservation.vehicle.brand + " " + reservation.vehicle.model + " " + reservation.vehicle.license_plate +
        "</td>" +
        "<td>" +
        reservation.start_date +
        "</td>" +
        "<td>" +
        reservation.end_date +
        "</td>" +
        "<td>" +
        reservation.pickup_time +
        "</td>" +
        "<td>" +
        reservation.return_time +
        "</td>" +
        "<td>" +
        reservation.deposit_amount +
        "</td>" +
        "<td>" +
        reservation.total_amount +
        "</td>" +
        "<td>" +
        (reservation.payment_received
            ? "Pagamento ricevuto"
            : "In attesa di pagamento") +
        "</td>" +
        "<td>" +
        (reservation.customer_signature_obtained
            ? "Firma ottenuta"
            : reservation.customer_signature_required
            ? "In attesa di firma"
            : "") +
        "</td>" +
        '<td><span class="status-badge status-' +
        reservation.status.toLowerCase().replace(" ", "-") +
        '">' +
         (reservation.status === "pending"
                ? "In Attesa"
                : reservation.status === "active"
                    ? "Attiva"
                    : reservation.status === "completed"
                        ? "Completato" : "Cancellato") +
        "</span></td>" +
        "<td>" +
        "<a href='"+reservation.contract_pdf_drive_file_url +"'>Apri contratto</a>"+
        "</td>" +
        '<td class="col-actions">' +
        '<div class="action-buttons">' +
        '<button class="btn-secondary edit-reservation" data-reservation-id="' +
        reservation.id +
        '">Modifica</button>' +
        '<button class="btn-danger delete-reservation" data-reservation-id="' +
        reservation.id +
        '" data-booking-code="' +
        reservation.booking_code +
        '" data-contract-number="' +
        reservation.contract_number +
        '" data-action="reservations/delete">Elimina</button>' +
        "</div>" +
        "</td>" +
        "</tr>"
    );
}

// Attacca gli event listeners ai bottoni di eliminazione
function attachEventListeners() {
    document.querySelectorAll(".delete-reservation").forEach((button) => {
        button.addEventListener("click", function () {
            const reservationId = button.getAttribute("data-reservation-id");
            const bookingCode = button.getAttribute("data-booking-code");
            const contractNumber = button.getAttribute("data-contract-number");
            const action = button.getAttribute("data-action");
            openDeleteModal(reservationId, bookingCode, contractNumber, action);
        });
    });
    /*document.querySelectorAll(".edit-vehicle").forEach((button) => {
        button.addEventListener("click", function () {
            const vehicleId = button.getAttribute("data-vehicle-id");
            openEditModal(vehicleId);
        });
    });
    document.querySelectorAll(".view-documents").forEach((button) => {
        button.addEventListener("click", function () {
            const vehicleId = button.getAttribute("data-vehicle-id");
            const vehicleName = button.getAttribute("data-vehicle-name");
            openDocumentModal(vehicleId, vehicleName);
        });
    });*/
}

// Pagina e paginazione
function renderPaginationControls() {
    const paginationDiv = document.getElementById("pagination-controls");
    paginationDiv.innerHTML = "";

    const totalPages = Math.ceil(totalReservations / pageSize);
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
    buttons.forEach((button) => button.classList.add("pagination-button"));
}

// Gestisce cambio pagina o filtro
function onPageChange() {
    loadReservations(currentPage, pageSize, searchQuery);
}

function openDeleteModal(reservationId, bookingCode, contractNumber, action){
    const deleteModal = document.getElementById("delete-modal");
    const modalCloseButton = document.getElementById("close-delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const deleteReservation = document.getElementById("delete-reservation");
    reservationIdToDelete = reservationId;

    deleteReservation.textContent = bookingCode + " " + contractNumber;

    deleteModal.style.display = "flex";

    modalCloseButton.addEventListener("click", closeDeleteModal);
    window.addEventListener("click", function (event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeDeleteModal();
        }
    });
}

function closeDeleteModal(){
    const deleteModal = document.getElementById("delete-modal");
    deleteModal.style.display = "none";
    reservationIdToDelete = null;

    //Se era aperto il modal documenti prima di aprire questo, riaprilo
    /*if (
        reopenDocumentModalAfterDelete &&
        lastVehicleIdForDocs &&
        lastVehicleNameForDocs
    ) {
        openDocumentModal(lastVehicleIdForDocs, lastVehicleNameForDocs);
    }*/
}

// Elimina una prenotazione
async function deleteReservation(reservationId) {
    showLoader();
    try {
        const response = await fetch("/reservations/delete/" + reservationId, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
                Accept: "application/json",
            },
        });
        if (!response.ok) throw new Error("DELETE request failed");
        showSuccess("Prenotazione eliminata con successo.");
    } catch {
        showError("Impossibile eliminare la prenotazione. Riprova più tardi.");
    } finally {
        // Dopo l'eliminazione, ricarica la pagina corrente
        loadReservations(currentPage, pageSize);
        closeDeleteModal();
        hideLoader();
    }
}

//Gestione Avvio
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const addButton = document.getElementById("add-reservation-button");
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
    
    //Event listner addButton
    //addButton.addEventListener("click", openAddModal);

    //Modal delete submit
    document.getElementById("delete-form").onsubmit = async function (e) {
        e.preventDefault();
        if (!reservationIdToDelete) return;
        await deleteReservation(reservationIdToDelete);
    };
    // Refresh button
   /* document.getElementById("refresh-btn").onclick = function () {
        currentPage = 1;
        onPageChange();
    };*/
    onPageChange(); // Carica la prima pagina all'avvio
});
