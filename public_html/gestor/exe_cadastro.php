<?php
@ini_set('display_errors', '0');
error_reporting(E_ALL);
session_start();

$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);
$user = $isLocalhost ? "root" : "filhosdafecom_aion";
$pass = $isLocalhost ? "" : "aionroot0713";

if(!$conect=mysqli_connect('localhost',$user,$pass,'filhosdafecom_bancox')) die ('erro ao conectar');
#Recolhendo os dados do formulário
  $datatime =$_POST["datatime"];
  $ip_cadastro =$_POST["ip_cadastro"];
  $valorcarteira = "30";
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
  $avatar =$_POST["avatar"];
  $batizado =$_POST["batizado"];
  $iniciado =$_POST["iniciado"];
  $cargo_funcao_cadastro =$_POST["cargo_funcao_cadastro"];
  $data_apresentacao =$_POST["data_apresentacao"];
  $data_filiacao =$_POST["data_filiacao"];
  $chefe_coroa =$_POST["chefe_coroa"];
  $nome_casa =$_POST["nome_casa"] ?? '';
  $nome_dirigente =$_POST["nome_dirigente"] ?? '';
  $telefone_casa =$_POST["telefone_casa"] ?? '';
  $nome_religioso =$_POST["nome_religioso"];
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

if(isset($_FILES['avatar']))
   {
      date_default_timezone_set("Brazil/East"); //Definindo timezone padrão

          $ext = strtolower(substr($_FILES['avatar']['name'],-4)); //Pegando extensão do arquivo
      $new_name = "avatar_"."$matricula_cadastro"."$ext"; //Definindo um novo nome para o arquivo
      $dir = 'uploads/'; //Diretório para uploads

      move_uploaded_file($_FILES['avatar']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
   }


# Verificando apenas um campo, no caso dado1.
$sql = $conect->query("SELECT * FROM cadastro WHERE cpf_cadastro='$cpf_cadastro'");
if(mysqli_num_rows($sql) > 0){
echo "<script type='text/javascript'>alert('CPF JÁ CADASTRADO, EM CASO DE DÚVIDAS ENTRE EM CONTATO'); window.location='cadastro.php'; </script>";
exit();
} else {
 if(!$conect->query("INSERT INTO cadastro (datatime, ip_cadastro, valorcarteira, situacao_cadastral, instituicao, matricula_cadastro, nome_lider, nome_cadastro, nascimento_cadastro, cpf_cadastro, rg_cadastro, titulo_eleitor, zona_eleitoral, secao_eleitoral, nome_pai, nome_mae, profissao, escolaridade, estado_civil, avatar, batizado, iniciado, cargo_funcao_cadastro, data_apresentacao, data_filiacao, nome_casa, nome_dirigente, telefone_casa, nome_religioso, chefe_coroa, orixas, entidades, disponibilidade, desenvolvimento, tel_resicencial, tel_celular, tel_emergencia, email_principal, cep_residencial, logradouro_residencial, numero_residencial, complemento_residencial, bairro_residencial, cidade_residencial, estado_residencial, observacoes) VALUES ('$datatime', '$ip_cadastro', '$valorcarteira', '$situacao_cadastral', '$instituicao', '$matricula_cadastro', '$nome_lider', '$nome_cadastro', '$nascimento_cadastro', '$cpf_cadastro', '$rg_cadastro', '$titulo_eleitor', '$zona_eleitoral', '$secao_eleitoral', '$nome_pai', '$nome_mae', '$profissao', '$escolaridade', '$estado_civil', '$new_name', '$batizado', '$iniciado', '$cargo_funcao_cadastro', '$data_apresentacao', '$data_filiacao', '$nome_casa', '$nome_dirigente', '$telefone_casa', '$nome_religioso', '$chefe_coroa', '$orixas', '$entidades', '$disponibilidade', '$desenvolvimento', '$tel_resicencial', '$tel_celular', '$tel_emergencia', '$email_principal', '$cep_residencial', '$logradouro_residencial', '$numero_residencial', '$complemento_residencial', '$bairro_residencial', '$cidade_residencial', '$estado_residencial', '$observacoes')")) die ('Os dados não foram inseridos');
	
	
	
$conect->close();



$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$mensagem1= "Código da Matrícula: $matricula_cadastro<br><br> Seja bem vindo: $nome_cadastro,<br> Seu cadastro cadastro foi efetivado com sucesso!<br><br><br>A equipe da Tenda Espírita Filhos da Fé agradece sua particição.<br><br>contato@filhosdafe.com.br<br>https://filhosdafe.com.br";

  $to1 = "$email_principal";
  $assunto1 = "CADASTRO - TENDA ESPÍRITA FILHOS DA FÉ";
  mail($to1,$assunto1,$mensagem1,$headers);

	
	
$_SESSION["nome_cadastro"] = $nome_cadastro;
$_SESSION["matricula_cadastro"] = $matricula_cadastro;
$_SESSION["cargo_funcao_cadastro"] = $cargo_funcao_cadastro;
	
	
	
	
		header('Location: sucesso.php');

}
?>
