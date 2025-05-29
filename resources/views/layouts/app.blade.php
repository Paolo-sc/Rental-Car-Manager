<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Il titolo della pagina sarà dinamico --}}
    <title>Rental Car Manager - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    {{-- Aggiungi qui eventuali stili extra specifici per una pagina --}}
    @stack('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ url('img/logo.png') }}" alt="Logo Acme Inc.">
            <span>Rental Car Manager</span>
        </div>
        <nav class="main-nav">
            <div class="nav-button">
                    <button class="quick-create-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Add Customer
                    </button>
            </div>
            <ul>
                {{-- Link per la dashboard --}}
                <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
            </ul>
        </nav>
        {{-- ... Altri elementi della sidebar ... --}}

        <div class="user-profile">
            <svg class="user-avatar-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21V19C19 17.9391 18.5786 16.9217 17.8284 16.1716C17.0783 15.4214 16.0609 15 15 15H9C7.93913 15 6.92172 15.4214 6.17157 16.1716C5.42143 16.9217 5 17.9391 5 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            <div class="user-info">
                {{-- Mostra il nome completo dell'utente autenticato --}}
                <div class="username">{{ auth()->user()->full_name}}</div>
                {{-- Mostra l'email dell'utente autenticato --}}
                <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <svg class="nav-icon"xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
            </svg>
        </div>
    </aside>

    <main class="main-content">
        <div class="main-card">
            <header class="top-header">
                <div class="header-left">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 3V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="span-divider"></span>
                    <h1 id="page-title">@yield('title')</h1>
                </div>
            </header>

            {{-- Qui verrà iniettato il contenuto specifico di ogni pagina --}}
            <div id="main-content-area">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Script extra specifici per una pagina --}}
    @stack('scripts')
</body>
</html>