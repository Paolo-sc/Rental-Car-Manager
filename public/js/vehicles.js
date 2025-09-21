let currentPage = 1;
const pageSize = 10;
let totalVehicles = 0;
let vehicleIdToDelete = null;
let searchQuery = "";
let lastVehicleIdForDocs = null;
let lastVehicleNameForDocs = null;
let originalFormContent = "";

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
    document.getElementById("vehiclesTableSection").style.display = "none";
    document.getElementById("loader-overlay").style.display = "flex";
}

// Nasconde il loader
function hideLoader() {
    document.getElementById("vehiclesTableSection").style.display = "block";
    document.getElementById("loader-overlay").style.display = "none";
}

// Carica i veicoli dalla pagina corrente e stato selezionato
async function loadVehicles(status, page = 1, pageSize = 10, search = "") {
    showLoader();
    try {
        let url =
            "/vehicles/get/" +
            status +
            "?page=" +
            page +
            "&pageSize=" +
            pageSize;
        if (search && search.trim() !== "") {
            url += "&search=" + encodeURIComponent(search.trim());
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
        tableBody.innerHTML =
            '<tr><td colspan="14">Nessun veicolo trovato.</td></tr>';
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
        vehicle.mileage +
        "</td>" +
        '<td><span class="status-badge status-' +
        vehicle.status.toLowerCase().replace(" ", "-") +
        '">' +
        vehicle.status +
        "</span></td>" +
        "<td>" +
        (vehicle.notes || "Nessuna Nota") +
        "</td>" +
        '<td class="col-actions">' +
        '<div class="action-buttons">' +
        '<button class="btn-info view-documents" data-vehicle-id="' +
        vehicle.id +
        '" data-vehicle-name="' +
        vehicle.license_plate +
        " " +
        vehicle.brand +
        " " +
        vehicle.model +
        " " +
        '">Documenti</button>' +
        "</div>" +
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
    document.querySelectorAll(".delete-vehicle").forEach((button) => {
        button.addEventListener("click", function () {
            const vehicleId = button.getAttribute("data-vehicle-id");
            const brand = button.getAttribute("data-brand");
            const model = button.getAttribute("data-model");
            const licensePlate = button.getAttribute("data-license-plate");
            const action = button.getAttribute("data-action");
            openDeleteModal(vehicleId, brand, model, licensePlate, action);
        });
    });
    document.querySelectorAll(".edit-vehicle").forEach((button) => {
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
    });
}

// Pulisce completamente il form mantenendo token e _method
function resetEditForm(editForm) {
    // salva _token e _method
    const token = editForm.querySelector('input[name="_token"]');
    const method = editForm.querySelector('input[name="_method"]');

    // cancella tutto
    editForm.innerHTML = "";

    // reinserisci token e method
    if (token) editForm.appendChild(token);
    if (method) editForm.appendChild(method);
}

function closeDocumentModal() {
    const documentModal = document.getElementById("document-modal");
    documentModal.style.display = "none";
}

async function openDocumentModal(vehicleId, vehicleName) {
    const documentModal = document.getElementById("document-modal");
    const modalCloseButton = document.getElementById("close-document-modal");
    const documentsList = document.getElementById("documents-list");
    const vehicleNameSpan = document.getElementById("vehicle-name");
    const addDocumentButton = document.getElementById("add-document-button");

     //Aggiungo l'event listener al pulsante di aggiunta documento
    addDocumentButton.onclick = function () {
        // Apro il modal di aggiunta documento
        openAddDocumentModal(vehicleId, vehicleName);
    };

    showLoader();
    //Salvo il veicolo
    lastVehicleIdForDocs = vehicleId;
    lastVehicleNameForDocs = vehicleName;
    reopenDocumentModalAfterDelete = false;

    // Imposta il nome del veicolo nel modal
    vehicleNameSpan.textContent = vehicleName;

    // Carica i documenti del veicolo via AJAX
    const response = await fetch("/vehicles/" + vehicleId + "/documents");
    documentsList.innerHTML = ""; // Pulisce la lista dei documenti
    if (!response.ok) throw new Error("Errore nel caricamento dei documenti");

    //Creo la lista con le informazioni dei documenti e due pulsanti uno per la modifica del doc e uno per la cancellazione
    const documents = await response.json();
    if (!documents || documents.length === 0) {
        documentsList.innerHTML = "<li>Nessun documento trovato.</li>";
    } else {
        documents.forEach((doc) => {
            const listItem = document.createElement("li");
            listItem.textContent =
                doc.document_type +
                " - Documento: " +
                doc.document_name +
                " - Caricato il: " +
                new Date(doc.created_at).toLocaleDateString() +
                " ";
            if (doc.drive_file_url) {
                const link = document.createElement("a");
                link.href = doc.drive_file_url;
                link.textContent = "Visualizza Documento";
                link.target = "_blank";
                listItem.appendChild(link);
            }
            // Pulsante per la modifica del documento
            const editButton = document.createElement("button");
            editButton.classList.add("btn-secondary");
            editButton.textContent = "Modifica";
            editButton.addEventListener("click", function () {
                openEditDocumentModal(doc.id, vehicleId, vehicleName);
            });
            listItem.appendChild(editButton);

            // Pulsante per la cancellazione del documento
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn-danger");
            deleteButton.addEventListener("click", function () {
                openDeleteDocumentModal(
                    doc.id,
                    doc.document_type,
                    doc.document_name,
                    vehicleId
                );
            });
            deleteButton.textContent = "Elimina";
            listItem.appendChild(deleteButton);

            documentsList.appendChild(listItem);
        });
    }

    documentModal.style.display = "flex";

    modalCloseButton.addEventListener("click", closeDocumentModal);
    window.addEventListener("click", function (event) {
        if (event.target === documentModal) {
            closeDocumentModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeDocumentModal();
        }
    });

    hideLoader();
}

function openDeleteDocumentModal(
    documentId,
    documentType,
    documentNumber,
    vehicleId
) {
    // Implementa la logica per aprire il modal di cancellazione del documento
    const deleteModal = document.getElementById("delete-modal");
    //Mi nascondo il modal di visualizzazione documenti se aperto
    showLoader();
    closeDocumentModal();
    reopenDocumentModalAfterDelete = true; // segnalo che va riaperto dopo

    deleteModal.style.display = "flex";

    //Prendi il paragraf figlio del form e settalo con le info del documento in unelemento <span>
    const deleteForm = document.getElementById("delete-form");
    const paragraph = deleteForm.querySelector("p");
    const span = document.createElement("span");
    span.style.fontWeight = "bold";
    span.textContent = documentType + " - Numero documento: " + documentNumber;
    paragraph.textContent = "Sei sicuro di voler eliminare il documento: ";
    paragraph.appendChild(span);

    //prendi l'ultimo paragraf figlio del form e settalo con un messaggio di avviso
    const warningParagraph = deleteForm.querySelectorAll("p")[1];
    warningParagraph.textContent =
        "Attenzione: Eliminando questo documento, non sarà più possibile recuperarlo.";

    //Imposto l'eliminazione del doc al click del submit
    const form = document.getElementById("delete-form");
    form.onsubmit = async function (e) {
        e.preventDefault();
        await deleteDocument(documentId, vehicleId);
    };

    const modalCloseButton = document.getElementById("close-delete-modal");
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
    hideLoader();
}

async function openEditDocumentModal(
    documentId,
    vehicleId = null,
    vehicleName = null
) {
    const editModal = document.getElementById("edit-modal");
    const modalCloseButton = document.getElementById("close-edit-modal");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-vehicle");
    const editForm = document.getElementById("edit-vehicle-form");

    showLoader();
    closeDocumentModal();
    reopenDocumentModalAfterDelete = true;
    lastVehicleIdForDocs = vehicleId;
    lastVehicleNameForDocs = vehicleName;

    //Salvo e poi Resetto il form
    if (!originalFormContent || originalFormContent.trim() === "") {
        originalFormContent = editForm.innerHTML;
    }
    resetEditForm(editForm);

    // setto action e method "virtuale"
    editForm.action = "vehicles/documents/update/" + documentId;
    editForm.method = "POST"; // Laravel gestisce il PUT tramite _method

    // pulisco SOLO i campi dinamici, NON gli hidden
    Array.from(editForm.querySelectorAll(".dynamic-field")).forEach((el) =>
        el.remove()
    );

    // --- Costruzione campi dinamici ---
    const fields = [];

    // Tipo documento
    const typeContainer = document.createElement("div");
    typeContainer.className = "input-container dynamic-field";
    typeContainer.innerHTML = `
        <label for="edit_document_type">Tipo di Documento</label>
        <select name="document_type" id="edit_document_type" required>
            <option value="registration">Libretto</option>
            <option value="manual">Manuale</option>
            <option value="insurance">Assicurazione</option>
            <option value="other">Altro</option>
        </select>`;
    fields.push(typeContainer);

    // Numero documento
    const numberContainer = document.createElement("div");
    numberContainer.className = "input-container dynamic-field";
    numberContainer.innerHTML = `
        <label for="edit_id_document_name">Id Documento</label>
        <input type="text" name="document_name" id="edit_id_document_name" required>`;
    fields.push(numberContainer);

    // Note
    const notesContainer = document.createElement("div");
    notesContainer.className = "input-container dynamic-field";
    notesContainer.innerHTML = `
        <label for="edit_notes">Note</label>
        <input type="text" name="notes" id="edit_notes">`;
    fields.push(notesContainer);

    // File documento
    const fileContainer = document.createElement("div");
    fileContainer.className = "input-container dynamic-field";
    fileContainer.innerHTML = `
        <label for="edit_document">File Documento (PDF, JPG, PNG)</label>
        <div id="current-document-container"></div>
        <input type="file" name="document" id="edit_document">
        <small>Lascialo vuoto se non vuoi sostituire il file attuale</small>`;
    fields.push(fileContainer);

    // vehicle_id hidden
    const hiddenVehicle = document.createElement("input");
    hiddenVehicle.type = "hidden";
    hiddenVehicle.name = "vehicle_id";
    hiddenVehicle.value = vehicleId;
    hiddenVehicle.classList.add("dynamic-field");
    fields.push(hiddenVehicle);

    // Aggiungo i campi al form
    fields.forEach((f) => editForm.appendChild(f));

    // Reinserisco o creo input _method=PUT (se non esiste)
    if (!editForm.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "PUT";
        editForm.appendChild(methodInput);
    }

    // Reinserisco o creo token CSRF (se non esiste)
    if (!editForm.querySelector('input[name="_token"]')) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfToken;
        editForm.appendChild(csrfInput);
    }

    // Titoli pulsanti
    submitButton.textContent = "Modifica";
    modalHeaderH2.textContent = "Modifica Documento";

    // Carico dati documento
    try {
        const response = await fetch("vehicles/documents/" + documentId);
        if (!response.ok)
            throw new Error("Errore nel caricamento del documento");
        const documentData = await response.json();

        editForm.querySelector("select[name='document_type']").value =
            documentData.document_type;
        editForm.querySelector("input[name='document_name']").value =
            documentData.document_name;
        editForm.querySelector("input[name='notes']").value =
            documentData.notes || "";
        editForm.querySelector("input[name='vehicle_id']").value =
            documentData.vehicle_id;

        if (documentData.drive_file_url) {
            const currentDocContainer = editForm.querySelector(
                "#current-document-container"
            );
            currentDocContainer.innerHTML = `
                <p>Documento attuale: 
                    <a href="${documentData.drive_file_url}" target="_blank">Visualizza</a>
                </p>`;
        }
    } catch (error) {
        console.error(error);
    }

    // Mostro modal
    editModal.style.display = "flex";
    modalCloseButton.addEventListener("click", closeEditModal);
    window.addEventListener("click", (e) => {
        if (e.target === editModal) closeEditModal();
    });
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeEditModal();
    });

    hideLoader();
}

