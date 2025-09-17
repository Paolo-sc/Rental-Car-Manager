document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
});

async function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn = document.getElementById('toggle-sidebar-btn');

    if (!sidebar || !mainContent) return;

    // Controlla se siamo in modalità tablet o mobile
    const isTablet = window.matchMedia('(max-width: 1024px)').matches;

    if (isTablet) {
        // Collassa automaticamente senza mostrare il toggle
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
        if (toggleBtn) toggleBtn.style.display = 'none';
        return; // Non serve caricare stato dal server
    }

    // Mostra il toggle per desktop
    if (toggleBtn) toggleBtn.style.display = '';

    // Recupera stato dalla localStorage o server
    const collapsed = await getSidebarState();
    if (collapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
    }

    // Evento toggle
    if (toggleBtn) {
        toggleBtn.addEventListener('click', async () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');

            // Aggiorna localStorage
            setLocalStorageState(isCollapsed);

            // Aggiorna server
            await updateSidebarState(isCollapsed);
        });
    }
}

// Recupera lo stato della sidebar (localStorage -> server)
async function getSidebarState() {
    const local = localStorage.getItem('sidebar_collapsed');
    if (local !== null) return local === '1';

    try {
        const response = await fetch('/sidebar-state');
        const data = await response.json();
        localStorage.setItem('sidebar_collapsed', data.collapsed ? '1' : '0');
        return data.collapsed;
    } catch (err) {
        console.error('Errore nel recupero stato sidebar:', err);
        return false;
    }
}

// Aggiorna localStorage
function setLocalStorageState(collapsed) {
    localStorage.setItem('sidebar_collapsed', collapsed ? '1' : '0');
}

// Aggiorna lo stato della sidebar sul server
async function updateSidebarState(collapsed) {
    try {
        await fetch('/sidebar-state', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ collapsed })
        });
    } catch (err) {
        console.error('Errore nell\'aggiornamento server sidebar:', err);
    }
}
