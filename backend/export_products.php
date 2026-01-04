<?php
include_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Acces interzis.");
}


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=lista_produse.xls");
header("Pragma: no-cache");
header("Expires: 0");


$sql = "SELECT P.Nume_Produs, P.Pret_Vanzare, C.Nume_categorie 
        FROM PRODUS P 
        JOIN CATEGORIE C ON P.ID_Categorie = C.ID_categorie";

$result = $conn->query($sql);


echo "<table border='1'>";
echo "<tr>
        <th>Nume Produs</th>
        <th>Categorie</th>
        <th>Pret (RON)</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Nume_Produs']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Nume_categorie']) . "</td>";
    echo "<td>" . str_replace('.', ',', $row['Pret_Vanzare']) . "</td>";
    echo "</tr>";
}
echo "</table>";
exit;
?>