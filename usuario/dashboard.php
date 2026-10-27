
<?php
require_once __DIR__ . '/../config/db.php';
require_role('usuario');

$pageTitle = 'Mi espacio | BIOGERMINACIÓN';

$usuarioId = (int) $_SESSION['user']['id'];
$nombreUsuario = $_SESSION['user']['name'] ?? 'Estudiante';

// Contar actividades disponibles.
$n = (int) $pdo->query(
    'SELECT COUNT(*) FROM activities'
)->fetchColumn();

// Contar intentos realizados por el usuario.
$s = $pdo->prepare(
    'SELECT COUNT(*) FROM results WHERE user_id = ?'
);
$s->execute([$usuarioId]);
$done = (int) $s->fetchColumn();

// Consultar las actividades más recientes.
$recent = $pdo->query(
    'SELECT id, title, description
     FROM activities
     ORDER BY id DESC
     LIMIT 3'
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../partials/header.php';
?>

<section class="welcome-panel">
    <span class="sobretitulo">TU ESPACIO DE APRENDIZAJE</span>

    <h1>¡Hola, <?= e($nombreUsuario) ?>!</h1>

    <p>
        Explora el mundo de las semillas, aprende qué necesitan
        para germinar y pon a prueba tus conocimientos con
        actividades interactivas.
    </p>

    <a class="boton boton-verde" href="actividades.php">
        Continuar aprendiendo <span>↗</span>
    </a>
</section>

<!-- RESUMEN DEL PROGRESO -->
<section class="stats">
    <div class="stat">
        <span>Actividades disponibles</span>
        <strong><?= $n ?></strong>
        <small>Para aprender practicando</small>
    </div>

    <div class="stat">
        <span>Intentos realizados</span>
        <strong><?= $done ?></strong>
        <small>Tu progreso de aprendizaje</small>
    </div>
</section>

<!-- ACCESOS PRINCIPALES -->
<section>
    <div class="encabezado-seccion">
        <div>
            <span class="sobretitulo">EMPIEZA POR AQUÍ</span>
            <h2>¿Qué te gustaría <em>hacer hoy?</em></h2>
        </div>

        <p>
            Accede a los recursos y revisa tu progreso cuando quieras.
        </p>
    </div>

    <div class="cards">

        <a class="card" href="contenidos.php">
            <span class="card-icon">01</span>
            <h3>Explorar contenidos</h3>
            <p>
                Aprende sobre la semilla, las etapas de germinación
                y los factores que favorecen el crecimiento.
            </p>
            <span class="card-link">Ver contenidos →</span>
        </a>

        <a class="card" href="actividades.php">
            <span class="card-icon">02</span>
            <h3>Resolver actividades</h3>
            <p>
                Responde las preguntas disponibles y comprueba
                lo que has aprendido.
            </p>
            <span class="card-link">Ver actividades →</span>
        </a>

        <a class="card" href="resultados.php">
            <span class="card-icon">03</span>
            <h3>Consultar resultados</h3>
            <p>
                Revisa tus puntajes e intentos anteriores
                para seguir mejorando.
            </p>
            <span class="card-link">Mi progreso →</span>
        </a>

    </div>
</section>

<!-- ACTIVIDADES RECIENTES -->
<?php if (!empty($recent)): ?>
    <section class="panel">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">PARA PRACTICAR</span>
                <h2>Actividades <em>disponibles</em></h2>
            </div>

            <a class="enlace" href="actividades.php">
                Ver todas →
            </a>
        </div>

        <div class="cards">
            <?php foreach ($recent as $a): ?>
                <article class="card">
                    <span class="sobretitulo">ACTIVIDAD</span>

                    <h3><?= e($a['title']) ?></h3>

                    <p><?= e($a['description']) ?></p>

                    <a
                        class="boton boton-verde"
                        href="resolver.php?id=<?= (int) $a['id'] ?>"
                    >
                        Comenzar ↗
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
