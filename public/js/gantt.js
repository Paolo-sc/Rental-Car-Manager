// Endpoint Laravel per ottenere veicoli + prenotazioni
const CALENDAR_DATA_URL = window.calendarDataUrl || '/calendar-data';

let veicoli = [];
let allBookings = {};
let currentMonthOffset = -1;    // finestra iniziale: [-1, 0, +1]
let currentScrollPosition = 0;
let allMonthsData = [];
let allDays = [];
const DAY_CELL_WIDTH = 100;
let isLoading = false;          // flag per evitare fetch concorrenti

const calendar     = document.getElementById('calendar');
const calendarGrid = document.getElementById('calendar-grid');
const yearSelect   = document.getElementById('yearSelect');
const monthSelect  = document.getElementById('monthSelect');
const resetFilter  = document.getElementById('resetFilter');

const monthNames = [
    'Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
    'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'
];

// 1) Utilità per calcolare mese/anno da offset
function getMonthYear(offset) {
    const d = new Date();
    d.setMonth(d.getMonth() + offset);
    return {
        mese: d.getMonth(),
        anno: d.getFullYear(),
        offset: offset
    };
}

// 2) Quanti giorni ha un mese
function giorniNelMese(mese, anno) {
    return new Date(anno, mese + 1, 0).getDate();
}

// 3) Formatta Date in "YYYY-MM-DD"
function formatDateToYMD(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

// 4) Popola dropdown anno e mese
function populateYearSelect() {
    const currentYear = new Date().getFullYear();
    yearSelect.innerHTML = ''; // svuota

    for (let y = currentYear - 2; y <= currentYear + 2; y++) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        if (y === currentYear) opt.selected = true; // seleziona quello corrente
        yearSelect.appendChild(opt);
    }
}

function populateMonthSelect() {
    const currentMonth = new Date().getMonth(); // 0 = Gennaio
    monthSelect.innerHTML = ''; // svuota completamente

    monthNames.forEach((m, idx) => {
        const opt = document.createElement('option');
        opt.value = idx;
        opt.textContent = m;
        if (idx === currentMonth) opt.selected = true; // seleziona il mese corrente
        monthSelect.appendChild(opt);
    });
}

// 5) Calcola l'offset (in mesi) da un anno e un mese selezionati
function getMonthOffsetFromYearMonth(year, month) {
    if (year === '' && month === '') return null;
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth();
    let targetYear = year === '' ? currentYear : parseInt(year);
    let targetMonth = month === '' ? currentMonth : parseInt(month);
    if (year !== '' && month === '') targetMonth = 0;
    if (year === '' && month !== '') targetYear = currentYear;
    const dtTarget = new Date(targetYear, targetMonth, 1);
    const dtCurrent = new Date(currentYear, currentMonth, 1);
    const diffTime = dtTarget.getTime() - dtCurrent.getTime();
    return Math.round(diffTime / (1000 * 60 * 60 * 24 * 30.44));
}

// 6) Fetch di veicoli + prenotazioni (solo alla prima volta)
//    Nelle chiamate successive aggiorna solo le prenotazioni
async function fetchCalendarData(from, to) {
    try {
        let url = CALENDAR_DATA_URL;
        if (from && to) url += `?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`;
        const response = await fetch(url);
        if (!response.ok) throw new Error('Errore recupero dati');
        const json = await response.json();
        // Se veicoli è vuoto, significa primo caricamento: salvo veicoli
        if (veicoli.length === 0) {
            veicoli = json.vehicles;
        }
        // In ogni caso aggiorno le prenotazioni
        allBookings = json.bookings;
    } catch (err) {
        console.error(err);
        alert('Impossibile caricare i dati del calendario.');
    }
}

