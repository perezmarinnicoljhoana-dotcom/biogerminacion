
<?php
$tituloPagina = 'Recursos educativos | BIOGERMINACIÓN';
require_once __DIR__ . '/partials/header.php';

$e = static fn(string $texto): string => htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');

$videoGuia = [
    'id'       => 'HUKkF8jVYmY',
    'titulo'   => 'La germinación: ¿cómo crece una planta?',
    'fuente'   => 'GuerrerosPlanet',
    'duracion' => 'Video educativo',
];

$video = [
    'id'       => 'gQ5y5gT-Wbc',
    'titulo'   => '¿Cómo nace y crece una planta?',
    'fuente'   => 'Isa Te Explica',
    'duracion' => 'Video educativo',
];

$enlaces = [
    [
        'fuente' => 'COLOMBIA APRENDE',
        'titulo' => 'Germinación y crecimiento de una planta',
        'texto'  => 'Consulta un recurso educativo sobre el proceso de germinación y el crecimiento de las plantas.',
        'url'    => 'https://redaprende.colombiaaprende.edu.co/metadatos/recurso/germinacion-y-crecimiento-de-una-planta/'
    ],
    [
        'fuente' => 'RECURSOS EDUCATIVOS',
        'titulo' => 'Nacimiento de las semillas',
        'texto'  => 'Observa cómo las semillas comienzan a crecer y cómo se desarrollan las raíces y las primeras hojas.',
        'url'    => 'https://www3.gobiernodecanarias.org/medusa/ecoescuela/recursosdigitales/2019/05/20/nacimiento-de-las-semillas-1/'
    ],
];

$diasBitacora = 7;
?>

<section class="seccion recursos rec-no-imprimir" aria-labelledby="titulo-recursos">
    <div class="encabezado-seccion">
        <div>
            <span class="sobretitulo">APRENDE A TU RITMO</span>
            <h1 id="titulo-recursos">Recursos para <em>explorar</em></h1>
        </div>
        <p>
            Complementa tus conocimientos con videos y materiales educativos
            sobre la germinación y el crecimiento de las plantas.
        </p>
    </div>

    <div class="recursos-grid">

        <article class="recurso recurso-imagen recurso-video">
            <button
                type="button"
                class="video-placeholder rec-miniatura"
                data-video="<?= $e($videoGuia['id']) ?>"
                data-titulo="<?= $e($videoGuia['titulo']) ?>"
                aria-label="Reproducir <?= $e($videoGuia['titulo']) ?>"
            >
                <img
                    src="https://i.ytimg.com/vi/<?= $e($videoGuia['id']) ?>/hqdefault.jpg"
                    alt=""
                    loading="lazy"
                >
                <span aria-hidden="true">▶</span>
                <small>GUÍA VISUAL · VIDEO EDUCATIVO</small>
            </button>

            <div>
                <span class="recurso-tipo">GUÍA VISUAL</span>
                <h2>Observa el crecimiento</h2>
                <p>
                    Conoce las etapas de la germinación y observa cómo una
                    semilla comienza a desarrollar sus raíces, tallo y hojas.
                </p>
                <p class="rec-meta">
                    <?= $e($videoGuia['fuente']) ?> ·
                    <?= $e($videoGuia['duracion']) ?>
                </p>

                <div class="rec-acciones">
                    <a
                        href="https://www.youtube.com/watch?v=<?= $e($videoGuia['id']) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >Ver en YouTube ↗</a>

                    <a href="germinacion.php">Conocer las etapas →</a>
                </div>
            </div>
        </article>

        <article class="recurso recurso-video">
            <button
                type="button"
                class="video-placeholder rec-miniatura"
                data-video="<?= $e($video['id']) ?>"
                data-titulo="<?= $e($video['titulo']) ?>"
                aria-label="Reproducir <?= $e($video['titulo']) ?>"
            >
                <img
                    src="https://i.ytimg.com/vi/<?= $e($video['id']) ?>/hqdefault.jpg"
                    alt=""
                    loading="lazy"
                >
                <span aria-hidden="true">▶</span>
                <small>APRENDE CON VIDEOS</small>
            </button>

            <div>
                <span class="recurso-tipo">PARA PROFUNDIZAR</span>
                <h2>Aprende con videos</h2>
                <p>
                    Descubre cómo nacen las plantas y qué cambios experimentan
                    durante su crecimiento, desde la semilla hasta los primeros brotes.
                </p>
                <p class="rec-meta">
                    <?= $e($video['fuente']) ?> ·
                    <?= $e($video['duracion']) ?>
                </p>

                <div class="rec-acciones">
                    <a
                        href="https://www.youtube.com/watch?v=<?= $e($video['id']) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >Ver en YouTube ↗</a>

                    <a
                        href="https://www.youtube.com/results?search_query=germinacion+y+crecimiento+de+las+plantas"
                        target="_blank"
                        rel="noopener noreferrer"
                    >Más videos ↗</a>
                </div>
            </div>
        </article>

    </div>
