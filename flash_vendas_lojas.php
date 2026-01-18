<?php
require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();
$contas = $Class->getContas( $_SESSION[ "idUsuario" ] );

?>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_geral.js"></script>
<script>
    
 $(document).ready(function () {
     
         
             carregaFornecedores(0);
                   carregaLojas();  
                    statusPedidos();
 
  $("#notaRodape").on("dblclick", function(){
     
        $("#notaRodape").fadeOut();
  
  });  
     
         
     
$("#conta").on("change", function () {
    
        var conta  = $("#conta").val();
        var fornec = $("#fornec").val();
        var busca  = $("#FiltraProd").val();  
    
       efeitoload('in');
                        
         carregaFornecedores(conta);
         carregaLojas();
});
     
 $("#btBuscarFlashVendas").on("click", function(){
  
      var conta  = $("#conta").val().length;
     
 // if(conta == 0){  $('#conta').find('option').prop('selected', true) }
     
     efeitoload('in'); 
     
     
  var post = $("#form1").serialize(); 
    
 
        
   $.post("flash_vendas_lojas_load.php", post,  function(dados){
           
   $("#divCarregado").html(dados).slideDown(); 

        efeitoload('out');
   

       
   }); 
     
  });   
   

     
 });   // fim do bloco     
    

        
   </script>
<body>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vendas por lojas</title>
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
   <div class="divBloco">
    <div class="div_titulos">Relatóro de venda por Lojas</div>
    <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
     <table border="0" align="center" cellpadding="5" cellspacing="5"  class="" style="">
      <tr>
       <td align="left" valign="middle">Contas <br>
        <select id="conta" name="conta[]" multiple="MULTIPLE" class="inputSizeM" >
         <?php foreach($contas as $col ) { ?>
         <option value="<?php echo $col['id'];  ?>"> <?php echo $col['nome_conta_bling'];  ?></option>
         <?php } ?>
        </select>
       </td>
       <td align="left">Lojas<br>
        <select name="lojas[]" autofocus="autofocus" multiple="MULTIPLE" class="inputSizeM" id="lojas" >
        </select>
       </td>
       <td align="left">Status pedido<br>
        <select name="statusPedidos[]" multiple="MULTIPLE" class="inputSizeM" id="statusPedidos">
        </select>
        <br>
       </td>
      </tr>
      <tr>
       <td align="left" valign="middle">
        <p>Data inicial<br>
         <input type="date" name="dataIni" id="dataIni" class="inputSizeM" value="<?php echo Date('Y-m-01'); ?>">
        </p>
       </td>
       <td align="left" valign="middle"> Data Final <br>
        <input type="date" name="dataFin" id="dataFin" class="inputSizeM" value="<?php echo Date('Y-m-d'); ?>">
       </td>
       <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
       <td align="left">&nbsp;</td>
       <td align="right">Exibir relatório
        <select name="exibeGeral" id="exibeGeral">
         <option value="G">Geral</option>
         <option value="C">Contas</option>
         <option value="GC">Geral e Contas</option>
        </select>
       </td>
       <td align="right">
        <input type="button" value="Buscar" id="btBuscarFlashVendas" class="inputSizeM">
       </td>
      </tr>
     </table>
    </form>
   </div>
   <div id="divCarregado"> </div>
  </div>
 </div>
</div>
<div id="rodape"></div>
<div id="absolut">
 <div class="load"><img src="imgs/loading-gif-transparent.gif"  width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>