// 7) Genera la griglia del calendario per la finestra corrente
function generateCalendar(filterMonth = null) {
    allDays = [];
    allMonthsData = [];

    let startOffset, endOffset;
    if (filterMonth !== null) {
        startOffset = filterMonth - 1;
        endOffset   = filterMonth + 1;
    } else {
        startOffset = currentMonthOffset;
        endOffset   = currentMonthOffset + 2;
    }

    for (let mo = startOffset; mo <= endOffset; mo++) {
        const { mese, anno, offset } = getMonthYear(mo);
        const giorni = giorniNelMese(mese, anno);
        const today = new Date();

        const nomeCompleto = new Date(anno, mese).toLocaleString('it-IT',{ month:'long', year:'numeric' });
        allMonthsData.push({
            name: nomeCompleto,
            days: giorni,
            startIndex: allDays.length,
            offset: offset
        });

        for (let d = 1; d <= giorni; d++) {
            const dt = new Date(anno, mese, d);
            const isToday = dt.toDateString() === today.toDateString();
            const isWeekend = dt.getDay() === 0 || dt.getDay() === 6;
            allDays.push({ day: d, date: dt, isToday, isWeekend, monthOffset: offset });
        }
    }

    calendarGrid.innerHTML = '';

    // Header mesi
    let monthsHeader = document.querySelector('.months-header');
    if (!monthsHeader) {
        monthsHeader = document.createElement('div');
        monthsHeader.className = 'months-header';
        calendarGrid.parentNode.insertBefore(monthsHeader, calendarGrid);
    } else {
        monthsHeader.innerHTML = '';
    }
    allMonthsData.forEach(m => {
        const cell = document.createElement('div');
        cell.className = 'month-header-cell';
        cell.style.width = `${m.days * DAY_CELL_WIDTH}px`;
        cell.textContent = m.name;
        cell.setAttribute('data-days', m.days);
        cell.title = `${m.name} - ${m.days} giorni`;
        monthsHeader.appendChild(cell);
    });

    // Header giorni
    const dayRow = document.createElement('div');
    dayRow.className = 'days-header';
    const daysLabel = document.createElement('div');
    daysLabel.className = 'days-header-label';
    daysLabel.textContent = 'Giorni';
    dayRow.appendChild(daysLabel);

    allDays.forEach(dInfo => {
        const cell = document.createElement('div');
        cell.className = 'day-header-cell';
        if (dInfo.isToday) cell.classList.add('today');
        if (dInfo.isWeekend) cell.classList.add('weekend');
        cell.textContent = dInfo.day;
        dayRow.appendChild(cell);
    });
    calendarGrid.appendChild(dayRow);

    // Se non ho veicoli (primo fetch fallito), esco
    if (veicoli.length === 0) return;

    // Righe veicolo + prenotazioni
    veicoli.forEach((vObj) => {
        const row = document.createElement('div');
        row.className = 'vehicle-row';

        const nameCell = document.createElement('div');
        nameCell.className = 'vehicle-name-cell';
        nameCell.textContent = vObj.name;
        row.appendChild(nameCell);

        const vehicleBookings = allBookings[vObj.id] || [];
        allDays.forEach((dInfo, dIndex) => {
            const cell = document.createElement('div');
            cell.className = 'booking-cell';

            const visible = vehicleBookings.filter(bk => {
                const bs = new Date(bk.start_date);
                const be = new Date(bk.end_date);
                const cd = dInfo.date;
                if (bs.toDateString() === cd.toDateString()) return true;
                if (bs < allDays[0].date && be >= cd && bs <= cd && dIndex === 0) return true;
                return false;
            });

            visible.forEach(bk => {
                const el = createBookingElementFromData(bk, allDays, dIndex);
                if (el) cell.appendChild(el);
            });

            row.appendChild(cell);
        });

        calendarGrid.appendChild(row);
    });

    if (filterMonth === null) {
        calendar.scrollLeft = currentScrollPosition;
    }
}

// 8) Crea elemento HTML per prenotazione
function createBookingElementFromData(booking, allDays, dayIndex) {
    const startDate = new Date(booking.start_date);
    const endDate   = new Date(booking.end_date);

    let visibleLength = 0;

    if (startDate < allDays[0].date) {
        const totalDaysInBooking = Math.ceil((endDate - startDate)/(1000*60*60*24)) + 1;
        const daysBeforeRange = Math.ceil((allDays[0].date - startDate)/(1000*60*60*24));
        visibleLength = Math.min(totalDaysInBooking - daysBeforeRange, allDays.length);
    } else {
        visibleLength = Math.min(
            Math.ceil((endDate - startDate)/(1000*60*60*24)) + 1,
            allDays.length - dayIndex
        );
    }

    if (visibleLength <= 0) return null;

    const bookingEl = document.createElement('div');
    bookingEl.className = 'booking';
    bookingEl.style.width = `${(visibleLength * DAY_CELL_WIDTH) - 12}px`;
    bookingEl.style.right = 'auto';

    const ddStart = String(startDate.getDate()).padStart(2, '0');
    const mmStart = String(startDate.getMonth()+1).padStart(2, '0');
    const ddEnd   = String(endDate.getDate()).padStart(2, '0');
    const mmEnd   = String(endDate.getMonth()+1).padStart(2, '0');

    bookingEl.textContent = `${ddStart}/${mmStart} - ${ddEnd}/${mmEnd}`;
    bookingEl.onclick = () => {
        alert(`Contratto ID: ${booking.id}\nDal: ${startDate.toLocaleDateString('it-IT')}\nAl: ${endDate.toLocaleDateString('it-IT')}`);
    };

    return bookingEl;
}

// 9) Scroll a mese specifico
function scrollToMonth(monthOffset) {
    currentMonthOffset = monthOffset - 1;
    generateCalendar();
    setTimeout(() => {
        const mData = allMonthsData.find(m => m.offset === monthOffset);
        if (mData) {
            const pos = mData.startIndex * DAY_CELL_WIDTH;
            calendar.scrollLeft = pos;
            currentScrollPosition = pos;
        }
    }, 50);
}

