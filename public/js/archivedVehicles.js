document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('archivedCheckbox');
    const tableBody = document.getElementById('vehiclesTableBody');

    let archivedLoaded = false;
    let archivedVehicles = [];

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            if (!archivedLoaded) {
                fetch('/vehicles/archived')
                    .then((response) => response.json())
                    .then((vehicles) => {
                        archivedLoaded = true;
                        archivedVehicles = vehicles;
                        renderAllRows();
                    });
            } else {
                renderAllRows();
            }
        } else {
            renderAllRows();
        }
    });

    function getCurrentRowsData() {
        // Prendi i dati dai tr già presenti (non archiviati)
        const rows = Array.from(tableBody.querySelectorAll('tr:not(.archived-row)'));
        return rows.map((row) => {
            return {
                id: parseInt(row.querySelector('.col-id').textContent),
                html: row.outerHTML,
                archived: false,
            };
        });
    }

    function getArchivedRowsData() {
        // Converte i dati archiviati in HTML
        return archivedVehicles.map((vehicle) => ({
            id: vehicle.id,
            html: generateRowHtml(vehicle),
            archived: true,
        }));
    }

    function renderAllRows() {
        // Unisci (o meno) archiviati e non, ordina e riscrivi la tabella
        let data = getCurrentRowsData();
        if (checkbox.checked && archivedLoaded) {
            data = data.concat(getArchivedRowsData());
        }
        data.sort((a, b) => a.id - b.id); // Ordina per ID

        tableBody.innerHTML = data.map((row) => row.html).join('');
    }

    function generateRowHtml(vehicle) {
        // Genera il markup per un veicolo archiviato
        return `<tr class="archived-row">
            <td class="col-id">${vehicle.id}</td>
            <td>${vehicle.license_plate}</td>
            <td>${vehicle.brand}</td>
            <td>${vehicle.model}</td>
            <td>${vehicle.year}</td>
            <td>${vehicle.color}</td>
            <td>${vehicle.fuel_type}</td>
            <td>${vehicle.transmission}</td>
            <td>${vehicle.seats}</td>
            <td>${vehicle.vin}</td>
            <td>${vehicle.engine_size}</td>
            <td>${vehicle.mileage}</td>
            <td>
                <span class="status-badge status-${vehicle.status.toLowerCase().replace(/ /g, '-')}">
                    ${vehicle.status}
                </span>
            </td>
            <td>${vehicle.notes ?? 'Nessuna nota'}</td>
            <td class="col-actions">
                <div class="action-buttons">
                    <button class="btn-secondary">Modifica</button>
                    <button class="btn-danger delete-vehicle-btn"
                        data-vehicle-id="${vehicle.id}"
                        data-brand="${vehicle.brand}"
                        data-model="${vehicle.model}"
                        data-license_plate="${vehicle.license_plate}"
                        data-action="/vehicles/${vehicle.id}">Elimina</button>
                </div>
            </td>
        </tr>`;
    }
});