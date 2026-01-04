<?php
include_once 'config/db.php';

if (isset($_POST['export'])) {
    $sql = "SELECT ID_Produs, Nume_Produs, Pret_Vanzare, Pret_Vechi FROM PRODUS";
    $result = $conn->query($sql);

    $csv_content = "ID,Nume Produs,Pret Actual,Pret Vechi\n";
    while ($row = $result->fetch_assoc()) {
        $nume = str_replace(',', ' ', $row['Nume_Produs']);
        $csv_content .= "{$row['ID_Produs']},{$nume},{$row['Pret_Vanzare']},{$row['Pret_Vechi']}\n";
    }

    $zip_filename = tempnam(sys_get_temp_dir(), "zip");
    $zip = new ZipArchive();

    if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $zip->addFromString('lista_produse.csv', $csv_content);
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=export_produse.zip');
        header('Content-Length: ' . filesize($zip_filename));
        readfile($zip_filename);

        unlink($zip_filename);
        exit;
    } else {
        $error = "Nu s-a putut crea arhiva ZIP.";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <title>Modul Export</title>
    <link rel="stylesheet" href="assets/stil.css">
</head>

<body>
    <div class="container" style="max-width: 500px; margin-top: 50px; text-align: center;">
        <h2>📦 Modul Export Date</h2>
        <p>Descarcă lista de produse în format CSV, arhivată ZIP.</p>

        <form method="POST">
            <button type="submit" name="export"
                style="padding: 15px 30px; background: #e67e22; color: white; border: none; font-size: 16px; cursor: pointer;">⬇️
                Descarcă Arhiva ZIP</button>
        </form>
    </div>
</body>

</html>