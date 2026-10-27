
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Página actual.
$paginaActual = basename($_SERVER['PHP_SELF']);

// Título predeterminado.
if (!isset($tituloPagina)) {
    $tituloPagina = 'BIOGERMINACIÓN | Aprende cómo nace una planta';
}

// Detectar si la página está en una subcarpeta.
$subcarpetas = ['auth', 'admin', 'usuario'];
$carpetaActual = basename(dirname($_SERVER['SCRIPT_NAME']));
$enSubcarpeta = in_array($carpetaActual, $subcarpetas, true);
$p = $enSubcarpeta ? '../' : '';

// Obtener la sesión creada en login.php.
$usuarioSesion = $_SESSION['user'] ?? null;

$sesionIniciada = is_array($usuarioSesion)
    && !empty($usuarioSesion['id']);

// Obtener nombre y rol.
$nombreUsuario = '';
$rolUsuario = '';

if ($sesionIniciada) {
    $nombreUsuario = trim($usuarioSesion['name'] ?? '');
    $rolUsuario = strtolower(trim($usuarioSesion['role'] ?? ''));

    if ($nombreUsuario === '') {
        $nombreUsuario = 'Mi cuenta';
    }
}

// Determinar el dashboard según el rol.
$dashboardUsuario = '';

if ($sesionIniciada) {
    if ($rolUsuario === 'admin') {
        $dashboardUsuario = $p . 'admin/dashboard.php';
    } elseif ($rolUsuario === 'usuario') {
        $dashboardUsuario = $p . 'usuario/dashboard.php';
    }
}

// Proteger el nombre del usuario.
$nombreUsuarioSeguro = htmlspecialchars(
    $nombreUsuario,
    ENT_QUOTES,
    'UTF-8'
);

$tituloSeguro = htmlspecialchars(
    $tituloPagina,
    ENT_QUOTES,
    'UTF-8'
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="Plataforma educativa sobre la germinación de las semillas."
    >

    <title><?= $tituloSeguro ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="<?= $p ?>assets/css/estilos.css">
</head>

<body>

<header class="encabezado">
    <div class="contenedor-encabezado">

        <!-- LOGO -->
        <a
            class="marca"
            href="<?= $p ?>index.php"
            aria-label="BIOGERMINACIÓN, ir al inicio"
        >
            <span class="marca-logo">
                <img
                    src="<?= $p ?>assets/img/logo.png"
                    alt="Logo de BIOGERMINACIÓN"
                    width="58"
                    height="58"
                >
            </span>

            <span class="marca-texto">
                <span class="marca-nombre">
                    BIO<span class="verde">GERMINACIÓN</span>
                </span>

                <small>APRENDER · CULTIVAR · CRECER</small>
            </span>
        </a>

        <!-- MENÚ PARA CELULARES -->
        <button
            class="menu-toggle"
            type="button"
            aria-label="Abrir menú"
            aria-expanded="false"
            aria-controls="menu-principal"
        >
            ☰
        </button>

        <!-- NAVEGACIÓN -->
        <nav
            class="navegacion"
            id="menu-principal"
            aria-label="Menú principal"
        >

            <a
                href="<?= $p ?>index.php"
                class="<?= $paginaActual === 'index.php' ? 'activo' : '' ?>"
            >Inicio</a>

            <a
                href="<?= $p ?>germinacion.php"
                class="<?= $paginaActual === 'germinacion.php' ? 'activo' : '' ?>"
            >Germinación</a>

            <a
                href="<?= $p ?>factores.php"
                class="<?= $paginaActual === 'factores.php' ? 'activo' : '' ?>"
            >Factores</a>

            <a
                href="<?= $p ?>recursos.php"
                class="<?= $paginaActual === 'recursos.php' ? 'activo' : '' ?>"
            >Recursos</a>

            <a
                href="<?= $p ?>cuestionario.php"
                class="<?= $paginaActual === 'cuestionario.php' ? 'activo' : '' ?>"
            >Cuestionario</a>

            <a
                href="<?= $p ?>contacto.php"
                class="<?= $paginaActual === 'contacto.php' ? 'activo' : '' ?>"
            >Contáctanos</a>

            <?php if ($sesionIniciada): ?>

                <!-- NOMBRE DEL USUARIO -->
                <span class="usuario-saludo">
                    <span class="usuario-icono" aria-hidden="true">♙</span>
                    <span class="usuario-nombre">
                        <?= $nombreUsuarioSeguro ?>
                    </span>
                </span>

                <!-- ACCESO AL DASHBOARD SEGÚN EL ROL -->
                <?php if ($dashboardUsuario !== ''): ?>
                    <a
                        class="nav-boton"
                        href="<?= htmlspecialchars($dashboardUsuario, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        Mi espacio
                    </a>
                <?php endif; ?>

                <!-- CERRAR SESIÓN -->
                <a
                    class="nav-cerrar-sesion"
                    href="<?= $p ?>auth/logout.php"
                >
                    Cerrar sesión
                </a>

            <?php else: ?>

                <!-- OPCIONES PARA VISITANTES -->
                <a
                    class="nav-ingresar"
                    href="<?= $p ?>auth/login.php"
                >
                    Ingresar
                </a>

                <a
                    class="nav-boton"
                    href="<?= $p ?>auth/register.php"
                >
                    Crear cuenta
                </a>

            <?php endif; ?>

        </nav>
    </div>
</header>

<main>
