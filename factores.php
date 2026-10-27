
<?php
require_once __DIR__ . '/config/db.php';

$tituloPagina = 'Factores de la germinación | BIOGERMINACIÓN';

$e = static function ($texto): string {
    return htmlspecialchars(
        (string) ($texto ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$introduccion = null;
$factores = [];
$recordatorio = null;
$errorConsulta = false;
$mensajeError = '';

try {
    $consulta = $pdo->prepare("
        SELECT
            id,
            seccion,
            tipo,
            titulo,
            descripcion,
            detalle,
            imagen,
            texto_imagen,
            dato,
            orden
        FROM germinacion_contenidos
        WHERE seccion = :seccion
          AND activo = 1
        ORDER BY orden ASC, id ASC
    ");

    $consulta->execute([
        'seccion' => 'factores'
    ]);

    $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);

    foreach ($registros as $registro) {
        switch ($registro['tipo']) {
            case 'intro':
                $introduccion = $registro;
                break;

            case 'factor':
                $factores[] = $registro;
                break;

            case 'recordatorio':
                $recordatorio = $registro;
                break;
        }
    }
} catch (PDOException $ex) {
    error_log(
        'Error al consultar los factores de germinación: '
        . $ex->getMessage()
    );

    $errorConsulta = true;
    $mensajeError = $ex->getMessage();
}

require_once __DIR__ . '/partials/header.php';
?>

<div class="factores-pagina">
    <section class="factores-seccion">
        <div class="seccion factores-interior">

            <div class="encabezado-seccion">
                <div>
                    <span class="sobretitulo">
                        LO QUE NECESITA UNA SEMILLA
                    </span>

                    <h1>
                        <?php if ($introduccion): ?>
                            <?= $e($introduccion['titulo']) ?>
                        <?php else: ?>
                            Factores que hacen posible el
                            <em>crecimiento</em>
                        <?php endif; ?>
                    </h1>
                </div>

                <p>
                    <?= $e(
                        $introduccion['descripcion']
                        ?? 'Conoce las condiciones que favorecen la germinación de las semillas.'
                    ) ?>
                </p>
            </div>

            <?php if ($introduccion && !empty($introduccion['detalle'])): ?>
                <p class="factores-introduccion">
                    <?= nl2br($e($introduccion['detalle'])) ?>
                </p>
            <?php endif; ?>

            <?php if ($errorConsulta): ?>

                <div style="
                    padding: 18px;
                    margin: 20px 0;
                    background: #ffe5e5;
                    color: #900;
                    border: 1px solid #e5a0a0;
                    border-radius: 8px;
                    overflow-wrap: anywhere;
                ">
                    <strong>Error al cargar los factores:</strong>
                    <p><?= $e($mensajeError) ?></p>
                    <p>
                        Revisa la conexión y las columnas de la tabla
                        germinacion_contenidos.
                    </p>
                </div>

            <?php elseif (!empty($factores)): ?>

                <div class="factores-grid">

                    <?php foreach ($factores as $indice => $factor): ?>
                        <article class="factor">

                            <?php if (!empty($factor['imagen'])): ?>
                                <figure class="factor-imagen">
                                    <img
                                        src="<?= $e($factor['imagen']) ?>"
                                        alt="<?= $e(
                                            $factor['texto_imagen']
                                            ?: $factor['titulo']
                                        ) ?>"
                                        loading="lazy"
                                    >
                                </figure>
                            <?php endif; ?>

                            <div class="factor-contenido">

                                <span class="factor-tag">
                                    <?= $e(
                                        str_pad(
                                            (string) ($indice + 1),
                                            2,
                                            '0',
                                            STR_PAD_LEFT
                                        )
                                    ) ?>
                                    /
                                    <?= $e(
                                        mb_strtoupper(
                                            $factor['titulo'],
                                            'UTF-8'
                                        )
                                    ) ?>
                                </span>

                                <h2>
                                    <?= $e($factor['titulo']) ?>
                                </h2>

                                <?php if (!empty($factor['descripcion'])): ?>
                                    <p>
                                        <?= nl2br(
                                            $e($factor['descripcion'])
                                        ) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($factor['detalle'])): ?>
                                    <div class="factor-linea"></div>

                                    <small>
                                        <strong>Recomendación:</strong>
                                        <?= nl2br(
                                            $e($factor['detalle'])
                                        ) ?>
                                    </small>
                                <?php endif; ?>

                                <?php if (!empty($factor['dato'])): ?>
                                    <div class="factor-dato">
                                        <strong>¿Sabías que...?</strong>

                                        <p>
                                            <?= nl2br(
                                                $e($factor['dato'])
                                            ) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </article>
                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p class="factores-introduccion">
                    En este momento no hay factores publicados.
                </p>

            <?php endif; ?>

            <?php if ($recordatorio): ?>
                <div class="factores-recordatorio">

                    <div
                        class="factores-recordatorio-icono"
                        aria-hidden="true"
                    >
                        i
                    </div>

                    <div>
                        <h2>
                            <?= $e($recordatorio['titulo']) ?>
                        </h2>

                        <?php if (!empty($recordatorio['descripcion'])): ?>
                            <p>
                                <?= nl2br(
                                    $e($recordatorio['descripcion'])
                                ) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>

            <div class="factores-enlaces">
                <a class="factor-boton" href="germinacion.php">
                    &larr; Volver a la germinación
                </a>

                <a
                    class="factor-boton factor-boton-secundario"
                    href="cuestionario.php"
                >
                    Poner a prueba lo aprendido &rarr;
                </a>
            </div>

        </div>
    </section>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
