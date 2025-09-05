let currentPage = 1;
const pageSize = 10;
let totalCustomers = 0;
let customerIdToDelete = null;
let searchQuery = "";
let reopenDocumentModalAfterDelete = false;
let lastCustomerIdForDocs = null;
let lastCustomerNameForDocs = null;

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
    document.getElementById("customersTableSection").style.display = "none";
    document.getElementById("loader-overlay").style.display = "flex";
}

// Nasconde il loader
function hideLoader() {
    document.getElementById("customersTableSection").style.display = "block";
    document.getElementById("loader-overlay").style.display = "none";
}

// Carica i clienti dalla pagina corrente e stato selezionato
async function loadCustomers( page = 1, pageSize = 10, search = "",filterValue="all") {
    showLoader();
    try {
        let url =
            "/customers/get/" +
            filterValue +
            "?page=" +
            page +
            "&pageSize=" +
            pageSize;
        if (search && search.trim() !== "") {
            url += "&search=" + encodeURIComponent(search.trim());
        }
        const response = await fetch(url);
        const data = await response.json();
        totalCustomers = data.total;
        renderTable(data.customers);
        renderPaginationControls();

    } catch {
        showError("Impossibile caricare i clienti. Riprova più tardi.");
        renderTable([]);
        renderPaginationControls();
    } finally {
        hideLoader();
    }
}

// Genera l'HTML per una riga della tabella
function generateRowHtml(customer) {
    return (
        '<tr data-customer-id="' +
        customer.id +
        '">' +
        "<td>" +
        (customer.first_name || "-") +
        "</td>" +
        "<td>" +
        (customer.last_name || "-") +
        "</td>" +
        "<td>" +
        (customer.tax_code || "-") +
        "</td>" +
        "<td>" +
        customer.email +
        "</td>" +
        "<td>" +
        customer.phone +
        "</td>" +
        "<td>" +
        customer.address +
        "</td>" +
        "<td>" +
        (customer.company_name || "-") +
        "</td>" +
        "<td>" +
        customer.country +
        "</td>" +
        "<td>" +
        customer.city +
        "</td>" +
        "<td>" +
        customer.postal_code +
        "</td>" +
        "<td>" +
        (customer.vat_number || "-")+
        "</td>" +
        "<td>" +
        (customer.notes || "-") +
        "</td>" +
        '<td class="col-actions">' +
        '<div class="action-buttons">' +
        '<button class="btn-info view-documents" data-customer-id="' +
        customer.id +
        '" data-customer-name="' +
        (customer.first_name || customer.company_name) +
        " " +
        (customer.last_name || "") +
        '">Visualizza Documenti</button>' +
        "</div>" +
        "</td>" +
        '<td class="col-actions">' +
        '<div class="action-buttons">' +
        '<button class="btn-secondary edit-customer" data-customer-id="' +
        customer.id +
        '">Modifica</button>' +
        '<button class="btn-danger delete-customer" data-customer-id="' +
        customer.id +
        '" data-first-name="' +
        (customer.first_name || customer.company_name) +
        '" data-last-name="' +
        (customer.last_name || "")+
        '" data-tax-code="' +
        (customer.tax_code || customer.vat_number)+
        '" data-action="delete">Elimina</button>' +
        "</div>" +
        "</td>" +
        "</tr>"
    );
}

//Attacca gli event listeners ai bottoni di eliminazione
function attachEventListeners() {
    document.querySelectorAll(".delete-customer").forEach((button) => {
        button.addEventListener("click", function () {
            const customerId = button.getAttribute("data-customer-id");
            const firstName = button.getAttribute("data-first-name");
            const lastName = button.getAttribute("data-last-name");
            const taxCode = button.getAttribute("data-tax-code");
            const action = button.getAttribute("data-action");
            openDeleteModal(customerId, firstName, lastName, taxCode, action);
        });
    });
    document.querySelectorAll(".view-documents").forEach((button) => {
        button.addEventListener("click", function () {
            const customerId = button.getAttribute("data-customer-id");
            const customerName = button.getAttribute("data-customer-name");
            openDocumentModal(customerId, customerName);
        });
    });
}

// Renderizza la tabella con i clienti ricevuti
function renderTable(customers) {
    const tableBody = document.getElementById("customersTableBody");
    tableBody.innerHTML = ""; // Pulisce il corpo della tabella

    if (!customers || customers.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="14">Nessun cliente trovato.</td></tr>';
        return;
    }
    tableBody.innerHTML = customers.map(generateRowHtml).join("");
    attachEventListeners();
}

// Pagina e paginazione
function renderPaginationControls() {
    const paginationDiv = document.getElementById("pagination-controls");
    paginationDiv.innerHTML = "";

    const totalPages = Math.ceil(totalCustomers / pageSize);
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
    const filterValue = document.getElementById("typeFilter").value;
    loadCustomers(
        currentPage,
        pageSize,
        searchQuery,
        filterValue
    );
}

