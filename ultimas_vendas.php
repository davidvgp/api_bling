<?php

require_once( "session.php" );
require_once( "config.php" );

?>
<script src="js/jquery-3.7.1.js"></script> 
<link rel="stylesheet" href="css/style.css" />

<script>
        
function carregaUltimasVendas() {
        
        
        $("#notaRodape").html("Atualizando...").fadeIn();

        efeitoload('in');
            
            $.post("ultimas_vendas_load.php",{},  function (dados) {

                $("#divUltimasVendas").html(dados).fadeIn();
                   
            
            });
        $("#notaRodape").fadeOut();
        efeitoload('out');
       
    }
    
    function carregaParcialVendas() {
        
        
        $("#notaRodape").html("Atualizando...").fadeIn();

        efeitoload('in');
            
            $.post("parciais_vendas_load.php",{},  function (dados) {

                $("#divParcialVendas").html(dados).fadeIn();
                   
            
            });
        $("#notaRodape").fadeOut();
        efeitoload('out');
       
    }  
    
$(document).ready(function() {
    
    carregaParcialVendas();
    carregaUltimasVendas();
   
    //setInterval(function(){ carregaUltimasVendas(); }, 24000);
    setInterval(function(){ carregaParcialVendas(); }, 120000);
    setInterval(function(){ carregaUltimasVendas(); }, 240000);
    });
 </script>



<!doctype html>
<html>
<head>
<title>Flash de Vendas</title>
</head>

<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
<div id="main">
<div id="divMenu" class=""><?php require_once( "menu.php" ); ?></div>

<div id="divContainer" >
    
    <div class="divBlocoGrande">
   <div id="divParcialVendas"> </div> 
   <div id="divUltimasVendas"> </div> 
</div> 
        
</div>   
</div>   
    
<div id="rodape">41</div>
<div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif"  width="50"></div>
</div>
</div>

<div id="notaRodape"> </div>
    
</body>
</html>