// 10) Cambio filtro anno/mese con fetch dinamico
async function handleFilterChange() {
    const sy = yearSelect.value;
    const sm = monthSelect.value;
    if (sy === '' && sm === '') {
        currentMonthOffset = -1;
        await loadAndRenderInitialWindow();
    } else {
        const targetOffset = getMonthOffsetFromYearMonth(sy, sm);
        if (targetOffset !== null) {
            // Carico prenotazioni per [targetOffset−1, targetOffset, targetOffset+1]
            const first = getMonthYear(targetOffset - 1);
            const third = getMonthYear(targetOffset + 1);
            const from = formatDateToYMD(new Date(first.anno, first.mese, 1));
            const to   = formatDateToYMD(new Date(third.anno, third.mese, giorniNelMese(third.mese, third.anno)));

            // Eseguo fetch e aggiorno offset
            isLoading = true;
            await fetchCalendarData(from, to);
            currentMonthOffset = targetOffset - 1;
            generateCalendar();
            setTimeout(() => scrollToMonth(targetOffset), 100);
            isLoading = false;
        }
    }
}

// 11) Listener di scroll per caricare un mese per volta
calendar.addEventListener('scroll', async () => {
    if (isLoading) return;
    currentScrollPosition = calendar.scrollLeft;
    const maxScroll = calendar.scrollWidth - calendar.clientWidth;

    // Verso destra: carico mese successivo
    if (calendar.scrollLeft + 200 >= maxScroll) {
        isLoading = true;
        const newOffset = currentMonthOffset + 1;

        // Intervallo di tre mesi: [newOffset, newOffset+1, newOffset+2]
        const first = getMonthYear(newOffset);
        const third = getMonthYear(newOffset + 2);
        const from = formatDateToYMD(new Date(first.anno, first.mese, 1));
        const to   = formatDateToYMD(new Date(third.anno, third.mese, giorniNelMese(third.mese, third.anno)));

        await fetchCalendarData(from, to);
        currentMonthOffset = newOffset;
        generateCalendar();

        // Compenso scroll rimuovendo un mese
        const removedWidth = allMonthsData[0].days * DAY_CELL_WIDTH;
        calendar.scrollLeft = currentScrollPosition - removedWidth;
        currentScrollPosition = calendar.scrollLeft;
        isLoading = false;
    }

    // Verso sinistra: carico mese precedente
    if (calendar.scrollLeft <= 200) {
        isLoading = true;
        const newOffset = currentMonthOffset - 1;

        // Intervallo di tre mesi: [newOffset, newOffset+1, newOffset+2]
        const first = getMonthYear(newOffset);
        const third = getMonthYear(newOffset + 2);
        const from = formatDateToYMD(new Date(first.anno, first.mese, 1));
        const to   = formatDateToYMD(new Date(third.anno, third.mese, giorniNelMese(third.mese, third.anno)));

        await fetchCalendarData(from, to);
        currentMonthOffset = newOffset;
        generateCalendar();

        // Compenso scroll aggiungendo un mese
        const addedWidth = allMonthsData[0].days * DAY_CELL_WIDTH;
        calendar.scrollLeft = currentScrollPosition + addedWidth;
        currentScrollPosition = calendar.scrollLeft;
        isLoading = false;
    }
});

// 12) Drag e touch
let isDown = false, startX, scrollLeft;
calendar.addEventListener('mousedown', e => {
    isDown = true;
    calendar.classList.add('dragging');
    startX = e.pageX;
    scrollLeft = calendar.scrollLeft;
});
calendar.addEventListener('mouseup', () => {
    isDown = false;
    calendar.classList.remove('dragging');
});
calendar.addEventListener('mouseleave', () => {
    isDown = false;
    calendar.classList.remove('dragging');
});
calendar.addEventListener('mousemove', e => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX;
    const walk = (x - startX) * 1.2;
    calendar.scrollLeft = scrollLeft - walk;
});
calendar.addEventListener('touchstart', e => {
    isDown = true;
    startX = e.touches[0].pageX;
    scrollLeft = calendar.scrollLeft;
});
calendar.addEventListener('touchend', () => { isDown = false; });
calendar.addEventListener('touchmove', e => {
    if (!isDown) return;
    const x = e.touches[0].pageX;
    const walk = (x - startX) * 1.2;
    calendar.scrollLeft = scrollLeft - walk;
});
calendar.addEventListener('wheel', e => {
    if (e.deltaY !== 0) {
        e.preventDefault();
        calendar.scrollLeft += e.deltaY;
    }
}, { passive: false });

// 13) Carica e disegna la finestra iniziale [-1, 0, +1]
async function loadAndRenderInitialWindow() {
    const first = getMonthYear(-1);
    const third = getMonthYear(1);

    const from = formatDateToYMD(new Date(first.anno, first.mese, 1));
    const to   = formatDateToYMD(new Date(third.anno, third.mese, giorniNelMese(third.mese, third.anno)));

    await fetchCalendarData(from, to);
    generateCalendar();
    setTimeout(() => scrollToMonth(0), 100);
}

// 14) Inizializzazione
window.addEventListener('DOMContentLoaded', async () => {
    populateYearSelect();
    populateMonthSelect();
    await loadAndRenderInitialWindow();
    yearSelect.addEventListener('change', handleFilterChange);
    monthSelect.addEventListener('change', handleFilterChange);
    resetFilter.addEventListener('click', () => {
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth();
        yearSelect.value = currentYear;
        monthSelect.value = currentMonth;
        loadAndRenderInitialWindow();
    });
});
