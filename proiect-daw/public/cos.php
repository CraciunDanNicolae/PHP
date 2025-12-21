<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coș de Cumpărături - Magazin Online</title>
    <link rel="stylesheet" href="assets/stil.css">
    <link rel="stylesheet" href="assets/cos.css">
</head>

<body>
    <header>
        <h1>Cele mai bune preturi din Romania!</h1>
        <p>🔥Acum la dispozitia ta🔥</p>
    </header>

    <nav>
        <a href="index.php">🛒Produse</a>
    </nav>

    <div class="container">
        <h2>Coșul Tău de Cumpărături</h2>

        <div class = "cos" id="cos-articole">
            </div>

        <div class="sumar-comanda">
            <h3>Sumar Comandă</h3>
            <div id="subtotal">Subtotal: 0,00 lei</div>
            <div id="transport">Transport: 15,00 lei</div>
            <div class="total">
                <div id="total-final">TOTAL: 15,00 lei</div>
            </div>
            <button class="finalizare-comanda-button">Finalizează Comanda</button>
        </div>
    </div>

    <script src="assets/cos_script.js" defer></script>
</body>

</html>