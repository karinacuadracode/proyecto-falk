const formulario = document.getElementById('form-login');
const mensaje = document.getElementById('mensaje-error');
const boton = formulario.querySelector('button');

function mostrarMensaje(texto, esExito = false) {
    mensaje.textContent = texto; // textContent, nunca innerHTML (evita XSS)
    mensaje.classList.toggle('exito', esExito);
}

// RN-04: si la sesión se cerró por inactividad, se avisa.
// RF-09: si viene de crear la cuenta, se confirma el registro.
const motivo = new URLSearchParams(window.location.search).get('motivo');
if (motivo === 'inactividad') {
    mostrarMensaje('Tu sesión se cerró por inactividad. Volvé a ingresar.');
} else if (motivo === 'registro') {
    mostrarMensaje('Cuenta creada. Ya podés iniciar sesión.', true);
}

formulario.addEventListener('submit', async (evento) => {
    evento.preventDefault();
    mostrarMensaje('');

    if (!formulario.checkValidity()) {
        mostrarMensaje('Completá usuario y contraseña.');
        return;
    }

    boton.disabled = true;

    try {
        const respuesta = await fetch('controllers/login-controller.php', {
            method: 'POST',
            body: new FormData(formulario),
        });
        const datos = await respuesta.json();

        if (datos.success) {
            window.location.href = 'pages-back/home.php'; // destino fijo (evita open redirect)
        } else {
            mostrarMensaje(datos.message);
            formulario.contrasena.value = '';
        }
    } catch (error) {
        mostrarMensaje('No pudimos conectar con el servidor. Intentá nuevamente.');
    } finally {
        boton.disabled = false;
    }
});