async function deleteDocument(documentId, vehicleId) {
    showLoader();
    try {
        const response = await fetch(
            "/vehicles/" + vehicleId + "/documents/" + documentId,
            {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'input[name="_token"]'
                    ).value,
                    Accept: "application/json",
                },
            }
        );
        if (!response.ok)
            throw new Error("Errore nella cancellazione del documento");
        showSuccess("Documento eliminato con successo.");
    } catch (error) {
        showError("Impossibile eliminare il documento. Riprova più tardi.");
    } finally {
        closeDeleteModal();
        hideLoader();
    }
}

async function openEditModal(vehicleId) {
    //Apro il modale e carico i dati del veicoli dentro gli input del modale
    const editModal = document.getElementById("edit-modal");
    const editForm = document.getElementById("edit-vehicle-form");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-vehicle");
    const editModalCloseButton = document.getElementById("close-edit-modal");
    modalHeaderH2.textContent = "Modifica Veicolo";
    submitButton.textContent = "Salva modifiche";

    // Carico i dati del veicolo nel form prendendoli dal backend
    showLoader();
    try {
        let url = "vehicles/getById/" + vehicleId;
        const response = await fetch(url);
        const data = await response.json();
        //Riempio il form con i dati ottenuti
        editForm.elements["license_plate"].value = data.license_plate;
        editForm.elements["brand"].value = data.brand;
        editForm.elements["model"].value = data.model;
        editForm.elements["year"].value = data.year;
        editForm.elements["color"].value = data.color;
        editForm.elements["fuel_type"].value = data.fuel_type;
        editForm.elements["transmission"].value = data.transmission;
        editForm.elements["seats"].value = data.seats;
        editForm.elements["vin"].value = data.vin;
        editForm.elements["engine_size"].value = data.engine_size;
        editForm.elements["mileage"].value = data.mileage;
        editForm.elements["status"].value = data.status;
        editForm.elements["notes"].value = data.notes;
    } catch {
        showError("Impossibile caricare i veicoli. Riprova più tardi.");
        renderTable([]);
        renderPaginationControls();
    } finally {
        hideLoader();
    }

    //caricamento dati
    editForm.action = "vehicles/update/" + vehicleId;
    editForm.method = "POST"; // Imposto il metodo a POST

    //Rimuovi eventuale input_method precedente
    const oldMethodInput= editForm.querySelector('input[name="_method"]');
    if(oldMethodInput) oldMethodInput.remove();

    //Aggiungi l'input per simulare PUT
    const methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "PUT";
    editForm.appendChild(methodInput);

    editModal.style.display = "flex";
    editModalCloseButton.addEventListener("click", closeEditModal);

    window.addEventListener("click", function (event) {
        if (event.target === editModal) {
            closeEditModal();
        }
    });
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeEditModal();
        }
    });
}

