document.addEventListener('DOMContentLoaded', () => {
    const mainContentArea = document.getElementById('main-content-area');
    const pageTitle = document.getElementById('page-title');
    const navLinks = document.querySelectorAll('.sidebar nav ul li a');

    // Mappa per associare i nomi delle pagine a titoli e breadcrumbs
    const pageInfo = {
        'dashboard': { title: 'Dashboard'},
        'documents': { title: 'Documenti'},
        'data-library': { title: 'Data Library'},
        'reports': { title: 'Reports'}
    };

    // Funzione asincrona per caricare il contenuto delle pagine
    async function loadPage(pageName) {
        const info = pageInfo[pageName] || { title: 'Pagina non trovata'};
        
        pageTitle.textContent = info.title;

        try {
            // Tenta di caricare il file HTML dalla cartella 'pages'
            const response = await fetch(`pages/${pageName}.html`);
            if (!response.ok) { // Controlla se la risposta HTTP è un successo (status 200-299)
                throw new Error(`Impossibile caricare la pagina: ${response.statusText}`);
            }
            const htmlContent = await response.text(); // Ottieni il testo del file HTML
            mainContentArea.innerHTML = htmlContent; // Inserisci il contenuto nell'area principale

            // Qui aggiungere logica post-caricamento specifica per ogni pagina,
            // ad esempio per inizializzare grafici o altri elementi interattivi.
            if (pageName === 'dashboard') {
                // Esempio: potresti chiamare una funzione per inizializzare un grafico
                // initializeDashboardCharts();
            }

        } catch (error) {
            console.error('Errore nel caricamento della pagina:', error);
            mainContentArea.innerHTML = `
                <section class="simple-page-content">
                    <h2>${info.title}</h2>
                    <p>Si è verificato un errore durante il caricamento di questa sezione: ${error.message}.</p>
                    <p>Assicurati che il file <code>pages/${pageName}.html</code> esista.</p>
                </section>
            `;
        }
    }

    // Gestione dei click sui link della sidebar
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const page = link.getAttribute('data-page');

            navLinks.forEach(navLink => navLink.closest('li').classList.remove('active'));
            link.closest('li').classList.add('active');

            window.location.hash = page; // Aggiorna l'URL hash
            loadPage(page); // Carica la pagina
        });
    });

    // Gestione del caricamento iniziale e della navigazione tramite URL hash
    function handleHashChange() {
        const hash = window.location.hash.substring(1);
        const initialPage = hash || 'dashboard'; // Default a 'dashboard'

        navLinks.forEach(navLink => {
            if (navLink.getAttribute('data-page') === initialPage) {
                navLink.closest('li').classList.add('active');
            } else {
                navLink.closest('li').classList.remove('active');
            }
        });

        loadPage(initialPage);
    }

    window.addEventListener('hashchange', handleHashChange); // Ascolta i cambiamenti nell'hash
    handleHashChange(); // Carica la pagina iniziale
});