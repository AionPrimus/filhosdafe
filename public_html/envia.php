<?php
@ini_set('display_errors', '0');
error_reporting(E_ALL);


$name   =$_POST["name"];
$subject   =$_POST["subject"];
$message   =$_POST["message"];

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

  $mensagem= "O(a) senhor(a) $name, ennviou a senginte mensagem: <br>Assunto/: $subject <br>Mensagem: $message <br>";

  $to = "tendaespiritafilhosdafe@gmail.com";
  $assunto = "NOVA MENSAGEM DO SITE";
  mail($to,$assunto,$mensagem,$headers);


// Mail it




 echo "<script type='text/javascript'>alert('Seu email foi enviado com sucesso! Retorne ao site para apreciar nosso conteúdo!'); window.location='index.html'; </script>";




	

?>