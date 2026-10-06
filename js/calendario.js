const tituloMes = document.getElementById('titulo-mes');
const grilla = document.getElementById('grilla-calendario');
const detalle = document.getElementById('detalle-eventos');
const botonAnterior = document.getElementById('mes-anterior');
const botonSiguiente = document.getElementById('mes-siguiente');

const NOMBRES_MESES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

// Arma la clave "AAAA-MM-DD" para buscar los eventos de un día.
function fechaClave(anio, mes, dia) {
    return `${anio}-${String(mes + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
}

const hoy = new Date();

// Eventos de ejemplo (provisorio): cuando exista la tabla de eventos, van a venir de evento-controller.php.
const eventos = {
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 8)]: ['Entrega TP 1 - Programación Web'],
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 15)]: ['Clase de consulta', 'Parcial de Base de Datos'],
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 22)]: ['Cierre de inscripción a cursos'],
};

let anioActual = hoy.getFullYear();
let mesActual = hoy.getMonth(); // 0 = enero

// Vuelve el panel de eventos al mensaje inicial (por ejemplo, al cambiar de mes).
function reiniciarDetalle() {
    const mensaje = document.createElement('p');
    mensaje.textContent = 'Elegí un día para ver tus eventos.';
    detalle.replaceChildren(mensaje);
}

function dibujarCalendario() {
    tituloMes.textContent = `${NOMBRES_MESES[mesActual]} ${anioActual}`;
    grilla.replaceChildren(); // borra los días del mes anterior
    reiniciarDetalle();       // y el detalle del día que se había elegido

    // getDay() da 0 = domingo; lo pasamos a semana que empieza el lunes (0 = lunes).
    const primerDia = (new Date(anioActual, mesActual, 1).getDay() + 6) % 7;
    const diasDelMes = new Date(anioActual, mesActual + 1, 0).getDate();

    // Espacios vacíos antes del día 1.
    for (let i = 0; i < primerDia; i++) {
        grilla.append(document.createElement('span'));
    }

    for (let dia = 1; dia <= diasDelMes; dia++) {
        const clave = fechaClave(anioActual, mesActual, dia);
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'calendario-dia';
        boton.textContent = dia;

        if (eventos[clave]) {
            boton.classList.add('con-evento');
            boton.setAttribute('aria-label', `${dia}, tiene eventos`);
        }

        if (anioActual === hoy.getFullYear() && mesActual === hoy.getMonth() && dia === hoy.getDate()) {
            boton.classList.add('hoy');
            boton.setAttribute('aria-current', 'date');
        }

        boton.addEventListener('click', () => mostrarEventos(dia, clave));
        grilla.append(boton);
    }
}

// Muestra los eventos del día elegido. Usa textContent (nunca innerHTML) para evitar XSS.
function mostrarEventos(dia, clave) {
    detalle.replaceChildren();

    const titulo = document.createElement('h3');
    titulo.textContent = `${dia} de ${NOMBRES_MESES[mesActual].toLowerCase()}`;
    detalle.append(titulo);

    const lista = eventos[clave];
    if (!lista) {
        const vacio = document.createElement('p');
        vacio.textContent = 'No hay eventos para este día.';
        detalle.append(vacio);
        return;
    }

    const ul = document.createElement('ul');
    lista.forEach((nombre) => {
        const li = document.createElement('li');
        li.textContent = nombre;
        ul.append(li);
    });
    detalle.append(ul);
}

botonAnterior.addEventListener('click', () => {
    mesActual--;
    if (mesActual < 0) {
        mesActual = 11;
        anioActual--;
    }
    dibujarCalendario();
});

botonSiguiente.addEventListener('click', () => {
    mesActual++;
    if (mesActual > 11) {
        mesActual = 0;
        anioActual++;
    }
    dibujarCalendario();
});

dibujarCalendario();