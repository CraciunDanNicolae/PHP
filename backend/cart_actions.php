<?php
include_once '../config/db.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];
$user_id = $_SESSION['user_id'] ?? null;
$action = $_POST['action'] ?? '';
$product_id = intval($_POST['product_id'] ?? 0);

if (!$user_id) {
    $response['message'] = "Trebuie să fii autentificat pentru a gestiona coșul.";
    echo json_encode($response);
    exit;
}


function getOrCreateCartId($conn, $user_id)
{
    $stmt_find_cart = $conn->prepare("SELECT ID_Cos FROM COS_CUMPARATURI WHERE ID_User = ?");
    $stmt_find_cart->bind_param("i", $user_id);
    $stmt_find_cart->execute();
    $result = $stmt_find_cart->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['ID_Cos'];
    } else {
        $stmt_create_cart = $conn->prepare("INSERT INTO COS_CUMPARATURI (ID_User) VALUES (?)");
        $stmt_create_cart->bind_param("i", $user_id);
        if ($stmt_create_cart->execute()) {
            return $conn->insert_id;
        }
        return null;
    }
}

$cos_id = getOrCreateCartId($conn, $user_id);

if (!$cos_id) {
    $response['message'] = "Eroare la obținerea/crearea coșului.";
    echo json_encode($response);
    exit;
}


switch ($action) {

    case 'add_to_cart':
        $quantity = 1;

        if ($product_id <= 0) {
            $response['message'] = 'ID produs invalid.';
            break;
        }

        $stmt_check = $conn->prepare("SELECT ID_Detaliu_Cos, Cantitate FROM Detalii_Cos WHERE ID_Cos = ? AND ID_Produs = ?");
        $stmt_check->bind_param("ii", $cos_id, $product_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $detail = $result_check->fetch_assoc();
            $new_quantity = $detail['Cantitate'] + $quantity;

            $stmt_update = $conn->prepare("UPDATE Detalii_Cos SET Cantitate = ? WHERE ID_Detaliu_Cos = ?");
            $stmt_update->bind_param("ii", $new_quantity, $detail['ID_Detaliu_Cos']);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO Detalii_Cos (ID_Cos, ID_Produs, Cantitate) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("iii", $cos_id, $product_id, $quantity);
            $stmt_insert->execute();
            $stmt_insert->close();
        }
        $stmt_check->close();

        $stmt_name = $conn->prepare("SELECT Nume_Produs FROM PRODUS WHERE ID_Produs = ?");
        $stmt_name->bind_param("i", $product_id);
        $stmt_name->execute();
        $product_name = $stmt_name->get_result()->fetch_assoc()['Nume_Produs'] ?? 'Produs';
        $stmt_name->close();

        $response['success'] = true;
        $response['message'] = "{$product_name} a fost adăugat în coș!";
        $response['cart_id'] = $cos_id;
        break;


    case 'get_items':
        $sql = "
            SELECT 
                DC.ID_Detaliu_Cos, 
                DC.Cantitate, 
                P.ID_Produs, 
                P.Nume_Produs, 
                P.Pret_Vanzare, 
                P.Imagine_URL, 
                P.Pret_Vechi 
            FROM Detalii_Cos DC
            JOIN PRODUS P ON DC.ID_Produs = P.ID_Produs
            WHERE DC.ID_Cos = ?
        ";

        $stmt_items = $conn->prepare($sql);
        $stmt_items->bind_param("i", $cos_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        $items = [];

        if ($result_items->num_rows > 0) {
            $items = $result_items->fetch_all(MYSQLI_ASSOC);

            foreach ($items as &$item) {
                $item['Pret_Vanzare'] = floatval($item['Pret_Vanzare']);
                $item['Pret_Vechi'] = $item['Pret_Vechi'] ? floatval($item['Pret_Vechi']) : null;
            }
        }

        $response['success'] = true;
        $response['items'] = $items;
        break;


    case 'update_quantity':
        $new_quantity = intval($_POST['quantity'] ?? 1);

        $stmt = $conn->prepare("UPDATE Detalii_Cos SET Cantitate = ? WHERE ID_Cos = ? AND ID_Produs = ?");
        $stmt->bind_param("iii", $new_quantity, $cos_id, $product_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Cantitatea a fost actualizată.";
        } else {
            $response['message'] = "Eroare la actualizarea cantității.";
        }
        $stmt->close();
        break;


    case 'remove_item':
        $stmt = $conn->prepare("DELETE FROM Detalii_Cos WHERE ID_Cos = ? AND ID_Produs = ?");
        $stmt->bind_param("ii", $cos_id, $product_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Produsul a fost eliminat din coș.";
        } else {
            $response['message'] = "Eroare la eliminarea produsului.";
        }
        $stmt->close();
        break;

    default:
        $response['message'] = 'Acțiune de coș invalidă.';
        break;
}

echo json_encode($response);
?>