<?php
// $p lo define header.php ('' en la raíz, '../' en subcarpetas)
$p = $p ?? '';
?>
</main>

<footer class="pie">
    <div class="pie-contenedor">

        <!-- Marca + enlaces -->
        <div class="pie-principal">

            <div class="pie-marca">
                <a class="marca marca-footer" href="<?= $p ?>index.php" aria-label="BIOGERMINACIÓN, ir al inicio">
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

                <p>Una semilla de conocimiento para un mundo más verde.</p>
            </div>

            <nav class="footer-enlaces" aria-label="Enlaces del pie de página">
                <a href="<?= $p ?>germinacion.php">Germinación</a>
                <a href="<?= $p ?>factores.php">Factores</a>
                <a href="<?= $p ?>recursos.php">Recursos</a>
                <a href="<?= $p ?>cuestionario.php">Cuestionario</a>
                <a href="<?= $p ?>contacto.php">Contacto</a>
                <a href="<?= $p ?>admin/login.php">Administración</a>
            </nav>

        </div>

        <!-- Logos institucionales -->
        <div class="pie-aliados">
            <p class="pie-aliados-titulo">Proyecto desarrollado con</p>

            <div class="pie-logos">
                <figure class="pie-logo">
                    <img
                        src="<?= $p ?>./assets/img/logo_pascual.png"
                        alt="Logo de Pascual Bravo"
                        height="56"
                        onerror="this.closest('.pie-logo').classList.add('sin-imagen')"
                    >
                    <figcaption>Pascual Bravo</figcaption>
                </figure>

                <figure class="pie-logo">
                    <img
                        src="<?= $p ?>./assets/img/IE-Republica-de-Uruguay.png"
                        alt="Logo de República de Uruguay"
                        height="56"
                        onerror="this.closest('.pie-logo').classList.add('sin-imagen')"
                    >
                    <figcaption>República de Uruguay</figcaption>
                </figure>
            </div>
        </div>

        <!-- Legal -->
        <div class="pie-legal">
            <small>© <?= date('Y') ?> BIOGERMINACIÓN S.A.S. Proyecto educativo PIA.</small>
        </div>

    </div>
</footer>

<script src="<?= $p ?>assets/js/app.js"></script>
</body>
</html>