<?php

@ini_set('display_errors', '1');

error_reporting(E_ALL);

session_start();

$id_cadastro = $_GET['id_cadastro'];







$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);

$servidor = "localhost";
$usuario = $isLocalhost ? "root" : "filhosdafecom_aion";
$senha = $isLocalhost ? "" : "aionroot0713";
$dbname = "filhosdafecom_bancox";

$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);





$selectidemail = "SELECT * FROM cadastro WHERE id_cadastro = '$id_cadastro'";

$resultidemail = mysqli_query($conn, $selectidemail);

$dados = mysqli_fetch_assoc($resultidemail);














include_once('/home/filhosdafecom/public_html/qrcode/vendor/autoload.php');



$url = "https://filhosdafe.com.br/gestor/prolife.php?id_cadastro=$id_cadastro";



$qrcode = (new \chillerlan\QRCode\QRCode())->render($url);



require './vendor/vendor/autoload.php';




// referenciando o namespace do dompdf



use Dompdf\Dompdf;



// instanciando o dompdf





$dompdf = new Dompdf(['enable_remote' => true]);



$dataa = date('d/m/Y');

$hora = date('H');

$minutos = date('i');

$segundos = date('s');

$time = "$hora:$minutos:$segundos";

$ip = $_SERVER["REMOTE_ADDR"];


 




$id_cadastro   = $dados["id_cadastro"];
$matricula_cadastro   = $dados["matricula_cadastro"];
$nome_cadastro   = $dados["nome_cadastro"];
$nome_religioso   = $dados["nome_religioso"];
$nascimento_cadastro   = $dados["nascimento_cadastro"];
$cpf_cadastro   = $dados["cpf_cadastro"];
$rg_cadastro   = $dados["rg_cadastro"];
$nome_pai   = $dados["nome_pai"];
$nome_mae   = $dados["nome_mae"];
$profissao   = $dados["profissao"];
$avatar   = $dados["avatar"];
$cargo_funcao_cadastro   = $dados["cargo_funcao_cadastro"];
$nome_religioso   = $dados["nome_religioso"];

 
 














	

	//lendo o arquivo HTML correspondente



$html = ('<<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />



<title>FILHOS DA FÉ</title>

<style type="text/css">



@page {

	margin: 0cm;

}



body {

  font-family: sans-serif;

	margin: 0.5cm ;

	text-align: justify;

	font-size: 7px;

}



#header,

#footer {

  position: fixed;

  left: 0;

	right: 0;

	color: #aaa;

	font-size: 0.9em;

}



#header {

  top: 0;

	border-bottom: 0.0pt;

}



#footer {

  bottom: 0;

  border-top: 0.0pt;

}



#header table,

#footer table {

	width: 100%;

	border-collapse: collapse;

	border: none;

}



#header td,

#footer td {

  padding: 0;

	width: 50%;

}



.page-number {

  text-align: center;

}



.page-number:before {

  content: "Página " counter(page);

}



hr {

  page-break-after: always;

  border: 0;

}

.cabeimg {

  content: "Página " counter(page);

}

.borderimg {

  border: 10px solid transparent;

}





.batallhaoproponente {

 position: fixed;

 top: 100px;

 left: 100px;

 z-index:100 !important;

}



.nomedeguerra {

 position: fixed;

 top: 113.8px;

 left: 27.5px;

 z-index:100 !important;

}



.validade {

 position: fixed;

 top: 130px;

 left: 123px;

 z-index:100 !important;

}



.postograduacao {

 position: fixed;

 top: 118px;

 left: 27.5px;

 z-index:100 !important;

}



.fotopessoal {

 position: fixed;

 top: 598px;

 left: 80px;

 z-index:100 !important;

}



.nomecompleto {

 position: fixed;

 top: 61px;

 left: 30px;

	 width: 125px !important;

	  max-width:200px;

	  text-align-last: left;



 z-index:100 !important;



 

}



.ncadastro {

 position: fixed;

 top: 48px;

 left: 162px;

 font-size: 3.8;

  max-width:40px;

  width:40px;

  text-align: center;

  z-index:100 !important;

}



.cpf {

 position: fixed;

 top: 163px;

 left: 173px;

 font-size: 7px;

 z-index:100 !important;

}



.identidaderg {

 position: fixed;

 top: 170.3px;

 left: 34px;

 font-size: 6.8px;

 z-index:100 !important;

}





.datadenascimento {

 position: fixed;

 top: 136px;

 left: 27.5px;

 z-index:100 !important;

}



.profissao {

 position: fixed;

 top: 84px;

 left: 64px;

 z-index:100 !important;

}



.nomedopai {

 position: fixed;

 top: 185.3px;

 left: 35px;

 z-index:100 !important;

}



.nomedamae {

 position: fixed;

 top: 191.9px;

 left: 35px;

 z-index:100 !important;

}



.nome_religioso {

 position: fixed;

 top: 101px;

 left: 65px;

 font-size: 6.5px;

 z-index:100 !important;

}





.fotopessoal {

 position: fixed;

 top: 77px;

 left: 179px;

 z-index:100 !important;

 max-width:40px;

 max-height:55px;

 width: auto;

 height: auto;

}



.qrcode {

 position: fixed;

 top: 75px;

 left: 23px;

 z-index:100 !important;

 border-radius: 5px;

 background-color: white;

 border-color: #0E00C5; 

}







.topbar {

 z-index:2000 !important;

}

</style>

  

</head>

<body>















<div id="fotopessoal" class="fotopessoal"><img class="fotopessoal" src="https://filhosdafe.com.br/gestor/uploads/'.$avatar.'" width="36px" height="auto">

</div>





<div id="qrcode" class="qrcode"><img class="qrcode" src='.$qrcode.' style="background-color: white" width="36px" height="auto" >

</div>





<div class="ncadastro">

'.$matricula_cadastro.'

</div>





<div class="nomecompleto">

'.$nome_cadastro.'

</div>



<div class="datadenascimento">

'.$nascimento_cadastro.'

</div>





<div class="nomedopai">

'.$nome_pai.'

</div>



<div class="nomedamae">

'.$nome_mae.'

</div>



<div class="nome_religioso">

'.$nome_religioso.'

</div>



<div class="profissao">

'.$profissao.'

</div>









<div class="postograduacao">

'.$cargo_funcao_cadastro.'

</div>



<div class="cpf">

'.$cpf_cadastro.'

</div>



<div class="identidaderg">

'.$rg_cadastro.'

</div>



<div class="validade">

31/12/2025

</div>











<div id="carteiramodelo">

<img src="https://filhosdafe.com.br/gestor/carteira.png" width="210" height="auto" />

</div>



<h2>IMPRIMA EM CARTÃO PVC</h2>

</body></html>');



//inserindo o HTML que queremos converter



$dompdf->loadHtml($html);



// Definindo o papel e a orientação



$dompdf->setPaper('A4','portrait');





$dompdf->render();







 $dompdf->stream("FILHOSDAFE_'.$matricula_cadastro.'.pdf", array('Attachment'=>0));

// $output = $dompdf->output();

 // file_put_contents($ncadastro."_cartaodevacina.pdf", $output);



 

// header('Location: https://cartoriodopet.com.br/pdf/certidao.php');



?>





