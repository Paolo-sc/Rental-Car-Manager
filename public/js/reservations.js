let currentPage = 1;
const pageSize = 10;
let totalReservations = 0;
let reservationIdToDelete = null;
let searchQuery = "";
let customerAutocompleteInitialized = false;
let vehicleAutocompleteInitialized = false;
let driverAutocompleteInitialized = false;
let signaturePadInstance = null;

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
            .join(" ") ||
        reservation.customer.company_name ||
        "";
    return (
        '<tr data-booking-code="' +
        reservation.booking_code +
        '">' +
        "<td>" +
        reservation.booking_code +
        "</td>" +
        "<td>" +
        customerName +
        "</td>" +
        "<td>" +
        reservation.vehicle.brand +
        " " +
        reservation.vehicle.model +
        " " +
        reservation.vehicle.license_plate +
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
            ? "Completato"
            : "Cancellato") +
        "</span></td>" +
        "<td>" +
        "<a class='view-contract'" +
        "' data-reservation-contract-pdf-drive-file-id='" +
        reservation.contract_pdf_drive_file_id +
        "' data-reservation-id='" +
        reservation.id +
        "'>Apri contratto</a>" +
        "</td>" +
        "<td>" +
        "<button class='btn-primary add-driver' data-reservation-id='" +
        reservation.id +
        "'>Visualizza Conducenti</button>" +
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
    
    document.querySelectorAll(".view-contract").forEach((a) => {
        a.addEventListener("click", function () {
            const reservationId = a.getAttribute("data-reservation-id");
            const contractPdfUrl = `https://drive.google.com/file/d/${a.getAttribute("data-reservation-contract-pdf-drive-file-id")}/preview`;
            openSignatureModal(contractPdfUrl, reservationId);
        });
    });

    /*document.querySelectorAll(".edit-vehicle").forEach((button) => {
        button.addEventListener("click", function () {
            const vehicleId = button.getAttribute("data-vehicle-id");
            openEditModal(vehicleId);
        });
    });*/
    document.querySelectorAll(".add-driver").forEach((button) => {
        button.addEventListener("click", function () {
            const reservationId = button.getAttribute("data-reservation-id");
            openDriversModal(reservationId);
        });
    });
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

