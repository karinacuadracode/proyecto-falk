// RF-27: antes de eliminar una cuenta se pide confirmación, porque no se puede deshacer.
document.querySelectorAll('form[data-confirmar]').forEach((formulario) => {
    formulario.addEventListener('submit', (evento) => {
        if (!window.confirm(formulario.dataset.confirmar)) {
            evento.preventDefault();
        }
    });
});
