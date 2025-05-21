// Variablen
let currentView = 'monat';
let currentDate = new Date();

// === Hilfsfunktion: Zeitstring (HH:mm) in Dezimalstunden umwandeln ===
function zeitStringZuStunde(zeit) {
    if (!zeit) return 0;
    const [h, m] = zeit.split(':').map(Number);
    return h + m / 60;
}

// === Views wechseln ===
function switchView(view) {
    currentView = view;
    document.getElementById('monatsansicht').style.display = view === 'monat' ? 'block' : 'none';
    document.getElementById('wochensicht').style.display = view === 'woche' ? 'block' : 'none';
    renderKalender();
    renderTermine(gespeicherteTermine);
}

// === Navigation ===
function navigate(offset) {
    if (currentView === 'monat') {
        currentDate.setMonth(currentDate.getMonth() + offset);
    } else {
        currentDate.setDate(currentDate.getDate() + offset * 7);
    }
    renderKalender();
    renderTermine(gespeicherteTermine);
}

// === Kalender-Render ===
function renderKalender() {
    const titel = document.getElementById('kalender-titel');
    const monate = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
    titel.innerText = `${monate[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    currentView === 'monat' ? renderMonatsansicht() : renderWochenansicht();
}

// === Monatsansicht rendern ===
function renderMonatsansicht() {
    const grid = document.querySelector('.kalender-grid.monat');
    grid.innerHTML = '';

    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDay = (firstDay.getDay() + 6) % 7; // Mo=0 ... So=6
    const daysInMonth = lastDay.getDate();
    const wochentage = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

    // Wochentage als Header
    wochentage.forEach(tag => {
        const header = document.createElement('div');
        header.className = 'tag-header';
        header.innerText = tag;
        grid.appendChild(header);
    });

    // Leere Felder vor erstem Tag
    for (let i = 0; i < startDay; i++) {
        const leer = document.createElement('div');
        leer.className = 'kalender-tag leer';
        grid.appendChild(leer);
    }

    // Tage des Monats
    for (let i = 1; i <= daysInMonth; i++) {
        const tag = document.createElement('div');
        tag.className = 'kalender-tag';
        tag.innerHTML = `<div class="tag-header">${i}</div><div class="termine"></div>`;
        grid.appendChild(tag);
    }
}

// === Wochenansicht rendern ===
function renderWochenansicht() {
    const grid = document.getElementById('wochen-grid');
    grid.innerHTML = '';

    // Start der Woche (Montag)
    const startOfWeek = new Date(currentDate);
    const weekday = (startOfWeek.getDay() + 6) % 7;
    startOfWeek.setHours(0, 0, 0, 0);
    startOfWeek.setDate(currentDate.getDate() - weekday);

    const wochentage = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

    // Erste leere Zelle oben links (für Zeitspalte)
    grid.appendChild(document.createElement('div'));

    // Kopfzeilen mit Wochentagen + Datum
    for (let i = 0; i < 7; i++) {
        const datum = new Date(startOfWeek);
        datum.setDate(startOfWeek.getDate() + i);
        const tag = datum.getDate().toString().padStart(2, '0');
        const monat = (datum.getMonth() + 1).toString().padStart(2, '0');
        const header = document.createElement('div');
        header.className = 'wochentag-header';
        header.innerText = `${wochentage[i]}, ${tag}.${monat}`;
        grid.appendChild(header);
    }

    // Stunden von 8 bis 20 Uhr
    for (let hour = 8; hour <= 20; hour++) {
        // Zeit-Spalte links
        const zeit = document.createElement('div');
        zeit.className = 'zeit-spalte';
        zeit.innerText = `${hour}:00`;
        grid.appendChild(zeit);

        // Slots für jeden Wochentag in der Stunde
        for (let i = 0; i < 7; i++) {
            const slot = document.createElement('div');
            slot.className = 'zeit-slot';
            grid.appendChild(slot);
        }
    }
}

// === Wiederholungen expandieren ===
function expandiereWiederholungen(termine) {
    const result = [];

    termine.forEach(t => {
        if (!t.wiederholung || t.wiederholung === 'einmalig') {
            result.push(t);
            return;
        }

        const startDate = new Date(t.datum);
        const endDate = t.enddatum ? new Date(t.enddatum) : startDate;

        let currentDate = new Date(startDate);
        currentDate.setHours(0,0,0,0);

        while (currentDate <= endDate) {
            // Neuen Termin mit dem aktuellen Wiederholungsdatum erzeugen
            const neuesDatumStr = currentDate.toISOString().split('T')[0];
            result.push({
                ...t,
                datum: neuesDatumStr,
            });

            switch (t.wiederholung) {
                case 'taeglich':
                case 'täglich':
                    currentDate.setDate(currentDate.getDate() + 1);
                    break;
                case 'woechentlich':
                case 'wöchentlich':
                    currentDate.setDate(currentDate.getDate() + 7);
                    break;
                case 'alle2wochen':
                    currentDate.setDate(currentDate.getDate() + 14);
                    break;
                case 'monatlich':
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    break;
                default:
                    currentDate = new Date(endDate.getTime() + 1); // Abbruch
                    break;
            }
        }
    });

    return result;
}

// === Termine rendern ===
function renderTermine(termine) {
    const expandedTermine = expandiereWiederholungen(termine);

    if (currentView === 'monat') {
        const tage = document.querySelectorAll('.kalender-grid.monat .kalender-tag');
        tage.forEach(tag => {
            const tagNummer = parseInt(tag.querySelector('.tag-header')?.innerText);
            const termineDiv = tag.querySelector('.termine');
            if (!tagNummer || !termineDiv) return;
            termineDiv.innerHTML = '';

            expandedTermine.forEach(t => {
                const tDatum = new Date(t.datum);
                if (
                    tDatum.getDate() === tagNummer &&
                    tDatum.getMonth() === currentDate.getMonth() &&
                    tDatum.getFullYear() === currentDate.getFullYear()
                ) {
                    const div = document.createElement('div');
                    div.className = 'termin';
                    div.style.backgroundColor = t.farbe || '#007bff';
                    div.innerText = t.titel;
                    div.title = t.beschreibung || '';
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', () => openEditTermin(t.id));
                    termineDiv.appendChild(div);
                }
            });
        });
    } else if (currentView === 'woche') {
        const startOfWeek = new Date(currentDate);
        const weekday = (startOfWeek.getDay() + 6) % 7;
        startOfWeek.setHours(0, 0, 0, 0);
        startOfWeek.setDate(currentDate.getDate() - weekday);

        // Alle Slots zurücksetzen
        const zeitSlots = document.querySelectorAll('.zeit-slot');
        zeitSlots.forEach(slot => slot.innerHTML = '');

        expandedTermine.forEach(t => {
            const tDatum = new Date(t.datum);
            tDatum.setHours(0, 0, 0, 0);
            const diff = Math.floor((tDatum - startOfWeek) / (1000 * 60 * 60 * 24));
            if (diff < 0 || diff > 6) return;

            const vonStunde = zeitStringZuStunde(t.von);
            const bisStunde = zeitStringZuStunde(t.bis);

            if (bisStunde <= 8 || vonStunde >= 20) return;

            const colIndex = diff + 1; // 1 Header-Spalte

            const grid = document.getElementById('wochen-grid');
            const slots = grid.querySelectorAll('.zeit-slot');

            // Auf ganze Stunden runden, um Slots zu belegen
            const startRowIndex = Math.max(Math.floor(vonStunde), 8) - 8 + 1;
            const endRowIndex = Math.min(Math.ceil(bisStunde), 20) - 8 + 1;

            for (let rowIndex = startRowIndex; rowIndex < endRowIndex; rowIndex++) {
                const slotIndex = (rowIndex - 1) * 7 + (colIndex - 1);
                if (slots[slotIndex]) {
                    const div = document.createElement('div');
                    div.className = 'termin';
                    div.style.backgroundColor = t.farbe || '#007bff';
                    div.innerText = t.titel;
                    div.title = t.beschreibung || '';
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', () => openEditTermin(t.id));
                    slots[slotIndex].appendChild(div);
                }
            }
        });
    }
}

// === Toast anzeigen ===
function showToast(message) {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.backgroundColor = '#333';
        toast.style.color = '#fff';
        toast.style.padding = '10px 20px';
        toast.style.borderRadius = '5px';
        toast.style.zIndex = '10000';
        toast.style.display = 'none';
        document.body.appendChild(toast);
    }

    toast.innerText = message;
    toast.style.display = 'block';

    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

// === Event-Listener für View & Navigation ===
document.getElementById('btn-monat').addEventListener('click', () => switchView('monat'));
document.getElementById('btn-woche').addEventListener('click', () => switchView('woche'));
document.getElementById('btn-prev').addEventListener('click', () => navigate(-1));
document.getElementById('btn-next').addEventListener('click', () => navigate(1));

// Export oder globale Sichtbarkeit, falls gebraucht
window.kalender = {
    renderKalender,
    renderTermine,
    expandiereWiederholungen,
    switchView,
    navigate,
    showToast,
};
