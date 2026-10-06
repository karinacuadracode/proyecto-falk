const botonMenu = document.getElementById('boton-menu');
const menu = document.getElementById('menu-principal');

function abrirMenu() {
    menu.hidden = false;
    botonMenu.setAttribute('aria-expanded', 'true');
    botonMenu.setAttribute('aria-label', 'Cerrar menú');
    menu.querySelector('a').focus(); // accesibilidad: el foco pasa al primer link
}

function cerrarMenu() {
    menu.hidden = true;
    botonMenu.setAttribute('aria-expanded', 'false');
    botonMenu.setAttribute('aria-label', 'Abrir menú');
}

// El botón ☰ abre o cierra según cómo esté.
botonMenu.addEventListener('click', () => {
    if (menu.hidden) {
        abrirMenu();
    } else {
        cerrarMenu();
    }
});

// Se cierra con la tecla Escape.
document.addEventListener('keydown', (evento) => {
    if (evento.key === 'Escape' && !menu.hidden) {
        cerrarMenu();
        botonMenu.focus();
    }
});

// Se cierra al hacer clic afuera del menú.
document.addEventListener('click', (evento) => {
    if (!menu.hidden && !menu.contains(evento.target) && !botonMenu.contains(evento.target)) {
        cerrarMenu();
    }
});