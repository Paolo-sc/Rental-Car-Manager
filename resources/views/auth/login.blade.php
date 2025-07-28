<html lang="it">
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="{{url('css/login.css')}}">
    </head>
    <body>
        <main>
            <div id="login">
                <div class="container">
                    <div id="title-container">
                        <h1>Bentornato</h1>
                        <p>Inserisci le tue credenziali per accedere</p>
                    </div>
                    @if ($errors->has('email') && $errors->first('email') === __('auth.failed'))
                        <div id="login-error-message" class="error-message-container" role="alert" aria-live="assertive">
                            <p class="error-text">Indirizzo email o password non validi.</p>
                        </div>
                    @endif
                    <form name="login" method="POST">
                        @csrf
                        <div id="email-container" class="input-container">
                            <div id="email-label"><label for="email">Email</label></div>
                            <div><input type="email" id="email" name="email" placeholder="m@example.com" value="{{ old("email") }}"></div>
                            @error('email')
                            {{-- Solo se l'errore NON è quello generico delle credenziali non valide --}}
                                @if ($message !== __('auth.failed'))
                                    <span class="input-error-message">{{ $message }}</span>
                                @endif
                        @enderror
                        </div>
                        <div id="password-container" class="input-container">
                            <div id="psw-label">
                                <label for="password">Password</label>
                                <a href="">Hai dimenticato la password?</a>
                            </div>
                                <div><input type="password" id="password" name="password"></div>
                                <div><img id="eye" src="{{ url('img/icon-eye.svg') }}" alt="Mostra password" onclick="togglePasswordVisibility()" class="
                                    @error('password')fixed-eye @enderror"></div>
                                @error('password')
                                    {{-- Blocco per l'errore specifico del campo Password --}}
                                    <span class="input-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        <div id="remember-container" class="input-container">
                            <div><input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}></div>
                            <div><label for="remember">Ricordami</label></div>
                        </div>
                        <div id="login-button-container" class="input-container">
                            <div><input type="submit" id="login-button" name="login" value="Login"></div></div>
                    </form>
                </div>
                <div id="image">
                        <img src="{{ url('img/login.png') }}" alt="Login Image">
                </div>
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