</section>

<div class="rec-fondo">
    <section class="seccion" id="bitacora" aria-labelledby="titulo-bitacora">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">PARA IMPRIMIR</span>
                <h2 id="titulo-bitacora">Bitácora de <em>observación</em></h2>
            </div>
            <p>
                Registra diariamente los cambios que observas en tu semilla.
                La observación te ayudará a comprender su proceso de desarrollo.
            </p>
        </div>

        <div class="rec-bitacora-acciones">
            <button
                type="button"
                class="boton boton-verde"
                onclick="window.print()"
            >Imprimir bitácora</button>
        </div>

        <div class="table-wrap rec-tabla">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Día</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">¿Qué observo?</th>
                        <th scope="col">Medida o dibujo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($dia = 1; $dia <= $diasBitacora; $dia++): ?>
                        <tr>
                            <th scope="row"><?= $dia ?></th>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <p class="nota">
            Sugerencia: registra la fecha, los cambios de tamaño,
            la aparición de la raíz y el crecimiento del tallo y las hojas.
        </p>
    </section>
</div>

<section class="seccion rec-no-imprimir" aria-labelledby="titulo-mas">
    <div class="encabezado-seccion">
        <div>
            <span class="sobretitulo">SIGUE EXPLORANDO</span>
            <h2 id="titulo-mas">Más para <em>explorar</em></h2>
        </div>
        <p>
            Amplía tus conocimientos con otros recursos educativos
            sobre el desarrollo de las plantas.
        </p>
    </div>

    <div class="rec-enlaces">
        <?php foreach ($enlaces as $recurso): ?>
            <a
                class="rec-enlace"
                href="<?= $e($recurso['url']) ?>"
                target="_blank"
                rel="noopener noreferrer"
            >
                <span class="rec-enlace-fuente">
                    <?= $e($recurso['fuente']) ?>
                </span>

                <h3><?= $e($recurso['titulo']) ?></h3>
                <p><?= $e($recurso['texto']) ?></p>
                <span class="rec-ir">Abrir recurso ↗</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section
    class="seccion rec-cierre-seccion rec-no-imprimir"
    aria-labelledby="titulo-cierre"
>
    <div class="rec-cierre">
        <div>
            <h2 id="titulo-cierre">
                ¿Listo para poner a prueba lo <em>aprendido</em>?
            </h2>
            <p>
                Responde el cuestionario y comprueba tus conocimientos
                sobre la germinación.
            </p>
        </div>

        <a class="boton boton-verde" href="cuestionario.php">
            Ir al cuestionario →
        </a>
    </div>
</section>

<dialog
    class="rec-modal"
    id="modal-video"
    aria-labelledby="modal-video-titulo"
>
    <div class="rec-modal-cabecera">
        <h2 id="modal-video-titulo">Video educativo</h2>

        <button
            type="button"
            class="rec-modal-cerrar"
            aria-label="Cerrar video"
        >✕</button>
    </div>

    <div class="rec-modal-video"></div>

    <p class="rec-modal-pie">
        ¿No se reproduce?
        <a
            id="modal-video-enlace"
            href="#"
            target="_blank"
            rel="noopener noreferrer"
        >Abrir en YouTube ↗</a>
    </p>
</dialog>

<script>
(function () {
    const modal = document.getElementById('modal-video');

    if (!modal || typeof modal.showModal !== 'function') {
        return;
    }

    const contenedor = modal.querySelector('.rec-modal-video');
    const titulo = document.getElementById('modal-video-titulo');
    const enlace = document.getElementById('modal-video-enlace');
    const botonCerrar = modal.querySelector('.rec-modal-cerrar');

    document.querySelectorAll('[data-video]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            const id = boton.getAttribute('data-video');
            const nombreVideo =
                boton.getAttribute('data-titulo') || 'Video educativo';

            const iframe = document.createElement('iframe');

            iframe.src =
                'https://www.youtube-nocookie.com/embed/' +
                encodeURIComponent(id) +
                '?autoplay=1&rel=0';

            iframe.title = nombreVideo;
            iframe.allow =
                'autoplay; encrypted-media; picture-in-picture; fullscreen';
            iframe.allowFullscreen = true;
            iframe.loading = 'eager';
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';

            contenedor.replaceChildren(iframe);
            titulo.textContent = nombreVideo;

            enlace.href =
                'https://www.youtube.com/watch?v=' +
                encodeURIComponent(id);

            modal.showModal();
        });
    });

    function cerrarModal() {
        modal.close();
    }

    botonCerrar.addEventListener('click', cerrarModal);

    modal.addEventListener('click', function (evento) {
        if (evento.target === modal) {
            cerrarModal();
        }
    });

    modal.addEventListener('close', function () {
        contenedor.replaceChildren();
    });
})();
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