function openDeleteModal(reservationId, bookingCode, contractNumber, action) {
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

function closeDeleteModal() {
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

function openAddModal() {
    const addModal = document.getElementById("edit-modal");
    const editModalCloseButton = document.getElementById("close-edit-modal");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-reservation");
    const editForm = document.getElementById("edit-reservation-form");

    editForm.action = editForm.getAttribute("data-add-action");
    editForm.method = "POST"; // Imposto il metodo a POST
    editForm.reset();

    // reset indicatori di autocomplete
    customerAutocompleteInitialized = false;
    vehicleAutocompleteInitialized = false;
    driverAutocompleteInitialized = false;


    //Rimuovi eventuale input_method
    const oldMethodInput = editForm.querySelector("input[name='_method']");
    if (oldMethodInput) oldMethodInput.remove();

    submitButton.textContent = "Aggiungi";
    modalHeaderH2.textContent = "Aggiungi Prenotazione";
    addModal.style.display = "flex";

    editModalCloseButton.addEventListener("click", closeEditModal);

    window.addEventListener("click", function (event) {
        if (event.target === addModal) {
            closeEditModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeEditModal();
        }
    });
}

//Modal di edit: chiudi
function closeEditModal() {
    const editModal = document.getElementById("edit-modal");
    editModal.style.display = "none";

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

function customerAutocomplete(inputId, hiddenId, suggestionsId) {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const suggestions = document.getElementById(suggestionsId);
    let debounceTimeout, currentIndex = -1, items = [], lastData = [];

    // Stili per dropdown (puoi spostarli in CSS)
    suggestions.style.position = "absolute";
    suggestions.style.zIndex = 1000;
    suggestions.style.background = "#fff";
    suggestions.style.border = "1px solid #ccc";
    suggestions.style.maxHeight = "250px";
    suggestions.style.overflowY = "auto";
    suggestions.style.width = "100%";
    suggestions.style.display = "none";

    input.addEventListener("input", function () {
        const query = input.value.trim();
        hidden.value = "";
        if (query.length < 2) {
            suggestions.innerHTML = "";
            suggestions.style.display = "none";
            return;
        }
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => fetchCustomers(query), 250);
    });

    function fetchCustomers(query) {
        fetch(`/customers/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => showSuggestions(data))
            .catch(() => { suggestions.innerHTML = ""; suggestions.style.display = "none"; });
    }

    function showSuggestions(data) {
        suggestions.innerHTML = "";
        items = [];
        lastData = data;
        currentIndex = -1;
        if (!data.length) {
            suggestions.innerHTML = '<div class="dropdown-item disabled">Nessun cliente trovato</div>';
            suggestions.style.display = "block";
            return;
        }
        data.forEach((cliente, idx) => {
            const nome = cliente.display || ((cliente.first_name || '') + ' ' + (cliente.last_name || '')) || cliente.company_name;
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.textContent = nome;
            div.tabIndex = -1;
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectItem(idx);
            });
            suggestions.appendChild(div);
            items.push(div);
        });
        suggestions.style.display = "block";
    }

    function selectItem(idx) {
        if (items.length === 0 || idx < 0 || idx >= items.length) return;
        const item = items[idx];
        input.value = item.textContent;
        hidden.value = lastData[idx].id;
        suggestions.innerHTML = '';
        suggestions.style.display = "none";
    }

    // Navigazione tastiera
    input.addEventListener("keydown", function (e) {
        if (suggestions.style.display !== "block" || !items.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % items.length;
            updateActiveItem();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateActiveItem();
        } else if (e.key === "Enter") {
            if (currentIndex >= 0 && currentIndex < items.length) {
                e.preventDefault();
                selectItem(currentIndex);
            }
        } else if (e.key === "Escape") {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });

    function updateActiveItem() {
        items.forEach((item, i) => {
            if (i === currentIndex) {
                item.classList.add("active");
                item.scrollIntoView({ block: "nearest" });
            } else {
                item.classList.remove("active");
            }
        });
    }

    // Chiudi i suggerimenti se clicchi fuori
    document.addEventListener('mousedown', function (e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });
}

function driverAutocomplete(inputId, hiddenId, suggestionsId) {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const suggestions = document.getElementById(suggestionsId);
    let debounceTimeout, currentIndex = -1, items = [], lastData = [];

    // Stili per dropdown (spostare in CSS)
    suggestions.style.position = "absolute";
    suggestions.style.zIndex = 1000;
    suggestions.style.background = "#fff";
    suggestions.style.border = "1px solid #ccc";
    suggestions.style.maxHeight = "250px";
    suggestions.style.overflowY = "auto";
    suggestions.style.width = "100%";
    suggestions.style.display = "none";

    input.addEventListener("input", function () {
        const query = input.value.trim();
        hidden.value = "";
        if (query.length < 2) {
            suggestions.innerHTML = "";
            suggestions.style.display = "none";
            return;
        }
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => fetchDrivers(query), 250);
    });

    function fetchDrivers(query) {
        fetch(`/drivers/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => showSuggestions(data))
            .catch(() => { suggestions.innerHTML = ""; suggestions.style.display = "none"; });
    }

    function showSuggestions(data) {
        suggestions.innerHTML = "";
        items = [];
        lastData = data;
        currentIndex = -1;
        if (!data.length) {
            suggestions.innerHTML = '<div class="dropdown-item disabled">Nessun driver trovato</div>';
            suggestions.style.display = "block";
            return;
        }
        data.forEach((driver, idx) => {
            const nome = driver.display || ((driver.first_name || '') + ' ' + (driver.last_name || '')) || driver.company_name;
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.textContent = nome;
            div.tabIndex = -1;
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectItem(idx);
            });
            suggestions.appendChild(div);
            items.push(div);
        });
        suggestions.style.display = "block";
    }

    function selectItem(idx) {
        if (items.length === 0 || idx < 0 || idx >= items.length) return;
        const item = items[idx];
        input.value = item.textContent;
        hidden.value = lastData[idx].id;
        suggestions.innerHTML = '';
        suggestions.style.display = "none";
    }

    // Navigazione tastiera
    input.addEventListener("keydown", function (e) {
        if (suggestions.style.display !== "block" || !items.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % items.length;
            updateActiveItem();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateActiveItem();
        } else if (e.key === "Enter") {
            if (currentIndex >= 0 && currentIndex < items.length) {
                e.preventDefault();
                selectItem(currentIndex);
            }
        } else if (e.key === "Escape") {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });

    function updateActiveItem() {
        items.forEach((item, i) => {
            if (i === currentIndex) {
                item.classList.add("active");
                item.scrollIntoView({ block: "nearest" });
            } else {
                item.classList.remove("active");
            }
        });
    }

    // Chiudi i suggerimenti se clicchi fuori
    document.addEventListener('mousedown', function (e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });
}

function vehicleAutocomplete(inputId, hiddenId, suggestionsId) {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const suggestions = document.getElementById(suggestionsId);
    let debounceTimeout, currentIndex = -1, items = [], lastData = [];


    input.addEventListener("input", function () {
        const query = input.value.trim();
        hidden.value = "";
        if (query.length < 2) {
            suggestions.innerHTML = "";
            suggestions.style.display = "none";
            return;
        }
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => fetchVehicles(query), 250);
    });

    function fetchVehicles(query) {
        fetch(`/vehicles/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => showSuggestions(data))
            .catch(() => { suggestions.innerHTML = ""; suggestions.style.display = "none"; });
    }

    function showSuggestions(data) {
        suggestions.innerHTML = "";
        items = [];
        lastData = data;
        currentIndex = -1;
        if (!data.length) {
            suggestions.innerHTML = '<div class="dropdown-item disabled">Nessun veicolo trovato</div>';
            suggestions.style.display = "block";
            return;
        }
        data.forEach((veicolo, idx) => {
            const nome = veicolo.display || ((veicolo.brand || '') + ' ' + (veicolo.model || '')) || veicolo.license_plate;
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.textContent = nome;
            div.tabIndex = -1;
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectItem(idx);
            });
            suggestions.appendChild(div);
            items.push(div);
        });
        suggestions.style.display = "block";
    }

    function selectItem(idx) {
        if (items.length === 0 || idx < 0 || idx >= items.length) return;
        const item = items[idx];
        input.value = item.textContent;
        hidden.value = lastData[idx].id;
        suggestions.innerHTML = '';
        suggestions.style.display = "none";
    }

    // Navigazione tastiera
    input.addEventListener("keydown", function (e) {
        if (suggestions.style.display !== "block" || !items.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % items.length;
            updateActiveItem();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateActiveItem();
        } else if (e.key === "Enter") {
            if (currentIndex >= 0 && currentIndex < items.length) {
                e.preventDefault();
                selectItem(currentIndex);
            }
        } else if (e.key === "Escape") {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });

    function updateActiveItem() {
        items.forEach((item, i) => {
            if (i === currentIndex) {
                item.classList.add("active");
                item.scrollIntoView({ block: "nearest" });
            } else {
                item.classList.remove("active");
            }
        });
    }

    // Chiudi i suggerimenti se clicchi fuori
    document.addEventListener('mousedown', function (e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
            suggestions.style.display = "none";
        }
    });
}

async function addReservation(formData) {
    // Implementa la logica per aggiungere una prenotazione
    // Usa fetch per inviare i dati al server
    // mostra i dati del form per ora
    //setuppo i dati da inviare al server
    //aggiungo il campo total_days che è la differenza tra start_date e end_date
    const startDate = new Date(formData.start_date);
    const endDate = new Date(formData.end_date);
    const timeDiff = Math.abs(endDate - startDate);
    const totalDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;
    formData.total_days = totalDays;

    //Aggiungo il campo numero di prenotazione generato come "BKG" + data in formato YYYYMMDD + un numero casuale di 4 cifre
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const randomNum = Math.floor(1000 + Math.random() * 9000);
    formData.booking_code = `BKG${year}${month}${day}${randomNum}`;

    //Setto il customer_signature_obtained o customer_signature_obtained in base al valore del campo customer_signature che arriva come signed o not signed
    if (formData.customer_signature_status === "signed") {
        formData.customer_signature_obtained = true;
        formData.customer_signature_required = false;
    } else if (formData.customer_signature_status === "not signed") {
        formData.customer_signature_obtained = false;
        formData.customer_signature_required = true;
    }

    //calcolo il tax_amount come total_amount - tax_rate (%)
    const totalAmount = parseFloat(formData.total_amount) || 0;
    const taxRate = parseFloat(formData.tax_rate) || 0;
    const taxAmount = (totalAmount * taxRate) / 100;
    formData.tax_amount = taxAmount.toFixed(2);

    //levo customer e vehicle e main_driver che sono i campi di testo e non servono al server
    delete formData.customer;
    delete formData.vehicle;
    delete formData.main_driver;
    delete formData.customer_signature_status;

    showLoader();
    try {
        const response = await fetch("/reservations/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            showError(errorData.error || "Errore nella richiesta");
            throw new Error("Errore nella richiesta");
        }

        const result = await response.json();
    } catch (error) {
        console.error("Si è verificato un errore:", error);
    } finally {
        hideLoader();
        
    }
}


function openSignatureModal(pdfUrl, reservationId) {
    document.getElementById("signature-modal").style.display = "flex";
    document.getElementById("contract-pdf-frame").src = pdfUrl;
    // Inizializza SignaturePad quando apri il modal
    const canvas = document.getElementById("signature-pad");
    signaturePadInstance = new SignaturePad(canvas);
    // Pulisci canvas all'apertura
    signaturePadInstance.clear();

    // Handler salva firma
    document.getElementById("save-signature-btn").onclick = function() {
        if (signaturePadInstance.isEmpty()) {
            alert("Firma non presente!");
            return;
        }
        const signatureData = signaturePadInstance.toDataURL();
        // Invia al server (aggiungi il reservationId se serve)
        saveSignature(signatureData, reservationId);
    };

    // Handler cancella firma
    document.getElementById("clear-signature-btn").onclick = function() {
        signaturePadInstance.clear();
    };

    // Handler chiudi modal
    document.getElementById("close-signature-modal").onclick = function() {
        closeSignatureModal();
    };
    window.addEventListener("click", function (event) {
        if (event.target === document.getElementById("signature-modal")) {
            closeSignatureModal();
        }
    });
}

function closeSignatureModal() {
    document.getElementById("signature-modal").style.display = "none";
    document.getElementById("contract-pdf-frame").src = "";
    signaturePadInstance = null;
}

async function saveSignature(signatureData, reservationId) {
    try {
        // Mostra un loader, opzionale
        showLoader();

        const response = await fetch(`/reservations/sign/${reservationId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: JSON.stringify({ signature: signatureData })
        });

        if (!response.ok) {
            throw new Error("Errore nel salvataggio firma.");
        }

        const data = await response.json();

        if (data.success) {
            alert("Firma salvata! Il PDF firmato è pronto.");
            closeSignatureModal();
            // Aggiorna la tabella (o la riga) delle prenotazioni per mostrare che la firma è stata acquisita
            onPageChange(); // se usi la funzione per ricaricare la tabella
            // Volendo apri il PDF firmato:
            // window.open(data.contract_link, "_blank");
        } else {
            alert("Errore: il server ha risposto ma non ha salvato il PDF.");
        }
    } catch (error) {
        alert(error.message || "Errore imprevisto nel salvataggio firma.");
    } finally {
        hideLoader();
    }
}

