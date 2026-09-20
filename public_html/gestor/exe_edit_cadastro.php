<?php
@ini_set('display_errors', '0');
error_reporting(E_ALL);
session_start();

$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);
$user = $isLocalhost ? "root" : "filhosdafecom_aion";
$pass = $isLocalhost ? "" : "aionroot0713";
$dbname = "filhosdafecom_bancox";

if(!$conect=mysqli_connect('localhost',$user,$pass,$dbname)) die ('erro ao conectar');

  $id_cadastro =$_POST["id_cadastro"];
  $datatime =$_POST["datatime"];
  $ip_cadastro =$_POST["ip_cadastro"];
  $situacao_cadastral =$_POST["situacao_cadastral"];
  $instituicao =$_POST["instituicao"];
  $matricula_cadastro =$_POST["matricula_cadastro"];
  $nome_lider =$_POST["nome_lider"] ?? '';
  $nome_cadastro =$_POST["nome_cadastro"];
  $nascimento_cadastro =$_POST["nascimento_cadastro"];
  $cpf_cadastro =$_POST["cpf_cadastro"];
  $rg_cadastro =$_POST["rg_cadastro"];
  $titulo_eleitor =$_POST["titulo_eleitor"] ?? '';
  $zona_eleitoral =$_POST["zona_eleitoral"] ?? '';
  $secao_eleitoral =$_POST["secao_eleitoral"] ?? '';
  $nome_pai =$_POST["nome_pai"];
  $nome_mae =$_POST["nome_mae"];
  $profissao =$_POST["profissao"];
  $escolaridade =$_POST["escolaridade"];
  $estado_civil =$_POST["estado_civil"];
  $batizado =$_POST["batizado"];
  $iniciado =$_POST["iniciado"];
  $cargo_funcao_cadastro =$_POST["cargo_funcao_cadastro"];
  $data_apresentacao =$_POST["data_apresentacao"];
  $data_filiacao =$_POST["data_filiacao"];
  $nome_religioso =$_POST["nome_religioso"];
  $nome_casa =$_POST["nome_casa"] ?? '';
  $nome_dirigente =$_POST["nome_dirigente"] ?? '';
  $telefone_casa =$_POST["telefone_casa"] ?? '';
  $chefe_coroa =$_POST["chefe_coroa"];
  $orixas =$_POST["orixas"];
  $entidades =$_POST["entidades"];
  $disponibilidade =$_POST["disponibilidade"];
  $desenvolvimento =$_POST["desenvolvimento"];
  $tel_resicencial =$_POST["tel_resicencial"];
  $tel_celular =$_POST["tel_celular"];
  $tel_emergencia =$_POST["tel_emergencia"];
  $email_principal =$_POST["email_principal"];
  $cep_residencial =$_POST["cep_residencial"];
  $logradouro_residencial =$_POST["logradouro_residencial"];
  $numero_residencial =$_POST["numero_residencial"];
  $complemento_residencial =$_POST["complemento_residencial"];
  $bairro_residencial =$_POST["bairro_residencial"];
  $cidade_residencial =$_POST["cidade_residencial"];
  $estado_residencial =$_POST["estado_residencial"];
  $observacoes =$_POST["observacoes"];

$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);
$servername = "localhost";
$username = $isLocalhost ? "root" : "filhosdafecom_aion";
$password = $isLocalhost ? "" : "aionroot0713";
$dbname = "filhosdafecom_bancox";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE cadastro SET id_cadastro  = '$id_cadastro', datatime  = '$datatime', ip_cadastro  = '$ip_cadastro', situacao_cadastral  = '$situacao_cadastral', instituicao  = '$instituicao', nome_lider = '$nome_lider', nome_cadastro  = '$nome_cadastro', nascimento_cadastro  = '$nascimento_cadastro', cpf_cadastro  = '$cpf_cadastro', rg_cadastro  = '$rg_cadastro', titulo_eleitor = '$titulo_eleitor', zona_eleitoral = '$zona_eleitoral', secao_eleitoral = '$secao_eleitoral', nome_pai  = '$nome_pai', nome_mae  = '$nome_mae', profissao  = '$profissao', escolaridade  = '$escolaridade', estado_civil  = '$estado_civil',  batizado  = '$batizado', iniciado  = '$iniciado', cargo_funcao_cadastro  = '$cargo_funcao_cadastro', data_apresentacao  = '$data_apresentacao', data_filiacao  = '$data_filiacao', nome_casa  = '$nome_casa', nome_dirigente  = '$nome_dirigente', telefone_casa  = '$telefone_casa', nome_religioso  = '$nome_religioso', chefe_coroa  = '$chefe_coroa', orixas  = '$orixas', entidades  = '$entidades', disponibilidade  = '$disponibilidade', desenvolvimento  = '$desenvolvimento', tel_resicencial  = '$tel_resicencial', tel_celular  = '$tel_celular', tel_emergencia  = '$tel_emergencia', email_principal  = '$email_principal', cep_residencial  = '$cep_residencial', logradouro_residencial  = '$logradouro_residencial', numero_residencial  = '$numero_residencial', complemento_residencial  = '$complemento_residencial', bairro_residencial  = '$bairro_residencial', cidade_residencial  = '$cidade_residencial', estado_residencial  = '$estado_residencial', observacoes  = '$observacoes' WHERE id_cadastro ='$id_cadastro'";

if ($conn->query($sql) === TRUE) {
 echo "<script type='text/javascript'>alert('CADASTRO EDITADO COM SUCESSO!'); window.location='https://filhosdafe.com.br/gestor/listar.php'; </script>";
} else {
  echo "Error updating record: " . $conn->error;
}

$conn->close();
?>