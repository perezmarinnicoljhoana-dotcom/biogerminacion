
document.addEventListener('DOMContentLoaded', () => {
    // Menú responsive
    const botonMenu = document.querySelector('.menu-toggle');
    const menu = document.querySelector('#menu-principal');

    if (botonMenu && menu) {
        botonMenu.addEventListener('click', () => {
            const abierto = menu.classList.contains('abierto');

            menu.classList.toggle('abierto', !abierto);

            botonMenu.setAttribute('aria-expanded', String(!abierto));
            botonMenu.setAttribute(
                'aria-label',
                abierto ? 'Abrir menú' : 'Cerrar menú'
            );
        });

        // Cerrar el menú al seleccionar un enlace
        menu.querySelectorAll('a').forEach(enlace => {
            enlace.addEventListener('click', () => {
                menu.classList.remove('abierto');
                botonMenu.setAttribute('aria-expanded', 'false');
                botonMenu.setAttribute('aria-label', 'Abrir menú');
            });
        });

        // Cerrar el menú al volver a una pantalla de escritorio
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                menu.classList.remove('abierto');
                botonMenu.setAttribute('aria-expanded', 'false');
                botonMenu.setAttribute('aria-label', 'Abrir menú');
            }
        });
    }

    // Estado de envío de los formularios
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const boton = form.querySelector(
                'button[type="submit"], button:not([type])'
            );

            if (boton && form.checkValidity()) {
                boton.dataset.originalText = boton.textContent;
                boton.textContent = 'Procesando…';
                boton.disabled = true;
            }
        });
    });
});