function openDriversModal(reservationId) {
    // Implementa l'apertura del modal per la gestione dei conducenti
    const driversModal = document.getElementById("drivers-modal");
    const modalCloseButton = document.getElementById("close-drivers-modal");

    // Carica i conducenti associati alla prenotazione
    loadDrivers(reservationId);

    driversModal.style.display = "flex";

    modalCloseButton.addEventListener("click", closeDriversModal);
    window.addEventListener("click", function (event) {
        if (event.target === driversModal) {
            closeDriversModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeDriversModal();
        }
    });

    // Aggiungi event listener al bottone "Aggiungi Conducente"
    document.getElementById("add-new-driver-button").onclick = function() {
        openAddDriverModal(reservationId);
    };

}

function closeDriversModal() {
    const driversModal = document.getElementById("drivers-modal");
    driversModal.style.display = "none";
}

async function loadDrivers(reservationId) {
    // Implementa il caricamento dei conducenti associati alla prenotazione
    // Usa fetch per ottenere i dati dal server e popolare il modal
    showLoader();
    try {
        const response = await fetch(`/reservations/${reservationId}/drivers`);
        if (!response.ok) throw new Error("GET request failed");
        const data = await response.json();
        // Popola il modal con i dati ricevuti
        console.log(data);
        const driversList = document.getElementById("drivers-list");
        driversList.innerHTML = "";
        data.forEach(driver => {
            console.log(driver);
            const listItem = document.createElement("li");
            listItem.textContent = `${driver.first_name} ${driver.last_name} - Patente: ${driver.driving_license_number} (Scadenza: ${driver.driving_license_expires_at})`;
            driversList.appendChild(listItem);
        });
    } catch {
        showError("Impossibile caricare i conducenti. Riprova più tardi.");
    } finally {
        hideLoader();
    }
}

