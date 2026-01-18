<?php
session_start();


require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );


?>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_oculta_menu.js"></script>
<script src="js/js_geral.js"></script>

<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<script>
    
 $(document).ready(function(){
  
     /*
$('#btSalvaFrete').keypress(function(event) {
    
    if (event.which == 13) { 
         $(this).click();
    }
});
    
    */
      
     
    function InNumCalc(x) {
    return parseFloat(x.replace(",", "."));
  }


  function OutNumCalc(x) {
    return x.replace(".", ",");
  }   
     
     
     
     
     
  $(".formatNum").on("change", function () {

    var v = InNumCalc($(this).val());

    $(this).val(v));

  });

     
     
$("#pesoDe").on("change",  function(){ 
    
    var x  = InNumCalc($(this).val());
    
    $(this).val(x).toFixed(0);

});
     
$("#pesoAte").on("change",  function(){ 
    
    var x  = InNumCalc($(this).val());
    
    $(this).val(x).toFixed(0);

});  
    
    
$("#pesoAte").on("change",   function(){ $(this).val( InNumCalc($(this).val()).toFixed(0));});    
$("#custoFrete").on("change",function(){ $(this).val( InNumCalc($(this).val().toFixed(2)));});     
  
  */

    
     
  function NomeLojasTipo() {

    $.post("carrega_dinamicos_html.php", {
        
      func: 'nome_lojas_tipo',
      conta: $("#conta").val() 
    
    }, function (dados) {
        
      $("#lojas").html(dados);

    });

  } 
     
     
  function NomeLojas() {
  
    $.post("carrega_dinamicos_html.php", {
        
      func: 'nome_lojas',
      conta: $("#conta").val()
        
    }, function (dados) {
        
      $("#lojas").html(dados);

    });

  }
   
     
/* Listandos a tabela de frete cadastrada no banco */
  
 function listFreteLojas() {

    $.post("exibe_tabela_frete.php", {

      func : "listFreteLojas",
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#tbFrete").html(dados).slideDown();

       listDescFrete();
   //     listaTxFixa();

    });
  }


/*** função que exibe o desconto de frete que está cadastrado ****************/
  function listDescFrete() {

    $.post("carrega_dinamicos_value.php", {

      func: "listDescFrete",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#descFrete").val(dados);

    });

  }
     
/********* função que lista o valor da taxa fixa que está cadastrada ************/
  function listaTxFixa() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaTxFixa",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#txFixa").val(dados);

    });

  }  
     
    function listaPedMin() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaPedMin",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#vPedMin").val(dados);

    });

  }

  

/********************************   SALVANDO DADOS ******************************************************/    

     
/****** função para salvar os valor dos frete por faixa de peso **********************/
  
function SalvaFrete() {

       efeitoload('in');
 
    var   vpesoDe     = $("#pesoDe").val();
    var   vpesoAte    = $("#pesoAte").val();
    var   vcustoFrete = $("#custoFrete").val();
    
    
    if(vpesoDe > vpesoAte || vpesoDe == vpesoAte){ alert("Verifique os pesos"); }
    

     $.post("salva_formularios.php", 
            
    {
     func:    "salvaFrete",
         pesoDe:  vpesoDe,
         pesoAte: vpesoAte,
         custoFrete: vcustoFrete,
         lojas: $("#lojas option:selected").val()
         
     }, function (dados) {

        $("#notaRodape").html(dados);
         
         listFreteLojas();
         
      });

   
    $("#pesoDe").val(vpesoAte); 
    
    $("#pesoAte").val("").focus();
    
    $("#custoFrete").val("");
    
    efeitoload('out');
  }




     
