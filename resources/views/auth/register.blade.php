<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="{{ url('css/register.css') }}">
</head>

<body>
    <main>
        <div id="registration">
            <div class="container">
                <div id="title-container" class="input-container">
                    <h1>Registrati</h1>
                    <p>Compila il modulo per creare un nuovo account</p>
                </div>
                <form name="registration" method="POST">
                    @csrf
                    <div id="first-name-container" class="input-container">
                        <div class="label" id="first-name-label"><label for="first-name">Nome</label></div>
                        <div><input type="text" id="first-name" name="first-name" placeholder="Mario"
                                value="{{ old('first-name') }}"></div>
                        @error('first-name')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="surname-container" class="input-container">
                        <div class="label" id="last-name-label"><label for="last-name">Cognome</label></div>
                        <div><input type="text" id="last-name" name="last-name" placeholder="Rossi"
                                value="{{ old('last-name') }}"></div>
                        @error('last-name')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="phone-container" class="input-container">
                        <div class="label" id="phone-label"><label for="phone">Telefono</label></div>
                        <div><input type="tel" id="phone" name="phone" placeholder="1234567890"
                                value="{{ old('phone') }}"></div>
                        @error('phone')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="email-container" class="input-container">
                        <div class="label" id="email-label"><label for="email">Email</label></div>
                        <div>
                            <input type="email" id="email" name="email" placeholder="m@example.com"
                                value="{{ $invitation->email }}" readonly>
                        </div>
                        @error('email')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="password-container" class="input-container">
                        <div class="label" id="psw-label"><label for="password">Password</label></div>
                        <div><input type="password" id="password" name="password"></div>
                        <div><img id="eye" src="{{ url('img/icon-eye.svg') }}" alt="Mostra password"
                            class="@error('password')fixed-eye @enderror">
                        </div>
                        @error('password')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="confirm-password-container" class="input-container">
                        <div class="label" id="confirm-psw-label"><label for="password_confirmation">Conferma Password</label></div>
                        <div><input type="password" id="password_confirmation" name="password_confirmation"></div>
                        @error('password_confirmation')
                            <span class="input-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="register-button-container" class="input-container">
                        <div><input type="submit" id="register-button" name="register" value="Registrati"></div>
                    </div>
                </form>
            </div>

    </main>
    <footer>
        <div id="footer">
            <p>&copy; 2025 Manager Car rental. Tutti i diritti riservati.</p>
        </div>
    </footer>
    <script src="{{ url('js/register.js') }}" defer></script>
</body>

</html>
