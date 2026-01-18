<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );
    
$Class = new classMasterApi();
$sql = new Sql();

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );

?>

<!doctype html>
<html>
<head>
 <?php require_once("conteudo_header.php");  ?>
    
<title>Configurações Lojas</title>    
     
 <script src="js/jquery-3.7.1.js"></script>
<script src="js/js_oculta_menu.js"></script>
<script src="js/js_geral.js"></script>

<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
    
    
</head>   
    
<script>
    
 $(document).ready( function(){
  


$('#btSalvaFrete').keypress(function(event) {
    
    if (event.which == 13) { 
         $(this).click();
    }
});
    

     
  function InNumCalc(v) {
    return parseFloat(v.replace(",", "."));
  }


  function OutNumCalc(v) {
      
    return v.replace(".", ",");
  }    
     
 
  $(".formatNum").on("change", function (){

    var v = InNumCalc( $(this).val() );

    $(this).val(v).toFixed(2);

  });

   
     
$("#pesoDe").on("change",  function(){ 
    
    var v  = InNumCalc($(this).val());
    
    $(this).val(v).toFixed(1);

}); 
     
$("#pesoAte").on("change",  function(){ 
    
    var v  = InNumCalc($(this).val());
    
    $(this).val(v).toFixed(1);

});
     
$("#pesoAte").on("change",  function(){ 
    
    var v  = InNumCalc($(this).val());
    
    $(this).val(v).toFixed(2);

});  
    
    
 
/*     
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
  function listaTxFixa1() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaTxFixa1",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#txFixa1").val(dados);

    });

  } 
     
function listaComissao() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaComissao",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#comissaoLoja").val(dados);

    });

  }  
     
    function listaPedMin1() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaPedMin1",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#vPedMin1").val(dados);

    });

  } 
     

     
    function listaTxFixa2() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaTxFixa2",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#txFixa2").val(dados);

    });

  }  
     
    function listaPedMin2() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaPedMin2",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#vPedMin2").val(dados);

    });

  }  
     
    function listaMargLucro() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaMargLucro",
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#MargLucro").val(dados);

    });

  }

  

/********************************   SALVANDO DADOS ******************************************************/    

     
/****** função para salvar os valor dos frete por faixa de peso **********************/
  
function SalvaFrete() {

       efeitoload('in');
 
    var   vpesoDe     = InNumCalc($("#pesoDe").val()).toFixed(2);
    var   vpesoAte    = InNumCalc($("#pesoAte").val()).toFixed(2);

    
    if(vpesoDe > vpesoAte ){ alert("Verifique os pesos"); }
    

     $.post("salva_formularios.php", 
            
    {
     func:    "salvaFrete",
         pesoDe:  $("#pesoDe").val(),
         pesoAte: $("#pesoAte").val(),
         custoFrete: InNumCalc($("#custoFrete").val()),
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


    
  $("#btSalvaAliq").on("click", function () {
      
    var post = $("#form1").serialize();
      
   $.post("salva_formularios.php", post, function (dados) {

          
        $('#notaRodape').html(dados).slideUP();

          
        });

    });

     
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
  
    function salvaTxFixa(x) {

    $.post("salva_formularios.php", {

      func: "salvaTxFixaPedMin",
      taxa: $("#txFixa"+x).val(),
      vPedMin: $("#vPedMin"+x).val(),
      nivel: x,        
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function () {
    listaComissao();
    listaTxFixa1();
    listaPedMin1();
    listaTxFixa2();
    listaPedMin2();

    });
  }   
     
     

function salvaComissao(x) {

    $.post("salva_formularios.php", {
      func: "salvaComissao",
      comissao: $("#comissaoLoja").val(),
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function () {
   listaComissao();
    listaTxFixa1();
    listaPedMin1();
    listaTxFixa2();
    listaPedMin2();

    });
  }     
     
     
    function salvaMargLucro() {

 
    $.post("salva_formularios.php", {

      func: "salvaMargLucro",
      MargLucro: InNumCalc($("#MargLucro").val()),
      conta: $("#conta option:selected").val(),
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      listaMargLucro();
        

    });


  }     
      
     
/********** FIM DAS FUNÇÕES QUE SALVAM DADOS *********************/     
     
  /***************** CAPTURANDO O EVENTOS E  CHAMANDO AS FUNÇOES  *******************/
    
  $("#conta").on("change", function () {
    
          NomeLojas();

  });


  $("#lojas").on("change", function () {
    listaComissao();
      listDescFrete();
    listaTxFixa1();
    listaPedMin1();
    listaTxFixa2();
    listaPedMin2();
    listFreteLojas();
    listaMargLucro();
    
  });

     
  $("#btSalvaDesc").on("click", function () {
    salvaDescFrete();
    listFreteLojas();
    listDescFrete();
  });
     
  $(".salvaTaxaPedMin").on("click", function () {
      
      var x = $(this).attr("title");
 
    salvaTxFixa(x);
    listaTxFixa1();
    listaPedMin1();
    listaTxFixa2();
    listaPedMin2();
      
  }); 
    
 
     
    $("#btSalvaMargem").on("click", function () {
        
    salvaMargLucro();

  }); 
     
     $("#btSalvaComissao").on("click", function () {
        
    salvaComissao();

  });
    
  $("#btSalvaFrete").on("click", function () {
        SalvaFrete();
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


<div class="divBloco">
    <form action="" id="form2" class="formPadrao1" method="post" accept-charset="UTF-8" >
     <div class="div_titulos" title="">Configurações de venda lojas</div>
        <table  width="100%" align="center" cellpadding="5" cellspacing="5" >
            <tr>
                <th>Conta</th>
                <td>
                    <select id="conta" name="conta" class="inputSizeP">
                        <option value="">Selecione</option>
                        <?php foreach($sel_conta as $id_conta ) { ?>
                        <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?></option>
                        <?php } ?>
                    </select>
                </td>
              
                <th>Loja</th>
                <td>
                    <select id="lojas" name="lojas" class="inputSizeP" >
                        <option value="0">Selecione</option>
                    </select>
                </td>
           
            </tr>
            
        </table>
        <br>

         <div class="div_titulos" title="">Margem de venda padrão </div>
        <table  width="100%" align="center" cellpadding="5" cellspacing="5" >
            <tr>
         
                <th>Minha Margem Lucro Padrão</th>
                <th align="right">
                    %
                    <input type="text"  class="inputSizePP"  style="text-align:center" id="MargLucro" value="" name="MargLucro">
                </th>
                <td>
                    <input type="button" id="btSalvaMargem" value="Salvar" class="inputSizePP">
                </td>
            </tr>
     </table>
        <br>

         <div class="div_titulos" title="">Taxas e comissão </div>
         <table  width="100%" align="center" cellpadding="5" cellspacing="5" >
            <tr>
          
             <th>Comissão da loja</th>
             <th align="right">
              %
              <input type="text"  class="inputSizePP formatNum"  style="text-align:center" id="comissaoLoja" value="" name="comissaoLoja">
             </th>
             <th align="left">
              <input name="btSalvaComissao" type="button" class="inputSizePP" id="btSalvaComissao" title="1"  value="Salvar">
             </th>
          
            </tr>
            <tr>
             <th>Desconto no frete </th>
             <th align="right">%
              <input type="text"  class="inputSizePP formatNum"  style="text-align:center" id="descFrete" value="" name="descFrete">
             </th>
             <th align="left">
              <input type="button" id="btSalvaDesc" value="Salvar" class="inputSizePP">
             </th>
            </tr>
            <tr>
             
                <th>Taxa fixa 1</th>
                <th align="right">R$
                 <input type="text"  class="inputSizePP" title="1" style="text-align:center" id="txFixa1" value="" name="txFixa1">
                </th>
                <th>Pedido Mìnimo R$
                    <input type="text"  class="inputSizePP formatNum " title="1"  style="text-align:center" id="vPedMin1" value="" name="vPedMin1">
                 <input type="button" id="btSalvaTxFixaPedMin2" title="1"  value="Salvar" class="inputSizePP salvaTaxaPedMin">
                </th>
        
            </tr>
            <tr>
            
                <th>Taxa fixa 2</th>
                <th align="right">
                    R$
                    <input type="text"  class="inputSizePP formatNum " title="2" style="text-align:center" id="txFixa2" value="" name="txFixa2">
                </th>
                <th>Pedido Mìnimo R$                
                    <input type="text"  class="inputSizePP formatNum " title="2"  style="text-align:center" id="vPedMin2" value="" name="vPedMin2">
                 <input type="button" id="btSalvaTxFixaPedMin" title="2" value="Salvar" class="inputSizePP salvaTaxaPedMin">
                </th>
           
            </tr>
            

     </table>
        <br>
         <div class="div_titulos" title="">Tabela de frete</div>
     <table width="100%">
            
            <tr>
                <td align="center">Cadastra valor por faixa de peso</td>
                <th align="center">&nbsp;</th>
       
            </tr>
            <tr>
                <td align="center">De
                 <input type="text" id="pesoDe" name="pesoDe" class="inputSizePP" value="" style="text-align:center">
até
<input type="text" id="pesoAte" name="pesoAte" class="inputSizePP" value="" style="text-align:center">
Kg</td>
                <td align="left">Valor R$
                 <input type="text" id="custoFrete" name="custoFrete" class="inputSizePP formatNum" value="" style="text-align:center">
                 <input type="button" id="btSalvaFrete" value="Salvar" class="inputSizePP">
                <input type="hidden" name="formSalvaFrete" value="S">
                </td>
           
            </tr>
 

        </table>
    </form>
  
</div>
<br>
<br>

    <div id="tbFrete" style="display:none" class="divMiniBloco width_30p"></div>

</div>
    

 
</div>
    
   <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
 <div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape">
 </div>   
</body>
</html>