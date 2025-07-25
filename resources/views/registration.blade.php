<html lang="it">
    <head>
        <title>Registrazione</title>
        <link rel="stylesheet" href="{{url('css/login.css')}}">
    </head>
    <body>
        <main>
            <div id="registration">
                <div class="container">
                    <div id="title-container" class="input-container">
                        <h1>Registrati</h1>
                        <p>Compila il modulo per creare un nuovo account</p>
                    </div>
                    @if ($errors->any())
                        <div id="registration-error-message" class="error-message-container" role="alert" aria-live="assertive">
                            <p class="error-text">{{ $errors->first() }}</p>
                        </div>
                    @endif
                    <form name="registration" method="POST">
                        @csrf
                        <div id="name-container" class="input-container">
                            <div id="name-label"><label for="name">Nome</label></div>
                            <div><input type="text" id="name" name="name" placeholder="Mario Rossi" value="{{ old('name') }}"></div>
                            @error('name')
                                <span class="input-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div id="email-container" class="input-container">
                            <div id="email-label"><label for="email">Email</label></div>
                            <div><input type="email" id="email" name="email" placeholder="m@example.com" value="{{ old('email') }}"></div>
                            @error('email')
                                <span class="input-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div id="password-container" class="input-container">
                            <div id="psw-label"><label for="password">Password</label></div>
                            <div><input type="password" id="password" name="password"></div>
                            <div><img id="eye" src="{{ url('img/icon-eye.svg') }}" alt="Mostra password" onclick="togglePasswordVisibility()" class="@error('password')fixed-eye @enderror"></div>
                            @error('password')
                                <span class="input-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div id="confirm-password-container" class="input-container">
                            <div id="confirm-psw-label"><label for="password_confirmation">Conferma Password</label></div>
                            <div><input type="password" id="password_confirmation" name="password_confirmation"></div>
                            @error('password_confirmation')
                                <span class="input-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div id="registration-button-container" class="input-container">
                            <div><input type="submit" id="registration-button" name="register" value="Registrati"></div>
                        </div>
                    </form>
                </div>

        </main>
        <footer>
            <div id="footer">
                <p>&copy; 2025 Manager Car rental. Tutti i diritti riservati.</p>
            </div>
        </footer>
        <script src="{{url('js/login.js')}}" defer></script>
    </body>
</html>
