<?php
$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);

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
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $err) {
    // echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
}
