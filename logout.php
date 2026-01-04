<?php
include_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT Nume_Utilizator, Email, Data_Inregistrare FROM USER WHERE ID_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contul Meu - Magazin Online</title>
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
        <h2>Contul Meu</h2>
        <div class="login-form">
            <p><strong>Nume utilizator:</strong> <?php echo htmlspecialchars($user['Nume_Utilizator']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            <p><strong>Data înregistrării:</strong> <?php echo date("d-m-Y H:i", strtotime($user['Data_Inregistrare'])); ?></p>
            <a href="logout.php?action=logout" class="login-button" style="text-align: center; text-decoration: none; display: block;">Deconectare</a>
        </div>
    </div>
</body>
</html>