function openAddDriverModal(reservationId) {
    // Implementa l'apertura del modal per aggiungere un conducente
    const addDriverModal = document.getElementById("add-driver-modal");
    const modalCloseButton = document.getElementById("close-add-driver-modal");
    addDriverModal.style.display = "flex";

    modalCloseButton.addEventListener("click", closeAddDriverModal);
    window.addEventListener("click", function (event) {
        if (event.target === addDriverModal) {
            closeAddDriverModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeAddDriverModal();
        }
    });

    // Aggiungi event listener al form di aggiunta conducente
    document
        .getElementById("driver-input")
        .addEventListener("focus", function () {
            if (!driverAutocompleteInitialized) {
                driverAutocomplete(
                    "driver-input",
                    "driver-id",
                    "driver-suggestions"
                );
                driverAutocompleteInitialized = true;
            }
        });

    document.getElementById("add-existing-driver-button").onclick = function() {
        console.log("Aggiungi conducente a prenotazione ID:", reservationId);
        const driverId = document.getElementById("driver-id").value;
        if (driverId) {
            addDriverToReservation(reservationId, driverId);
        }
    };

    document.getElementById("save-new-driver-btn").onclick = function() {
        // Prendi i dati del form li metti in newDriverData e li passi alla funzione
            const newDriverData = {
                first_name: document.getElementById("first_name").value,
                last_name: document.getElementById("last_name").value,
                email: document.getElementById("email").value,
                phone: document.getElementById("phone").value,
                tax_code: document.getElementById("tax_code").value,
                birth_date: document.getElementById("birth_date").value,
                birth_place: document.getElementById("birth_place").value,
                driving_license_number: document.getElementById("driving_license_number").value,
                driving_license_issue_date: document.getElementById("driving_license_issue_date").value,
                driving_license_expires_at: document.getElementById("driving_license_expires_at").value,
                address: document.getElementById("address").value,
                city: document.getElementById("city").value,
                postal_code: document.getElementById("postal_code").value,
                country: document.getElementById("country").value,
                notes: document.getElementById("notes").value
            };
            addDriverToReservation(reservationId, null, newDriverData);
        };
}

