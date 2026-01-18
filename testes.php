<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );
    
$Class = new classMasterApi();
$sql = new Sql();
$Class->setIdContaToken($_SESSION[ "idUsuario" ]);

$dados = $Class->getIdContaToken();

?>

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
<?php

 
// Array retornado pela função
 $Class->setIdContaToken($_SESSION[ "idUsuario" ]);
    
$dados = $Class->getIdContaToken();
// Variável para armazenar o mapeamento de IDs para tokens

$idConta = 2;
$token = $dados[$idConta];
 
echo "Conta ". $idConta;
echo "<br>";
echo $token;    
        

echo "<hr>";
    
         $conta = 1;
         $idContato ='';
         $dataInicial = '2024-10-14';
         $dataFinal = '2024-10-15';
         $numero = '';
         $idLoja ='';
         $atualizaPedido = false;
    
$param = array('idContato'   => $idContato,
               '$numero'     => $numero, 
               'dataInicial' => $dataInicial,
               'dataFinal'   => $dataFinal,
               'numero'      => $numero,
               'idLoja'      => $idLoja);
    
    
echo $Class->atualizaVenda($conta, $param, $atualizaPedido );
    
?>    
    
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