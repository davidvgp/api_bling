<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql   = new Sql();

 if ( !empty($_SESSION[ "idUsuario" ] )) {

    $idsContas = $Class->getContas( $_SESSION[ "idUsuario" ] ); 

 }
?>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 
<script src="js/js_oculta_menu.js"></script> 
<script>
    
$(document).ready(function(){
    
$("#btAtualizar").on("click",function(){

    efeitoload("in");
    
    var numPed = $("#numeroPedido").val();
    
    if(numPed.length > 0){
        
    $.post("carrega_dinamicos_html.php",{
        
    func:"atualizaUmPedido",
    conta: $("#conta").val(),
    atualizaPedido: $("#atualizaPedido").val(),
    numPed: $("#numeroPedido").val()
        
    }, function(dados){
        
      
        efeitoload("out");
        notaRodape(dados);
    });
    
    
    }else{
    
    
    $.post("carrega_dinamicos_html.php",{
        
    func:"atualizaPedido",
    conta: $("#conta").val(),
    dataInicial: $("#dataInicial").val(),
    dataFinal: $("#dataFinal").val(),
    atualizaPedido: $("#atualizaPedido").val()
        
    }, function(dados){
        
        efeitoload("out"); 
        notaRodape(dados);
    });
    
    }
    
});
    
    
});    
    
</script>    
    
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>Atualiza Base Pedidos Itens</title>
</head>

<body>
<div id="header"> <?php require_once("header.php");  ?></div>
    
<div id="main" >
    
<div id="divMenu"><?php require_once("menu.php");  ?></div>
    
<div id="divContainer">    
<div class="divBlocoGrande">    
  <div class="divBloco">
    <div class="div_titulos">Atualizar base de pedidos de vendas</div>
      <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
      <table border="0" align="center" cellpadding="5">
        <tbody>
          <tr>
              <th align="left">Conta</th>
              <td align="left">
                  <select id="conta" name="conta" class="inputSizeM" >
                      <option value="">Todas</option>
                      <?php foreach($idsContas as $id_conta ) { ?>
                      <option value="<?php echo (int)$id_conta['id'];  ?>"> <?php echo $id_conta['conta'];  ?></option>
                      <?php } ?>
              </select>
              </td>
              </tr>
          <tr>
            <th align="left">Data inicial</th>
            <td align="left">
                <input type="date" name="dataInicial" id="dataInicial"  value="<?php echo Date('Y-m-d');?>">
            </td>
            </tr>
          <tr>
            <th align="left">Data Final</th>
            <td align="left">
                <input type="date" name="dataFinal" id="dataFinal"    value="<?php echo Date('Y-m-d');?>">
            </td>
            </tr>
          <tr>
              <th align="left">Numero Pedido</th>
              <td align="left">
                  <input type="text" name="numeroPedido" id="numeroPedido">
              </td>
              </tr>
          <tr>
           <th align="left">Atualizar Itens do pedido</th>
           <td>
            <select name="atualizaPedido" id="atualizaPedido">
             <option value="false"> Não </option>
             <option value="true"> Sim </option>
            </select>
           </td>
          </tr>
          <tr>
            <th align="left">&nbsp;</th>
            <td>
                <input type="button" class="inputSizeP" id="btAtualizar" value="Atualizar">
            </td>
            </tr>
        </tbody>
      </table>
    </form>
  </div>
  </div>
</div>
</div>
<div id="rodape"></div>    
<div id="absolut">
<div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape" title="Dois clicks para fechar">
</div>   
</body>
</html>