function closeAddDriverModal() {
    const addDriverModal = document.getElementById("add-driver-modal");
    addDriverModal.style.display = "none";
}

async function addDriverToReservation(reservationId, driverId, newDriverData = null) {
    // Implementa la logica per aggiungere un conducente alla prenotazione
    // Usa fetch per inviare i dati al server
    console.log(newDriverData);
    showLoader();
    try {
        //se clicco su aggiungi conduncete associo il conducente alla prenotazione(serve solo l'id del driver e della prenotazione)
        if (!newDriverData) {
        const response = await fetch(`/reservations/${reservationId}/drivers/add`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({ driver_id: driverId })
        });
        } else {
            console.log("Creazione nuovo conducente e associazione a prenotazione ID:", reservationId, newDriverData);
            console.log('/reservations/' + reservationId + '/drivers/add');
            //se clicco su crea conducente creo il conducente e lo associo alla prenotazione (serve l'id della prenotazione e i dati del nuovo conducente)
            const response = await fetch(`/reservations/${reservationId}/drivers/add`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ new_driver: newDriverData })
            });
        }
    } catch (error) {
        console.error("Error adding driver:", error);
    } finally {
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
    addButton.addEventListener("click", openAddModal);

    document
        .getElementById("customer-input")
        .addEventListener("focus", function () {
            if (!customerAutocompleteInitialized) {
                customerAutocomplete(
                    "customer-input",
                    "customer-id",
                    "customer-suggestions"
                );
                customerAutocompleteInitialized = true;
            }
        });
    
    document
        .getElementById("vehicle-input")
        .addEventListener("focus", function () {
            if (!vehicleAutocompleteInitialized) {
                vehicleAutocomplete(
                    "vehicle-input",
                    "vehicle-id",
                    "vehicle-suggestions"
                );
                vehicleAutocompleteInitialized = true;
            }
        });
    
    document
        .getElementById("main-driver-input")
        .addEventListener("focus", function () {
            if (!driverAutocompleteInitialized) {
                driverAutocomplete(
                    "main-driver-input",
                    "main-driver-id",
                    "main-driver-suggestions"
                );
                driverAutocompleteInitialized = true;
            }
        });


    //Modal delete submit
    document.getElementById("delete-form").onsubmit = async function (e) {
        e.preventDefault();
        if (!reservationIdToDelete) return;
        await deleteReservation(reservationIdToDelete);
    };

    //Modal add submit
    document.getElementById("edit-reservation-form").onsubmit = function (e) {
        e.preventDefault();
        const form = e.target;
        const action = form.action;
        const method = form.method.toUpperCase();

        //crea un oggetto FormData dai dati del form
        form.data = new FormData(form);

        const data= {};
        form.data.forEach((value, key) => {
            data[key] = value;
        });
        addReservation(data);
    }
    // Refresh button
    /* document.getElementById("refresh-btn").onclick = function () {
        currentPage = 1;
        onPageChange();
    };*/
    onPageChange(); // Carica la prima pagina all'avvio
});
