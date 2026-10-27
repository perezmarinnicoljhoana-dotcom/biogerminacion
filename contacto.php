
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';

$tituloPagina = 'Contáctanos | BIOGERMINACIÓN';

$mensaje = '';
$tipoMensaje = '';

$nombre = '';
$correo = '';
$asunto = '';
$contenido = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contacto'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');

    if (
        $nombre === '' ||
        strlen($nombre) > 100 ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        strlen($correo) > 150 ||
        strlen($asunto) > 150 ||
        $contenido === '' ||
        strlen($contenido) > 2000
    ) {
        $mensaje = 'Revisa los campos obligatorios y verifica que la información sea válida.';
        $tipoMensaje = 'error';
    } else {
        try {
            $sql = 'INSERT INTO mensajes (nombre, correo, asunto, contenido)
                    VALUES (:nombre, :correo, :asunto, :contenido)';

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':asunto' => $asunto,
                ':contenido' => $contenido
            ]);

            $mensaje = 'Tu mensaje fue enviado correctamente. Gracias por contactar a BIOGERMINACIÓN.';
            $tipoMensaje = 'exito';

            $nombre = '';
            $correo = '';
            $asunto = '';
            $contenido = '';
        } catch (PDOException $e) {
            $mensaje = 'No fue posible enviar tu mensaje. Inténtalo nuevamente más tarde.';
            $tipoMensaje = 'error';
        }
    }
}

require_once __DIR__ . '/partials/header.php';
?>