function openAddModal() {
    const addModal = document.getElementById("edit-modal");
    const editModalCloseButton = document.getElementById("close-edit-modal");
    const modalHeaderH2 = document.getElementById("modal-header-h2");
    const submitButton = document.getElementById("submit-edit-customer");
    const editForm = document.getElementById("edit-customer-form");

    editForm.action = editForm.getAttribute("data-add-action");
    editForm.method = "POST"; // Imposto il metodo a POST
    editForm.reset();

     //Rimuovi eventuale input_method
    const oldMethodInput = editForm.querySelector("input[name='_method']");
    if (oldMethodInput) oldMethodInput.remove();

    submitButton.textContent = "Aggiungi";
    modalHeaderH2.textContent = "Aggiungi Cliente";
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

function openDeleteModal(customerId, firstName, lastName, taxCode,action) {
    const deleteModal = document.getElementById("delete-modal");
    const modalCloseButton = document.getElementById("close-delete-modal");
    const deleteForm = document.getElementById("delete-form");
    const deleteCustomerName = document.getElementById("delete-customer-name");
    customerIdToDelete = customerId;

    deleteForm.action=action;
    deleteCustomerName.textContent = firstName + " " + lastName + " (" + taxCode + ")";

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

async function openDocumentModal(customerId, customerName) {
    const documentModal = document.getElementById("document-modal");
    const modalCloseButton = document.getElementById("close-document-modal");
    const documentsList = document.getElementById("documents-list");
    const customerNameSpan = document.getElementById("customer-name");

    showLoader();
    //Salvo il cliente
    lastCustomerIdForDocs = customerId;
    lastCustomerNameForDocs = customerName;
    reopenDocumentModalAfterDelete = false;

    // Imposta il nome del cliente nel modal
    customerNameSpan.textContent = customerName;
    

    // Carica i documenti del cliente via AJAX
    const response = await fetch("/customers/" + customerId + "/documents");
    documentsList.innerHTML = ""; // Pulisce la lista dei documenti
    if (!response.ok) throw new Error("Errore nel caricamento dei documenti");

    //Creo la lista con le informazioni dei documenti e due pulsanti uno per la modifica del doc e uno per la cancellazione
    const documents = await response.json();
    if (!documents || documents.length === 0) {
        documentsList.innerHTML = "<li>Nessun documento trovato.</li>";
    } else {
        documents.forEach(doc => {
            const listItem = document.createElement("li");
            listItem.textContent = doc.document_type + " - Numero documento: " + doc.id_document_number + " - Caricato il: " + new Date(doc.created_at).toLocaleDateString() + " - Scadenza: " + (doc.expiry_date ? new Date(doc.expiry_date).toLocaleDateString() : "N/A") + " ";
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
                openEditDocumentModal(doc.id);
            });
            listItem.appendChild(editButton);

            // Pulsante per la cancellazione del documento
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn-danger");
            deleteButton.addEventListener("click", function () {
                openDeleteDocumentModal(doc.id, doc.document_type, doc.id_document_number, customerId);
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

function openDeleteDocumentModal(documentId, documentType, documentNumber, customerId) {
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
    warningParagraph.textContent = "Attenzione: Eliminando questo documento, non sarà più possibile recuperarlo.";

    //Imposto l'eliminazione del doc al click del submit
    const form = document.getElementById("delete-form");
    form.onsubmit = async function (e) {
        e.preventDefault();
        await deleteDocument(documentId, customerId);
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

async function deleteDocument(documentId, customerId) {
    showLoader();
    try {
        const response = await fetch("/customers/" + customerId + "/documents/" + documentId, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                Accept: "application/json",
            }
        });
        if (!response.ok) throw new Error("Errore nella cancellazione del documento");
        showSuccess("Documento eliminato con successo.");
    } catch (error) {
        showError("Impossibile eliminare il documento. Riprova più tardi.");
    } finally {
        closeDeleteModal();
        hideLoader();
    }
}

async function deleteCustomer(customerId) {
    showLoader();
    try {
        const response = await fetch("/customers/delete/"+customerId, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
                Accept: "application/json",
            }
        });
        if (!response.ok) throw new Error("Errore nella cancellazione del cliente");
        showSuccess("Cliente eliminato con successo.");
    } catch (error) {
        showError("Impossibile eliminare il cliente. Riprova più tardi.");
    } finally {
        //Dopo l'eliminazione, ricarica la pagina corrente con il filtro attivo
        const filterValue = document.getElementById("typeFilter").value;
        loadCustomers(currentPage, pageSize, searchQuery, filterValue);
        closeDeleteModal();
        hideLoader();
    }
}

function closeDocumentModal() {
    const documentModal = document.getElementById("document-modal");
    documentModal.style.display = "none";
}

function closeDeleteModal() {
    const deleteModal = document.getElementById("delete-modal");
    deleteModal.style.display = "none";

    //Se era aperto il modal documenti prima di aprire questo, riaprilo
    if (reopenDocumentModalAfterDelete && lastCustomerIdForDocs && lastCustomerNameForDocs) {
        openDocumentModal(lastCustomerIdForDocs, lastCustomerNameForDocs);
    }
}

function closeEditModal() {
    const editModal = document.getElementById("edit-modal");
    editModal.style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const addButton = document.getElementById("add-customer-button");
    const filterSelect = document.getElementById("typeFilter");
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

    //Gestione filtro
    filterSelect.addEventListener("change", function () {
        currentPage = 1; // Reset della pagina corrente al cambio filtro
        onPageChange();
    });

    //Event listner addButton
    addButton.addEventListener("click", openAddModal);

    // Modal delete submit
    document.getElementById("delete-form").onsubmit = async function (e) {
        e.preventDefault();
        if (!customerIdToDelete) return;
        await deleteCustomer(customerIdToDelete);
    };
    // Refresh button
    document.getElementById("refresh-btn").onclick = function () {
        currentPage = 1;
        onPageChange();
    };
    onPageChange();
});
