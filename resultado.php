<?php
session_start();
require_once("session.php"); 

require_once( "config.php" );

$Class = new classMasterApi();
$sql   = new Sql();
$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM tb_user_api" );

?>

<!doctype html>
<html>
<head>
<?php require_once("conteudo_header.php");  ?>
<title>Resultado</title>
</head>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_geral.js"></script>
</head>
<script>
    
$(document).ready(function () {    
    
    $("#btExibirResultado").on("click",function(){
        
        var rel = $("#divCarregado").html();
      
        $("#divCarregado").slideUp();
           var conta     = $("#conta").val();
        var exercicio = $("#exercicio").val();
        
          if(conta.length == 0)  {  $('#conta').find('option').prop('selected', true) }   
  
        $.post("resultado_load.php", {
            
            conta:$("#conta").val(),
            exercicio:exercicio
            
        }, function(dados){
             
            $("#divCarregado").html(rel+dados).slideDown();
        });
     });
   });    
 </script>

<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
<div id="main">    
<div id="divMenu">
    <?php require_once("menu.php");   ?>
</div>
<div id="divContainer">
    <div id="" class="divBlocoGrande">
        
        <div class="divBlocoNV1"> 
            
        <div id="" class="divBloco">
            
            <div class="div_titulos size18"> Analise Resultado</div>
            <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
                <table cellpadding="5" cellspacing="5" class="">
                    <tr>
                        <th>Conta</th>
                        <th align="left">&nbsp;</th>
                        <th>Exercício</th>
                    </tr>
                    <tr>
                        <th valign="top">
                            <select name="conta[]" multiple="MULTIPLE" class="inputSizeM" id="conta" >
                                <?php foreach($sel_conta as $id_conta ) { ?>
                                <option value="<?php echo (int)$id_conta['id'];  ?>"> <?php echo $id_conta['conta'];  ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th align="left">&nbsp;</th>
                        <th valign="top">
                            <input name="exercicio" type="month" id="exercicio" title="m" value="<?php echo date('Y-m');?>">
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" align="right">
                         <input type="button" class="inputSizeP" id="btExibirResultado" value=" Exibir ">
                        </th>
                    </tr>
                </table>
            </form>
        </div>



        
</div>
<div id="divCarregado"> </div>
</div>
</div>
</div>
    <div id="rodape"></div>
<!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
<div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>