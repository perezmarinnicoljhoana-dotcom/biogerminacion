<?php
require_once __DIR__ . '/../config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Completa los datos. La contraseña debe tener mínimo 8 caracteres.';
    } else {
        try {
            $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $check->execute([$email]);
            if ($check->fetch()) {
                $error = 'Ese correo ya está registrado.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'usuario')");
                $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'No fue posible crear la cuenta. Verifica la conexión y los datos.';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Crear cuenta | BIOGERMINACIÓN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body class="auth-bg">
<form class="form-card" method="post">
<a href="../index.php" class="back">← Volver al inicio</a>
<h1>Comienza a aprender</h1><p>Crea tu cuenta para explorar la germinación y completar actividades educativas.</p>
<?php if ($error !== ''): ?><div class="alert"><?=e($error)?></div><?php endif; ?>
<label for="name">Nombre completo</label><input id="name" name="name" maxlength="120" required value="<?=e($_POST['name'] ?? '')?>">
<label for="email">Correo electrónico</label><input id="email" name="email" type="email" required value="<?=e($_POST['email'] ?? '')?>">
<label for="password">Contraseña (mínimo 8 caracteres)</label><input id="password" name="password" type="password" minlength="8" required>
<button class="btn full" type="submit">Crear mi cuenta</button>
<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</form>
</body></html>
