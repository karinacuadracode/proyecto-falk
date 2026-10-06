const formulario = document.getElementById('form-registro');
const mensaje = document.getElementById('mensaje-error');
const boton = formulario.querySelector('button');

function mostrarMensaje(texto, esExito = false) {
    // textContent (nunca innerHTML): si llega un <script>, se muestra como texto y no se ejecuta (evita XSS).
    mensaje.textContent = texto;
    mensaje.classList.toggle('exito', esExito);
}

formulario.addEventListener('submit', async (evento) => {
    evento.preventDefault();
    mostrarMensaje('');

    // Validación rápida para el usuario. NO es seguridad: el PHP vuelve a validar todo.
    if (!formulario.checkValidity()) {
        mostrarMensaje('Revisá los campos: usuario de 3 a 30 caracteres, email válido y contraseña de al menos 8.');
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

        mostrarMensaje(datos.message, datos.success);

        if (datos.success) {
            formulario.reset();
            // RF-09: con la cuenta creada, el usuario va al Login para verificar sus credenciales.
            // El botón queda deshabilitado hasta que cambia la página (evita un segundo envío).
            setTimeout(() => {
                window.location.href = '../index.html'; // destino fijo (evita open redirect)
            }, 1500);
            return;
        }

        boton.disabled = false; // hubo un error de datos: se puede corregir y reintentar
    } catch (error) {
        mostrarMensaje('No pudimos conectar con el servidor. Intentá nuevamente.');
        boton.disabled = false;
    }
});