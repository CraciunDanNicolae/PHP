<?php
include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Eroare de securitate: Token CSRF invalid sau lipsă.");
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        if (!isset($_POST['captcha']) || !isset($_SESSION['captcha_register']) || intval($_POST['captcha']) !== $_SESSION['captcha_register']) {
            $_SESSION['register_message'] = "Verificare anti-bot eșuată! Calcul incorect.";
            header("Location: ../register.php");
            exit;
        }

        $username = trim($_POST['reg_username']);
        $email = trim($_POST['reg_email']);
        $password = $_POST['reg_password'];
        $confirm_password = $_POST['confirm_password'];
        $role = 'Normal';
        $data_inregistrare = date('Y-m-d H:i:s');

        if ($password !== $confirm_password) {
            $_SESSION['register_message'] = "Parolele nu coincid!";
            header("Location: ../register.php");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO USER (Nume_Utilizator, Email, Parola, Rol, Data_Inregistrare) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $password_hash, $role, $data_inregistrare);

        if ($stmt->execute()) {
            require_once __DIR__ . '/../mailer.php';
            trimiteEmailBunVenit($email, $username);

            $_SESSION['login_message'] = "Înregistrare reușită! Vă rugăm să vă autentificați.";
            header("Location: ../login.php");
        } else {
            $_SESSION['register_message'] = "Eroare la înregistrare. Emailul sau numele de utilizator ar putea exista deja.";
            header("Location: ../register.php");
        }
        $stmt->close();
        exit;

    } elseif ($action === 'login') {
        if (!isset($_POST['captcha']) || !isset($_SESSION['captcha_login']) || intval($_POST['captcha']) !== $_SESSION['captcha_login']) {
            $_SESSION['login_message'] = "Verificare anti-bot eșuată! Calcul incorect.";
            header("Location: ../login.php");
            exit;
        }

        $username_or_email = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT ID_user, Nume_Utilizator, Parola, Rol FROM USER WHERE Nume_Utilizator = ? OR Email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Parola'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['ID_user'];
                $_SESSION['username'] = $user['Nume_Utilizator'];
                $_SESSION['role'] = $user['Rol'];
                header("Location: ../index.php");
                exit;
            }
        }
        $_SESSION['login_message'] = "Nume de utilizator/Email sau parolă incorectă.";
        header("Location: ../login.php");
        exit;
    }
}

header("Location: ../index.php");
exit;
?>