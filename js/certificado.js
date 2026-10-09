// Abre el diálogo de impresión del navegador (desde ahí se puede guardar como PDF)
const botonImprimir = document.getElementById('boton-imprimir');

if (botonImprimir) {
    botonImprimir.addEventListener('click', () => window.print());
}