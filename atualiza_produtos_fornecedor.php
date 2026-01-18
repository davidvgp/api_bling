<?php
require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();
$sel_conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

?>


<!doctype html>
<html>
<head>
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<title>Atualiza Produtos Fornecedor</title>    
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>  
</head>
    
<script>
    
    $(document).ready(function(){
        
    $("#fecharRodaPe").click(function(){ $("#notaRodape").fadeOut();});    
        
  
        $("#conta").on("change",function() {         
 
 $.post("carrega_dinamicos_html.php", {
     
     func: 'nome_fornecedorGeral',   
     conta: $("#conta option:selected").val()
   
 },   function(dados){
     
     var d = '<option value="T">Atualizar todos</option>';
     
    $("#fornec").html(d+dados); 
       
    
    });
        
     });  

         
 // ATUALIZANDO PRODUTOS DE TODOS OS FORNECEDORES 
        
 $("#btBuscar").on("click", function(){
       
     efeitoload('in');
     
if($("#fornec option:selected").val() == 'T') {
         
    $('#divCarregado').slideUp( function(){
        
         $('.load').show();
       $("#absolut").fadeIn(); 
           
        
    $.post("atualiza_produtos_todos_fornecedores.php", {
           
           conta:$("#conta").val() }
           
           , function(dados){
  
    $("#divCarregado").html(dados); 
    $('#divCarregado').slideDown(800, function(){
        
          $('.load').hide();
        $("#absolut").hide();   
         efeitoload('out');
    });
});
        
});
   
                             
}
// ATUALIZANDO PRODUTO DE UM FORNECEDOR.
     
if($("#fornec option:selected").val() != 'T') {     
    
        $('.load').show();
       $("#absolut").fadeIn(); 
     
  var post = $("#form1").serialize(); 
   
   $('#notaRodape').slideUp( function(){
       
    $.post("carrega_atualiza_produtos_fornecedor.php", post,  function(dados){
  
    $("#notaRodape").html(dados); 
    $('#notaRodape').slideDown();
    
        $("#absolut").hide();
        $('.load').hide();

   
    }); 
       
   }); 
    
}
     
 });     

        
});        
    </script>   
    
<body>    
    
<div id="header">
  <?php require_once("header.php");  ?>
</div>
    
    
<div id="divMenu" class="col-2">
    
  <?php require_once("menu_api.php");   ?>
    
</div>
    

    
<div id="divContainer" class="col-10">
<div id="" class="divBlocoGrande">

     
<div id="" class="divMiniBloco width_50p">    
    <form action="#" method="post" id="form1" class="formPadrao1" accept-charset="UTF-8" >
      <div class="div_titulos size20">Base de produtos fornecedor</div>
        <hr>
        
        <table align="center">
        <tr>
        <td>   <select id="conta" name="conta" class="inputSizeM" >
        <option value="0">Conta</option>
        <?php foreach($sel_conta as $id_conta ) { ?>
        <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?> </option>
        <?php } ?>
      </select></td>    
        <td colspan="2">
          <select  id="fornec" name="fornec" class="inputSizeXG" >
            <option value="0">Fornecedor</option>
          </select></td>    
        </tr> 
        <tr>
        <th>Atualizar estoque</th>    
        <td align="left"> <input type="checkbox" name="atualiza" id="atualiza"  value="S">        </td>    
        <td align="right"><input type="button" value="Atualizar" id="btBuscar" class="inputSizeP"></td>    
        </tr>
        </table>
     </form>
  </div>
    
</div>
    
    
    
 
</div>
    
<div class='divBlocoGrande'>
        <div id="divCarregado"> </div>
</div>
    
   <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
 <div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape">
 </div>   
</body>
</html>





