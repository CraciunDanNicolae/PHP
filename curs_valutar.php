<?php
function get_curs_valutar()
{
    $url = "https://api.exchangerate-api.com/v4/latest/RON";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => 'Eroare cURL: ' . curl_error($ch)];
    }
    curl_close($ch);

    if ($http_code == 200) {
        $data = json_decode($response, true);
        return $data;
    } else {
        return ['error' => "Nu s-au putut prelua datele. Cod HTTP: $http_code"];
    }
}

$date_curs = get_curs_valutar();
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <title>Integrare Externă</title>
    <link rel="stylesheet" href="assets/stil.css">
</head>

<body>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <h2>🌍 Integrare API Extern (Curs Valutar)</h2>
        <p>Date preluate live folosind <strong>cURL</strong>.</p>

        <?php if (isset($date_curs['error'])): ?>
            <p style="color: red;"><?php echo $date_curs['error']; ?></p>
        <?php else: ?>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                <h3>Baza: 1 RON (Leu Românesc)</h3>
                <ul style="list-style: none; padding: 0; font-size: 18px;">
                    <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                        🇪🇺 1 EUR = <strong><?php echo number_format(1 / $date_curs['rates']['EUR'], 4); ?> RON</strong>
                    </li>
                    <li style="padding: 5px 0;">
                        🇺🇸 1 USD = <strong><?php echo number_format(1 / $date_curs['rates']['USD'], 4); ?> RON</strong>
                    </li>
                </ul>
                <small style="color: #777;">Actualizat la:
                    <?php echo date('d-m-Y', $date_curs['time_last_updated']); ?></small>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>