<?php
include_once 'config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT C.Nume_categorie, COUNT(P.ID_Produs) as NrProduse 
        FROM CATEGORIE C 
        LEFT JOIN PRODUS P ON C.ID_categorie = P.ID_Categorie 
        GROUP BY C.ID_categorie";
$result = $conn->query($sql);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['Nume_categorie'];
    $data[] = $row['NrProduse'];
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistici - Magazin Online</title>
    <link rel="stylesheet" href="assets/stil.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <h1>Statistici Magazin</h1>
        <p>Analiză grafică a produselor</p>
    </header>

    <nav>
        <a href="index.php">⬅ Înapoi la Magazin</a>
    </nav>

    <div class="container">
        <h2>Distribuția Produselor pe Categorii</h2>
        <div
            style="max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Număr de Produse',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.6)',
                        'rgba(46, 204, 113, 0.6)',
                        'rgba(155, 89, 182, 0.6)',
                        'rgba(231, 76, 60, 0.6)',
                        'rgba(241, 196, 15, 0.6)'
                    ],
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>

</html>