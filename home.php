<?php

require_once( "session.php" );
require_once( "config.php" );


$Class = new classMasterApi();
$sql   = new Sql();

 if ( !empty($_SESSION[ "idUsuario" ] )) {

    $idsContas = $Class->getContas( $_SESSION[ "idUsuario" ] ); 

 }

?>

<!doctype html>
<html>
<head>
<title>Início</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<link rel="stylesheet" href="css/style.css" />
    
<script src="js/jquery-3.7.1.js"></script> 
    
<script src="js/js_geral.js"></script>
    
    <script>
    $(document).ready(function(){
    // Define o intervalo de tempo em milissegundos (ex.: 30000 = 30 segundos)
    setInterval(function(){
        location.reload();
    }, 120000);
});

    
    </script>
</head>
<body>
<div id="header">  <?php require_once("header.php");  ?> </div>
<div id="main">
    
<div id="divMenu" class=""> <?php require_once("menu.php"); ?></div>
    
<div id="divContainer">
    <?php

    /************** FASH DE VENDAS GERAL ****************************************************************************************************/

    $flashVendasDia = $sql->select( "CALL flashVendas (:DATA_INI, :DATA_FIN)",
        array( ":DATA_INI" => Date( 'Y-m-d' ), ":DATA_FIN" => Date( 'Y-m-d' ) ) );
    
   $flashVendasOntem = $sql->select("CALL flashVendas (:DATA_INI, :DATA_FIN)", 
    array(
        ":DATA_INI" => Date('Y-m-d', strtotime('-1 day')), ":DATA_FIN" => Date('Y-m-d', strtotime('-1 day'))
    )
);

    $flashVendasMes = $sql->select( "CALL flashVendas (:DATA_INI, :DATA_FIN)",
        array( ":DATA_INI" => Date( 'Y-m-1' ),  ":DATA_FIN" => Date( 'Y-m-d' ) ) );

