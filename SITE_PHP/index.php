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

$products_json = json_encode($products);
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