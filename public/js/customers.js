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
async function loadCustomers( page = 1, pageSize = 10, search = "") {
    showLoader();
    try {
        let url =
            "/customers/get/" +
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
        customer.tax_code +
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
        "<td data-document="+(customer.id_document_number || "Nessun Documento") +">" +
        (customer.id_document_number || "Nessun Documento") +
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
        customer.first_name +
        '" data-last-name="' +
        customer.last_name +
        '" data-tax-code="' +
        customer.tax_code +
        '" data-action="delete">Elimina</button>' +
        "</div>" +
        "</td>" +
        "</tr>"
    );
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
    //attachEventListeners();
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
    loadCustomers(
        currentPage,
        pageSize,
        searchQuery
    );
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const addButton = document.getElementById("add-customer-button");
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
    onPageChange();
});
