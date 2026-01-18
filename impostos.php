<?php
session_start();

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );


?>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_geral.js"></script>
<script src="js/js_oculta_menu.js"></script>

<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<script>
    
 $(document).ready(function(){
     
        
  $("#conta").on("change", function () {

    $.post("carrega_dinamicos_value.php", {
      func: 'aliqImpostoMes',
      conta: $("#conta option:selected").val(),
      month: $("#month").val()

    }, function (dados) {

      $("#aliqImp").val(dados);

    });

  });


     
  $("#aliqImp").on("change", function () {
      
     var aliq = $(this).val();
      
      if (aliq.length > 0) { 
          
          aliq = InNumCalc(aliq); 
          
        $(this).val(aliq);
      }
      
        
  });
      
      
  $("#month").on("change", function () {
      
  var mesAno = $("#month").val();
   var conta = $("#conta option:selected").val();
      
  
     $.post("carrega_dinamicos_value.php", {
        
      func: 'aliqImpostoMes',
      conta: conta,
      month: mesAno

    }, function (dados) {

      $("#aliqImp").val(dados);


    });


  });
     
       
  $("#btSalvaAliq").on("click", function () {
      
      
    var post = $("#form1").serialize();
      
   $.post("salva_formularios.php", post, function (dados) {
          
        $('#notaRodape').html(dados).fadeIn();

          
        });

    });  
     
  });



</script> 
<!doctype html>
<html>
<head>
<title>Cadastra Aliq. Imposto</title>
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
  <div id="" class="divBlocoGrande">
   <div class="divBloco">
    <form name="form1" id="form1" class="formPadrao1" accept-charset="UTF-8" >
     <div class="div_titulos" title="Aliquota de imposto Simples Nascional">Cadastrar Aliquota de Imposto</div>
     <table width="100%" align="center" class="">
      <tr>
       <th width="50%">
        <select id="conta" name="conta" class="inputSizeM">
         <option value="">Conta</option>
         <?php foreach($sel_conta as $id_conta ) { ?>
         <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?> </option>
         <?php } ?>
        </select>
       </th>
       <th>
        <input type="month" id="month" name="month" value="<?php echo Date('Y-m'); ?>" class="inputSizeM">
       </th>
      </tr>
      <tr>
       <th><Br>
       </th>
       <td>&nbsp;</td>
      </tr>
      <tr>
       <td>Aliquota Simples
        <input type="text" id="aliqImp" name="aliqImp" class="inputSizeP formatNum" value="" style="text-align:center">
        %</td>
       <td>
        <input type="button" id="btSalvaAliq" value="Salvar" class="inputSizeP">
       </td>
      </tr>
      <tr>
       <td>
        <input type="hidden" name="func" id="func" value="formSalvAliq">
       </td>
       <td></td>
      </tr>
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
<div id="notaRodape"> </div>
