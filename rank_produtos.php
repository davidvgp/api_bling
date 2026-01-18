<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


?>

<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Produtos mais vendidos</title>
</head>
<body>
<body>
<div id="header">
  <?php require_once("header.php");  ?>
</div>
<div id="main">    
<div id="divMenu">
  <?php require_once( "menu.php" ); ?>
</div>
<div id="divContainer">
<div class='divBlocoGrande'>
  <?php

  $periodo = 30;  

 $dataMesAtual =    Date('Y-m-d', strtotime( - $periodo . 'days'));
 $dataAnoAtual =    Date('Y-1-1');   
 $rankTop      = 20;  


/***************RANK POR QUANTIDADE *******************************************************************************************************/   
    
$Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app
$dados = $Class->getIdContaToken();
   
foreach ( $dados as $idConta => $token ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling

echo "<div class='divBloco'>";
  

 $ult_pedidos = $sql->select( "CALL p_rank_prod_valor (:ID_CONTA_BLING,:DATA,:RANK)", 
                                array( ":ID_CONTA_BLING" =>$idConta,
                                       ":DATA"           =>$dataMesAtual,
                                       ":RANK"           => $rankTop ));
      
    
echo "<div class='div_titulos'>". $Class->getConta($idConta)."</div>" ;
    
echo "<span>Top ". $rankTop." em valor nos últimos ".$periodo." dias</span>";
    
echo "<br>";
    
echo "<table class='tblStyle1'>";

  foreach ( $ult_pedidos as $col) {
      
        echo "<tr>";
    echo "<th>Rank</th>";
      
    foreach($col as $li => $a){
          
         echo "<th>" .  $li. "</th>";

      }
      
    echo "</tr>";
      break;
}
      
      $rnk =1;
    
  foreach ( $ult_pedidos as $col) {    
      
    echo "<tr>";
      
    echo "<td>" .$rnk++."</td>"; 
      
    echo "<td>" .$col['Código']. "</td>";
    echo "<td>" .$col['Descrição']. "</td>";
    echo "<td>" .$col['Qtde']. "</td>";
    echo "<td>" .number_format($col['Valor'],0,',','.'). "</td>";
          
   echo "</tr>";
   }   
echo "</table>";
 
echo "</div>"; 


    
/***************RANK POR  QTDE *********************************************************************************************************/  
    
    $ult_pedidos = $sql->select( "CALL p_rank_prod_qtde (:ID_CONTA_BLING,:DATA,:RANK)", 
                                array( ":ID_CONTA_BLING" =>$idConta,
                                       ":DATA"           =>$dataMesAtual,
                                       ":RANK"           =>$rankTop ));
echo "<div class='divBloco'>";  
    
echo "<div class='div_titulos'>". $Class->getConta($idConta)."</div>" ;
      
echo "<div class='subTitulos'>Top ". $rankTop." em quantidade nos últimos ".$periodo." dias</div>";
echo "<br>";
      
      
echo "<table class='tblStyle1'>";

  foreach ( $ult_pedidos as $col) {
      
        echo "<tr>";
    echo "<th>Rank</th>";
      
    foreach($col as $li => $a){
          
         echo "<th>" .  $li. "</th>";

      }
      
    echo "</tr>";
      break;
}
      
      $rnk =1;
    
  foreach ( $ult_pedidos as $col) {    
      
    echo "<tr>";
      
    echo "<td>" .$rnk++."</td>"; 
      
    echo "<td>" .$col['Código']. "</td>";
    echo "<td>" .$col['Descrição']. "</td>";
    echo "<td>" .$col['Qtde']. "</td>";
    echo "<td>" .number_format($col['Valor'],0,',','.'). "</td>";
          
   echo "</tr>";
   }
    
echo "</table>";
     
 echo '</div>';
   
}
 ?>   
    
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