<?php
define('DB_SERVER', 'db');
define('DB_USERNAME', 'appuser');
define('DB_PASSWORD', 'secret123');
define('DB_NAME', 'appdb');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

$conn->set_charset("utf8");


define('SMTP_HOST_CFG', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT_CFG', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER_CFG', getenv('SMTP_USER') ?: 'magazinonline740@gmail.com');
define('SMTP_PASS_CFG', getenv('SMTP_PASS') ?: 'fjbmohvqgqzldmpr');

session_start();
?>