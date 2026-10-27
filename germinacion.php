
<?php
require_once __DIR__ . '/config/db.php';

$tituloPagina = 'Germinación | BIOGERMINACIÓN';

$e = static fn($texto): string =>
    htmlspecialchars((string)($texto ?? ''), ENT_QUOTES, 'UTF-8');

$obtener = static function (string $tipo) use ($pdo): array {
    $consulta = $pdo->prepare("
        SELECT id, seccion, tipo, titulo, descripcion, detalle,
               imagen, texto_imagen, dato, orden
        FROM germinacion_contenidos
        WHERE seccion = 'germinacion'
          AND tipo = ?
          AND activo = 1
        ORDER BY orden ASC, id ASC
    ");

    $consulta->execute([$tipo]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
};

$obtenerUno = static function (string $tipo) use ($obtener): ?array {
    $registros = $obtener($tipo);
    return $registros[0] ?? null;
};

try {
    $introduccion = $obtenerUno('intro');

    $condicionesBD = $obtener('condicion');
    $partesBD = $obtener('parte');
    $etapasBD = $obtener('etapa');
    $tiposBD = $obtener('tipo_germinacion');
    $materialesBD = $obtener('material');
    $pasosBD = $obtener('paso');
    $glosarioBD = $obtener('glosario');
    $preguntasBD = $obtener('pregunta');

} catch (PDOException $ex) {
    error_log($ex->getMessage());

    $introduccion = null;
    $condicionesBD = [];
    $partesBD = [];
    $etapasBD = [];
    $tiposBD = [];
    $materialesBD = [];
    $pasosBD = [];
    $glosarioBD = [];
    $preguntasBD = [];
}

/*
|--------------------------------------------------------------------------
| Contenido predeterminado
|--------------------------------------------------------------------------
| Se utiliza cuando todavía no hay registros para esa categoría.
*/

$condicionesPredeterminadas = [
    ['Agua', 'Activa la semilla y ablanda su cubierta.'],
    ['Oxígeno', 'Permite obtener energía de las reservas.'],
    ['Temperatura adecuada', 'Cada especie tiene un rango en el que germina mejor.'],
];

$partesPredeterminadas = [
    [
        'etiqueta' => 'PROTECCIÓN',
        'titulo' => 'Cubierta (testa)',
        'descripcion' => 'Capa externa que protege al embrión de golpes, sequía y microorganismos hasta que las condiciones sean favorables.'
    ],
    [
        'etiqueta' => 'RESERVA',
        'titulo' => 'Alimento almacenado',
        'descripcion' => 'Almidones, grasas y proteínas guardadas en el endospermo o en los cotiledones. Alimentan al embrión hasta que pueda hacer fotosíntesis.'
    ],
    [
        'etiqueta' => 'FUTURA RAÍZ',
        'titulo' => 'Radícula',
        'descripcion' => 'Parte del embrión que se convierte en la raíz. Es la primera en salir de la semilla.'
    ],
    [
        'etiqueta' => 'FUTURO TALLO',
        'titulo' => 'Plúmula',
        'descripcion' => 'Pequeño brote del embrión que da origen al tallo y a las primeras hojas.'
    ],
    [
        'etiqueta' => 'PRIMERAS HOJAS',
        'titulo' => 'Cotiledones',
        'descripcion' => 'Hojas embrionarias. En muchas especies almacenan alimento; en otras funcionan como las primeras hojas de la plántula.'
    ],
];

$etapasPredeterminadas = [
    [
        'numero' => '01',
        'titulo' => 'Hidratación',
        'descripcion' => 'La semilla absorbe agua (imbibición), se hincha y su cubierta se ablanda. El agua activa las enzimas que transforman las reservas en energía.',
        'detalle' => 'La semilla aumenta de tamaño y su cubierta se afloja o se arruga.',
        'imagen' => 'img/germinacion.jpg',
        'texto_imagen' => 'Semilla durante la hidratación.'
    ],
    [
        'numero' => '02',
        'titulo' => 'Salida de la raíz',
        'descripcion' => 'La radícula rompe la cubierta y crece hacia abajo. Fija la planta y empieza a absorber agua.',
        'detalle' => 'Aparece una pequeña punta clara que sale de la semilla.',
        'imagen' => 'img/etapa-raiz.jpg',
        'texto_imagen' => 'Salida de la raíz durante la germinación.'
    ],
    [
        'numero' => '03',
        'titulo' => 'Desarrollo del brote',
        'descripcion' => 'El tallo se alarga hacia arriba en busca de la luz y pueden aparecer las primeras hojas.',
        'detalle' => 'Se observa un tallito que se endereza al salir.',
        'imagen' => 'img/etapa-brote.jpg',
        'texto_imagen' => 'Desarrollo del brote.'
    ],
    [
        'numero' => '04',
        'titulo' => 'Plántula',
        'descripcion' => 'La planta joven despliega sus primeras hojas y comienza a hacer fotosíntesis, dependiendo cada vez menos de las reservas.',
        'detalle' => 'Aparecen hojas verdes y raíces cada vez más desarrolladas.',
        'imagen' => 'img/plantula.jpg',
        'texto_imagen' => 'Plántula con hojas y raíces.'
    ],
];

$tiposPredeterminados = [
    [
        'titulo' => 'Epigea',
        'descripcion' => 'Los cotiledones salen del suelo cuando el tallo se alarga por debajo de ellos.',
        'detalle' => 'Ejemplo: frijol.',
        'imagen' => 'img/germinacion-epigea.jpg',
        'texto_imagen' => 'Ejemplo de germinación epigea.'
    ],
    [
        'titulo' => 'Hipogea',
        'descripcion' => 'Los cotiledones permanecen bajo tierra y solo sale el tallo con las primeras hojas.',
        'detalle' => 'Ejemplos: arveja y haba.',
        'imagen' => 'img/germinacion-hipogea.jpg',
        'texto_imagen' => 'Ejemplo de germinación hipogea.'
    ],
];

$materialesPredeterminados = [
    'Un frasco de vidrio transparente o una bolsa transparente',
    'Algodón o papel absorbente',
    '3 o 4 semillas de frijol',
    'Agua',
    'Un cuaderno para anotar o dibujar',
];

$pasosPredeterminados = [
    'Remoja las semillas en agua durante unas horas.',
    'Humedece el algodón, sin empaparlo, y colócalo dentro del frasco.',
    'Pon las semillas entre el algodón y la pared de vidrio para poder observarlas.',
    'Deja el frasco en un lugar templado, con luz indirecta.',
    'Mantén el algodón húmedo y observa cada día. Anota los cambios y dibuja lo que ves.',
];

$glosarioPredeterminado = [
    [
        'titulo' => 'Germinación',
        'descripcion' => 'Proceso por el cual el embrión retoma su crecimiento y la semilla da origen a una nueva planta.'
    ],
    [
        'titulo' => 'Latencia',
        'descripcion' => 'Periodo en el que la semilla está viva pero inactiva, esperando condiciones adecuadas.'
    ],
    [
        'titulo' => 'Imbibición',
        'descripcion' => 'Absorción inicial de agua que hincha la semilla y da comienzo a la germinación.'
    ],
    [
        'titulo' => 'Plántula',
        'descripcion' => 'Planta joven en las primeras etapas después de germinar.'
    ],
    [
        'titulo' => 'Fotosíntesis',
        'descripcion' => 'Proceso con el que la planta usa luz, agua y dióxido de carbono para producir su alimento.'
    ],
];

$preguntasPredeterminadas = [
    [
        'titulo' => '¿Todas las semillas necesitan luz para germinar?',
        'descripcion' => 'No. Muchas germinan en la oscuridad, bajo tierra, y otras necesitan luz; depende de la especie. Cuando el brote sale, la plántula sí necesita luz para crecer.'
    ],
    [
        'titulo' => '¿Por qué una semilla no germina?',
        'descripcion' => 'Las causas más comunes son semillas viejas o dañadas, exceso o falta de agua, una temperatura inadecuada o sembrarlas demasiado profundo. Algunas especies tienen latencia y requieren un tratamiento previo.'
    ],
    [
        'titulo' => '¿Cuánto tarda en germinar una semilla?',
        'descripcion' => 'Varía mucho: desde pocos días hasta semanas o más, según la especie y las condiciones del ambiente.'
    ],
    [
        'titulo' => '¿Se puede germinar sin tierra?',
        'descripcion' => 'Sí. La germinación depende de agua, oxígeno y temperatura. La semilla se alimenta de sus propias reservas; la tierra se necesita después, para aportar nutrientes a la plántula.'
    ],
];

/*
|--------------------------------------------------------------------------
| Preparar datos de la base de datos o los valores predeterminados
|--------------------------------------------------------------------------
*/

$condiciones = $condicionesBD
    ? array_map(static fn($r) => [
        $r['titulo'],
        $r['descripcion'] ?? ''
    ], $condicionesBD)
    : $condicionesPredeterminadas;

$partes = $partesBD
    ? array_map(static fn($r) => [
        'etiqueta' => $r['detalle'] ?: 'PARTE DE LA SEMILLA',
        'titulo' => $r['titulo'],
        'descripcion' => $r['descripcion'] ?? ''
    ], $partesBD)
    : $partesPredeterminadas;

$etapas = $etapasBD
    ? array_map(static fn($r) => [
        'numero' => $r['dato'] ?: '01',
        'titulo' => $r['titulo'],
        'descripcion' => $r['descripcion'] ?? '',
        'detalle' => $r['detalle'] ?? '',
        'imagen' => $r['imagen'] ?: 'assets/img/germinacion.jpg',
        'texto_imagen' => $r['texto_imagen'] ?: $r['titulo']
    ], $etapasBD)
    : $etapasPredeterminadas;

$tipos = $tiposBD
    ? array_map(static fn($r) => [
        'titulo' => $r['titulo'],
        'descripcion' => $r['descripcion'] ?? '',
        'detalle' => $r['detalle'] ?? '',
        'imagen' => $r['imagen'] ?: 'assets/img/germinacion.jpg',
        'texto_imagen' => $r['texto_imagen'] ?: $r['titulo']
    ], $tiposBD)
    : $tiposPredeterminados;

$materiales = $materialesBD
    ? array_map(static fn($r) => $r['titulo'], $materialesBD)
    : $materialesPredeterminados;

$pasosExperimento = $pasosBD
    ? array_map(static fn($r) => $r['titulo'], $pasosBD)
    : $pasosPredeterminados;

$glosario = $glosarioBD
    ? array_map(static fn($r) => [
        'titulo' => $r['titulo'],
        'descripcion' => $r['descripcion'] ?? ''
    ], $glosarioBD)
    : $glosarioPredeterminado;

$preguntas = $preguntasBD
    ? array_map(static fn($r) => [
        'titulo' => $r['titulo'],
        'descripcion' => $r['descripcion'] ?? ''
    ], $preguntasBD)
    : $preguntasPredeterminadas;

$imagenPrincipal = $introduccion['imagen'] ?? 'assets/img/germinacion.jpg';
$textoImagenPrincipal = $introduccion['texto_imagen'] ?? 'Semilla de frijol germinando';

require_once __DIR__ . '/partials/header.php';

$indice = [
    'que-es' => '¿Qué es?',
    'partes' => 'Partes de la semilla',
    'etapas' => 'Etapas',
    'tipos' => 'Tipos de germinación',
    'experimento' => 'Experimento',
    'glosario' => 'Glosario',
    'preguntas' => 'Preguntas frecuentes',
];
?>

<div class="germ-pagina">

    <section class="seccion germ-cabecera" id="que-es" aria-labelledby="titulo-germinacion">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">EL CICLO DE LA VIDA</span>
                <h1 id="titulo-germinacion">
                    <?= $e($introduccion['titulo'] ?? 'De semilla a plántula') ?>
                </h1>
            </div>

            <p>
                <?= $e($introduccion['descripcion'] ??
                    'Conoce qué es la germinación, cómo es una semilla por dentro y las etapas que atraviesa al iniciar su desarrollo.') ?>
            </p>
        </div>

        <div class="germ-intro">
            <div class="germ-intro-texto">
                <h2>¿Qué es la germinación?</h2>

                <p>
                    <?= $e($introduccion['detalle'] ??
                        'Es el proceso en el que una semilla, que estaba inactiva, despierta y empieza a crecer hasta convertirse en una plántula. Dentro de cada semilla hay un embrión y una reserva de alimento que lo sostiene hasta que puede producir el suyo propio.') ?>
                </p>

                <p>
                    <?= $e($introduccion['dato'] ??
                        'Para que ocurra, la semilla necesita encontrar el ambiente adecuado. Estas son las condiciones básicas:') ?>
                </p>

                <ul class="germ-condiciones">
                    <?php foreach ($condiciones as [$nombre, $detalle]): ?>
                        <li>
                            <strong><?= $e($nombre) ?></strong>
                            <span><?= $e($detalle) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <figure class="germ-imagen-principal">
                <img src="<?= $e($imagenPrincipal) ?>"
                     alt="<?= $e($textoImagenPrincipal) ?>"
                     loading="eager">
                <figcaption>
                    La germinación marca el inicio del crecimiento de una nueva planta.
                </figcaption>
            </figure>
        </div>

        <nav class="germ-indice" aria-label="En esta página">
            <span>En esta página:</span>
            <?php foreach ($indice as $ancla => $texto): ?>
                <a href="#<?= $e($ancla) ?>"><?= $e($texto) ?></a>
            <?php endforeach; ?>
        </nav>
    </section>

    <div class="germ-fondo">
        <section class="seccion" id="partes" aria-labelledby="titulo-partes">
            <div class="encabezado-seccion">
                <div>
                    <span class="sobretitulo">POR DENTRO</span>
                    <h2 id="titulo-partes">Partes de la <em>semilla</em></h2>
                </div>
                <p>
                    Cada parte cumple una función para que el embrión sobreviva
                    y pueda crecer cuando llegue el momento.
                </p>
            </div>

            <figure class="germ-imagen-principal">
                <img src="assets/img/partes-semilla.jpg"
                     alt="Ilustración de las partes internas de una semilla"
                     loading="lazy">
                <figcaption>
                    La cubierta, las reservas y el embrión cumplen funciones fundamentales.
                </figcaption>
            </figure>

            <div class="germ-partes">
                <?php foreach ($partes as $parte): ?>
                    <article class="germ-parte">
                        <span class="germ-etiqueta"><?= $e($parte['etiqueta']) ?></span>
                        <h3><?= $e($parte['titulo']) ?></h3>
                        <p><?= $e($parte['descripcion']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <section class="seccion" id="etapas" aria-labelledby="titulo-etapas">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">PASO A PASO</span>
                <h2 id="titulo-etapas">Las etapas de la <em>germinación</em></h2>
            </div>
            <p>
                Observa cómo la semilla se hidrata, desarrolla sus raíces
                y comienza a formar una planta joven.
            </p>
        </div>

        <div class="germ-etapas">
            <?php foreach ($etapas as $etapa): ?>
                <article class="germ-etapa">
                    <figure class="germ-imagen-etapa">
                        <img src="<?= $e($etapa['imagen']) ?>"
                             alt="<?= $e($etapa['texto_imagen']) ?>"
                             loading="lazy">
                    </figure>

                    <div class="germ-etapa-contenido">
                        <span class="germ-numero"><?= $e($etapa['numero']) ?></span>
                        <h3><?= $e($etapa['titulo']) ?></h3>
                        <p><?= $e($etapa['descripcion']) ?></p>

                        <?php if ($etapa['detalle'] !== ''): ?>
                            <p class="germ-senal">
                                <strong>Qué observar:</strong>
                                <?= $e($etapa['detalle']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <p class="nota">
            Las etapas y el tiempo de germinación varían según la especie
            y las condiciones ambientales.
        </p>
    </section>

    <div class="germ-fondo">
        <section class="seccion" id="tipos" aria-labelledby="titulo-tipos">
            <div class="encabezado-seccion">
                <div>
                    <span class="sobretitulo">NO TODAS SON IGUALES</span>
                    <h2 id="titulo-tipos">Dos formas de <em>germinar</em></h2>
                </div>
                <p>
                    Según lo que ocurra con los cotiledones, la germinación
                    puede ser epigea o hipogea.
                </p>
            </div>

            <div class="germ-tipos">
                <?php foreach ($tipos as $tipo): ?>
                    <article class="germ-tipo">
                        <figure class="germ-imagen-tipo">
                            <img src="<?= $e($tipo['imagen']) ?>"
                                 alt="<?= $e($tipo['texto_imagen']) ?>"
                                 loading="lazy">
                        </figure>

                        <div class="germ-tipo-contenido">
                            <span class="germ-etiqueta">GERMINACIÓN</span>
                            <h3><?= $e($tipo['titulo']) ?></h3>
                            <p><?= $e($tipo['descripcion']) ?></p>
                            <small><?= $e($tipo['detalle']) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <section class="seccion" id="experimento" aria-labelledby="titulo-experimento">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">APRENDE HACIENDO</span>
                <h2 id="titulo-experimento">Observa la germinación <em>en casa</em></h2>
            </div>
            <p>
                Realiza un experimento sencillo para observar el crecimiento
                de una semilla de frijol.
            </p>
        </div>

        <figure class="germ-imagen-principal">
            <img src="./assets/img/experimento-frijol.jpg"
                 alt="Experimento de germinación de frijoles en un frasco con algodón"
                 loading="lazy">
            <figcaption>
                El frasco transparente permite observar el crecimiento de las raíces.
            </figcaption>
        </figure>

        <div class="germ-experimento">
            <div class="germ-caja">
                <h3>Materiales</h3>
                <ul>
                    <?php foreach ($materiales as $item): ?>
                        <li><?= $e($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="germ-caja">
                <h3>Procedimiento</h3>
                <ol>
                    <?php foreach ($pasosExperimento as $paso): ?>
                        <li><?= $e($paso) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </section>

    <div class="germ-fondo">
        <section class="seccion" id="glosario" aria-labelledby="titulo-glosario">
            <div class="encabezado-seccion">
                <div>
                    <span class="sobretitulo">VOCABULARIO</span>
                    <h2 id="titulo-glosario">Palabras <em>clave</em></h2>
                </div>
            </div>

            <dl class="germ-glosario">
                <?php foreach ($glosario as $termino): ?>
                    <div>
                        <dt><?= $e($termino['titulo']) ?></dt>
                        <dd><?= $e($termino['descripcion']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>
    </div>

    <section class="seccion" id="preguntas" aria-labelledby="titulo-preguntas">
        <div class="encabezado-seccion">
            <div>
                <span class="sobretitulo">DUDAS COMUNES</span>
                <h2 id="titulo-preguntas">Preguntas <em>frecuentes</em></h2>
            </div>
        </div>

        <div class="germ-preguntas">
            <?php foreach ($preguntas as $pregunta): ?>
                <details>
                    <summary><?= $e($pregunta['titulo']) ?></summary>
                    <p><?= $e($pregunta['descripcion']) ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="seccion germ-cierre" aria-labelledby="titulo-siguiente">
        <div class="germ-cta">
            <div>
                <span class="sobretitulo">SIGUE APRENDIENDO</span>
                <h2 id="titulo-siguiente">
                    ¿Qué necesita una semilla para <em>germinar</em>?
                </h2>
                <p>
                    Descubre cómo influyen el agua, la temperatura y la luz,
                    y pon a prueba lo aprendido.
                </p>
            </div>

            <div class="germ-cta-acciones">
                <a class="boton boton-verde" href="factores.php">Ver factores →</a>
                <a class="enlace" href="cuestionario.php">Ir al cuestionario</a>
            </div>
        </div>
    </section>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
