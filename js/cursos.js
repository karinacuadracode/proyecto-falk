document.addEventListener("DOMContentLoaded", function() {
    const btnMostrarMas = document.getElementById("btn-mostrar-mas");
    const cursosExtra = document.querySelectorAll(".curso-extra");

    if (btnMostrarMas && cursosExtra.length > 0) {
        btnMostrarMas.addEventListener("click", function() {
            // Verificamos si tienen la clase 'oculto'
            const estanOcultos = cursosExtra[0].classList.contains("oculto");

            cursosExtra.forEach(function(curso) {
                if (estanOcultos) {
                    curso.classList.remove("oculto");
                } else {
                    curso.classList.add("oculto");
                }
            });

            // Giramos la flecha agregando o quitando clase
            if (estanOcultos) {
                btnMostrarMas.classList.add("girado");
            } else {
                btnMostrarMas.classList.remove("girado");
            }
        });
    }
});