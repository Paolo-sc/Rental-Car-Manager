function getWeather(lat, lon) {
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&timezone=Europe/Rome`;

    fetch(url)
        .then((res) => res.json())
        .then((data) => {
            const weather = data.current_weather;
            const temp = weather.temperature;
            const code = weather.weathercode; // codice condizione meteo
            const wind = weather.windspeed;

            // Testo dinamico
            document.getElementById("weather-text").innerHTML = `
        ${temp}°C </br> ${mapWeatherCode(code)}
      `;

            // Icona dinamica
            document.getElementById("weather-icon").innerHTML =
                getWeatherIcon(code);
        })
        .catch((err) => {
            console.error("Errore meteo:", err);
            document.getElementById("weather-text").innerText =
                "Meteo non disponibile";
        });
}

// Mappa codici meteo Open-Meteo → descrizione
function mapWeatherCode(code) {
    const codes = {
        0: "Sereno",
        1: "Prevalentemente sereno",
        2: "Parzialmente nuvoloso",
        3: "Nuvoloso",
        45: "Nebbia",
        48: "Nebbia ghiacciata",
        51: "Pioggerella leggera",
        61: "Pioggia leggera",
        63: "Pioggia moderata",
        65: "Pioggia forte",
        71: "Neve leggera",
        95: "Temporale",
    };
    return codes[code] || "Condizione sconosciuta";
}

// Icone SVG semplificate in base al codice
function getWeatherIcon(code) {
    if ([0, 1].includes(code)) {
        return `<path d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 2V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 20V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4.93005 4.92993L6.34005 6.33993" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.66 17.6599L19.07 19.0699" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M2 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M6.34005 17.6599L4.93005 19.0699" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19.07 4.92993L17.66 6.33993" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`; // sole
    } else if ([2, 3].includes(code)) {
        return `<path d="M12 2V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4.93005 4.92999L6.34005 6.33999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19.07 4.92999L17.66 6.33999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M15.947 12.65C16.07 11.9045 15.9794 11.1394 15.6857 10.4432C15.3919 9.74711 14.9069 9.14841 14.287 8.71648C13.667 8.28455 12.9374 8.03705 12.1826 8.00263C11.4278 7.96821 10.6787 8.14828 10.022 8.522" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M13 22H6.99996C6.05393 21.9998 5.12737 21.7312 4.32788 21.2255C3.5284 20.7197 2.8888 19.9975 2.48339 19.1427C2.07797 18.288 1.92336 17.3358 2.03752 16.3966C2.15168 15.4575 2.52992 14.5701 3.12832 13.8373C3.72672 13.1046 4.52071 12.5567 5.41808 12.2572C6.31545 11.9577 7.27938 11.9189 8.1979 12.1454C9.11642 12.3718 9.95185 12.8542 10.6072 13.5366C11.2625 14.2189 11.7108 15.0731 11.9 16H13C13.7956 16 14.5587 16.3161 15.1213 16.8787C15.6839 17.4413 16 18.2044 16 19C16 19.7956 15.6839 20.5587 15.1213 21.1213C14.5587 21.6839 13.7956 22 13 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`; // nuvola
    } else if ([61, 63, 65].includes(code)) {
        return `<path d="M3.99997 14.899C3.25701 14.1399 2.69654 13.2217 2.36101 12.214C2.02547 11.2062 1.92368 10.1353 2.06333 9.08232C2.20299 8.02938 2.58043 7.02202 3.16707 6.13655C3.75371 5.25109 4.53416 4.51074 5.44931 3.97157C6.36445 3.43241 7.3903 3.10857 8.44914 3.0246C9.50798 2.94062 10.572 3.09871 11.5607 3.48688C12.5494 3.87505 13.4368 4.48313 14.1557 5.26506C14.8746 6.04698 15.4061 6.98225 15.71 8.00002H17.5C18.4655 7.99991 19.4054 8.31034 20.181 8.88546C20.9565 9.46058 21.5265 10.2699 21.8067 11.1938C22.087 12.1178 22.0627 13.1074 21.7373 14.0164C21.412 14.9254 20.8028 15.7057 20 16.242" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 14V20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 14V20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 16V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`; // pioggia
    } else if ([71].includes(code)) {
        return `<path d="M3.99997 14.899C3.25701 14.1399 2.69654 13.2217 2.36101 12.214C2.02547 11.2062 1.92368 10.1353 2.06333 9.08232C2.20299 8.02938 2.58043 7.02202 3.16707 6.13655C3.75371 5.25109 4.53416 4.51074 5.44931 3.97157C6.36445 3.43241 7.3903 3.10857 8.44914 3.0246C9.50798 2.94062 10.572 3.09871 11.5607 3.48688C12.5494 3.87505 13.4368 4.48313 14.1557 5.26506C14.8746 6.04698 15.4061 6.98225 15.71 8.00002H17.5C18.4655 7.99991 19.4054 8.31034 20.181 8.88546C20.9565 9.46058 21.5265 10.2699 21.8067 11.1938C22.087 12.1178 22.0627 13.1074 21.7373 14.0164C21.412 14.9254 20.8028 15.7057 20 16.242" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 15H8.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 19H8.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12 21H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 15H16.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 19H16.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`; // neve
    } else {
        return `<path d="M3.99997 14.899C3.25701 14.1399 2.69654 13.2217 2.36101 12.214C2.02547 11.2062 1.92368 10.1353 2.06333 9.08232C2.20299 8.02938 2.58043 7.02202 3.16707 6.13655C3.75371 5.25109 4.53416 4.51074 5.44931 3.97157C6.36445 3.43241 7.3903 3.10857 8.44914 3.0246C9.50798 2.94062 10.572 3.09871 11.5607 3.48688C12.5494 3.87505 13.4368 4.48313 14.1557 5.26506C14.8746 6.04698 15.4061 6.98225 15.71 8.00002H17.5C18.4655 7.99991 19.4054 8.31034 20.181 8.88546C20.9565 9.46058 21.5265 10.2699 21.8067 11.1938C22.087 12.1178 22.0627 13.1074 21.7373 14.0164C21.412 14.9254 20.8028 15.7057 20 16.242" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16 17H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17 21H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`; // default
    }
}

// Prendi posizione utente (fallback su Enna)
navigator.geolocation.getCurrentPosition(
    (pos) => getWeather(pos.coords.latitude, pos.coords.longitude),
    (err) => {
        console.warn("Geolocalizzazione negata, uso Enna");
        getWeather(37.5667, 14.2667);
    }
);
