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
            setTimeout(() => {
                window.location.href = '../index.html';
            }, 1500);
        }
    } catch (error) {
        mostrarMensaje('No pudimos conectar con el servidor. Intentá nuevamente.');
    } finally {
        boton.disabled = false;
    }
});