// Modal di eliminazione: apri
function openDeleteModal(vehicleId, brand, model, licensePlate, action) {
    const deleteModal = document.getElementById("delete-modal");
    const modalCloseButton = document.getElementById("close-delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const deleteCarName = document.getElementById("delete-car-name");
    vehicleIdToDelete = vehicleId;

    deleteForm.action = action;
    deleteCarName.textContent = brand + " " + model + " (" + licensePlate + ")";

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
//Modal di edit: chiudi
function closeEditModal() {
    const editModal = document.getElementById("edit-modal");
    editModal.style.display = "none";

    //Se era aperto il modal documenti prima di aprire questo, riaprilo
    if (
        reopenDocumentModalAfterDelete &&
        lastVehicleIdForDocs &&
        lastVehicleNameForDocs
    ) {
        openDocumentModal(lastVehicleIdForDocs, lastVehicleNameForDocs);
    }
}

// Modal di eliminazione: chiudi
function closeDeleteModal() {
    const deleteModal = document.getElementById("delete-modal");
    deleteModal.style.display = "none";
    vehicleIdToDelete = null;

    //Se era aperto il modal documenti prima di aprire questo, riaprilo
    if (
        reopenDocumentModalAfterDelete &&
        lastVehicleIdForDocs &&
        lastVehicleNameForDocs
    ) {
        openDocumentModal(lastVehicleIdForDocs, lastVehicleNameForDocs);
    }
}

function openAddDocumentModal(vehicleId = null, vehicleName = null) {
    const addModal = document.getElementById("edit-modal");
    const modalCloseButton = document.getElementById("close-edit-modal");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-vehicle");
    const editForm = document.getElementById("edit-vehicle-form");

    showLoader();
    closeDocumentModal();
    reopenDocumentModalAfterDelete = true; // segnalo che va riaperto dopo
    lastVehicleIdForDocs = vehicleId;
    lastVehicleNameForDocs = vehicleName;

    editForm.action = "vehicles/add-document";
    editForm.method = "POST"; // Imposto il metodo a POST
    editForm.reset();
    editForm.setAttribute("enctype", "multipart/form-data");

    //Rimuovi eventuale input_method
    const oldMethodInput = editForm.querySelector("input[name='_method']");
    if (oldMethodInput) oldMethodInput.remove();

    submitButton.textContent = "Aggiungi";
    modalHeaderH2.textContent = "Aggiungi Documento";

    //salvo il contenuto originale del form per il ripristino
    if (!originalFormContent || originalFormContent.trim() === "") {
        originalFormContent = editForm.innerHTML;
    }

    //Svuoto il form e lo riempio con i campi per l'aggiunta documento
    editForm.innerHTML = `
            <div class="input-container">
                <label for="edit_document_type">Tipo di Documento</label>
                <select name="document_type" id="edit_document_type" required>
                    <option value="registration">Libretto</option>
                    <option value="manual">Manuale</option>
                    <option value="insurance">Assicurazione</option>
                    <option value="other">Altro</option>
                </select>
                </div>
            <div class="input-container">
                <label for="edit_document_name">Id Documento</label>
                <input type="text" name="document_name" id="edit_document_name" required>
            </div>
            <div class="input-container">
                <label for="edit_notes">Note</label>
                <input type="text" name="notes" id="edit_notes">
            </div>
            <div class="input-container">
                <label for="edit_document">File Documento (PDF, JPG, PNG)</label>
                <input type="file" name="document" id="edit_document" required>
            </div>
            <input type="hidden" name="vehicle_id" value="${vehicleId}">`;

    // reinserisco il CSRF token
    // reinserisco il CSRF token se non è già presente
    if (!editForm.querySelector('input[name="_token"]')) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfToken;
        editForm.prepend(csrfInput);
    }

    addModal.style.display = "flex";

    modalCloseButton.addEventListener("click", closeEditModal);

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
    hideLoader();
}

// Elimina un veicolo
async function deleteVehicle(vehicleId) {
    showLoader();
    try {
        const response = await fetch("/vehicles/delete/" + vehicleId, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
                Accept: "application/json",
            },
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
    buttons.forEach((button) => button.classList.add("pagination-button"));
}

// Gestisce cambio pagina o filtro
function onPageChange() {
    const isChecked = document.getElementById("archivedCheckbox").checked;
    loadVehicles(
        isChecked ? "archived" : "active",
        currentPage,
        pageSize,
        searchQuery
    );
}

function openAddModal() {
    const addModal = document.getElementById("edit-modal");
    const editModalCloseButton = document.getElementById("close-edit-modal");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-vehicle");
    const editForm = document.getElementById("edit-vehicle-form");

    editForm.action = editForm.getAttribute("data-add-action");
    editForm.method = "POST"; // Imposto il metodo a POST
    editForm.reset();

    //Rimuovi eventuale input_method
    const oldMethodInput = editForm.querySelector("input[name='_method']");
    if (oldMethodInput) oldMethodInput.remove();

    submitButton.textContent = "Aggiungi";
    modalHeaderH2.textContent = "Aggiungi Veicolo";
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

// Gestione checkbox e avvio
document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("archivedCheckbox");
    const searchInput = document.getElementById("searchInput");
    const addButton = document.getElementById("add-vehicle-button");
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
