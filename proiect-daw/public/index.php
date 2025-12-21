<?php
include_once 'config/db.php';

$sql = "SELECT ID_Produs, Nume_Produs, Pret_Vanzare, Imagine_URL, Pret_Vechi, Procent_Reducere FROM PRODUS";
$result = $conn->query($sql);
$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$products_json = json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazin online</title>
    <link rel="stylesheet" href="assets/stil.css">
</head>

<body>
    <header>
        <h1>Cele mai bune preturi din Romania!</h1>
        <p>🔥Acum la dispozitia ta🔥</p>
    </header>

    <nav>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php" title="Click pentru a te deloga">
                Bună, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>

        <a href="cos.php">Cos de cumparaturi🛒</a>
    </nav>

    <?php if ($is_admin): ?>
        <div class="container" style="border: 2px solid #e74c3c; padding: 20px; margin-bottom: 20px; border-radius: 8px;">
            <h2 style="color: #e74c3c;">Panou Administrator</h2>

            <h3>Adaugă Produs Nou</h3>
            <form action="backend/admin_actions.php" method="POST" style="display: grid; gap: 10px; max-width: 500px;">
                <input type="hidden" name="action" value="add_product">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label>Nume Produs:</label>
                <input type="text" name="nume_produs" required style="padding: 5px;">

                <label>Preț Vânzare (RON):</label>
                <input type="number" step="0.01" name="pret_vanzare" required style="padding: 5px;">

                <label>Preț Vechi (Opțional):</label>
                <input type="number" step="0.01" name="pret_vechi" style="padding: 5px;">

                <label>URL Imagine:</label>
                <input type="text" name="imagine_url" required style="padding: 5px;" placeholder="https://...">

                <button type="submit"
                    style="background-color: #2ecc71; color: white; border: none; padding: 10px; cursor: pointer;">Adaugă
                    Produs</button>
            </form>

            <hr style="margin: 20px 0;">

            <h3>Gestionează Produse Existente</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9f9f9; text-align: left; position: sticky; top: 0;">
                            <th style="padding: 8px;">Produs</th>
                            <th style="padding: 8px;">Preț & Reducere</th>
                            <th style="padding: 8px; text-align: right;">Acțiuni</th>
                        </tr>
                    </thead>
                    <?php foreach ($products as $prod): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px;"><?php echo htmlspecialchars($prod['Nume_Produs']); ?></td>
                            <td style="padding: 8px;">
                                <form action="backend/admin_actions.php" method="POST"
                                    style="display: flex; align-items: center; gap: 5px;">
                                    <input type="hidden" name="action" value="update_price">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id_produs" value="<?php echo $prod['ID_Produs']; ?>">

                                    <input type="number" step="0.01" name="pret_vanzare"
                                        value="<?php echo $prod['Pret_Vanzare']; ?>" style="width: 70px;" placeholder="Preț Nou"
                                        title="Preț Vânzare">
                                    <input type="number" step="0.01" name="pret_vechi"
                                        value="<?php echo $prod['Pret_Vechi']; ?>" style="width: 70px;" placeholder="Preț Vechi"
                                        title="Preț Vechi (pentru reducere)">

                                    <button type="submit"
                                        style="background-color: #3498db; color: white; border: none; padding: 5px 8px; cursor: pointer;"
                                        title="Salvează modificările">💾</button>
                                </form>
                            </td>
                            <td style="padding: 8px; text-align: right;">
                                <form action="backend/admin_actions.php" method="POST"
                                    onsubmit="return confirm('Sigur vrei să ștergi acest produs?');">
                                    <input type="hidden" name="action" value="delete_product">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id_produs" value="<?php echo $prod['ID_Produs']; ?>">
                                    <button type="submit"
                                        style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; cursor: pointer;">Șterge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Produse</h2>

        <input type="text" id="search-bar" placeholder="Căutați produse...">

        <div class="lista-produse" id="produse-container">
        </div>
    </div>

    <script>
        const ALL_PRODUCTS = <?php echo $products_json; ?>;
    </script>
    <script src="assets/produse_script.js" defer></script>
</body>

</html>