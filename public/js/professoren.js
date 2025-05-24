document.addEventListener("DOMContentLoaded", function() {
    function showProfile(kennung, name, email, buero, labore) {

        const profileContainer = document.getElementById('professor-profile');

        // Alle Buttons zurücksetzen
        const allButtons = document.querySelectorAll('.professoren-item');
        allButtons.forEach(button => button.classList.remove('selected'));

        // Den ausgewählten Button hervorheben
        const selectedButton = document.querySelector(`button[data-id='${kennung}']`);
        if (selectedButton) {
            selectedButton.classList.add('selected');
        } else {
            console.warn('Kein Button gefunden für Kennung:', kennung);
        }

        // Avatar-URL: Bild aus public/images/FHKennung.png, Standard: avatar.png
        let avatarUrl = `/images/${kennung}.png`;

        // Prüfe, ob das Bild existiert, ansonsten Standard nehmen
        checkImage(avatarUrl, function(exists) {
            if (!exists) {
                avatarUrl = '/images/avatar.png';
            }

            // labore als Liste
            const laboreList = labore ? labore.split(',').map(l => `<li>${l.trim()}</li>`).join('') : '<li>Keine labore angegeben</li>';

            // Profil anzeigen
            profileContainer.innerHTML = `
                <div class="profil-container">
                    <div class="profil-header">
                        <img src="${avatarUrl}" alt="Profilbild" class="profil-avatar">
                        <h2>${name}</h2>
                        <p class="rolle">Professor</p>
                    </div>
                    <ul>
                        <li><strong>Name:</strong> ${name}</li>
                        <li><strong>Email:</strong> ${email || 'Nicht angegeben'}</li>
                        <li><strong>Büroraum:</strong> ${buero || 'Nicht angegeben'}</li>
                        <li>
                            <strong>Labore:</strong>
                            <ul>
                                ${laboreList}
                            </ul>
                        </li>
                    </ul>
                    <button id="write-btn" class="btn">Schreiben</button>
                </div>
            `;

            // Schreiben-Button
            document.getElementById('write-btn').addEventListener('click', writeMessage);
        });
    }

    function writeMessage() {
        alert('Schreibe eine Nachricht an den Professor!');
    }

    // Image Check
    function checkImage(url, callback) {
        const img = new Image();
        img.onload = () => callback(true);
        img.onerror = () => callback(false);
        img.src = url;
    }

    // Eventlistener für die Buttons
    const buttons = document.querySelectorAll('.professoren-item');
    console.log('Gefundene Buttons:', buttons.length);
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const kennung = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const buero = this.getAttribute('data-buero');
            const labore = this.getAttribute('data-labore');

            showProfile(kennung, name, email, buero, labore);
        });
    });

    // Automatische Auswahl bei URL-Parameter
    const urlParams = new URLSearchParams(window.location.search);
    const kennung = urlParams.get('kennung');

    if (kennung) {
        const targetButton = document.querySelector(`button[data-id='${kennung}']`);
        if (targetButton) {
            targetButton.scrollIntoView({behavior: 'smooth', block: 'center'});
            targetButton.click();
        }
    }
});
