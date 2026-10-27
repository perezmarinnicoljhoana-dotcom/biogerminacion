
<?php
require_once __DIR__ . '/../config/db.php';
require_role('admin');

$tituloPagina = 'Panel de administración | BIOGERMINACIÓN';

$secciones = [
    'inicio',
    'paginas',
    'contenidos',
    'actividades',
    'preguntas',
    'usuarios',
    'mensajes',
    'resultados'
];

$seccion = $_GET['section'] ?? 'inicio';

if (!in_array($seccion, $secciones, true)) {
    $seccion = 'inicio';
}

if (empty($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}

if (!function_exists('admin_e')) {
    function admin_e($valor): string
    {
        return htmlspecialchars(
            (string)($valor ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

if (!isset($_SESSION['admin_flash'])) {
    $_SESSION['admin_flash'] = '';
}

$error = '';
$mensaje = $_SESSION['admin_flash'];
unset($_SESSION['admin_flash']);

function admin_redirect(string $section): void
{
    header('Location: dashboard.php?section=' . urlencode($section));
    exit;
}

function admin_flash(string $message): void
{
    $_SESSION['admin_flash'] = $message;
}

function admin_upload_image(array $archivo): string
{
    if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException(
            'No se pudo cargar la imagen. Inténtalo nuevamente.'
        );
    }

    if (!is_uploaded_file($archivo['tmp_name'])) {
        throw new RuntimeException('El archivo seleccionado no es válido.');
    }

    if ((int)$archivo['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException(
            'La imagen no puede superar los 5 MB.'
        );
    }

    if (!function_exists('finfo_open')) {
        throw new RuntimeException(
            'El servidor no tiene habilitada la validación de archivos.'
        );
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($archivo['tmp_name']);

    $extensiones = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif'
    ];

    if (
        !isset($extensiones[$mime]) ||
        @getimagesize($archivo['tmp_name']) === false
    ) {
        throw new RuntimeException(
            'Selecciona una imagen JPG, PNG, WEBP o GIF válida.'
        );
    }

    $directorio = __DIR__ . '/../img/contenidos';

    if (
        !is_dir($directorio) &&
        !mkdir($directorio, 0755, true) &&
        !is_dir($directorio)
    ) {
        throw new RuntimeException(
            'No se pudo crear la carpeta img/contenidos.'
        );
    }

    $nombre = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
    $destino = $directorio . '/' . $nombre;

    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        throw new RuntimeException(
            'No se pudo guardar la imagen en el servidor.'
        );
    }

    return 'img/contenidos/' . $nombre;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_admin'], $token)
    ) {
        $error = 'La sesión del formulario venció. Recarga la página e inténtalo de nuevo.';
    } else {
        $action = $_POST['action'] ?? '';
        $seccionPost = $_POST['section'] ?? 'inicio';

        if (!in_array($seccionPost, $secciones, true)) {
            $seccionPost = 'inicio';
        }

        try {
            switch ($action) {

                /* ==========================================
                   PÁGINAS WEB: FACTORES Y GERMINACIÓN
                ========================================== */

                case 'pagina_save':
                    $id = (int)($_POST['id'] ?? 0);
                    $pagina = $_POST['pagina'] ?? 'factores';
                    $tipo = $_POST['tipo'] ?? 'factor';
                    $titulo = trim($_POST['titulo'] ?? '');
                    $descripcion = trim($_POST['descripcion'] ?? '');
                    $detalle = trim($_POST['detalle'] ?? '');
                    $dato = trim($_POST['dato'] ?? '');
                    $textoImagen = trim($_POST['texto_imagen'] ?? '');
                    $orden = (int)($_POST['orden'] ?? 0);
                    $activo = isset($_POST['activo']) ? 1 : 0;

                    $tiposFactores = [
                        'intro',
                        'factor',
                        'recordatorio'
                    ];

                    $tiposGerminacion = [
                        'intro',
                        'condicion',
                        'parte',
                        'etapa',
                        'tipo_germinacion',
                        'material',
                        'paso',
                        'glosario',
                        'pregunta',
                        'recordatorio'
                    ];

                    if (!in_array(
                        $pagina,
                        ['factores', 'germinacion'],
                        true
                    )) {
                        throw new RuntimeException(
                            'Selecciona una página válida.'
                        );
                    }

                    $tiposValidos = $pagina === 'factores'
                        ? $tiposFactores
                        : $tiposGerminacion;

                    if (!in_array($tipo, $tiposValidos, true)) {
                        throw new RuntimeException(
                            'El tipo de contenido no corresponde a la página seleccionada.'
                        );
                    }

                    if ($titulo === '') {
                        throw new RuntimeException(
                            'El título es obligatorio.'
                        );
                    }

                    if ($id > 0) {
                        $consulta = $pdo->prepare(
                            'SELECT imagen
                             FROM germinacion_contenidos
                             WHERE id = ?'
                        );
                        $consulta->execute([$id]);
                        $registroAnterior = $consulta->fetch(
                            PDO::FETCH_ASSOC
                        );

                        if (!$registroAnterior) {
                            throw new RuntimeException(
                                'El contenido que intentas editar no existe.'
                            );
                        }

                        $imagen = $registroAnterior['imagen'] ?? '';
                    } else {
                        $imagen = '';
                    }

                    if (
                        isset($_FILES['imagen']) &&
                        ($_FILES['imagen']['error'] ?? UPLOAD_ERR_NO_FILE)
                            !== UPLOAD_ERR_NO_FILE
                    ) {
                        $imagenNueva = admin_upload_image($_FILES['imagen']);

                        if ($imagenNueva !== '') {
                            $imagen = $imagenNueva;
                        }
                    }

                    $datos = [
                        $pagina,
                        $tipo,
                        $titulo,
                        $descripcion,
                        $detalle,
                        $imagen,
                        $textoImagen,
                        $dato,
                        $orden,
                        $activo
                    ];

                    if ($id > 0) {
                        $stmt = $pdo->prepare(
                            'UPDATE germinacion_contenidos
                             SET seccion = ?,
                                 tipo = ?,
                                 titulo = ?,
                                 descripcion = ?,
                                 detalle = ?,
                                 imagen = ?,
                                 texto_imagen = ?,
                                 dato = ?,
                                 orden = ?,
                                 activo = ?
                             WHERE id = ?'
                        );

                        $datos[] = $id;
                        $stmt->execute($datos);

                        admin_flash(
                            'Contenido de la página actualizado correctamente.'
                        );
                    } else {
                        $stmt = $pdo->prepare(
                            'INSERT INTO germinacion_contenidos
                             (
                                 seccion,
                                 tipo,
                                 titulo,
                                 descripcion,
                                 detalle,
                                 imagen,
                                 texto_imagen,
                                 dato,
                                 orden,
                                 activo
                             )
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                        );

                        $stmt->execute($datos);

                        admin_flash(
                            'Contenido creado correctamente.'
                        );
                    }

                    admin_redirect('paginas');

                case 'pagina_toggle':
                    $id = (int)($_POST['id'] ?? 0);

                    $stmt = $pdo->prepare(
                        'UPDATE germinacion_contenidos
                         SET activo = IF(activo = 1, 0, 1)
                         WHERE id = ?'
                    );
                    $stmt->execute([$id]);

                    admin_flash(
                        'Estado de publicación actualizado.'
                    );

                    admin_redirect('paginas');

                case 'pagina_delete':
                    $id = (int)($_POST['id'] ?? 0);

                    $stmt = $pdo->prepare(
                        'DELETE FROM germinacion_contenidos
                         WHERE id = ?'
                    );
                    $stmt->execute([$id]);

                    admin_flash(
                        'Contenido eliminado de la base de datos.'
                    );

                    admin_redirect('paginas');

                /* ==========================================
                   CONTENIDOS EDUCATIVOS
                ========================================== */

                case 'content_save':
                    $id = (int)($_POST['id'] ?? 0);
                    $title = trim($_POST['title'] ?? '');
                    $body = trim($_POST['body'] ?? '');

                    if ($title === '' || $body === '') {
                        throw new RuntimeException(
                            'Debes completar el título y el contenido.'
                        );
                    }

                    if ($id > 0) {
                        $stmt = $pdo->prepare(
                            'UPDATE contents
                             SET title = ?, body = ?
                             WHERE id = ?'
                        );
                        $stmt->execute([$title, $body, $id]);

                        admin_flash(
                            'Contenido educativo actualizado.'
                        );
                    } else {
                        $stmt = $pdo->prepare(
                            'INSERT INTO contents (title, body)
                             VALUES (?, ?)'
                        );
                        $stmt->execute([$title, $body]);

                        admin_flash(
                            'Contenido educativo creado.'
                        );
                    }

                    admin_redirect('contenidos');

                case 'content_delete':
                    $stmt = $pdo->prepare(
                        'DELETE FROM contents WHERE id = ?'
                    );
                    $stmt->execute([(int)($_POST['id'] ?? 0)]);

                    admin_flash('Contenido educativo eliminado.');
                    admin_redirect('contenidos');

                /* ==========================================
                   ACTIVIDADES
                ========================================== */

                case 'activity_save':
                    $id = (int)($_POST['id'] ?? 0);
                    $title = trim($_POST['title'] ?? '');
                    $description = trim($_POST['description'] ?? '');

                    if ($title === '' || $description === '') {
                        throw new RuntimeException(
                            'Completa el nombre y la descripción de la actividad.'
                        );
                    }

                    if ($id > 0) {
                        $stmt = $pdo->prepare(
                            'UPDATE activities
                             SET title = ?, description = ?
                             WHERE id = ?'
                        );
                        $stmt->execute([$title, $description, $id]);

                        admin_flash('Actividad actualizada.');
                    } else {
                        $stmt = $pdo->prepare(
                            'INSERT INTO activities (title, description)
                             VALUES (?, ?)'
                        );
                        $stmt->execute([$title, $description]);

                        admin_flash('Actividad creada.');
                    }

                    admin_redirect('actividades');

                case 'activity_delete':
                    $stmt = $pdo->prepare(
                        'DELETE FROM activities WHERE id = ?'
                    );
                    $stmt->execute([(int)($_POST['id'] ?? 0)]);

                    admin_flash(
                        'Actividad eliminada. Si la base de datos tiene configurada la eliminación en cascada, sus preguntas asociadas también se eliminan.'
                    );

                    admin_redirect('actividades');

                /* ==========================================
                   PREGUNTAS
                ========================================== */

                case 'question_save':
                    $id = (int)($_POST['id'] ?? 0);
                    $activityId = (int)($_POST['activity_id'] ?? 0);
                    $question = trim($_POST['question'] ?? '');
                    $a = trim($_POST['option_a'] ?? '');
                    $b = trim($_POST['option_b'] ?? '');
                    $c = trim($_POST['option_c'] ?? '');
                    $correct = $_POST['correct_option'] ?? '';

                    $stmt = $pdo->prepare(
                        'SELECT id FROM activities WHERE id = ?'
                    );
                    $stmt->execute([$activityId]);

                    if (!$stmt->fetch()) {
                        throw new RuntimeException(
                            'Selecciona una actividad válida.'
                        );
                    }

                    if (
                        $question === '' ||
                        $a === '' ||
                        $b === '' ||
                        $c === '' ||
                        !in_array($correct, ['a', 'b', 'c'], true)
                    ) {
                        throw new RuntimeException(
                            'Completa la pregunta, las tres opciones y la respuesta correcta.'
                        );
                    }

                    if ($id > 0) {
                        $stmt = $pdo->prepare(
                            'UPDATE questions
                             SET activity_id = ?,
                                 question = ?,
                                 option_a = ?,
                                 option_b = ?,
                                 option_c = ?,
                                 correct_option = ?
                             WHERE id = ?'
                        );

                        $stmt->execute([
                            $activityId,
                            $question,
                            $a,
                            $b,
                            $c,
                            $correct,
                            $id
                        ]);

                        admin_flash('Pregunta actualizada.');
                    } else {
                        $stmt = $pdo->prepare(
                            'INSERT INTO questions
                             (
                                 activity_id,
                                 question,
                                 option_a,
                                 option_b,
                                 option_c,
                                 correct_option
                             )
                             VALUES (?, ?, ?, ?, ?, ?)'
                        );

                        $stmt->execute([
                            $activityId,
                            $question,
                            $a,
                            $b,
                            $c,
                            $correct
                        ]);

                        admin_flash('Pregunta creada.');
                    }

                    admin_redirect('preguntas');

                case 'question_delete':
                    $stmt = $pdo->prepare(
                        'DELETE FROM questions WHERE id = ?'
                    );
                    $stmt->execute([(int)($_POST['id'] ?? 0)]);

                    admin_flash('Pregunta eliminada.');
                    admin_redirect('preguntas');

                /* ==========================================
                   USUARIOS
                ========================================== */

                case 'user_toggle':
                    $id = (int)($_POST['id'] ?? 0);

                    $stmt = $pdo->prepare(
                        "UPDATE users
                         SET active = IF(active = 1, 0, 1)
                         WHERE id = ? AND role = 'usuario'"
                    );
                    $stmt->execute([$id]);

                    admin_flash('Estado del usuario actualizado.');
                    admin_redirect('usuarios');

                /* ==========================================
                   MENSAJES
                ========================================== */

                case 'message_delete':
                    $stmt = $pdo->prepare(
                        'DELETE FROM mensajes WHERE id = ?'
                    );
                    $stmt->execute([(int)($_POST['id'] ?? 0)]);

                    admin_flash('Mensaje eliminado.');
                    admin_redirect('mensajes');

                /* ==========================================
                   RESULTADOS
                ========================================== */

                case 'result_delete':
                    $stmt = $pdo->prepare(
                        'DELETE FROM results WHERE id = ?'
                    );
                    $stmt->execute([(int)($_POST['id'] ?? 0)]);

                    admin_flash('Resultado eliminado.');
                    admin_redirect('resultados');

                default:
                    throw new RuntimeException(
                        'La acción solicitada no es válida.'
                    );
            }
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        } catch (PDOException $ex) {
            error_log($ex->getMessage());

            $error = 'No se pudo completar la operación. Verifica los datos, las columnas de la tabla y los permisos de la base de datos.';
        } catch (Throwable $ex) {
            error_log($ex->getMessage());
            $error = 'Ocurrió un error inesperado al procesar la solicitud.';
        }
    }
}

/* ==========================================
   ESTADÍSTICAS
========================================== */

$counts = [
    'Usuarios' => (int)$pdo->query(
        "SELECT COUNT(*) FROM users WHERE role = 'usuario'"
    )->fetchColumn(),

    'Contenidos educativos' => (int)$pdo->query(
        'SELECT COUNT(*) FROM contents'
    )->fetchColumn(),

    'Actividades' => (int)$pdo->query(
        'SELECT COUNT(*) FROM activities'
    )->fetchColumn(),

    'Preguntas' => (int)$pdo->query(
        'SELECT COUNT(*) FROM questions'
    )->fetchColumn(),

    'Mensajes' => (int)$pdo->query(
        'SELECT COUNT(*) FROM mensajes'
    )->fetchColumn(),

    'Resultados' => (int)$pdo->query(
        'SELECT COUNT(*) FROM results'
    )->fetchColumn(),

    'Contenidos de páginas' => (int)$pdo->query(
        'SELECT COUNT(*) FROM germinacion_contenidos'
    )->fetchColumn()
];

/* ==========================================
   CONSULTAS PARA LAS TABLAS
========================================== */

$paginasContenidos = $pdo->query(
    'SELECT *
     FROM germinacion_contenidos
     ORDER BY seccion ASC, orden ASC, id ASC'
)->fetchAll(PDO::FETCH_ASSOC);

$contents = $pdo->query(
    'SELECT * FROM contents ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$activities = $pdo->query(
    'SELECT a.*, COUNT(q.id) AS questions_count
     FROM activities a
     LEFT JOIN questions q ON q.activity_id = a.id
     GROUP BY a.id, a.title, a.description, a.created_at
     ORDER BY a.id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$questions = $pdo->query(
    'SELECT q.*, a.title AS activity_title
     FROM questions q
     INNER JOIN activities a ON a.id = q.activity_id
     ORDER BY q.id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$users = $pdo->query(
    "SELECT id, name, email, active, created_at
     FROM users
     WHERE role = 'usuario'
     ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$messages = $pdo->query(
    'SELECT id, nombre, correo, asunto, contenido, fecha_creacion
     FROM mensajes
     ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$results = $pdo->query(
    'SELECT r.*, u.name, u.email, a.title AS activity_title
     FROM results r
     INNER JOIN users u ON u.id = r.user_id
     INNER JOIN activities a ON a.id = r.activity_id
     ORDER BY r.created_at DESC'
)->fetchAll(PDO::FETCH_ASSOC);

/* ==========================================
   FORMULARIOS DE EDICIÓN
========================================== */

$editPagina = null;

if ($seccion === 'paginas' && isset($_GET['edit_pagina'])) {
    $stmt = $pdo->prepare(
        'SELECT *
         FROM germinacion_contenidos
         WHERE id = ?'
    );
    $stmt->execute([(int)$_GET['edit_pagina']]);
    $editPagina = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$editContent = null;

if ($seccion === 'contenidos' && isset($_GET['edit_content'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM contents WHERE id = ?'
    );
    $stmt->execute([(int)$_GET['edit_content']]);
    $editContent = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$editActivity = null;

if ($seccion === 'actividades' && isset($_GET['edit_activity'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM activities WHERE id = ?'
    );
    $stmt->execute([(int)$_GET['edit_activity']]);
    $editActivity = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$editQuestion = null;

if ($seccion === 'preguntas' && isset($_GET['edit_question'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM questions WHERE id = ?'
    );
    $stmt->execute([(int)$_GET['edit_question']]);
    $editQuestion = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

require __DIR__ . '/../partials/header.php';
?>

<style>
.admin-wrap {
    max-width: 1450px;
    margin: 0 auto;
    padding: 28px 4% 55px;
}

.admin-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.admin-heading h1 {
    margin: 4px 0 8px;
}

.admin-subtitle {
    color: #657368;
    margin: 0;
}

.admin-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 22px 0;
}

.admin-nav a {
    display: inline-block;
    padding: 10px 14px;
    border: 1px solid #dce5da;
    border-radius: 8px;
    text-decoration: none;
    color: #315b3c;
    background: #fff;
    font-size: 14px;
}

.admin-nav a.active,
.admin-nav a:hover {
    background: #2f6b43;
    color: white;
    border-color: #2f6b43;
}

.admin-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin: 20px 0 28px;
}

.admin-stat {
    background: #fff;
    border: 1px solid #e0e8de;
    border-radius: 12px;
    padding: 18px;
}

.admin-stat span {
    display: block;
    color: #657368;
    font-size: 14px;
}

.admin-stat strong {
    display: block;
    font-size: 28px;
    color: #2f6b43;
    margin-top: 8px;
}

.admin-panel {
    background: #fff;
    border: 1px solid #e0e8de;
    border-radius: 12px;
    padding: 22px;
    margin: 18px 0;
    min-width: 0;
}

.admin-panel h2 {
    margin-top: 0;
}

.admin-form {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
}

.admin-form label {
    font-weight: 600;
    font-size: 14px;
}

.admin-form input:not([type="checkbox"]):not([type="file"]),
.admin-form textarea,
.admin-form select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccd7ca;
    border-radius: 7px;
    font: inherit;
    box-sizing: border-box;
    background: #fff;
}

.admin-form input[type="checkbox"] {
    width: auto;
    margin-right: 7px;
}

.admin-form textarea {
    resize: vertical;
}

.admin-button {
    display: inline-block;
    border: 0;
    border-radius: 7px;
    background: #2f6b43;
    color: #fff;
    padding: 10px 15px;
    cursor: pointer;
    text-decoration: none;
    font: inherit;
    text-align: center;
}

.admin-button:hover {
    background: #1c402d;
    color: #fff;
}

.admin-button.secondary {
    background: #edf2e9;
    color: #315b3c;
}

.admin-button.secondary:hover {
    background: #dce8d7;
}

.admin-button.danger {
    background: #a63131;
    color: white;
}

.admin-button.danger:hover {
    background: #812525;
}

.admin-inline {
    display: inline;
}

.admin-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.admin-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 680px;
}

.admin-table th,
.admin-table td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #e5ebe3;
    vertical-align: top;
    font-size: 14px;
}

.admin-table th {
    background: #edf2e9;
    color: #315b3c;
}

.admin-table td p {
    margin: 5px 0;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.admin-alert,
.admin-success {
    border-radius: 8px;
    padding: 13px 15px;
    margin: 16px 0;
}

.admin-alert {
    background: #fff0e7;
    color: #85421e;
}

.admin-success {
    background: #eaf5e9;
    color: #285b32;
}

.admin-muted {
    color: #68766a;
    font-size: 13px;
}

.admin-upload {
    display: flex;
    flex-direction: column;
    gap: 9px;
    padding: 16px;
    border: 1px dashed #9aaf98;
    border-radius: 10px;
    background: #f7faf5;
}

.admin-upload input[type="file"] {
    display: block;
    width: 100%;
    padding: 10px;
    background: #fff;
    border: 1px solid #ccd7ca;
    border-radius: 7px;
    box-sizing: border-box;
}

.admin-preview {
    display: block;
    width: 145px;
    max-width: 100%;
    max-height: 120px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dce5da;
    margin-top: 8px;
}

.admin-status {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 20px;
    background: #eaf5e9;
    color: #285b32;
    font-size: 12px;
}

.admin-status.off {
    background: #fff0e7;
    color: #85421e;
}

.admin-section-title {
    margin: 28px 0 12px;
}

@media (max-width: 760px) {
    .admin-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .admin-wrap {
        padding: 20px 4% 40px;
    }

    .admin-panel {
        padding: 15px;
    }
}

@media (max-width: 420px) {
    .admin-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-wrap">

    <div class="admin-heading">
        <div>
            <p class="admin-muted">BIOGERMINACIÓN / ADMINISTRACIÓN</p>
            <h1>Panel de administración</h1>
            <p class="admin-subtitle">
                Gestiona las páginas, los contenidos educativos,
                las actividades y el seguimiento de los usuarios.
            </p>
        </div>

        <a class="admin-button secondary" href="../index.php">
            Ver página web
        </a>
    </div>

    <nav class="admin-nav">
        <?php
        $navItems = [
            'inicio' => 'Inicio',
            'paginas' => 'Páginas web',
            'contenidos' => 'Contenidos',
            'actividades' => 'Actividades',
            'preguntas' => 'Preguntas',
            'usuarios' => 'Usuarios',
            'mensajes' => 'Mensajes',
            'resultados' => 'Resultados'
        ];

        foreach ($navItems as $key => $label):
        ?>
            <a
                class="<?= $seccion === $key ? 'active' : '' ?>"
                href="dashboard.php?section=<?=admin_e($key)?>"
            >
                <?=admin_e($label)?>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if ($error !== ''): ?>
        <div class="admin-alert"><?=admin_e($error)?></div>
    <?php endif; ?>

    <?php if ($mensaje !== ''): ?>
        <div class="admin-success"><?=admin_e($mensaje)?></div>
    <?php endif; ?>

    <?php if ($seccion === 'inicio'): ?>

        <h2>Resumen general</h2>

        <div class="admin-stats">
            <?php foreach ($counts as $label => $number): ?>
                <div class="admin-stat">
                    <span><?=admin_e($label)?></span>
                    <strong><?=admin_e($number)?></strong>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="admin-panel">
            <h2>Accesos rápidos</h2>

            <div class="admin-actions">
                <a class="admin-button" href="dashboard.php?section=paginas">
                    Administrar páginas web
                </a>

                <a class="admin-button" href="dashboard.php?section=contenidos">
                    Gestionar contenidos educativos
                </a>

                <a class="admin-button" href="dashboard.php?section=actividades">
                    Gestionar actividades
                </a>

                <a class="admin-button" href="dashboard.php?section=preguntas">
                    Gestionar preguntas
                </a>

                <a class="admin-button" href="dashboard.php?section=usuarios">
                    Consultar usuarios
                </a>

                <a class="admin-button" href="dashboard.php?section=mensajes">
                    Ver mensajes
                </a>

                <a class="admin-button" href="dashboard.php?section=resultados">
                    Ver resultados
                </a>
            </div>
        </div>

    <?php elseif ($seccion === 'paginas'): ?>

        <h2>Administración de páginas web</h2>

        <p class="admin-muted">
            Administra los contenidos de factores.php y germinacion.php.
            Puedes agregar texto, editarlo, adjuntar imágenes y controlar
            qué contenido se encuentra publicado.
        </p>

        <div class="admin-panel">
            <h2>
                <?= $editPagina ? 'Editar contenido de página' : 'Agregar contenido a una página' ?>
            </h2>

            <form
                class="admin-form"
                method="post"
                enctype="multipart/form-data"
            >
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?=admin_e($_SESSION['csrf_admin'])?>"
                >

                <input type="hidden" name="section" value="paginas">
                <input type="hidden" name="action" value="pagina_save">

                <input
                    type="hidden"
                    name="id"
                    value="<?=admin_e($editPagina['id'] ?? 0)?>"
                >

                <label for="pagina">Página donde aparecerá el contenido</label>

                <select id="pagina" name="pagina" required>
                    <option
                        value="factores"
                        <?=($editPagina['seccion'] ?? 'factores') === 'factores' ? 'selected' : ''?>
                    >
                        Factores de la germinación
                    </option>

                    <option
                        value="germinacion"
                        <?=($editPagina['seccion'] ?? '') === 'germinacion' ? 'selected' : ''?>
                    >
                        Germinación
                    </option>
                </select>

                <label for="tipo">Tipo de contenido</label>

                <select id="tipo" name="tipo" required>
                    <?php
                    $tiposPagina = [
                        'intro' => 'Introducción',
                        'factor' => 'Factor de germinación',
                        'recordatorio' => 'Recordatorio',
                        'condicion' => 'Condición de germinación',
                        'parte' => 'Parte de la semilla',
                        'etapa' => 'Etapa de germinación',
                        'tipo_germinacion' => 'Tipo de germinación',
                        'material' => 'Material del experimento',
                        'paso' => 'Paso del experimento',
                        'glosario' => 'Glosario',
                        'pregunta' => 'Pregunta informativa'
                    ];

                    $tipoActual = $editPagina['tipo'] ?? 'factor';

                    foreach ($tiposPagina as $valor => $nombre):
                    ?>
                        <option
                            value="<?=admin_e($valor)?>"
                            <?= $tipoActual === $valor ? 'selected' : '' ?>
                        >
                            <?=admin_e($nombre)?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <p class="admin-muted">
                    Para factores.php utiliza Introducción, Factor de germinación
                    o Recordatorio. Para germinacion.php puedes utilizar los
                    demás tipos según la estructura de esa página.
                </p>

                <label for="pagina-titulo">Título</label>

                <input
                    id="pagina-titulo"
                    name="titulo"
                    maxlength="255"
                    required
                    value="<?=admin_e($editPagina['titulo'] ?? '')?>"
                >

                <label for="pagina-descripcion">
                    Descripción o texto principal
                </label>

                <textarea
                    id="pagina-descripcion"
                    name="descripcion"
                    rows="5"
                ><?=admin_e($editPagina['descripcion'] ?? '')?></textarea>

                <label for="pagina-detalle">
                    Recomendación, detalle o texto complementario
                </label>

                <textarea
                    id="pagina-detalle"
                    name="detalle"
                    rows="4"
                ><?=admin_e($editPagina['detalle'] ?? '')?></textarea>

                <label for="pagina-dato">Dato curioso (opcional)</label>

                <textarea
                    id="pagina-dato"
                    name="dato"
                    rows="3"
                ><?=admin_e($editPagina['dato'] ?? '')?></textarea>

                <label for="texto-imagen">
                    Texto alternativo de la imagen
                </label>

                <input
                    id="texto-imagen"
                    name="texto_imagen"
                    maxlength="255"
                    value="<?=admin_e($editPagina['texto_imagen'] ?? '')?>"
                >

                <div class="admin-upload">
                    <label for="imagen">Adjuntar imagen</label>

                    <input
                        id="imagen"
                        type="file"
                        name="imagen"
                        accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif"
                    >

                    <span class="admin-muted">
                        Selecciona una imagen de tu computador.
                        Formatos permitidos: JPG, PNG, WEBP y GIF.
                        Tamaño máximo: 5 MB.
                    </span>

                    <?php if (!empty($editPagina['imagen'])): ?>
                        <div>
                            <span class="admin-muted">Imagen actual:</span>

                            <img
                                class="admin-preview"
                                src="../<?=admin_e($editPagina['imagen'])?>"
                                alt="<?=admin_e($editPagina['texto_imagen'] ?? '')?>"
                            >

                            <p class="admin-muted">
                                Si no seleccionas una imagen nueva, se conserva
                                la imagen actual.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <label for="orden">Orden de aparición</label>

                <input
                    id="orden"
                    type="number"
                    name="orden"
                    value="<?=admin_e($editPagina['orden'] ?? 0)?>"
                >

                <label>
                    <input
                        type="checkbox"
                        name="activo"
                        value="1"
                        <?=(!isset($editPagina['activo']) || (int)$editPagina['activo'] === 1) ? 'checked' : ''?>
                    >
                    Publicar este contenido
                </label>

                <div class="admin-actions">
                    <button class="admin-button" type="submit">
                        Guardar contenido de la página
                    </button>

                    <?php if ($editPagina): ?>
                        <a
                            class="admin-button secondary"
                            href="dashboard.php?section=paginas"
                        >
                            Cancelar edición
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="admin-panel">
            <h2>
                Contenidos registrados
                (<?=count($paginasContenidos)?>)
            </h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Página y contenido</th>
                            <th>Imagen</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($paginasContenidos as $r): ?>
                        <tr>
                            <td>
                                <strong><?=admin_e($r['titulo'])?></strong>

                                <p>
                                    Página:
                                    <?=admin_e($r['seccion'])?>
                                </p>

                                <p>
                                    Tipo:
                                    <?=admin_e($r['tipo'])?>
                                </p>

                                <p>
                                    <?=admin_e(
                                        mb_substr(
                                            $r['descripcion'] ?? '',
                                            0,
                                            180,
                                            'UTF-8'
                                        )
                                    )?>
                                </p>
                            </td>

                            <td>
                                <?php if (!empty($r['imagen'])): ?>
                                    <img
                                        class="admin-preview"
                                        src="../<?=admin_e($r['imagen'])?>"
                                        alt="<?=admin_e($r['texto_imagen'] ?? '')?>"
                                    >
                                <?php else: ?>
                                    <span class="admin-muted">
                                        Sin imagen
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td><?=admin_e($r['orden'])?></td>

                            <td>
                                <span class="admin-status <?=((int)$r['activo'] === 1) ? '' : 'off'?>">
                                    <?=((int)$r['activo'] === 1) ? 'Publicado' : 'Oculto'?>
                                </span>
                            </td>

                            <td>
                                <div class="admin-actions">
                                    <a
                                        class="admin-button secondary"
                                        href="dashboard.php?section=paginas&edit_pagina=<?=(int)$r['id']?>"
                                    >
                                        Editar
                                    </a>

                                    <form method="post" class="admin-inline">
                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?=admin_e($_SESSION['csrf_admin'])?>"
                                        >
                                        <input type="hidden" name="section" value="paginas">
                                        <input type="hidden" name="action" value="pagina_toggle">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?=(int)$r['id']?>"
                                        >

                                        <button
                                            class="admin-button secondary"
                                            type="submit"
                                        >
                                            <?=((int)$r['activo'] === 1) ? 'Ocultar' : 'Publicar'?>
                                        </button>
                                    </form>

                                    <form
                                        method="post"
                                        class="admin-inline"
                                        onsubmit="return confirm('¿Eliminar definitivamente este contenido?');"
                                    >
                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?=admin_e($_SESSION['csrf_admin'])?>"
                                        >
                                        <input type="hidden" name="section" value="paginas">
                                        <input type="hidden" name="action" value="pagina_delete">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?=(int)$r['id']?>"
                                        >

                                        <button
                                            class="admin-button danger"
                                            type="submit"
                                        >
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$paginasContenidos): ?>
                        <tr>
                            <td colspan="5">
                                Todavía no hay contenidos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <p class="admin-muted">
                Ocultar un contenido lo mantiene en la base de datos.
                Eliminarlo lo borra de la tabla.
            </p>
        </div>

    <?php elseif ($seccion === 'contenidos'): ?>

        <h2>Gestión de contenidos educativos</h2>

        <div class="admin-panel">
            <h2><?= $editContent ? 'Editar contenido' : 'Crear contenido' ?></h2>

            <form class="admin-form" method="post">
                <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                <input type="hidden" name="section" value="contenidos">
                <input type="hidden" name="action" value="content_save">
                <input type="hidden" name="id" value="<?=admin_e($editContent['id'] ?? 0)?>">

                <label for="content-title">Título</label>
                <input
                    id="content-title"
                    name="title"
                    maxlength="180"
                    required
                    value="<?=admin_e($editContent['title'] ?? '')?>"
                >

                <label for="content-body">Contenido educativo</label>
                <textarea
                    id="content-body"
                    name="body"
                    rows="6"
                    required
                ><?=admin_e($editContent['body'] ?? '')?></textarea>

                <div class="admin-actions">
                    <button class="admin-button" type="submit">
                        Guardar contenido
                    </button>

                    <?php if ($editContent): ?>
                        <a class="admin-button secondary" href="dashboard.php?section=contenidos">
                            Cancelar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="admin-panel">
            <h2>Contenidos registrados (<?=count($contents)?>)</h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Título y contenido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($contents as $r): ?>
                        <tr>
                            <td>
                                <strong><?=admin_e($r['title'])?></strong>
                                <p>
                                    <?=admin_e(
                                        mb_substr(
                                            strip_tags($r['body']),
                                            0,
                                            240,
                                            'UTF-8'
                                        )
                                    )?>
                                </p>
                            </td>

                            <td>
                                <div class="admin-actions">
                                    <a
                                        class="admin-button secondary"
                                        href="dashboard.php?section=contenidos&edit_content=<?=(int)$r['id']?>"
                                    >
                                        Editar
                                    </a>

                                    <form
                                        class="admin-inline"
                                        method="post"
                                        onsubmit="return confirm('¿Eliminar este contenido?');"
                                    >
                                        <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                        <input type="hidden" name="section" value="contenidos">
                                        <input type="hidden" name="action" value="content_delete">
                                        <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                        <button class="admin-button danger" type="submit">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$contents): ?>
                        <tr>
                            <td colspan="2">No hay contenidos registrados.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($seccion === 'actividades'): ?>

        <h2>Gestión de actividades</h2>

        <div class="admin-panel">
            <h2><?= $editActivity ? 'Editar actividad' : 'Crear actividad' ?></h2>

            <form class="admin-form" method="post">
                <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                <input type="hidden" name="section" value="actividades">
                <input type="hidden" name="action" value="activity_save">
                <input type="hidden" name="id" value="<?=admin_e($editActivity['id'] ?? 0)?>">

                <label for="activity-title">Nombre</label>
                <input
                    id="activity-title"
                    name="title"
                    maxlength="180"
                    required
                    value="<?=admin_e($editActivity['title'] ?? '')?>"
                >

                <label for="activity-description">Descripción</label>
                <textarea
                    id="activity-description"
                    name="description"
                    rows="4"
                    required
                ><?=admin_e($editActivity['description'] ?? '')?></textarea>

                <div class="admin-actions">
                    <button class="admin-button" type="submit">
                        Guardar actividad
                    </button>

                    <?php if ($editActivity): ?>
                        <a class="admin-button secondary" href="dashboard.php?section=actividades">
                            Cancelar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="admin-panel">
            <h2>Actividades registradas (<?=count($activities)?>)</h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Preguntas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($activities as $r): ?>
                        <tr>
                            <td>
                                <strong><?=admin_e($r['title'])?></strong>
                                <p><?=admin_e($r['description'])?></p>
                            </td>

                            <td><?=admin_e($r['questions_count'])?></td>

                            <td>
                                <div class="admin-actions">
                                    <a
                                        class="admin-button secondary"
                                        href="dashboard.php?section=actividades&edit_activity=<?=(int)$r['id']?>"
                                    >
                                        Editar
                                    </a>

                                    <a class="admin-button secondary" href="dashboard.php?section=preguntas">
                                        Preguntas
                                    </a>

                                    <form
                                        class="admin-inline"
                                        method="post"
                                        onsubmit="return confirm('¿Eliminar esta actividad?');"
                                    >
                                        <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                        <input type="hidden" name="section" value="actividades">
                                        <input type="hidden" name="action" value="activity_delete">
                                        <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                        <button class="admin-button danger" type="submit">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$activities): ?>
                        <tr>
                            <td colspan="3">No hay actividades registradas.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($seccion === 'preguntas'): ?>

        <h2>Gestión de preguntas</h2>

        <div class="admin-panel">
            <h2><?= $editQuestion ? 'Editar pregunta' : 'Crear pregunta' ?></h2>

            <?php if (!$activities): ?>
                <p>Primero debes crear una actividad.</p>
            <?php else: ?>

                <form class="admin-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                    <input type="hidden" name="section" value="preguntas">
                    <input type="hidden" name="action" value="question_save">
                    <input type="hidden" name="id" value="<?=admin_e($editQuestion['id'] ?? 0)?>">

                    <label for="question-activity">Actividad</label>
                    <select id="question-activity" name="activity_id" required>
                        <option value="">Selecciona una actividad</option>

                        <?php foreach ($activities as $a): ?>
                            <option
                                value="<?=(int)$a['id']?>"
                                <?=((int)($editQuestion['activity_id'] ?? 0) === (int)$a['id']) ? 'selected' : ''?>
                            >
                                <?=admin_e($a['title'])?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="question-text">Pregunta</label>
                    <textarea id="question-text" name="question" rows="3" required><?=admin_e($editQuestion['question'] ?? '')?></textarea>

                    <label for="option-a">Opción A</label>
                    <input id="option-a" name="option_a" maxlength="255" required value="<?=admin_e($editQuestion['option_a'] ?? '')?>">

                    <label for="option-b">Opción B</label>
                    <input id="option-b" name="option_b" maxlength="255" required value="<?=admin_e($editQuestion['option_b'] ?? '')?>">

                    <label for="option-c">Opción C</label>
                    <input id="option-c" name="option_c" maxlength="255" required value="<?=admin_e($editQuestion['option_c'] ?? '')?>">

                    <label for="correct-option">Respuesta correcta</label>
                    <select id="correct-option" name="correct_option" required>
                        <option value="a" <?=($editQuestion['correct_option'] ?? 'a') === 'a' ? 'selected' : ''?>>Opción A</option>
                        <option value="b" <?=($editQuestion['correct_option'] ?? '') === 'b' ? 'selected' : ''?>>Opción B</option>
                        <option value="c" <?=($editQuestion['correct_option'] ?? '') === 'c' ? 'selected' : ''?>>Opción C</option>
                    </select>

                    <div class="admin-actions">
                        <button class="admin-button" type="submit">
                            Guardar pregunta
                        </button>

                        <?php if ($editQuestion): ?>
                            <a class="admin-button secondary" href="dashboard.php?section=preguntas">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

            <?php endif; ?>
        </div>

        <div class="admin-panel">
            <h2>Preguntas registradas (<?=count($questions)?>)</h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Actividad y pregunta</th>
                            <th>Opciones</th>
                            <th>Correcta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($questions as $r): ?>
                        <tr>
                            <td>
                                <strong><?=admin_e($r['activity_title'])?></strong>
                                <p><?=admin_e($r['question'])?></p>
                            </td>

                            <td>
                                <p>A. <?=admin_e($r['option_a'])?></p>
                                <p>B. <?=admin_e($r['option_b'])?></p>
                                <p>C. <?=admin_e($r['option_c'])?></p>
                            </td>

                            <td><?=strtoupper(admin_e($r['correct_option']))?></td>

                            <td>
                                <div class="admin-actions">
                                    <a
                                        class="admin-button secondary"
                                        href="dashboard.php?section=preguntas&edit_question=<?=(int)$r['id']?>"
                                    >
                                        Editar
                                    </a>

                                    <form
                                        class="admin-inline"
                                        method="post"
                                        onsubmit="return confirm('¿Eliminar esta pregunta?');"
                                    >
                                        <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                        <input type="hidden" name="section" value="preguntas">
                                        <input type="hidden" name="action" value="question_delete">
                                        <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                        <button class="admin-button danger" type="submit">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$questions): ?>
                        <tr>
                            <td colspan="4">No hay preguntas registradas.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($seccion === 'usuarios'): ?>

        <h2>Gestión de usuarios</h2>

        <div class="admin-panel">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($users as $r): ?>
                        <tr>
                            <td><?=admin_e($r['name'])?></td>
                            <td><?=admin_e($r['email'])?></td>
                            <td><?=((int)$r['active'] === 1) ? 'Activo' : 'Inactivo'?></td>
                            <td><?=admin_e($r['created_at'])?></td>

                            <td>
                                <form
                                    method="post"
                                    onsubmit="return confirm('¿Cambiar el estado de esta cuenta?');"
                                >
                                    <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                    <input type="hidden" name="section" value="usuarios">
                                    <input type="hidden" name="action" value="user_toggle">
                                    <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                    <button
                                        class="admin-button <?=((int)$r['active'] === 1) ? 'danger' : ''?>"
                                        type="submit"
                                    >
                                        <?=((int)$r['active'] === 1) ? 'Desactivar' : 'Activar'?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$users): ?>
                        <tr>
                            <td colspan="5">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <p class="admin-muted">
                Desde esta sección se activa o desactiva la cuenta.
            </p>
        </div>

    <?php elseif ($seccion === 'mensajes'): ?>

        <h2>Mensajes recibidos</h2>

        <div class="admin-panel">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Remitente</th>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($messages as $r): ?>
                        <tr>
                            <td><?=admin_e($r['fecha_creacion'])?></td>

                            <td>
                                <strong><?=admin_e($r['nombre'])?></strong>
                                <p><?=admin_e($r['correo'])?></p>
                            </td>

                            <td><?=admin_e($r['asunto'] ?? '')?></td>

                            <td><p><?=admin_e($r['contenido'])?></p></td>

                            <td>
                                <form
                                    method="post"
                                    onsubmit="return confirm('¿Eliminar este mensaje?');"
                                >
                                    <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                    <input type="hidden" name="section" value="mensajes">
                                    <input type="hidden" name="action" value="message_delete">
                                    <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                    <button class="admin-button danger" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$messages): ?>
                        <tr>
                            <td colspan="5">Todavía no hay mensajes recibidos.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($seccion === 'resultados'): ?>

        <h2>Resultados de actividades</h2>

        <div class="admin-panel">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Actividad</th>
                            <th>Puntaje</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td>
                                <strong><?=admin_e($r['name'])?></strong>
                                <p><?=admin_e($r['email'])?></p>
                            </td>

                            <td><?=admin_e($r['activity_title'])?></td>

                            <td>
                                <?=admin_e($r['score'])?>
                                /
                                <?=admin_e($r['total'])?>
                            </td>

                            <td><?=admin_e($r['created_at'])?></td>

                            <td>
                                <form
                                    method="post"
                                    onsubmit="return confirm('¿Eliminar este resultado? Esta acción no se puede deshacer.');"
                                >
                                    <input type="hidden" name="csrf_token" value="<?=admin_e($_SESSION['csrf_admin'])?>">
                                    <input type="hidden" name="section" value="resultados">
                                    <input type="hidden" name="action" value="result_delete">
                                    <input type="hidden" name="id" value="<?=(int)$r['id']?>">

                                    <button class="admin-button danger" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$results): ?>
                        <tr>
                            <td colspan="5">Todavía no hay resultados guardados.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <p class="admin-muted">
                Los resultados se consultan desde la tabla results.
            </p>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
