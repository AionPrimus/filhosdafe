
<?php

@ini_set('display_errors', '1');

error_reporting(E_ALL);



$id_cadastro = $_GET["id_cadastro"];

settype($id, "integer");



$conn = new mysqli("localhost", "filhosdafecom_aion", "aionroot0713", "filhosdafecom_bancox");

// Check connection

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

} 







$sql = "delete from cadastro where id_cadastro = $id_cadastro";





if ($conn->query($sql) === TRUE) {

 echo "<script type='text/javascript'>alert('CADASTRO EXCLUIDO COM SUCESSO!'); window.location='https://filhosdafe.com.br/gestor/listar.php'; </script>";
	
} else {

    echo "Error: " . $sql . "<br>" . $conn->error;

}



$conn->close();



?>