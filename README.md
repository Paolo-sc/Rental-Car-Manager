# Rental Car Manager Laravel

Gestisci in modo semplice ed efficiente un sistema di noleggio auto!  
Questa applicazione Laravel offre tutte le funzionalità fondamentali per gestire veicoli, clienti, prenotazioni e amministrazione.

## Caratteristiche

- **Gestione Veicoli:** Aggiungi, modifica e rimuovi auto dal parco veicoli.
- **Gestione Clienti:** Anagrafica clienti con dettagli e storico prenotazioni.
- **Prenotazioni:** Crea e gestisci prenotazioni, controlla la disponibilità delle auto.
- **Dashboard Amministrativa:** Statistiche, report e strumenti di amministrazione.
- **Autenticazione:** Accesso sicuro tramite autenticazione Laravel.

## Tecnologie utilizzate

- **Backend:** [Laravel](https://laravel.com/)
- **Database:** MySQL/MariaDB (configurabile)
- **Frontend:** Blade (personalizzabile)
- **Altre dipendenze:** Composer, npm

## Installazione

1. **Clona la repository:**
   ```bash
   git clone https://github.com/Paolo-sc/Rental-Car-Manager-Laravel.git
   cd Rental-Car-Manager-Laravel
   ```

2. **Installa le dipendenze PHP:**
   ```bash
   composer install
   ```

3. **Installa le dipendenze JavaScript:**
   ```bash
   npm install
   npm run build
   ```

4. **Configura il file `.env`:**
   - Copia `.env.example` in `.env`
   - Inserisci le credenziali del database e altri parametri

5. **Genera la chiave dell'app:**
   ```bash
   php artisan key:generate
   ```

6. **Esegui le migrazioni:**
   ```bash
   php artisan migrate
   ```

7. **Avvia il server di sviluppo:**
   ```bash
   php artisan serve
   ```

## Utilizzo

- Accedi tramite la pagina di login.
- Configura i veicoli, crea clienti e gestisci le prenotazioni.
- Usa la dashboard amministrativa per statistiche e report.

## Contribuire

Sono benvenuti contributi!  
Per proporre modifiche o nuove funzionalità, apri una Issue o un Pull Request.

1. Forka il progetto
2. Crea il tuo branch feature (`git checkout -b feature/NuovaFunzionalita`)
3. Commit delle modifiche (`git commit -am 'Aggiunta nuova funzionalità'`)
4. Push sul tuo branch (`git push origin feature/NuovaFunzionalita`)
5. Apri una Pull Request

## License

Questo progetto è distribuito sotto licenza MIT.  
Consulta il file [LICENSE](LICENSE) per maggiori dettagli.

## Autore

Paolo-sc  
[GitHub](https://github.com/Paolo-sc)
