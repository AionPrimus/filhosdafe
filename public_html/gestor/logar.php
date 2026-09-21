
<?php
require_once __DIR__ . '/conexao.php';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}



if(isset($_POST['login']) || isset($_POST['senha'])) {

    if(strlen($_POST['login']) == 0) {
        echo "Preencha seu login";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $login = $mysqli->real_escape_string($_POST['login']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM cadastro WHERE login = '$login' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
            }

            $_SESSION['login'] = $usuario['login'];
 echo "<script type='text/javascript'> window.location='https://filhosdafe.com.br/gestor/listar.php'; </script>";  
		} else {
 echo "<script type='text/javascript'>alert('Verifique seus dados de login e tente novamente!'); window.location='https://filhosdafe.com.br/gestor/'; </script>";        }

    }

}
?>



