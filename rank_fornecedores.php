<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

    header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


?>
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 


<script>
    
    $(document).ready(function(){
        
      $("#btbusca").on("click", function(){
          
          efeitoload('in');
 
          var post = $("#form1").serialize(); 
          
         $.post("rank_fornecedores_load.php", post,  function(dados){

          $('#divCarregado').html(dados).slideDown();   
             
             efeitoload('out');
             
         }); 
          
      });  
        
    });
</script>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analise venda por fornecedor</title>
</head>
<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
    
<div id="main">
<div id="divMenu">
    <?php require_once("menu.php");   ?>
</div>
<div id="divContainer">
<div class='divBlocoGrande'>
    <div class="divBloco ">
     <form name='form1' id="form1" class="formPadrao1" >
           
      <div class="div_titulos">Analise de venda Forneceor</div>
             <table class='tabela_padrao'>
                 <tr>
                <td>Período <input type="date" name="dataIni"  value="<?php echo Date( 'Y-m-01' ); ?>"></td>
                <td>até <input type="date" name="dataFin"  value="<?php echo Date( 'Y-m-d' ); ?>"></td>
              
                 </tr>
                    <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                    </tr>
                    <tr>
                  <td colspan="2" align="right"><input type="button" id="btbusca" value="Buscar" class="inputSizeM" ></td>
                 </tr>
            </table>
            
        </form>
    </div>
    <div id="divCarregado"> </div>
</div>
</div>
</div>
<div id="rodape"></div>
</body>
</html>