<main class="contacto seccion">

    <!-- INFORMACIÓN DE CONTACTO -->
    <section class="contacto-info">

        <span class="sobretitulo">ESTAMOS PARA AYUDARTE</span>

        <h1>
            Sigamos aprendiendo
            <br>
            <em>juntos.</em>
        </h1>

        <p class="contacto-descripcion">
            En BIOGERMINACIÓN queremos acompañarte en el aprendizaje
            sobre las semillas, el crecimiento de las plantas y el cuidado
            del medioambiente. Contáctanos para resolver tus dudas,
            compartir sugerencias o conocer nuestros recursos educativos.
        </p>

        <div class="contacto-dato">
            <span class="contacto-icono" aria-hidden="true">✉</span>

            <div>
                <strong>Correo electrónico</strong>
                <small>Preguntas, comentarios y sugerencias</small>
                <a href="mailto:contacto@biogerminacion.example">
                    contacto@biogerminacion.example
                </a>
            </div>
        </div>

        <div class="contacto-dato">
            <span class="contacto-icono" aria-hidden="true">☎</span>

            <div>
                <strong>Teléfono de contacto</strong>
                <small>Atención y orientación educativa</small>
                <a href="tel:+576043456789">
                    (604) 345 6789
                </a>
            </div>
        </div>

        <div class="contacto-dato">
            <span class="contacto-icono" aria-hidden="true">📍</span>

            <div>
                <strong>Ubicación</strong>
                <small>Medellín, Antioquia, Colombia</small>
                <span class="contacto-detalle">
                    Centro de atención educativa BIOGERMINACIÓN
                </span>
            </div>
        </div>

        <div class="contacto-dato">
            <span class="contacto-icono" aria-hidden="true">◷</span>

            <div>
                <strong>Horario de atención</strong>
                <small>Lunes a viernes</small>
                <span class="contacto-detalle">
                    8:00 a. m. a 5:00 p. m.
                </span>
                <span class="contacto-detalle">
                    Sábados: 9:00 a. m. a 12:00 m.
                </span>
            </div>
        </div>

        <!-- REDES SOCIALES DE EJEMPLO -->
        <div class="contacto-redes">
            <h3>Síguenos y aprende con nosotros</h3>

            <p>
                Descubre consejos, actividades y contenidos sobre
                el fascinante mundo de las plantas.
            </p>

            <div class="contacto-redes-enlaces">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                    Instagram
                </a>

                <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">
                    Facebook
                </a>

                <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">
                    YouTube
                </a>
            </div>
        </div>

        <!-- TEMAS DE AYUDA -->
        <div class="contacto-temas">
            <h3>¿Sobre qué puedes consultarnos?</h3>

            <ul>
                <li>Proceso y etapas de la germinación.</li>
                <li>Factores ambientales para el crecimiento de las plantas.</li>
                <li>Material educativo y actividades de aprendizaje.</li>
                <li>Problemas técnicos o sugerencias sobre la página.</li>
            </ul>
        </div>

    </section>

    <!-- FORMULARIO -->
    <section class="contacto-formulario">

        <form
            class="form-contacto"
            method="post"
            action="contacto.php"
        >

            <span class="contacto-form-etiqueta">
                FORMULARIO DE CONTACTO
            </span>

            <h2>Escríbenos</h2>

            <p class="contacto-ayuda">
                Cuéntanos qué necesitas. Los campos marcados con *
                son obligatorios.
            </p>

            <?php if ($mensaje !== ''): ?>
                <div
                    class="alerta <?= htmlspecialchars($tipoMensaje, ENT_QUOTES, 'UTF-8') ?>"
                    role="status"
                    aria-live="polite"
                >
                    <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="contacto" value="1">

            <div class="campo-contacto">
                <label for="nombre">Nombre completo *</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="100"
                    autocomplete="name"
                    placeholder="Escribe tu nombre completo"
                    value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="campo-contacto">
                <label for="correo">Correo electrónico *</label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    maxlength="150"
                    autocomplete="email"
                    placeholder="nombre@correo.com"
                    value="<?= htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

                <small>Ingresa un correo válido para identificar tu consulta.</small>
            </div>

            <div class="campo-contacto">
                <label for="asunto">Tema de la consulta</label>

                <select id="asunto" name="asunto">
                    <option value="">Selecciona un tema</option>

                    <option value="Germinación" <?= $asunto === 'Germinación' ? 'selected' : '' ?>>
                        Germinación de semillas
                    </option>

                    <option value="Crecimiento de plantas" <?= $asunto === 'Crecimiento de plantas' ? 'selected' : '' ?>>
                        Crecimiento de las plantas
                    </option>

                    <option value="Recursos educativos" <?= $asunto === 'Recursos educativos' ? 'selected' : '' ?>>
                        Recursos educativos
                    </option>

                    <option value="Problema técnico" <?= $asunto === 'Problema técnico' ? 'selected' : '' ?>>
                        Problema con la página
                    </option>

                    <option value="Sugerencia" <?= $asunto === 'Sugerencia' ? 'selected' : '' ?>>
                        Sugerencia o comentario
                    </option>

                    <option value="Otro" <?= $asunto === 'Otro' ? 'selected' : '' ?>>
                        Otro tema
                    </option>
                </select>
            </div>

            <div class="campo-contacto">
                <label for="contenido">Tu mensaje *</label>

                <textarea
                    id="contenido"
                    name="contenido"
                    rows="6"
                    maxlength="2000"
                    placeholder="Cuéntanos cómo podemos ayudarte..."
                    required
                ><?= htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8') ?></textarea>

                <small>Máximo 2000 caracteres.</small>
            </div>

            <button
                class="boton boton-verde boton-completo"
                type="submit"
            >
                Enviar mensaje →
            </button>

            <p class="contacto-privacidad">
                Utiliza este formulario para consultas relacionadas
                con BIOGERMINACIÓN. Evita compartir contraseñas
                u otros datos personales innecesarios.
            </p>

        </form>

        <div class="contacto-nota">
            <span aria-hidden="true">🌱</span>

            <div>
                <strong>Aprender también es compartir</strong>

                <p>
                    Tus preguntas y sugerencias nos ayudan a fortalecer
                    los contenidos educativos y a mejorar la experiencia
                    de aprendizaje de BIOGERMINACIÓN.
                </p>
            </div>
        </div>

    </section>

</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
