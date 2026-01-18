<?php
require_once( "session.php" );

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

if ( !empty( $_SESSION[ "idUsuario" ] ) ) {

 $sel_conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

}

?>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 
<script>
    
 $(document).ready(function () {
     
  
           carregaFornecedores(0);
                   carregaLojas();  
        carregaProfFornCodigo(0,0);      
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
    
    if(fornec.length > 0) {
        
        carregaAnuncios(conta, fornec);
    }
    
      FiltraProd(0,0,busca);

      });   
   
          
 $("#fornec").on("change", function () {
       
        efeitoload('in');
      
        var conta  = $("#conta").val();
        var fornec = $("#fornec").val();
        var busca  = $("#FiltraProd").val();
        carregaProfFornCodigo(conta, fornec);
     
     //   FiltraProd(0,0,busca);

    });   
     
 
   $("#FiltraProd").on("keyup",function(){
  
      var conta  = $("#conta").val();
      var fornec = $("#fornec").val();
      var busca  = $("#FiltraProd").val();
   
       if(conta == 0)   {  $('#conta').find('option').prop('selected', true) }   
   
     if( busca.length > 2 ){
      
       FiltraProd(conta,fornec,busca);
     }
       
 
   });
 
     
 $("#btBuscar").on("click", function(){
  
        var conta    = $("#conta").val().length;
        var fornec   = $("#fornec").val().length;
        var lojas    = $("#lojas").val().length;
        var produtos = $("#produtos").val().length;
        var busca    = $("#FiltraProd").val().length;
         
        if(conta == 0){  $('#conta').find('option').prop('selected', true) }
  //      if(lojas == 0){  $('#lojas').find('option').prop('selected', true) }
  //      if(fornec == 0){  $('#fornec').find('option').prop('selected', true) }
     
  //      if(produtos == 0 && busca >= 3){  $('#produtos').find('option').prop('selected', true); }
     
     
  var post = $("#form1").serialize(); 
    
     efeitoload('in'); 
     
     
     
   $('#divCarregado').slideUp( function(){
       
   $.post("analise_vendas_load.php", post,  function(dados){
           
   $("#divCarregado").html(dados).slideDown(); 
   efeitoload('out');
    }); 
   }); 
  });   
 });   // fim do bloco     

</script> 
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Análise de Vendas</title>
</head>
<body>
<div id="header">
 <?php require_once("header.php");  ?>
</div>
<div id="main">
 <div id="divMenu">
  <?php require_once("menu.php");  ?>
 </div>
 <div id="divContainer">
  <div class='divBlocoGrande'>
   <div class="divBloco">
    <div class="div_titulos size20"> Análise de Vendas</div>
    <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
     <table border="0" align="center" cellpadding="2"  class="" style="">
      <tr>
       <td align="left" valign="middle">Contas</td>
       <td align="left" valign="middle">Fornecedores </td>
       <td align="left" valign="middle">Produtos
        <input type="text" name="FiltraProd" id="FiltraProd" value="" class="inputSizeM">
       </td>
       <td align="left" valign="middle">Lojas</td>
       <td align="left" valign="middle">Status pedido</td>
      </tr>
      <tr>
       <th align="left" valign="middle">
        <select id="conta" name="conta[]" multiple="MULTIPLE" class="inputSizeP" >
         <?php foreach($sel_conta as $id_conta ) { ?>
         <option value="<?php echo (int)$id_conta['id'];  ?>"> <?php echo $id_conta['conta'];  ?></option>
         <?php } ?>
        </select>
       </th>
       <th align="left">
        <select name="fornec[]" multiple="MULTIPLE" class="inputSizeXG" id="fornec">
        </select>
       </th>
       <th align="left">
        <select name="produtos[]" multiple="MULTIPLE" class="inputSizeXG" id="produtos">
        </select>
       </th>
       <th align="left">
        <select name="lojas[]" autofocus="autofocus" multiple="MULTIPLE" class="inputSizeP" id="lojas" >
        </select>
       </th>
       <th align="left">
        <select name="statusPedidos[]" multiple="MULTIPLE" class="inputSizeP" id="statusPedidos">
        </select>
       </th>
      </tr>
      <tr>
       <th align="left" valign="middle">&nbsp;</th>
       <th colspan="2" align="center"> Data inicial
        <input name="dataIni" id="dataIni" type="date" class="inputSizeP" value="<?php echo Date('Y-m-d', strtotime( -2 . 'days' ));?>">
        Data Final
        <input name="dataFin"id="dataFin" type="date" class="inputSizeP" value="<?php echo Date('Y-m-d');?>">
       </th>
       <th colspan="2" align="left">
        <input type="reset" class="inputSizeP" id="btlimpar" value="Limpar">
        <input type="button" autofocus="autofocus" class="inputSizeP" id="btBuscar" value="Buscar">
       </th>
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
 <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>