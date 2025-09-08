<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Il titolo della pagina sarà dinamico --}}
    <title>Rental Car Manager - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ url('css/main.css') }}">
    <link rel="preload" href="/fonts/Geist/Geist-Regular.woff2" as="font" type="font/woff2" crossorigin>
    {{-- Aggiungi qui eventuali stili extra specifici per una pagina --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ url('img/logo.jpg') }}" alt="Logo Acme Inc.">
            <span>Rental Car Manager</span>
        </div>

        <nav class="main-nav">
            <ul>
                {{-- Link per la dashboard --}}
                <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3H3V12H10V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M21 3H14V8H21V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M21 12H14V21H21V12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 16H3V21H10V16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="{{ Request::routeIs('reservations') ? 'active' : '' }}">
                    <a href="{{ route('reservations') }}">
                        <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 14H8.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 14H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 14H16.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 18H8.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 18H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 18H16.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        Prenotazioni
                    </a>
                </li>
                <li class="{{ Request::routeIs('vehicles.index') ? 'active' : '' }}">
                    <a href="{{ route('vehicles.index') }}">
                        <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 16H9M19 16H22V12.85C22.0007 12.612 21.9165 12.3816 21.7625 12.2001C21.6085 12.0187 21.3949 11.8981 21.16 11.86L16 11L13.3 7.39999C13.2069 7.27579 13.0861 7.17499 12.9472 7.10556C12.8084 7.03613 12.6552 6.99999 12.5 6.99999H5.24C4.86727 6.99739 4.50123 7.09901 4.18318 7.29338C3.86513 7.48774 3.60772 7.76712 3.44 8.09999L2.64 9.72999C2.22015 10.5646 2.00099 11.4857 2 12.42V16H4"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M6.5 19C7.88071 19 9 17.8807 9 16.5C9 15.1193 7.88071 14 6.5 14C5.11929 14 4 15.1193 4 16.5C4 17.8807 5.11929 19 6.5 19Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M16.5 19C17.8807 19 19 17.8807 19 16.5C19 15.1193 17.8807 14 16.5 14C15.1193 14 14 15.1193 14 16.5C14 17.8807 15.1193 19 16.5 19Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Veicoli
                    </a>
                </li>
                <li class="{{ Request::routeIs('customers') ? 'active' : '' }}">
                    <a href="{{ route('customers') }}">
                        {{-- Puoi aggiungere un'icona SVG qui per i clienti --}}
                        <svg class="nav-icon"width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M22 20.9999V18.9999C21.9993 18.1136 21.7044 17.2527 21.1614 16.5522C20.6184 15.8517 19.8581 15.3515 19 15.1299"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M16 3.12988C16.8604 3.35018 17.623 3.85058 18.1676 4.55219C18.7122 5.2538 19.0078 6.11671 19.0078 7.00488C19.0078 7.89305 18.7122 8.75596 18.1676 9.45757C17.623 10.1592 16.8604 10.6596 16 10.8799"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        Clienti
                    </a>
                </li>
                <li class="{{ Request::routeIs('invitations') ? 'active' : '' }}">
                    <a href="{{ route('invitations') }}">
                        <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22 13V6C22 5.46957 21.7893 4.96086 21.4142 4.58579C21.0391 4.21071 20.5304 4 20 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V18C2 19.1 2.9 20 4 20H12"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M22 7L13.03 12.7C12.7213 12.8934 12.3643 12.996 12 12.996C11.6357 12.996 11.2787 12.8934 10.97 12.7L2 7"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M19 16V22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 19H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Inviti
                    </a>
                </li>
            </ul>
        </nav>
        {{-- ... Altri elementi della sidebar ... --}}

        <div class="user-profile">
            <svg class="user-avatar-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M19 21V19C19 17.9391 18.5786 16.9217 17.8284 16.1716C17.0783 15.4214 16.0609 15 15 15H9C7.93913 15 6.92172 15.4214 6.17157 16.1716C5.42143 16.9217 5 17.9391 5 19V21"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="user-info">
                {{-- Mostra il nome completo dell'utente autenticato --}}
                <div class="username">{{ auth()->user()->full_name }}</div>
                {{-- Mostra l'email dell'utente autenticato --}}
                <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <svg class="nav-icon"xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="1"></circle>
                <circle cx="12" cy="5" r="1"></circle>
                <circle cx="12" cy="19" r="1"></circle>
            </svg>
        </div>
    </aside>

    <main class="main-content">
        <div class="notification-popup"
            style="display: flex; right: 20px; z-index: 1000; background-color: #4BB543; color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
            <span id="notification-message">Cliente eliminato con successo</span>
        </div>
        <div class="main-card">
            <header class="top-header">
                <div class="header-left">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 3V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="span-divider"></span>
                    <h1 id="page-title">@yield('title')</h1>
                </div>
                <div class="header-right">
                    <div class="header-widget weather-widget">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M4.93005 4.92999L6.34005 6.33999" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M19.07 4.92999L17.66 6.33999" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M15.947 12.65C16.07 11.9045 15.9794 11.1394 15.6857 10.4432C15.3919 9.74711 14.9069 9.14841 14.287 8.71648C13.667 8.28455 12.9374 8.03705 12.1826 8.00263C11.4278 7.96821 10.6787 8.14828 10.022 8.522"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13 22H6.99996C6.05393 21.9998 5.12737 21.7312 4.32788 21.2255C3.5284 20.7197 2.8888 19.9975 2.48339 19.1427C2.07797 18.288 1.92336 17.3358 2.03752 16.3966C2.15168 15.4575 2.52992 14.5701 3.12832 13.8373C3.72672 13.1046 4.52071 12.5567 5.41808 12.2572C6.31545 11.9577 7.27938 11.9189 8.1979 12.1454C9.11642 12.3718 9.95185 12.8542 10.6072 13.5366C11.2625 14.2189 11.7108 15.0731 11.9 16H13C13.7956 16 14.5587 16.3161 15.1213 16.8787C15.6839 17.4413 16 18.2044 16 19C16 19.7956 15.6839 20.5587 15.1213 21.1213C14.5587 21.6839 13.7956 22 13 22Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="header-widget-title">24° Gradi </br> Nuvoloso</p>
                    </div>
                    <div class="header-widget google-drive-widget">
                        @if (auth()->user()->google_drive_token)
                            <button class="btn-secondary" disabled>Drive Collegato
                                {{ auth()->user()->google_drive_name }}</button>
                        @else
                            <button class="btn-secondary" id="google-drive-auth">Collega Google Drive</button>
                        @endif
                    </div>
                    <div class="header-widget theme-toggle">
                        <button class="btn-secondary"><svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 2V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 20V22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M4.93005 4.92993L6.34005 6.33993" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17.66 17.6599L19.07 19.0699" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M20 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6.34005 17.6599L4.93005 19.0699" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19.07 4.92993L17.66 6.33993" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg></button>
                    </div>
                    <button class="btn-secondary"><svg width="24" height="24" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Qui verrà iniettato il contenuto specifico di ogni pagina --}}
            @yield('content')
        </div>
    </main>
    <script>
        document.getElementById('google-drive-auth')?.addEventListener('click', function() {
            window.open(
                "{{ route('google.drive.auth.popup') }}",
                "GoogleAuth",
                "width=500,height=600"
            );
        });

        window.addEventListener("message", function(event) {
            if (event.data.googleDriveToken) {
                fetch("{{ route('google.drive.save') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        token: event.data.googleDriveToken
                    })
                }).then(() => location.reload());
            }
        });
    </script>

    {{-- Script extra specifici per una pagina --}}
    @stack('scripts')
</body>

</html>
