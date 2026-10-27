<?php
$tituloPagina = 'Inicio | BIOGERMINACIÓN';
require_once __DIR__ . '/partials/header.php';
?>

<section class="hero" id="inicio">
    <div class="hero-texto">
        <span class="etiqueta">
            <span class="punto"></span>
            EXPLORA EL MUNDO NATURAL
        </span>

        <h1>Todo gran comienzo<br>viene de una <em>semilla.</em></h1>

        <p>
            Descubre cómo una pequeña semilla despierta,
            crece y se transforma en una nueva planta.
            Aprende de forma sencilla, visual e interactiva.
        </p>

        <div class="hero-acciones">
            <a class="boton boton-verde" href="germinacion.php">
                Explorar el proceso →
            </a>

            <a class="enlace" href="factores.php">
                Conoce los factores →
            </a>
        </div>
    </div>

    <div class="hero-imagen">
        <img
            src="./assets/img/photo-1416879595882-3373a0480b5b.avif"
            alt="Plantas verdes en crecimiento"
        >
    </div>
</section>

<section class="franja">
    <div>
        <p>OBSERVA</p>
        <small>Reconoce cada etapa</small>
    </div>

    <div>
        <p>COMPRENDE</p>
        <small> Aprende qué necesita</small>
    </div>

    <div>
        <p>DESCUBRE</p>
        <small> Explora la naturaleza</small>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>