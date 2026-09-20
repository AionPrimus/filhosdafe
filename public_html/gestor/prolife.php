<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->


<?php
date_default_timezone_set('America/Sao_Paulo');
$dataa = date('d/m/Y');
$hora = date('H');
$minutos = date('i');
$segundos = date('s');
$time = "$hora:$minutos:$segundos";
$ip = $_SERVER["REMOTE_ADDR"];


$id_cadastro = $_GET['id_cadastro'];


$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);

$servidor = "localhost";
$usuario = $isLocalhost ? "root" : "filhosdafecom_aion";
$senha = $isLocalhost ? "" : "aionroot0713";
$dbname = "filhosdafecom_bancox";

$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

$result_usuario = "SELECT * FROM cadastro WHERE id_cadastro ='$id_cadastro'";
$resultado_usuario = mysqli_query($conn, $result_usuario);
$dados = mysqli_fetch_assoc($resultado_usuario);
$matricula_cadastro = $dados["matricula_cadastro"];



?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <link rel="icon" href="assets/faicon.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Membro — Filhos da Fé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-section { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:16px; padding:24px; margin-bottom:24px; box-shadow:0 8px 32px rgba(0,0,0,0.1); }
        .form-section-title { font-size:16px; font-weight:700; margin-bottom:20px; color:var(--text-primary); border-bottom:1px solid rgba(255,255,255,0.05); padding-bottom:12px; display:flex; align-items:center; gap:10px; }
        .form-section-title i { color:var(--accent); }
        .control-label { display:block; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:8px; font-weight:600; }
        .controls { background:rgba(0,0,0,0.15); border:1px solid var(--glass-border); padding:12px 16px; border-radius:12px; color:var(--text-primary); font-size:14px; min-height: 44px; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .col-lg-3, .col-lg-4, .col-lg-6, .col-lg-12 { flex: 1; min-width: 200px; }
        .col-lg-12 { flex-basis: 100%; }
        .col-lg-6 { flex-basis: calc(50% - 10px); }
        .col-lg-4 { flex-basis: calc(33.333% - 14px); }
        .col-lg-3 { flex-basis: calc(25% - 15px); }
        .member-badge { display:inline-flex;align-items:center;gap:15px;background:linear-gradient(135deg, rgba(168,85,247,0.15), rgba(124,58,237,0.05));border:1px solid rgba(168,85,247,0.3);border-radius:16px;padding:20px;margin-bottom:24px;width:100%;box-shadow:0 8px 32px rgba(0,0,0,0.2); }
        .member-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent); }
        h6[align="center"] { display: none; /* Hide old inline red headers, replaced by form-section-title */ }
    </style>
</head>
<body>
<?php include __DIR__ . '/views/includes/_sidebar.php'; ?>
<main class="main-content">
<?php include __DIR__ . '/views/includes/_header.php'; ?>
<div class="content-area" style="padding:24px;">

    <div class="member-badge">
        <?php if (!empty($dados['avatar'])): ?>
            <img src="uploads/<?= htmlspecialchars($dados['avatar']) ?>" class="member-avatar">
        <?php else: ?>
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:30px;color:var(--text-muted);"><i class="fa fa-user"></i></div>
        <?php endif; ?>
        <div>
            <div style="font-weight:800;font-size:22px;color:var(--text-primary);"><?= htmlspecialchars($dados['nome_cadastro']) ?></div>
            <div style="font-size:14px;color:var(--accent);margin-top:4px;"><i class="fa fa-id-badge"></i> Matrícula: <?= htmlspecialchars($dados['matricula_cadastro']) ?> &nbsp; | &nbsp; <i class="fa fa-circle-check" style="color:#25D366"></i> <?= htmlspecialchars($dados['situacao_cadastral']) ?></div>
        </div>
    </div>
   
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
      <div class="container">
       
        <form name="formeditcad" id="formeditcad" action="exe_edit_cadastro.php" method="post">
                  <input type="hidden" class="form-control" name="id_cadastro" value="<?php echo $dados["id_cadastro"];?>">
	
			
			<div class="row">

	<div class="col-lg-4" align="center">
		
	<h3>SITUAÇÃO CADASTRAL: <?php echo $dados["situacao_cadastral"];?></h3>
		
<img src="uploads/<?php echo $dados["avatar"];?>" width="300px" height="auto" alt=""/> </div>
			
			
			
		
				<div class="col-lg-4">
					<div align="center">
						<h4><?php echo $dados["nome_cadastro"];?><br>
                        <?php echo $dados["nome_religioso"];?></h4>
					</div>
<div style="font-size: 10px" align="center"  >
	    Matrícula:    <?php echo $dados["matricula_cadastro"];?> 
			
		  <br>

		 
		  </div>
<br>
<br>

                                        <div class="form-group">
                                            <label class="control-label">Situação Cadastral<font color="#FF0000"></font></label>
                                            <div class="controls"><?php echo $dados["situacao_cadastral"];?>
                                            </div>
                                    </div>
					   
									                                    </div>
			
	

										                                    </div><br>
