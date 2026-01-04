<?php
include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
    }


    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Eroare de securitate (CSRF).");
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT Nume_Utilizator, Email FROM USER WHERE ID_user = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        require_once __DIR__ . '/../mailer.php';

        if (trimiteEmailNewsletter($user['Email'], $user['Nume_Utilizator'])) {
            $_SESSION['newsletter_message'] = "✅ Te-ai abonat cu succes! Verifică-ți email-ul.";
        } else {
            $_SESSION['newsletter_message'] = "❌ Eroare la trimiterea email-ului.";
        }
    }

    header("Location: ../index.php");
    exit;
}