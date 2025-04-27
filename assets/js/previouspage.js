// Guarda el historial en sessionStorage
const currentPage = window.location.href;
const history = JSON.parse(sessionStorage.getItem('navigationHistory')) || [];

// Evita duplicados consecutivos
if (!history.length || history[history.length - 1] !== currentPage) {
    history.push(currentPage);
    sessionStorage.setItem('navigationHistory', JSON.stringify(history));
}

function volver() {
    const navigationHistory = JSON.parse(sessionStorage.getItem('navigationHistory')) || [];

    // Si hay más de una página en el historial
    if (navigationHistory.length > 1) {
        const previousPage = navigationHistory[navigationHistory.length - 2];

        // Verifica si la página anterior es "views/inicio.php"
        if (!previousPage.includes('views/inicio.php')) {
        navigationHistory.pop(); // Quita la página actual
        sessionStorage.setItem('navigationHistory', JSON.stringify(navigationHistory));
        window.location.href = previousPage; // Redirige a la página anterior
        }
    }
}