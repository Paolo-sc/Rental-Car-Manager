let currentPage = 1;
const pageSize = 10;
let totalCustomers = 0;
let customerIdToDelete = null;
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
        "<td data-document="+(customer.document_number || "Nessun Documento") +">" +
        (customer.document_number || "Nessun Documento") +
        "</td>" +
        "<td>" +
        (customer.notes || "Nessuna Nota") +
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

function closeDeleteModal() {
    const deleteModal = document.getElementById("delete-modal");
    deleteModal.style.display = "none";
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
