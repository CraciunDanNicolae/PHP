<?php
include_once 'config/db.php';
$message = $_SESSION['login_message'] ?? '';
unset($_SESSION['login_message']);
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$captcha_num1 = rand(1, 10);
$captcha_num2 = rand(1, 10);
$_SESSION['captcha_login'] = $captcha_num1 + $captcha_num2;
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magazin Online</title>
    <link rel="stylesheet" href="assets/stil.css">
    <link rel="stylesheet" href="assets/login.css">
</head>

<body>
    <header>
        <h1>Cele mai bune preturi din Romania!</h1>
        <p>🔥Acum la dispozitia ta🔥</p>
    </header>

    <nav>
        <a href="index.php">🛒Produse</a>
        <a href="cos.php">Cos de cumparaturi🛒</a>
    </nav>

    <div class="container">
        <h2>Autentificare</h2>

        <form id="login-form" class="login-form" method="POST" action="backend/auth.php">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="username">Nume utilizator / Email:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Parolă:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="captcha">Verificare (<?php echo $captcha_num1 . " + " . $captcha_num2; ?> = ?):</label>
                <input type="number" id="captcha" name="captcha" required style="width: 80px;">
            </div>

            <button type="submit" class="login-button">Intră în cont</button>

            <p class="register-link">
                Nu ai cont? <a href="register.php">Înregistrează-te aici!</a>
            </p>
        </form>

        <p id="login-message" style="color: red; margin-top: 15px;">
            <?php echo htmlspecialchars($message); ?>
        </p>

    </div>
</body>

</html>