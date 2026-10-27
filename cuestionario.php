
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tituloPagina = 'Cuestionario | BIOGERMINACIÓN';

$sesionIniciada = isset($_SESSION['user'])
    && is_array($_SESSION['user'])
    && !empty($_SESSION['user']['id']);

require_once __DIR__ . '/partials/header.php';
?>

<section class="quiz-seccion">
    <div class="quiz-caja">

        <?php if (!$sesionIniciada): ?>

            <div class="quiz-intro">
                <span class="sobretitulo">ACCESO AL CUESTIONARIO</span>

                <h1>¡Espera un momento!</h1>

                <p>
                    Debes iniciar sesión para realizar el cuestionario
                    y comprobar tus conocimientos sobre la germinación.
                </p>

                <div class="quiz-marca">BIO<span>GERMINACIÓN</span></div>
            </div>

            <div class="quiz-acceso-mensaje" role="alert">
                <p>Debes iniciar sesión para realizar el cuestionario.</p>

                <a href="auth/login.php" class="boton boton-verde boton-completo">
                    Iniciar sesión →
                </a>

                <a href="recursos.php" class="quiz-volver">
                    Volver a los recursos educativos
                </a>
            </div>

        <?php else: ?>

            <div class="quiz-intro">
                <span class="sobretitulo">PON A PRUEBA LO APRENDIDO</span>

                <h1>¿Cuánto sabes sobre la <em>germinación?</em></h1>

                <p>
                    Responde las preguntas y comprueba cuánto
                    aprendiste sobre el inicio de la vida de una planta.
                </p>

                <div class="quiz-marca">BIO<span>GERMINACIÓN</span></div>
            </div>

            <form id="quizForm" class="quiz-form">

                <div class="pregunta">
                    <p><span>01</span> ¿Qué necesita absorber una semilla para iniciar la germinación?</p>

                    <label><input type="radio" name="p1" value="a" required> Agua</label>
                    <label><input type="radio" name="p1" value="b"> Arena seca</label>
                    <label><input type="radio" name="p1" value="c"> Fertilizante siempre</label>
                </div>

                <div class="pregunta">
                    <p><span>02</span> ¿Qué estructura suele emerger primero durante la germinación?</p>

                    <label><input type="radio" name="p2" value="a" required> La flor</label>
                    <label><input type="radio" name="p2" value="b"> La raíz</label>
                    <label><input type="radio" name="p2" value="c"> El fruto</label>
                </div>

                <div class="pregunta">
                    <p><span>03</span> ¿Todas las semillas necesitan luz para germinar?</p>

                    <label><input type="radio" name="p3" value="a" required> Sí, todas sin excepción</label>
                    <label><input type="radio" name="p3" value="b"> No, depende de la especie</label>
                    <label><input type="radio" name="p3" value="c"> Ninguna necesita agua</label>
                </div>

                <button class="boton boton-verde boton-completo" type="submit">
                    Comprobar respuestas →
                </button>

                <div
                    id="resultadoQuiz"
                    class="resultado-quiz"
                    aria-live="polite"
                ></div>

            </form>

        <?php endif; ?>

    </div>
</section>

<?php if ($sesionIniciada): ?>
<script>
document.getElementById('quizForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const respuestas = {
        p1: 'a',
        p2: 'b',
        p3: 'b'
    };

    let puntaje = 0;

    for (const pregunta in respuestas) {
        const seleccionada = this.querySelector(
            'input[name="' + pregunta + '"]:checked'
        );

        if (seleccionada && seleccionada.value === respuestas[pregunta]) {
            puntaje++;
        }
    }

    const resultado = document.getElementById('resultadoQuiz');

    resultado.textContent =
        'Obtuviste ' + puntaje + ' de 3 respuestas correctas. ' +
        (puntaje === 3
            ? '¡Excelente trabajo!'
            : 'Puedes repasar los contenidos e intentarlo nuevamente.');
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
