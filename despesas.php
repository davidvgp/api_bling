<?php

require_once("session.php"); 
require_once( "config.php" );
?>
<link rel="stylesheet" href="css/style.css" /> 
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_pg_despesas.js"></script>
<!doctype html>
<html>
<head>

<title>Cadatro de Despesas</title>
</head>
<script>
  
 $(document).ready(function (){
    
     paginaDespeas("cadastra");
    
    $("#btlancarDespesa").on("click", function () {
       
        efeitoload('in');
        $("#divDespesas").slideUp();
        paginaDespeas("cadastra");

    });

    $("#exibirDespesa").on("click", function () {
        
        $("#divDespesas").slideUp();
        efeitoload('in');
        paginaDespeas("relatorio");

    });

    $("#conta").on("change", function () {

        if ($(this).val().length > 1) {

            $("#tbOpcoes").show();
        } else {
            $("#tbOpcoes").hide();
        }

    });


    $("#btCadastraDespesa").on("click", function () {

        var conta = $("#conta").val();

        if (conta.length > 0) {
            
            $("#valor").val(InNumCalc($("#valor").val()));

            var post = $("#form1").serialize();

            efeitoload('in');


            $.post("salva_formularios.php", post, function (dados) {

                $("#notaRodape").html(dados).fadeIn();

                carregaListaDespesas();
                
                efeitoload('out');
                
                  $("#valor").val(OutNumCalc($("#valor").val()));


            });

        } else {

            alert("Selecione uma conta!");

        }

    });

 $("#conta").on("change", function () {

        carregaListaDespesas();

    });

    

});   
    

    </script>

<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2">
    <?php require_once("menu.php");   ?>
</div>
<div id="divContainer" class="col-10">
    <div id="divDespesas" class="divBlocoGrande"> </div>
    <br>
    <br>
    <div class='divBlocoGrande'>
        <div id="divCarregado"> </div>
    </div>
</div>

<!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
<div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>