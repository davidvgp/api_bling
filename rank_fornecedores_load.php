 <?php
session_start();
require('config.php');

$Class = new classMasterApi();
$sql   = new Sql();

    $dataIni = $_POST[ 'dataIni' ]?? Date( 'Y-m-01' );
    $dataFin = $_POST[ 'dataFin' ]?? Date( 'Y-m-d' );


  /**************  RANK DE FORNECEDORES ****************************************************************************************************/

  $rank_geral = $sql->select( "CALL p_rel_rank_fornecedor (:ID_CONTA_BLING, :DATA_INI, :DATA_FIN)",
    array( ":ID_CONTA_BLING" => '0',
        ":DATA_INI" => $dataIni,
        ":DATA_FIN" => $dataFin ) );


  ?>

   <br>

<div class='divBloco width_50p'>
    
          <div class="div_titulos size20s">GERAL</div>    

          <span>Rank de forncedor de: <?php echo Date( 'd/m', strtotime( $dataIni ) ) . " a " . Date( 'd/m', strtotime( $dataFin ) ); ?></span><br>
    <table class='tabela_padrao'>
        <?php

        foreach ( $rank_geral as $col ) {

          echo "<tr>";
          echo "<th style='font-size:12px; text-align:center;'></th>";

          foreach ( $col as $li => $a ) {

            echo "<th style='font-size:12px; text-align:center;'>" . $li . "</th>";

          }

          echo "</tr>";
          break;
        }

        $rnk = 1;

        foreach ( $rank_geral as $col ) {

          echo "<tr>";

          echo "<td style='font-size:12px; text-align:right;'>" . $rnk++ . "</td>";

          foreach ( $col as $li => $a ) {


            if ( is_numeric( $a ) ) {

              echo "<td style='font-size:12px; text-align:right;'>" . number_format( $a, 2, ',', '.' ) . "</td>";

            } else {

              echo "<td style='font-size:12px; text-align:left;'>" . $a . "</td>";

            }
          }


          echo "</tr>";
        }
         
          
        $custo = array();
        $venda = array();
        $mkp   = array();

        foreach ( $rank_geral as $row ) {
             
              $custo[] = $row['Custo'];
              $venda[] = $row['Venda'];


        }   
          
        $mkpMed = array();
          
          foreach ( $rank_geral as $row ) {
    
         $mkpMed[] = $row['Mark-Up'] / array_sum($venda) *  $row['Venda'];

        }  
          
         $ttCusto = number_format(array_sum($custo),2,',','.');
         $ttVenda = number_format(array_sum($venda),2,',','.');
         $ttMkp   = number_format(array_sum($mkpMed),2,',','.');
          
          
        ?>
          
          <tr>
              <th></th>
              <th>Total</th>
        
              <th align="right"><?php echo $ttCusto; ?></th>
              <th align="right"><?php echo $ttVenda; ?></th>
              <th align="right"><?php echo $ttMkp; ?></th>
        </tr> 
          
    </table>
    </div>
  
  <!--***********************************************************************************************************************-->
  
  <?php

    $ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


  foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


    $contas = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api



    $rank_conta = $sql->select( "CALL p_rel_rank_fornecedor (:ID_CONTA_BLING, :DATA_INI, :DATA_FIN)",
      array( ":ID_CONTA_BLING" => $id_user[ "id" ],
        ":DATA_INI" => $dataIni,
        ":DATA_FIN" => $dataFin ) );


    ?>
   <br><br>


    <div class='divBloco width_50p'>
        
         <div class="div_titulos size20"><?php echo $id_user[ 'nome_conta_bling' ]?></div>
        
        <span>Rank de forncedor de: <?php echo Date( 'd/m', strtotime( $dataIni ) ) . " a " . Date( 'd/m', strtotime( $dataFin ) ); ?></span><br>
      <table class='tabela_padrao'>
        <?php

        foreach ( $rank_conta as $col ) {

          echo "<tr>";
          echo "<th style='font-size:12px; text-align:center;'></th>";

          foreach ( $col as $li => $a ) {

            echo "<th style='font-size:12px; text-align:center;'>" . $li . "</th>";

          }

          echo "</tr>";
          break;
        }

        $rnk = 1;

        foreach ( $rank_conta as $col ) {

          echo "<tr>";

          echo "<td style='font-size:12px; text-align:right;'>" . $rnk++ . "</td>";

          foreach ( $col as $li => $a ) {


            if ( is_numeric( $a ) ) {

              echo "<td style='font-size:12px; text-align:right;'>" . number_format( $a, 2, ',', '.' ) . "</td>";

            } else {

              echo "<td style='font-size:12px; text-align:left;'>" . $a . "</td>";

            }
          }


          echo "</tr>";
        }
      
        $custoc = array();
        $vendac = array();
        $mkpc   = array();

        foreach ( $rank_conta as $row ) {
             
              $custoc[] = $row['Custo'];
              $vendac[] = $row['Venda'];


        }   
          
        $mkpMedc = array();
          
          foreach ( $rank_conta as $row ) {
    
         $mkpMedc[] = $row['Mark-Up'] / array_sum($vendac) *  $row['Venda'];

        }  
          
         $ttCustoc = number_format(array_sum($custoc),2,',','.');
         $ttVendac = number_format(array_sum($vendac),2,',','.');
         $ttMkpc   = number_format(array_sum($mkpMedc),2,',','.');
          
      
      
      
        ?>
         <tr>
              <th></th>
              <th>Total</th>
        
              <th align="right"><?php echo $ttCustoc; ?></th>
              <th align="right"><?php echo $ttVendac; ?></th>
              <th align="right"><?php echo $ttMkpc; ?></th>
        </tr> 
      </table>
    </div>

  <?php
  }
  ?>