/*  função que salva o valor do desconto de frete */
  function salvaDescFrete() {

    $.post("salva_formularios.php", {


      func: "salvaDescFrete",
      desc: $("#descFrete").val(),
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function () {

      listFreteLojas();

    });


  }
/***** função que salva o valor da taxa fixa *********/
  
    function salvaTxFixa() {

    $.post("salva_formularios.php", {

      func: "salvaTxFixa",
      taxa: $("#txFixa").val(),
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function () {

      listaTxFixa();

    });


  }     
     
function salvaPedMin() {

    $.post("salva_formularios.php", {


      func: "salvaPedMin",
      vPedMin: $("#vPedMin").val(),
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function () {

      listaPedMin();

    });


  }     
      
     
/********** FIM DAS FUNÇÕES QUE SALVAM DADOS *********************/     
     
  /***************** CAPTURANDO O EVENTOS E  CHAMANDO AS FUNÇOES  *******************/
    
  $("#conta").on("change", function () {
    
      alert("ok");
        NomeLojas();

  });


  $("#lojas").on("change", function () {
    listDescFrete();
    listaTxFixa();
    listaPedMin();
    listFreteLojas();
       
  });

     
  $("#btSalvaDesc").on("click", function () {
    salvaDescFrete();
    listFreteLojas();
    listDescFrete();
  });
     
  $("#btSalvaTxFixa").on("click", function () {
    salvaTxFixa();
    listaTxFixa();
  });  
     
    $("#btSalvaPedMin").on("click", function () {
    salvaPedMin();
    listaPedMin();
  });
    
  $("#btSalvaFrete").on("click", function () {
    SalvaFrete();
  }); 
     
 
});
     

</script> 
<!doctype html>
<html>
<head>
 <?php require_once("conteudo_header.php");  ?>
    
<title>Template</title>    
    
</head>
<body>
<div id="header">
  <?php require_once("header.php");  ?>
</div>
    
    
<div id="divMenu" class="col-2">
  <?php require_once("menu_api.php");   ?>
</div>
    
        
<div id="divContainer" class="col-10">
    
    
<div id="" class="divBlocoGrande">


<!--**********FORMULÁRIO CADASTRA CUSTO FRETE LOJAS******************-->

<div class="divMiniBloco width_60p">
    <form action="" id="form2" class="formPadrao1" method="post" accept-charset="UTF-8" >
        <div class="div_titulos size16" title="Aliquota de imposto Simples Nascional">Cadastrar Custro de Frete por Lojas</div>
        <hr>
        <table align="center" >
            <tr>
                <td>&nbsp;</td>
                <td>
                    <select id="conta" name="conta" class="inputSizeM">
                        <option value="">Conta</option>
                        <?php foreach($sel_conta as $id_conta ) { ?>
                        <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <select id="lojas" name="lojas" class="inputSizeM" >
                        <option value="0">Lojas</option>
                    </select>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <th>Desconto no frete %</th>
                <th>%
                    <input type="text"  class="inputSizeP formatNum"  style="text-align:center" id="descFrete" value="" name="descFrete">
                </th>
                <td>
                    <input type="button" id="btSalvaDesc" value="Salvar" class="inputSizeP">
                </td>
            </tr>
            <tr>
                <td></td>
                <th>Taxa fixa administrativa</th>
                <th>R$
                    <input type="text"  class="inputSizeP formatNum "  style="text-align:center" id="txFixa" value="" name="txFixa">
                </th>
                <td>
                    <input type="button" id="btSalvaTxFixa" value="Salvar" class="inputSizeP">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <th>Pedido mínimo frete grátis</th>
                <th>R$
                    <input type="text"  class="inputSizeP formatNum "  style="text-align:center" id="vPedMin" value="" name="vPedMin">
                </th>
                <td>
                    <input type="button" id="btSalvaPedMin" value="Salvar" class="inputSizeP">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>Cadastra valor por faixa de peso</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">De
                    <input type="text" id="pesoDe" name="pesoDe" class="inputSizeP" value="" style="text-align:center">
                    até
                    <input type="text" id="pesoAte" name="pesoAte" class="inputSizeP" value="" style="text-align:center">
                    Kg</td>
                <td>Valor R$
                    <input type="text" id="custoFrete" name="custoFrete" class="inputSizeP formatNum" value="" style="text-align:center">
                </td>
                <td>
                    <input type="button" id="btSalvaFrete" value="Salvar" class="inputSizeP">
                </td>
            </tr>
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <input type="hidden" name="formSalvaFrete" value="S">
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
            </tr>
        </table>
    </form>
    <div id="retornoSalvaFrete" style="display:none"></div>
</div>
<div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
<div id="divCarregado" class="divMiniBloco width_20p" style="display:none"> </div>

<!--***************SEPARANDO OS FORMULÁIROS*********************-->

</div>
</div>
</div>
<div class='divBlocoGrande'>
    <div id="tbFrete" style="display:none" class="divMiniBloco width_30p"></div>
</div>
<div id="notaRodape"> </div>
