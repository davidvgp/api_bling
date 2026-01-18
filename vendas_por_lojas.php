<?php
require_once( "session.php" );
require_once( "config.php" );


$Class = new classMasterApi();
$sql   = new Sql();



  if ( isset( $_POST[ 'dataIni' ] ) ) {

    $dataIni = $_POST[ 'dataIni' ];
    $dataFin = $_POST[ 'dataFin' ];


  } else {
      
    $dataIni = Date( 'Y-m-01' );
    $dataFin = Date( 'Y-m-d' );
  }

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<script src="js/jquery-3.7.1.js"></script> 
<link rel="stylesheet" href="js/datatables.css" />
<script src="js/datatables.js"></script>
<link   rel="stylesheet" href="css/style.css" />
<script src="js/js_geral.js"></script> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vendas por lojas</title>
</head>
    
<script>
    
 $(document).ready(function(){

   $("#absolut").hide();
    $("body").fadeIn(); 
     
 });

</script>


    
<body>
       
<div class="header">
  <?php require_once("header.php");  ?>
</div>
<div id="main">    
<div id="divMenu">
  <?php require_once("menu.php");   ?>
</div>
<div id="divContainer">
  <div class='divBlocoGrande'>
    <div class="divBloco">
      <form action="vendas_por_lojas.php" class="" method="post" accept-charset="UTF-8" >
        <div class="div_titulos">Analise de venda por lojas</div>
          <hr>
          <br>

        Período  <input type="date" name="dataIni" class="inputSizeM" value="<?php echo $dataIni; ?>">
        até      <input type="date" name="dataFin" class="inputSizeM" value="<?php echo $dataFin; ?>">
      
        <input type="submit" value="Buscar" class="inputSizeP">
      </form>
    </div>
  </div>
  <?php

  /*************** GREAL *******************************************************************************************************/

  echo "<div class='divBlocoGrande'>";


  echo "<div class='divMiniBloco width_70p'>";

  $rel = $sql->select( "CALL p_rank_de_lojas_geral (:DATAINI,:DATAFIN)", array( ":DATAINI" => $dataIni,  ":DATAFIN" => $dataFin ) );


  echo "<div class='div_titulos size20'> GERAL </div>";

  echo "<span>Realizado entre " . Date( 'd/m/y', strtotime( $dataIni ) ) . " a " . Date( 'd/m/y', strtotime( $dataFin ) ) . "</span>";


  echo "<hr>";


  echo "<table id='tblDin' class='tblStyle1 display'>";


  echo "<tr>";
  echo "<th></th>";
  echo "<th>Loja</th>";
  echo "<th>Vendas</th>";
  echo "<th>Total</th>";
  echo "<th>Desc</th>";
  echo "<th>Custo</th>";
  echo "<th>Taxa</th>";
  echo "<th>%</th>";
  echo "<th>Frete</th>";
  echo "<th>Repasse</th>";
  echo "<th>Imp</th>";
  echo "<th>%Imp</th>";
  echo "<th>Lucr</th>";
  echo "<th>%Marg</th>";


  echo "</tr>";


  $rnk = 1;
  $Vendas = 0;
  $Total = 0;
  $TTotal = array();
  $Desc = 0;
  $Custo = 0;
  $Taxa = 0;
  $tx = 0;
  $Frete = 0;
  $Repas = 0;
  $Imp = 0;
  $I = 0;
  $Lucr = 0;
  $Mrg = 0;
  $mrgp = 0;

  foreach ( $rel as $col ) {

    echo "<tr>";

    echo "<td style='font-size:12px; text-align:right;'>" . $rnk++ . "</td>";
    echo "<td style='text-align:left'>" . $col[ 'Loja' ] . "</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Vendas' ], 0, ',', '.' ) . "</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Total' ], 2, ',', '.' ) . "</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Desc' ], 2, ',', '.' ) . "</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Custo' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Taxa' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ '%' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Frete' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Repas' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Imp' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ '%Imp' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:right'>" . number_format( $col[ 'Lucr' ], 2, ',', '.' ) . "	</td>";
    echo "<td style='text-align:center'>" . number_format( $col[ '%Marg' ], 2, ',', '.' ) . "	</td>";

    echo "</tr>";

         $TTotal[] = $col[ 'Total' ];

      }

      foreach ( $rel as $col ) {

        $Subtotal = " ";
        $Lojas    = "Total";
        $Vendas += $col[ 'Vendas' ];
        $Total  += $col[ 'Total' ];
        $Desc   += $col[ 'Desc'  ];
        $Custo  += $col[ 'Custo' ];
        $Taxa   += $col[ 'Taxa'  ];
        $Frete  += $col[ 'Frete' ];
        $Repas  += $col[ 'Repas' ];
       
          
        $Imp  += $col[ 'Imp' ];
        $I     = $col[ '%Imp' ];
        $Lucr += $col[ 'Lucr' ];
          
        $I   += ( $col[ '%Imp' ]  * ( $col[ 'Total' ] / array_sum( $TTotal ) ) );
        $tx  += ( $col[ '%' ]     * ( $col[ 'Total' ] / array_sum( $TTotal ) ) );
        $Mrg += ( $col[ '%Marg' ] * ( $col[ 'Total' ] / array_sum( $TTotal ) ) );


      }


      echo "<tr>";


  echo "<th style='text-align:center; border:none; border-top:1px solid #333;'>" . $Subtotal . "</th>";
  echo "<th style='text-align:center; border:none; border-top:1px solid #333;'>" . $Lojas . " </th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Vendas, 0, ',', '.' ) . "</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Total, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Desc, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Custo, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Taxa, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $tx, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Frete, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Repas, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Imp, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $I, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Lucr, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:center; border:none; border-top:1px solid #333;'>" . number_format( $Mrg, 2, ',', '.' ) . "	</th>";
  echo "</tr>";

  echo "</table>";
  echo "</div>";

    
    