<br>
<br>


						
						
			
		
			
			
			
			
			
			<h6 align="center" style="background-color:#FF0000; color:#FFFFFF; border-radius: 5px"><strong>DADOS PESSOAIS E LÍDER</strong></h6>
          <div class="row">
            <div class="col-lg-12" style="margin-bottom: 15px;">
              <div class="form-group" style="background:rgba(168,85,247,0.1); padding:10px; border-radius:8px; border:1px solid rgba(168,85,247,0.3);">
                <label class="control-label" style="color:var(--text-primary); font-weight:bold;">NOME DO LÍDER</label>
                <div class="controls" style="font-weight:bold; color:var(--accent); font-size:16px;">
                    <?php echo htmlspecialchars($dados["nome_lider"] ?? '');?>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Nome Completo<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["nome_cadastro"];?>
                </div>
              </div>
            </div>    
			  <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Nome Religioso<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["nome_religioso"];?>
                </div>
              </div>
            </div>
			  
			    <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Data de nascimento<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["nascimento_cadastro"];?>
                  
                </div>
              </div>
            </div>
			  
        
			
            </div>
             
		
			  
			 
        
            <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Nome do Pai<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["nome_pai"];?>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Nome da Mãe<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["nome_mae"];?>
                </div>
              </div>
            </div>
			 
	    <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Profissão<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["profissao"];?>
                </div>
              </div>
            </div>
			  
			  
			      <div class="col-lg-3">
              <div class="form-group">
                <label class="control-label">Telefone<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["tel_celular"];?>
                </div>
              </div>
            </div>
       		  
			              </div>
			<br>




		  
<div class="row">		

		   <div class="col-lg-6">
	
	 <div class="form-group">
                <label class="control-label">Estado Civil<font color="#FF0000" size="-1"></font></label>
                <div class="controls"><?php echo $dados["estado_civil"];?>
				  </div>
            </div>	
	       </div>  
			   
			                   

			   
			   
			    
	</div>
					
	<br><br>
	<h6 align="center" style="background-color:#3b82f6; color:#FFFFFF; border-radius: 5px"><strong>DADOS ELEITORAIS</strong></h6>
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
            <label class="control-label">Título de Eleitor<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["titulo_eleitor"] ?? '');?>
            </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
            <label class="control-label">Zona Eleitoral<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["zona_eleitoral"] ?? '');?>
            </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
            <label class="control-label">Seção Eleitoral<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["secao_eleitoral"] ?? '');?>
            </div>
            </div>
        </div>
    </div>
	
	<br><br>
	<h6 align="center" style="background-color:#7c3aed; color:#FFFFFF; border-radius: 5px"><strong>DADOS ESPIRITUAIS</strong></h6>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
            <label class="control-label">CASA / TENDA / TERREIRO / CENTRO<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["nome_casa"] ?? '');?>
            </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
            <label class="control-label">Nome do Dirigente da Casa<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["nome_dirigente"] ?? '');?>
            </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
            <label class="control-label">Telefone da Casa / Centro<font color="#FF0000" size="-1"></font></label>
            <div class="controls"><?php echo htmlspecialchars($dados["telefone_casa"] ?? '');?>
            </div>
            </div>
        </div>
    </div>
					
					<br>
<br>



	 
        </form>
			  	  <br>	  <br>	  <br>
</div>
		  
			
            </div>
   

          </div>				

          
            
          </div>
        
      
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
	 </div>
	<script type="text/javascript">
		 $(function() {
        $('.exibe-div').change(function(){
            $('.exibe').hide();
            $('#' + $(this).val()).show();
        });
    });
</script>
	<script type="text/javascript">
   alteraDiv = function (){
    if($('#id_tipo_contacto').val() == "CASADO"){
        $("#CASADO").show();
        $("#DIVORCIADO").hide();
    }
     if($('#id_tipo_contacto').val() == "UNIÃO ESTÁVEL"){
        $("#CASADO").show();
        $("#DIVORCIADO").hide();
    }
    if($('#id_tipo_contacto').val() == "DIVORCIADO"){
        $("#CASADO").hide();
        $("#DIVORCIADO").show();
    }
	   if($('#id_tipo_contacto').val() == "VIÚVO"){
        $("#CASADO").hide();
        $("#DIVORCIADO").show();
    }
	   if($('#id_tipo_contacto').val() == "SOLTEIRO"){
        $("#CASADO").hide();
        $("#DIVORCIADO").show();
    }
    
}
	</script>

 <script type="text/javascript">
        var senha = $('#senha');
var olho= $("#olho");

olho.mousedown(function() {
  senha.attr("type", "text");
});

olho.mouseup(function() {
  senha.attr("type", "password");
});
// para evitar o problema de arrastar a imagem e a senha continuar exposta, 
//citada pelo nosso amigo nos comentários
$( "#olho" ).mouseout(function() { 
  $("#senha").attr("type", "password");
});
    </script>
	<script type="text/javascript">
        var senha1 = $('#senha1');
var olho1= $("#olho1");

olho.mousedown(function() {
  senha1.attr("type", "text");
});

olho1.mouseup(function() {
  senha1.attr("type", "password");
});
// para evitar o problema de arrastar a imagem e a senha continuar exposta, 
//citada pelo nosso amigo nos comentários
$( "#olho1" ).mouseout(function() { 
  $("#senha1").attr("type", "password");
});
    </script>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
</div>
</main>
<script src="https://code.iconify.design/iconify-icon/1.0.2/iconify-icon.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script><!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="layout/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="layout/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="layout/dist/js/demo.js"></script>
	<script src="layout/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="public_html/layout/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="layout/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="layout/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="layout/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="layout/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="layout/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="layout/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="layout/plugins/jszip/jszip.min.js"></script>
<script src="layout/plugins/pdfmake/pdfmake.min.js"></script>
<script src="layout/plugins/pdfmake/vfs_fonts.js"></script>
<script src="layout/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="layout/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="layout/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="layout/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="layout/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
