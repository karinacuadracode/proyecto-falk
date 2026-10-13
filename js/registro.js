const formulario = document.getElementById('form-registro');
const mensaje = document.getElementById('mensaje-error');
const boton = formulario.querySelector('button');
const fechaNacimiento = document.getElementById('fecha-nacimiento');

// RF-26: la fecha de nacimiento no puede ser futura (el calendario no deja elegir después de hoy).
const hoy = new Date();
fechaNacimiento.max = new Date(hoy.getTime() - hoy.getTimezoneOffset() * 60000).toISOString().slice(0, 10);

function mostrarMensaje(texto, esExito = false) {
    mensaje.textContent = texto;
    mensaje.classList.toggle('exito', esExito);
}

formulario.addEventListener('submit', async (evento) => {
    evento.preventDefault();
    mostrarMensaje('');

    // Validación rápida para el usuario. NO es seguridad: el PHP vuelve a validar todo.
    if (!formulario.checkValidity()) {
        mostrarMensaje(fechaNacimiento.validity.valid
            ? 'Revisá los campos: usuario de 3 a 30 caracteres, email válido y contraseña de al menos 8.'
            : 'La fecha de nacimiento no es válida (no puede ser posterior a hoy). Si no querés cargarla, dejala vacía.');
        return;
    }

    // RN-07: al menos una mayúscula, un número y un símbolo (el mínimo de 8 ya lo controla minlength).
    const contrasena = document.getElementById('contrasena').value;
    if (!/[A-Z]/.test(contrasena) || !/[0-9]/.test(contrasena) || !/[^a-zA-Z0-9]/.test(contrasena)) {
        mostrarMensaje('La contraseña debe tener al menos 8 caracteres, con una mayúscula, un número y un símbolo.');
        return;
    }

    boton.disabled = true; // evita el doble envío por doble clic

    try {
        const respuesta = await fetch('../controllers/register-controller.php', {
            method: 'POST',
            body: new FormData(formulario),
        });
        const datos = await respuesta.json();

        if (datos.success) {
            // Con la cuenta creada, el usuario va directo al Login para verificar sus credenciales.
            // El mensaje de éxito lo muestra login.js. El botón queda deshabilitado (evita un segundo envío).
            window.location.href = '../index.html?motivo=registro';
            return;
        }

        mostrarMensaje(datos.message);
        boton.disabled = false; 
    } catch (error) {
        mostrarMensaje('No pudimos conectar con el servidor. Intentá nuevamente.');
        boton.disabled = false;
    }
});