/******************************* CONTAS ***********************************************/

    $contas = $Class->getContas($_SESSION[ "idUsuario" ] );
  
    foreach ( $contas as $ids ) { //  

      echo "<div class='divMiniBloco width_70p'>";

      $rel = $sql->select( "CALL p_rank_de_lojas (:ID_CONTA_BLING,:DATAINI,:DATAFIN)",
        array( ":ID_CONTA_BLING" => $ids[ 'id' ],
          ":DATAINI" => $dataIni,
          ":DATAFIN" => $dataFin ) );


      echo "<div class='div_titulos size20'>" . $id_user[ 'nome_conta_bling' ] . "</div>";

      echo "<span>Realizado entre " . Date( 'd/m/y', strtotime( $dataIni ) ) . " a " . Date( 'd/m/y', strtotime( $dataFin ) ) . "</span>";

      echo "<hr>";


      echo "<table class='tblStyle1 display'>";

      echo "<tr>";
      echo "<th>Rank</th>";
      echo "<th>Loja</th>";
      echo "<th>Vendas</th>";
      echo "<th>Total</th>";
      echo "<th>Desc</th>";
      echo "<th>Custo</th>";
      echo "<th>Taxa</th>";
      echo "<th>%</th>";
      echo "<th>Frete</th>";
      echo "<th>Repasse</th>";
      echo "<th>Imp</th>";
      echo "<th>%Imp</th>";
      echo "<th>Lucr</th>";
      echo "<th>%Marg</th>";


      echo "</tr>";


      $rnk = 1;
      $Vendas = 0;
      $Total = 0;
      $TTotal = array();
      $Desc = 0;
      $Custo = 0;
      $Taxa = 0;
      $tx = 0;
      $Frete = 0;
      $Repas = 0;
      $Imp = 0;
      $I = 0;
      $Lucr = 0;
      $Mrg = 0;
      $mrgp = 0;

      foreach ( $rel as $col ) {

        echo "<tr>";

        echo "<td style='font-size:12px; text-align:right;'>" . $rnk++ . "</td>";
        echo "<td style='text-align:left'>" . $col[ 'Loja' ] . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Vendas' ], 0, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Total' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Desc' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Custo' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Taxa' ], 2, ',', '.' ) . "	</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ '%' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Frete' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Repas' ], 2, ',', '.' ) . "</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Imp' ], 2, ',', '.' ) . "	</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ '%Imp' ], 2, ',', '.' ) . "	</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ 'Lucr' ], 2, ',', '.' ) . "	</td>";
        echo "<td style='text-align:right'>" . number_format( $col[ '%Marg' ], 1, ',', '.' ) . "</td>";

        echo "</tr>";

        $TTotal[] = $col[ 'Total' ];

      }

      foreach ( $rel as $col ) {

        $Subtotal = " ";
        $Lojas    = "Total";
        $Vendas += $col[ 'Vendas' ];
        $Total += $col[ 'Total' ];
        $Desc  += $col[ 'Desc' ];
        $Custo += $col[ 'Custo' ];
        $Taxa  += $col[ 'Taxa' ];
        $Frete += $col[ 'Frete' ];
        $Repas += $col[ 'Repas' ];
          
        $Imp  += $col[ 'Imp' ];
        $I     = $col[ '%Imp' ];
        $Lucr += $col[ 'Lucr' ];
          
        $I   += ($col[ '%Imp' ]   * ( $col[ 'Total' ] / array_sum( $TTotal ) ) );
        $tx  += ( $col[ '%' ]     * ( $col[ 'Total' ] / array_sum( $TTotal ) ) );
        $Mrg += ( $col[ '%Marg' ]  * ( $col[ 'Total' ]  / array_sum( $TTotal ) ) );


      }


      echo "<tr>";


  echo "<th style='text-align:center; border:none; border-top:1px solid #333;'>" . $Subtotal . "</th>";
  echo "<th style='text-align:center; border:none; border-top:1px solid #333;'>" . $Lojas . " </th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Vendas, 0, ',', '.' ) . "</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Total , 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Desc, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Custo, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Taxa, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $tx, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Frete, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Repas, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Imp, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $I, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Lucr, 2, ',', '.' ) . "	</th>";
  echo "<th style='text-align:right; border:none; border-top:1px solid #333;'>" . number_format( $Mrg, 2, ',', '.' ) . "	</th>";

      echo "</tr>";

      echo "</table>";
    }
    echo "</div>";
    echo "</div>";


   ?>   
    </div>
    </div>



</div>
</body>
</html>