document.addEventListener("DOMContentLoaded", function() {
    function showLaborProfile(name, raum, verantwortliche, projekte, stellen, news, feedback) {
        console.log('Labor Name:', name);
        const profileContainer = document.getElementById('labor-profile');

        // Alle Buttons zurücksetzen
        const allButtons = document.querySelectorAll('.laboren-item');
        allButtons.forEach(button => button.classList.remove('selected'));

        // Den ausgewählten Button hervorheben
        const selectedButton = document.querySelector(`button[data-name='${name}']`);
        if (selectedButton) {
            selectedButton.classList.add('selected');
        } else {
            console.warn('Kein Button gefunden für Labor:', name);
        }

        // Verantwortliche als Liste
        const verantwortlicheList = verantwortliche ? verantwortliche.split(',').map(v => `<li>${v.trim()}</li>`).join('') : '<li>Keine angegeben</li>';

        // Projekte
        const projekteList = projekte ? projekte.split(',').map(p => `<li>${p.trim()}</li>`).join('') : '<li>Keine Projekte</li>';

        // Stellenangebote
        const stellenList = stellen ? stellen.split(',').map(s => `<li>${s.trim()}</li>`).join('') : '<li>Keine Stellenangebote</li>';

        // News
        const newsList = news ? news.split('||').map(n => {
            const [titel, inhalt] = n.split('::');
            return `<div class="news-item"><h3>${titel}</h3><p>${inhalt}</p></div>`;
        }).join('') : '<p>Keine Neuigkeiten</p>';

        // Profil anzeigen
        profileContainer.innerHTML = `
        <div class="profil-container">
            <div class="profil-header">
                <h2>${name}</h2>
                <p class="rolle">Labor</p>
            </div>
            <ul>
                <li><strong>Raum:</strong> ${raum || 'Nicht angegeben'}</li>
                <li>
                    <strong>Verantwortliche:</strong>
                    <ul>${verantwortlicheList}</ul>
                </li>
                <li>
                    <strong>Projekte:</strong>
                    <ul>${projekteList}</ul>
                </li>
                <li>
                    <strong>Stellenangebote:</strong>
                    <ul>${stellenList}</ul>
                </li>
            </ul>
            <h3>Neuigkeiten</h3>
            <div class="news-grid">
                ${newsList}
            </div>
            <h3>Allgemeines Feedback</h3>
            <p>${feedback || 'Kein Feedback'}</p>
        </div>
    `;
    }

    // Eventlistener für die Buttons
    const buttons = document.querySelectorAll('.laboren-item');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const raum = this.getAttribute('data-raum');
            const verantwortliche = this.getAttribute('data-verantwortliche');
            const projekte = this.getAttribute('data-projekte');
            const stellen = this.getAttribute('data-stellen');
            const news = this.getAttribute('data-news');
            const feedback = this.getAttribute('data-feedback');

            showLaborProfile(name, raum, verantwortliche, projekte, stellen, news, feedback);
        });
    });
});
