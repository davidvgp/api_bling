<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

    header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );


?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" />
<title>Atualiza Base Vendas</title>
</head>
<script src="js/jquery-3.7.1.js"></script> 
    
    
<script>

 $(document).ready(function(){
    
    
    
$("#bt_atualiza_vendas").on("click", function(){
    
    $("#absolut").show();
    $(".load").show();
    $("#notaRodape").hide();
    
    $frm = $("#form1").serialize();
    
    
   $.post('atualiza_base_vendas_load.php', $frm, function(e){
       
    $("#absolut").fadeOut();
    $(".load").hide(); 
       
       $("#notaRodape").html(e).fadeIn();
      
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
    
    <div class="divBlocoGrande">
        
        <div class="divBloco">
            
            <div class="div_titulos">Atualizar base de Vendas</div>
           
            <form id="form1" class="formPadrao1"  accept-charset="UTF-8" >
                <table border="0">
                   
                        <tr>
                            <td>Conta</td>
                            <td>
                                <select id="conta" name="conta" class="inputSizeM">
                                    <option value="T">Todas</option>
                                    <?php foreach($conta as $id_conta ) { ?>
                                    <option value="<?php echo $id_conta['id'];  ?>"> <?php echo $id_conta['nome_conta_bling'];  ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Data inicial</td>
                            <td>
                                <input name="dataInicial" type="date" class="inputSizeM" value="<?php echo Date('Y-m-01');?>">
                            </td>
                            <td>Data Final</td>
                            <td>
                                <input name="dataFinal" type="date" class="inputSizeM" value="<?php echo Date('Y-m-d');?>">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <input type="button" id="bt_atualiza_vendas" class="inputSizeP" value="Atualizar">
                            </td>
                        </tr>
                 
                </table>
            </form>
            
        </div>
  
    </div>
    
    <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
    <div id="absolut">
       
        <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
        
    </div>
    <div id="notaRodape">  </div>
    
    
</div>
</div>
    <div id="rodape"></div>
</body>
</html>
