<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {
    
    
  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql      = new Sql();


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css" /> 
<link rel="stylesheet" href="css/style_menu.css" />
    
 <script src="js/jquery-3.7.1.js"></script>  
 <script src="js/js_oculta_menu.js"></script>
    

<title>Ultimos Pedidos</title>
</head>
<body>

<div class="header">
  <?php require_once("header.php");  ?>
</div>
<div id="main">    
<div id="divMenu">
    
  <?php require_once( "menu.php" ); ?>
    
</div>
<div id="divContainer">
    
<div class="divBlocoGrande">   
  <?php


  $ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


  foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


    $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

    
  
      echo "<div class='div_titulos'>Últimos pedidos: " . $id_user[ 'nome_conta_bling' ]."</div>";
      
    echo "<div class='divBloco scroll height_600'>";

    foreach ( $token as $ids ) { //  Laço 2 : EXECUTA A CHAMADA A API


      $ult_pedidos = $sql->select( "CALL p_flashUltimasVendas (:ID_CONTA_BLING)", array( ":ID_CONTA_BLING" => $ids[ 'id' ] ) );


    
      echo "<hr>";
      echo "<table class='tabela_padrao'>";

      echo "<tr>";
      echo "<th style='font-size:12px; text-align:center;'>Data</th>";
      echo "<th style='font-size:12px; text-align:center;'>Pedido</th>";
      echo "<th style='font-size:12px; text-align:center;'>Stt</th>";
      echo "<th style='font-size:12px; text-align:center;'>Pedido Loja</th>";
      echo "<th style='font-size:12px; text-align:center;'>Loja</th>";
      echo "<th style='font-size:12px; text-align:center;'>SKU</th>";
      echo "<th style='font-size:12px; text-align:center;'>Produto</th>";
      echo "<th style='font-size:12px; text-align:center;'>Qtde</th>";
      echo "<th style='font-size:12px; text-align:center;'>Valor</th>";
      echo "<th style='font-size:12px; text-align:center;'>Custo</th>";
      echo "<th style='font-size:12px; text-align:center;'>Desc</th>";
      echo "<th style='font-size:12px; text-align:center;'>Total</th>";
      echo "<th style='font-size:12px; text-align:center;'>Taxa</th>";
      echo "<th style='font-size:12px; text-align:center;'>Base C</th>";
      echo "<th style='font-size:12px; text-align:center;'>Repas</th>";
      echo "<th style='font-size:12px; text-align:center;'>Imp</th>";
      echo "<th style='font-size:12px; text-align:center;'>%</th>";
      echo "<th style='font-size:12px; text-align:center;'>Lucr</th>";
      echo "<th style='font-size:12px; text-align:center;'>%</th>";
      echo "</tr>";
   

      foreach ( $ult_pedidos as $col ) {


        echo "<tr>";

        //    if($a < 0.0){ $color ='#d69143'; echo "negativo"; }

        echo "<td style='font-size:12px; text-align:center;'>" . $col[ 'Data' ] . "</td>";
          
        echo "<td style='font-size:12px; text-align:center;'>";
        echo "<a href='https://www.bling.com.br/vendas.php#edit/".$col['idPedido']."' target='_blank'>";
        echo $col[ 'Pedido' ];
        echo "</a>";
        echo "</td>"; 
          
        echo '<td style="font-size:12px; text-align:center;"><span class="iconeBola" title="'.$col["Stt"].'" style="background-color:'.$col["Cor"].';"></span></td>';
        echo "<td style='font-size:12px; text-align:center;'>" . $col[ 'Pedido Loja' ] . "</td>";
        echo "<td style='font-size:12px; text-align:center;'>" . $col[ 'Loja' ] . "</td>";
        echo "<td style='font-size:12px; text-align:center;'>" . $col[ 'SKU' ] . "</td>";
        echo "<td style='font-size:12px; text-align:left;'>"   . substr( $col[ 'Produto' ], 0, 60 ) . "</td>";
        echo "<td style='font-size:12px; text-align:center;'>" . $col[ 'Qtde' ] . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Valor'  ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Custo'  ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Desc'   ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Total'  ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Taxa'   ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Base C.'], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'Repas'  ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ '$Imp'   ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ '%Imp'   ], 2, ',', '.' ) . "%</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'MC'     ], 2, ',', '.' ) . "</td>";
        echo "<td style='font-size:12px; text-align:right;'>" . number_format( $col[ 'M%'     ], 1, ',', '.' ) . "%</td>";
        echo "</tr>";
      }


      echo "</table>";
    

    }

    echo "</div>";
      
  }
    
  ?>
</div>
</div>
</div>
    <div id="rodape"></div>   
</body>
    
</html>
