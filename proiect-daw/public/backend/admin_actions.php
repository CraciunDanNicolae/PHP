<?php
include_once '../config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Eroare de securitate: Token CSRF invalid.");
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add_product') {
        $nume = trim($_POST['nume_produs']);
        $pret = floatval($_POST['pret_vanzare']);
        $pret_vechi = !empty($_POST['pret_vechi']) ? floatval($_POST['pret_vechi']) : NULL;
        $imagine = trim($_POST['imagine_url']);

        $id_categorie = 1;
        $id_furnizor = 1;

        $procent = 0;
        if ($pret_vechi && $pret_vechi > $pret) {
            $procent = round((($pret_vechi - $pret) / $pret_vechi) * 100);
        }

        $stmt = $conn->prepare("INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, Pret_Vechi, Imagine_URL, Procent_Reducere, ID_Categorie, ID_Furnizor) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sddsiii", $nume, $pret, $pret_vechi, $imagine, $procent, $id_categorie, $id_furnizor);

        if ($stmt->execute()) {
        }
        $stmt->close();
        header("Location: ../index.php");
        exit;

    } elseif ($action === 'delete_product') {
        $id_produs = intval($_POST['id_produs']);

        $stmt_cart = $conn->prepare("DELETE FROM Detalii_Cos WHERE ID_Produs = ?");
        $stmt_cart->bind_param("i", $id_produs);
        $stmt_cart->execute();
        $stmt_cart->close();

        $stmt = $conn->prepare("DELETE FROM PRODUS WHERE ID_Produs = ?");
        $stmt->bind_param("i", $id_produs);

        if ($stmt->execute()) {
        }
        $stmt->close();
        header("Location: ../index.php");
        exit;

    } elseif ($action === 'update_price') {
        $id_produs = intval($_POST['id_produs']);
        $pret = floatval($_POST['pret_vanzare']);
        $pret_vechi = !empty($_POST['pret_vechi']) ? floatval($_POST['pret_vechi']) : NULL;

        $procent = 0;
        if ($pret_vechi && $pret_vechi > $pret) {
            $procent = round((($pret_vechi - $pret) / $pret_vechi) * 100);
        }

        $stmt = $conn->prepare("UPDATE PRODUS SET Pret_Vanzare = ?, Pret_Vechi = ?, Procent_Reducere = ? WHERE ID_Produs = ?");
        $stmt->bind_param("ddii", $pret, $pret_vechi, $procent, $id_produs);
        $stmt->execute();
        $stmt->close();

        header("Location: ../index.php");
        exit;
    }
}

header("Location: ../index.php");
exit;
?>