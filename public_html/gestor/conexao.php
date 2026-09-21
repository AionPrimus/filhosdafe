<?php
$httpHost = strtolower($_SERVER['HTTP_HOST'] ?? '');
$serverName = strtolower($_SERVER['SERVER_NAME'] ?? '');
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';

$isLocalhost = empty($httpHost)
    || str_contains($httpHost, 'localhost')
    || str_contains($httpHost, '127.0.0.1')
    || str_contains($serverName, 'localhost')
    || in_array($remoteAddr, ['127.0.0.1', '::1'])
    || php_sapi_name() === 'cli';

$host   = "localhost";
$dbname = "filhosdafecom_bancox";
$port   = 3306;

if ($isLocalhost) {
    // Credenciais do XAMPP Local
    $user = "root";
    $pass = "";
} else {
    // Credenciais da Host (Online)
    $user = "filhosdafecom_aion";
    $pass = "aionroot0713";
}

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname . ";charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $err) {
    die("Erro ao conectar no banco de dados (" . ($isLocalhost ? "Ambiente Local" : "Ambiente Online") . "): " . $err->getMessage());
}
