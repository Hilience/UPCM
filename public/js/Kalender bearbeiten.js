// Termin-Daten aus localStorage laden oder leeres Array
let gespeicherteTermine = window.termineVomServer || [];

// Modal und Formular-Elemente
const modal = document.getElementById('termin-modal');
const form = document.getElementById('termin-form');
// === Farbwahl ===
const farbAuswahlContainer = document.getElementById('farb-auswahl');
const farbeInput = document.getElementById('farbe');
const farben = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'];

function renderFarbwahl() {
    farbAuswahlContainer.innerHTML = '';

    farben.forEach(farbcode => {
        const farbKreis = document.createElement('div');
        farbKreis.style.backgroundColor = farbcode;
        farbKreis.dataset.farbe = farbcode;

        if (farbeInput.value === farbcode) {
            farbKreis.classList.add('aktiv');
        }

        farbKreis.addEventListener('click', () => {
            farbeInput.value = farbcode;

            // Aktive Markierung aktualisieren
            [...farbAuswahlContainer.children].forEach(c => c.classList.remove('aktiv'));
            farbKreis.classList.add('aktiv');
        });

        farbAuswahlContainer.appendChild(farbKreis);
    });
}

window.addEventListener('DOMContentLoaded', () => {
    renderFarbwahl();
});

// === Termin laden in Formular ===
function openEditTermin(id) {
    if (!form) return;

    if (id === null) {
        // Neues Terminformular öffnen - Felder zurücksetzen
        form.reset();
        form.dataset.id = '';
        document.getElementById('btn-loeschen').style.display = 'none';
        showModal();
        return;
    }

    const termin = gespeicherteTermine.find(t => t.id === id);
    if (!termin) return;

    form.dataset.id = id;
    form.titel.value = termin.titel || '';
    form.datum.value = termin.datum || '';
    form.von.value = termin.von || '08:00';
    form.bis.value = termin.bis || '09:00';
    form.kommentar.value = termin.kommentar || '';
    form.wiederholung.value = termin.wiederholung || 'einmalig';
    form.enddatum.value = termin.enddatum || '';
    form.farbe.value = termin.farbe || '#007bff';

    document.getElementById('btn-loeschen').style.display = 'inline-block';
    showModal();
}

// === Modal anzeigen/verstecken ===
function showModal() {
    modal.classList.remove('hidden');
    modal.classList.add('aktiv');
}

function hideModal() {
    modal.classList.add('hidden');
    modal.classList.remove('aktiv');
    form.dataset.id = '';
    form.reset();
}

// === Termin speichern ===
function saveTermin(event) {
    event.preventDefault();

    const id = form.dataset.id || generateId();
    const neuerTermin = {
        id,
        titel: form.titel.value.trim(),
        datum: form.datum.value,
        von: form.von.value,
        bis: form.bis.value,
        kommentar: form.kommentar.value.trim(),
        wiederholung: form.wiederholung.value,
        enddatum: form.enddatum.value,
        farbe: form.farbe.value,
    };

    const index = gespeicherteTermine.findIndex(t => t.id === id);
    if (index >= 0) {
        gespeicherteTermine[index] = neuerTermin;
    } else {
        gespeicherteTermine.push(neuerTermin);
    }

    // Speichern auf dem Server per AJAX
    fetch('/kalender/speichern', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ termine: gespeicherteTermine })
    });
    kalender.renderTermine(gespeicherteTermine);
    kalender.showToast('Termin gespeichert!');
    hideModal();
}

// === Termin löschen ===
function deleteTermin() {
    const id = form.dataset.id;
    if (!id) return;

    gespeicherteTermine = gespeicherteTermine.filter(t => t.id !== id);
    fetch('/kalender/loeschen', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id })
    });
    kalender.renderTermine(gespeicherteTermine);
    kalender.showToast('Termin gelöscht!');
    hideModal();
}

// === ID generieren ===
function generateId() {
    return 'id-' + Math.random().toString(36).substr(2, 9);
}

// === Event Listeners ===
window.addEventListener('DOMContentLoaded', () => {
    form.addEventListener('submit', saveTermin);
    document.getElementById('btn-loeschen').addEventListener('click', () => {
        document.getElementById('loeschen-bestätigung-modal').classList.remove('hidden');
    });
    // === Lösch-Bestätigung Modal ===
    document.getElementById('btn-bestätigen-loeschen').addEventListener('click', () => {
        deleteTermin();
        document.getElementById('loeschen-bestätigung-modal').classList.add('hidden');
    });

    document.getElementById('btn-abbrechen-loeschen').addEventListener('click', () => {
        document.getElementById('loeschen-bestätigung-modal').classList.add('hidden');
    });
    document.getElementById('btn-schliessen').addEventListener('click', hideModal);
    document.getElementById('btn-neu').addEventListener('click', () => openEditTermin(null));
});

// === Kalender initial rendern ===
window.addEventListener('load', () => {
    kalender.renderKalender();
    kalender.renderTermine(gespeicherteTermine);
});