$flashVendasMesAnterior = $sql->select(
    "CALL flashVendas (:DATA_INI, :DATA_FIN)",
    array(
        ":DATA_INI" => date('Y-m-01', strtotime('first day of last month')),
        ":DATA_FIN" => date('Y-m-t', strtotime('last day of last month'))
    )
);



    $vendaAno = $sql->select( "CALL flashVendas (:DATA_INI, :DATA_FIN)",
        array( ":DATA_INI" => Date( 'Y-1-1' ), ":DATA_FIN" => Date( 'Y-m-d' ) ) );


    $periodos = array( "Hoje" => $flashVendasDia,"Ontem"=>$flashVendasOntem,
        "Mês atual" => $flashVendasMes, "Mes anterior" => $flashVendasMesAnterior, "Ano" => $vendaAno );

    ?>
    <div class='divBlocoGrande'>
        
    <div class='divBlocoNV1'>
     
     <div class='divBlocoNV2'>
         
     <div class='div_titulos'>GERAL</div>  
         
     <div class="maeFlex">  

         <?php   foreach ( $periodos as $periodo => $v ) {

            ?> 
      
        <div class='divMiniBloco'>
            <?php

            $totalV = 0;
            $totalQ = 0;


                 echo "<table class='tabela_padrao'>";
            echo "<thead>";
            echo "<tr><th colspan='4'>{$periodo}</th></tr>";
            echo "</thead>";
            echo "</tbody>";
            
            foreach ( $v as $value ) {

                echo "<tr>";

                echo "<td>";
                echo '<span class="iconeBola" title=".." style="background-color:' . $value[ "cor" ] . ';"></span>';
                echo "</td>";

                echo "<td style='text-align:left;'>" . $value[ 'situacao' ] . "</td>";
                echo "<td style='text-align:center;'>" . number_format( $value[ 'qtde' ], 0, ',', '.' ) . "</td>";
                echo "<td style='text-align:right;'>"  . number_format( $value[ 'valor' ], 2, ',', '.' ) . "</td>";

                echo "</th>";

                if ( $value[ 'situacao' ] == "Atendido"
                    or $value[ 'situacao' ] == "Em andamento" ) {

                    $totalV += $value[ 'valor' ];
                    $totalQ += $value[ 'qtde' ];


                }
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr >";
            echo "<td></td>";
            echo "<td style='font-weight:bold;'>Total</td>";
            echo "<td style='text-align:center; font-weight:bold;'>" . $totalQ . "</td>";
            echo "<td style='text-align:right; font-weight:bold;'>" . number_format( $totalV, 2, ',', '.' ) . "</td>";
            echo "</tr>";
            echo "</tfoot>";

            ?>
            </table>
        </div>
    
    
        <?php
        }


        /***************** FIM FLASH VENDAS GERAL *********************************************************************************************/


        /************** FASH DE VENDAS CONTAS ****************************************************************************************************/


        foreach ( $idsContas as $ids ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


                $flashVendasDia = $sql->select( "CALL p_FlashVendasContas (:DATA_INI, :DATA_FIN, :ID_CONTA_BLING)",
                    array( ":DATA_INI" => Date( 'Y-m-d' ),
                        ":DATA_FIN" => Date( 'Y-m-d' ),
                        ":ID_CONTA_BLING" => $ids[ 'id' ] ) ); 
                
                $flashVendasOntem = $sql->select( "CALL p_FlashVendasContas (:DATA_INI, :DATA_FIN, :ID_CONTA_BLING)",
                    array( ":DATA_INI" => Date('Y-m-d', strtotime('-1 day')),
                           ":DATA_FIN" => Date('Y-m-d', strtotime('-1 day')),
                           ":ID_CONTA_BLING" => $ids[ 'id' ] ) );

                $flashVendasMes = $sql->select( "CALL p_FlashVendasContas (:DATA_INI, :DATA_FIN, :ID_CONTA_BLING)",
                    array( ":DATA_INI" => Date( 'Y-m-01' ),
                        ":DATA_FIN" => Date( 'Y-m-d' ),
                        ":ID_CONTA_BLING" => $ids[ 'id' ] ) );


                $flashVendasMesAnterior = $sql->select( "CALL p_FlashVendasContas (:DATA_INI, :DATA_FIN, :ID_CONTA_BLING)",
                   array(
                        ":DATA_INI" => date('Y-m-01', strtotime('first day of last month')),
                        ":DATA_FIN" => date('Y-m-t', strtotime('last day of last month')),
                        ":ID_CONTA_BLING" => $ids[ 'id' ] ) );


                $vendaAno = $sql->select( "CALL p_FlashVendasContas (:DATA_INI, :DATA_FIN, :ID_CONTA_BLING)",
                    array( ":DATA_INI" => Date( 'Y-01-01' ),
                        ":DATA_FIN" => Date( 'Y-m-d' ),
                        ":ID_CONTA_BLING" => $ids[ 'id' ] ) );

                $periodos = array( "Hoje" => $flashVendasDia,"Ontem"=>$flashVendasOntem,
                    "Mês atual" => $flashVendasMes,
                    "Mês anterior" => $flashVendasMesAnterior,
                    "Ano" => $vendaAno );

                ?>
       
       </div>
       </div>
           
     <div class='divBlocoNV2'>  
    
     <div class='div_titulos'><?php echo $Class->getConta($ids['id']);?></div>
         
      <div class="maeFlex">    
          
        <?php   foreach ( $periodos as $periodo => $v ) {  ?>
       
           <div class='divMiniBloco'>
            <?php
            $totalV = 0;
            $totalQ = 0;


            echo "<table class='tabela_padrao'>";

            echo "<thead>";
            echo "<tr><th colspan='4'>{$periodo}</th></tr>";
            echo "</thead>";
            echo "</tbody>";

            foreach ( $v as $value ) {

                echo "<tr>";

                echo "<td>";
                echo '<span class="iconeBola" title=".." style="background-color:' . $value[ "cor" ] . ';"></span>';
                echo "</td>";

                echo "<td style='text-align:left;'>" . $value[ 'situacao' ] . "</td>";
                echo "<td style='text-align:center;'>" . number_format( $value[ 'qtde' ], 0, ',', '.' ) . "</td>";
                echo "<td style='text-align:right;'>" . number_format( $value[ 'valor' ], 2, ',', '.' ) . "</td>";

                echo "</th>";

                if ( $value[ 'situacao' ] == "Atendido"
                    or $value[ 'situacao' ] == "Em andamento" ) {   

                    $totalV += $value[ 'valor' ];
                    $totalQ += $value[ 'qtde' ];


                }
            }
              
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr >";
            echo "<td></td>";
            echo "<td style='font-weight:bold;'>Total</td>";
            echo "<td style='text-align:center; font-weight:bold;'>" . $totalQ . "</td>";
            echo "<td style='text-align:right; font-weight:bold;'>" . number_format( $totalV, 2, ',', '.' ) . "</td>";
            echo "</tr>";
            echo "</tfoot>";         
       
            ?>
            </table>
        </div>
        
        <?php } ?>
        </div>
        <?php } ?>
 

        


    </div>
    </div>

    </div>
    </div>

<div id="rodape">todos os direitos resevado.</div>
<div id="notaRodape"> </div>
</body>
</html>
