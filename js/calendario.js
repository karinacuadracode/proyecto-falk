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
// Cada evento tiene nombre, tipo (clase, entrega, examen u otro) y hora.
const eventos = {
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 8)]: [
        { nombre: 'Entrega TP 1 - Programación Web', tipo: 'entrega', hora: '23:59' },
    ],
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 15)]: [
        { nombre: 'Clase de consulta', tipo: 'clase', hora: '18:00' },
        { nombre: 'Parcial de Base de Datos', tipo: 'examen', hora: '19:30' },
    ],
    [fechaClave(hoy.getFullYear(), hoy.getMonth(), 22)]: [
        { nombre: 'Cierre de inscripción a cursos', tipo: 'otro', hora: '12:00' },
    ],
};

// Texto que se muestra en la etiqueta de cada tipo de evento
const TIPOS_EVENTO = { clase: 'Clase', entrega: 'Entrega', examen: 'Examen', otro: 'Otro' };

let anioActual = hoy.getFullYear();
let mesActual = hoy.getMonth(); // 0 = enero

// Vuelve el panel de eventos al mensaje inicial (por ejemplo, al cambiar de mes)
function reiniciarDetalle() {
    const mensaje = document.createElement('p');
    mensaje.textContent = 'Elegí un día para ver tus eventos.';
    detalle.replaceChildren(mensaje);
}

function dibujarCalendario() {
    tituloMes.textContent = `${NOMBRES_MESES[mesActual]} ${anioActual}`;
    grilla.replaceChildren(); 
    reiniciarDetalle();       

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
        boton.setAttribute('aria-pressed', 'false'); 

        if (eventos[clave]) {
            boton.classList.add('con-evento');
            boton.setAttribute('aria-label', `${dia}, tiene eventos`);
        }

        if (anioActual === hoy.getFullYear() && mesActual === hoy.getMonth() && dia === hoy.getDate()) {
            boton.classList.add('hoy');
            boton.setAttribute('aria-current', 'date');
        }

        boton.addEventListener('click', () => seleccionarDia(boton, dia, clave));
        grilla.append(boton);
    }
}

// Marca el día elegido, desmarca el anterior y muestra sus eventos
function seleccionarDia(boton, dia, clave) {
    const anterior = grilla.querySelector('.seleccionado');
    if (anterior) {
        anterior.classList.remove('seleccionado');
        anterior.setAttribute('aria-pressed', 'false');
    }

    boton.classList.add('seleccionado');
    boton.setAttribute('aria-pressed', 'true');
    mostrarEventos(dia, clave);
}

// Muestra los eventos del día elegido
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
    lista.forEach((evento) => {
        const li = document.createElement('li');
        li.className = 'evento';

        // Solo se aceptan los tipos conocidos; cualquier otro se muestra como "Otro"
        const tipo = TIPOS_EVENTO[evento.tipo] ? evento.tipo : 'otro';

        const etiqueta = document.createElement('span');
        etiqueta.className = `etiqueta-evento tipo-${tipo}`;
        etiqueta.textContent = TIPOS_EVENTO[tipo];

        const nombre = document.createElement('span');
        nombre.className = 'evento-nombre';
        nombre.textContent = evento.nombre;

        const hora = document.createElement('span');
        hora.className = 'evento-hora';
        hora.textContent = `${evento.hora} hs`;

        li.append(etiqueta, nombre, hora);
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