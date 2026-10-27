
<?php
require_once __DIR__ . '/../config/db.php';
require_role('usuario');

$pageTitle = 'Actividades | BIOGERMINACIÓN';

// Consultar todas las actividades registradas.
$sql = "
    SELECT
        a.*,
        COUNT(q.id) AS total_preguntas
    FROM activities a
    LEFT JOIN questions q ON q.activity_id = a.id
    GROUP BY a.id
    ORDER BY a.id DESC
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../partials/header.php';
?>

<section class="seccion actividades-pagina">

    <div class="encabezado-seccion">
        <div>
            <span class="sobretitulo">PON A PRUEBA LO QUE SABES</span>
            <h1>Actividades <em>de aprendizaje</em></h1>
            <p>
                Explora las actividades sobre la germinación,
                identifica lo que necesitan las semillas y comprueba
                tus conocimientos.
            </p>
        </div>

        <a class="boton boton-verde" href="dashboard.php">
            ← Volver a mi espacio
        </a>
    </div>

    <div class="stats">
        <div class="stat">
            <span>Actividades disponibles</span>
            <strong><?= count($rows) ?></strong>
            <small>Aprende practicando</small>
        </div>

        <div class="stat">
            <span>Preguntas disponibles</span>
            <strong>
                <?php
                $totalPreguntas = 0;

                foreach ($rows as $actividad) {
                    $totalPreguntas += (int) $actividad['total_preguntas'];
                }

                echo $totalPreguntas;
                ?>
            </strong>
            <small>Para poner a prueba tus conocimientos</small>
        </div>
    </div>

    <?php if (!empty($rows)): ?>

        <div class="cards">
            <?php foreach ($rows as $indice => $r): ?>

                <article class="card actividad-card">

                    <span class="card-icon">
                        <?= str_pad((string) ($indice + 1), 2, '0', STR_PAD_LEFT) ?>
                    </span>

                    <span class="sobretitulo">ACTIVIDAD DE APRENDIZAJE</span>

                    <h2><?= e($r['title']) ?></h2>

                    <p>
                        <?= e($r['description'] ?? 'Explora este tema y fortalece tus conocimientos sobre la germinación.') ?>
                    </p>

                    <div class="actividad-informacion">
                        <span>
                            <?= (int) $r['total_preguntas'] ?>
                            <?= (int) $r['total_preguntas'] === 1 ? 'pregunta' : 'preguntas' ?>
                        </span>
                    </div>

                    <?php if ((int) $r['total_preguntas'] > 0): ?>

                        <a
                            class="boton boton-verde"
                            href="resolver.php?id=<?= (int) $r['id'] ?>"
                        >
                            Comenzar actividad →
                        </a>

                    <?php else: ?>

                        <span class="actividad-pendiente">
                            Próximamente disponible
                        </span>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="panel actividades-vacias">
            <h2>Aún no hay actividades disponibles</h2>

            <p>
                En este momento no hay actividades registradas.
                Vuelve más tarde para consultar las nuevas actividades
                de aprendizaje.
            </p>

            <a class="boton boton-verde" href="dashboard.php">
                Regresar a mi espacio
            </a>
        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
