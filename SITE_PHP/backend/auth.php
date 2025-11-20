<?php
include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
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
            $_SESSION['login_message'] = "Înregistrare reușită! Vă rugăm să vă autentificați.";
            header("Location: ../login.php");
        } else {
            $_SESSION['register_message'] = "Eroare la înregistrare. Emailul sau numele de utilizator ar putea exista deja.";
            header("Location: ../register.php");
        }
        $stmt->close();
        exit;

    } elseif ($action === 'login') {
        $username_or_email = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT ID_user, Nume_Utilizator, Parola FROM USER WHERE Nume_Utilizator = ? OR Email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Parola'])) {
                $_SESSION['user_id'] = $user['ID_user'];
                $_SESSION['username'] = $user['Nume_Utilizator'];
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