<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invito Car Rental Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .btn {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚗 {{ $companyName }}</div>
            <div class="subtitle">Sistema di Gestione Noleggio Auto</div>
        </div>

        <div class="content">
            <h2>Sei stato invitato!</h2>
            
            <p>Ciao,</p>
            
            <p>Hai ricevuto un invito per registrarti nel nostro sistema di gestione noleggio auto. Clicca sul pulsante sottostante per completare la tua registrazione:</p>
            
            <div style="text-align: center;">
                <a href="{{ $invitationUrl }}" class="btn">
                    ✅ Completa la Registrazione
                </a>
            </div>
            
            <div class="info-box">
                <h3>📋 Dettagli dell'invito:</h3>
                <ul>
                    <li><strong>Email invitata:</strong> {{ $invitation->email }}</li>
                    <li><strong>Scadenza invito:</strong> {{ $expiresAt }}</li>
                    <li><strong>Invitato da:</strong> {{ $invitation->creator->fullName ?? 'Amministratore' }}</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>⚠️ Importante:</strong>
                <ul>
                    <li>Questo invito è valido solo per 7 giorni</li>
                    <li>Può essere utilizzato una sola volta</li>
                    <li>Non condividere questo link con altri</li>
                </ul>
            </div>
            
            <p>Una volta completata la registrazione, avrai accesso a tutte le funzionalità del sistema per gestire:</p>
            <ul>
                <li>🚙 Flotta veicoli</li>
                <li>📅 Prenotazioni</li>
                <li>👥 Clienti</li>
                <li>📊 Report e statistiche</li>
            </ul>
        </div>

        <div class="footer">
            <p>Se non hai richiesto questo invito, puoi ignorare questa email.</p>
            <p>Per assistenza, contatta il supporto tecnico.</p>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">
            <p style="font-size: 12px;">
                Questo messaggio è stato generato automaticamente dal sistema {{ $companyName }}<br>
                © {{ date('Y') }} - Tutti i diritti riservati
            </p>
        </div>
    </div>
</body>
</html>