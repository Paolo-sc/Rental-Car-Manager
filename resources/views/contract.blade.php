<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Contratto di Noleggio</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            background: #f0f0f0;
            padding: 4px;
        }

        .signature {
            margin-top: 50px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <h2>CONTRATTO DI NOLEGGIO</h2>

    <p>
        <strong>Data:</strong> {{ $reservation->signature_date ?? now()->format('d/m/Y H:i') }} <br>
        <strong>Codice Contratto:</strong> {{ $reservation->booking_code }}
    </p>

    {{-- ====================== DATI CLIENTE ====================== --}}
    <div class="section-title">Cliente</div>
    <table border="1" cellpadding="5">
        <tr>
            <td>Nome</td>
            <td>{{ $reservation->customer->first_name }}</td>
            <td>Cognome</td>
            <td>{{ $reservation->customer->last_name }}</td>
        </tr>
        <tr>
            <td>Cod. Fiscale</td>
            <td>{{ $reservation->customer->tax_code }}</td>
            <td>P. IVA</td>
            <td>{{ $reservation->customer->vat_number }}</td>
        </tr>
        <tr>
            <td>Indirizzo</td>
            <td colspan="3">{{ $reservation->customer->address }}</td>
        </tr>
        <tr>
            <td>Nazione</td>
            <td>{{ $reservation->customer->country }}</td>
            <td>Città</td>
            <td>{{ $reservation->customer->city }}</td>
        </tr>
        <tr>
            <td>CAP</td>
            <td>{{ $reservation->customer->postal_code }}</td>
            <td>Email</td>
            <td>{{ $reservation->customer->email }}</td>
        </tr>
        <tr>
            <td>Telefono</td>
            <td>{{ $reservation->customer->phone }}</td>
            <td>Documento</td>
            <td>{{ optional($reservation->customer->documents->first())->document_type ?? '-' }} -
                {{ optional($reservation->customer->documents->first())->id_document_number ?? '-' }}</td>
        </tr>
    </table>


    {{-- ====================== DATI CONDUCENTE ====================== --}}
    <div class="section-title">Conducente Principale</div>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td>Nome</td>
            <td>{{ $reservation->mainDriver->first_name }}</td>
            <td>Cognome</td>
            <td>{{ $reservation->mainDriver->last_name }}</td>
        </tr>
        <tr>
            <td>Patente N°</td>
            <td>{{ $reservation->mainDriver->driving_license_number }}</td>
            <td>Rilasciata da</td>
            <td>{{ $reservation->mainDriver->driving_license_issue_place }}</td>
        </tr>
        <tr>
            <td>Data Emissione</td>
            <td>{{ $reservation->mainDriver->driving_license_issue_date }}</td>
            <td>Scadenza</td>
            <td>{{ $reservation->mainDriver->driving_license_expires_at }}</td>
        </tr>
        <tr>
            <td>Data di Nascita</td>
            <td>{{ $reservation->mainDriver->birth_date }}</td>
            <td>Codice Fiscale</td>
            <td>{{ $reservation->mainDriver->tax_code }}</td>
        </tr>
        <tr>
            <td>Telefono</td>
            <td>{{ $reservation->mainDriver->phone }}</td>
            <td>Email</td>
            <td>{{ $reservation->mainDriver->email }}</td>
        </tr>
        <tr>
            <td>Indirizzo</td>
            <td colspan="3">{{ $reservation->mainDriver->address }}</td>
        </tr>
        <tr>
            <td>Nazione</td>
            <td>{{ $reservation->mainDriver->country }}</td>
            <td>Città</td>
            <td>{{ $reservation->mainDriver->city }}</td>
        </tr>
        <tr>
            <td>CAP</td>
            <td>{{ $reservation->mainDriver->postal_code }}</td>
            <td colspan="2"></td> {{-- celle vuote per mantenere 4 colonne --}}
        </tr>
    </table>


    {{-- ====================== DATI VEICOLO ====================== --}}
    <div class="section-title">Veicolo</div>
    <table>
        <tr>
            <td>Targa</td>
            <td>{{ $reservation->vehicle->license_plate }}</td>
            <td>Anno</td>
            <td>{{ $reservation->vehicle->year }}</td>
        </tr>
        <tr>
            <td>Marca</td>
            <td>{{ $reservation->vehicle->brand }}</td>
            <td>Modello</td>
            <td>{{ $reservation->vehicle->model }}</td>
        </tr>
        <tr>
            <td>Kilometri</td>
            <td>{{ $reservation->vehicle->mileage }}</td>
            <td>Vin</td>
            <td>{{ $reservation->vehicle->vin }}</td>
        </tr>
        <tr>
            <td>Alimentazione</td>
            <td>{{ $reservation->vehicle->fuel_type }}</td>
            <td>Cambio</td>
            <td>{{ $reservation->vehicle->transmission }}</td>
        </tr>
    </table>

    {{-- ====================== COSTI ====================== --}}
    <div class="section-title">Costi</div>
    <table>
        <tr>
            <td>Tariffa Giornaliera</td>
            <td>€ {{ number_format($reservation->daily_rate, 2, ',', '.') }}</td>
            <td>Totale Giorni</td>
            <td>{{ $reservation->total_days }}</td>
        </tr>
        <tr>
            <td>Subtotale</td>
            <td>€ {{ number_format($reservation->subtotal, 2, ',', '.') }}</td>
            <td>Sconto</td>
            <td>€ {{ number_format($reservation->discount_amount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>IVA ({{ $reservation->tax_rate }}%)</td>
            <td>€ {{ number_format($reservation->tax_amount, 2, ',', '.') }}</td>
            <td>Totale</td>
            <td>€ {{ number_format($reservation->total_amount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Versato</td>
            <td>€ {{ number_format($reservation->total_paid, 2, ',', '.') }}</td>
            <td>Residuo</td>
            <td>€ {{ number_format($reservation->total_amount - $reservation->total_paid, 2, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ====================== DETTAGLI NOLEGGIO ====================== --}}
    <div class="section-title">Dettagli Noleggio</div>
    <table>
        <tr>
            <td>Ritiro</td>
            <td>{{ $reservation->pickup_location }} - {{ $reservation->pickup_time }}</td>
            <td>Riconsegna</td>
            <td>{{ $reservation->return_location }} - {{ $reservation->return_time }}</td>
        </tr>
        <tr>
            <td>Km Inclusi</td>
            <td colspan="3">
                @if ($reservation->km_included_type === 'unlimited')
                    Illimitati
                @else
                    {{ $reservation->km_included_value }} km
                @endif
            </td>
        </tr>
        <tr>
            <td>Cauzione</td>
            <td>€ {{ $reservation->deposit_amount }}</td>
            <td>Metodo</td>
            <td>{{ ucfirst($reservation->deposit_payment_method) }}</td>
        </tr>
        <tr>
            <td>Franchigia Danni</td>
            <td>€ {{ $reservation->deductible_damage }}</td>
            <td>Franchigia RCA</td>
            <td>€ {{ $reservation->deductible_rca }}</td>
        </tr>
        <tr>
            <td>Franchigia Furto/Incendio</td>
            <td colspan="3">€ {{ $reservation->franchise_theft_fire }}</td>
        </tr>
    </table>

    {{-- ====================== FIRME ====================== --}}
    <p class="signature">
    Firma Cliente: <br>
    @if(!empty($signature_base64))
        <img src="{{ $signature_base64 }}" style="width:200px; height:auto;">
    @else
        ________________________
    @endif
    <br><br>
</p>
</body>

</html>
