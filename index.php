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

$newsletter_msg = $_SESSION['newsletter_message'] ?? '';
unset($_SESSION['newsletter_message']);

function getCursEuro()
{
    $url = "https://www.bnr.ro/nbrfxrates.xml";
    $xmlContent = @file_get_contents($url);

    if ($xmlContent === FALSE)
        return null;

    $xml = simplexml_load_string($xmlContent);
    if ($xml === FALSE)
        return null;

    foreach ($xml->Body->Cube->Rate as $rate) {
        if ((string) $rate['currency'] === 'EUR')
            return floatval($rate);
    }
    return null;
}
$cursEuro = getCursEuro();
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazin online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/stil.css">
</head>

<body>
    <header>
        <h1>Cele mai bune preturi din Romania!</h1>
        <p>🔥Acum la dispozitia ta🔥</p>
        <?php if ($cursEuro): ?>
            <div
                style="background: rgba(255,255,255,0.2); padding: 5px; margin-top: 10px; border-radius: 4px; display: inline-block;">
                Curs BNR: 1 EUR = <strong><?php echo $cursEuro; ?></strong> RON
                <small>(Actualizat automat din sursă externă)</small>
            </div>
        <?php endif; ?>
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

    <?php if ($newsletter_msg): ?>
        <div class="container"
            style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; text-align: center; margin-bottom: 20px;">
            <?php echo htmlspecialchars($newsletter_msg); ?>
        </div>
    <?php endif; ?>

    <?php if ($is_admin): ?>
        <div class="container" style="border: 2px solid #e74c3c; padding: 20px; margin-bottom: 20px; border-radius: 8px;">
            <h2 style="color: #e74c3c;">Panou Administrator</h2>

            <div style="margin-bottom: 20px; display: flex; gap: 10px;">
                <a href="backend/export_products.php"
                    style="background-color: #27ae60; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">📄
                    Exportă Produse (Excel)</a>
                <a href="statistici.php"
                    style="background-color: #8e44ad; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">📊
                    Vezi Statistici Grafice</a>
            </div>

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

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Normal'): ?>
        <div class="container"
            style="background-color: #e3f2fd; border-radius: 8px; padding: 30px; text-align: center; margin-top: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h2 style="color: #2980b9;">📩 Rămâi la curent cu noutățile!</h2>
            <p style="font-size: 1.1em; color: #555;">Abonează-te la newsletter pentru a primi cele mai bune oferte direct
                pe email.</p>

            <form action="backend/newsletter.php" method="POST" style="margin-top: 20px;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit"
                    style="background-color: #2980b9; color: white; border: none; padding: 12px 25px; font-size: 16px; cursor: pointer; border-radius: 5px; transition: background 0.3s;">Mă
                    abonez acum</button>
            </form>
        </div>
    <?php endif; ?>

    <script>
        const ALL_PRODUCTS = <?php echo $products_json; ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/produse_script.js" defer></script>
</body>

</html>