<?php
require_once( "session.php" );
require_once( 'config.php' );
$Class = new classMasterApi();
$sql = new Sql();

$contas = $Class->getIdContaToken( $_SESSION[ "idUsuario" ] );

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" /> 
 <script src="js/jquery-3.7.1.js"></script>  
 <script src="js/js_oculta_menu.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atualiza saldo estoque</title>
</head>
<body>
    
    
<div id="header">
  <?php require_once("header.php");  ?>
</div>
<div id="main">
<div id="divMenu">
  <?php require_once("menu.php");   ?>
</div>
<div id="divContainer" >
    
  <div id="" class="divBlocoGrande">
      
<?php
      $nPagina = 1; //numero páginas
      $limite  = 100; // linhas por página

      foreach($contas as $idConta => $token){
          
        $operApi    = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso    = "estoques/saldos/";
        $filtro     = "";
        $fullDados  = array();
        $requisicao = "";
        $deposito   = $sql->select( "SELECT `id_bling` as id FROM `tb_depositos` WHERE `id_conta_bling` = " . $idConta . " AND `padrao` = 1" );

        $cont_dados = 0;
          
        foreach ( $deposito as $dep ) {

          $dep[ 'id' ];

          $produtos = $sql->select( "call p_busca_prod_saldo (:ID_CONTA_BLING)", array( ":ID_CONTA_BLING" => $idConta ) );

          $id_prod = array();

          foreach ( $produtos as $codigos ) {
          foreach($codigos as $codigo => $cod) {   
          $id_prod[] = $cod ;
              
          }    
      }
        
        
        if ( is_array( $id_prod ) ) {

        $id_prod2 = implode( ',', $id_prod );

        }
            
        $filtro     = http_build_query(array('idsProdutos'=>$id_prod));
            
        $requisicao = $recurso . $dep[ 'id' ] . "?" . $filtro;
            
            usleep(334000);

            $dados = $Class->apiGET($requisicao, $operApi, $token );

            $dados = json_decode( $dados );
            /*
            echo "<Br>"; 
            print_r( $dados );
            echo "<hr>";
            */
            if ( empty( $dados->data ) ) {

              print_r( $dados );
              echo "<BR>";
              echo $Class->getConta($idConta);
                
               $DEL = "DELETE FROM `tb_produtos_detalhes` WHERE `id_bling` IN ('{$id_prod2}')";
               $sql->run($DEL);   
                
              echo "<hr>";

            }

            foreach ( $dados as $col ) {

              foreach ( $col as $lin ) {

                $call = "CALL p_cad_saldo_estoque (
                                                :ID_CONTA_BLING,
                                                :PRODUTO_ID,
                                                :DEPOSITOS_ID,
                                                :SALDOFISICO,
                                                :SALDOVIRTUAL

                                                 )";
      /*            
                  echo "id conta: ".$ids[ 'id' ];
                   echo "<br>";
                  echo "id produto: ".$lin->produto->id;
                   echo "<br>";
                  echo "id deposito: ".$dep[ 'id' ];
                   echo "<br>";
                  echo "id saldoFisico: ".$lin->saldoFisicoTotal;
                   echo "<br>";
                  echo "id saldoVitual: ".$lin->saldoVirtualTotal;
                  echo "<br>";
*/
                $value = array(
                  ":ID_CONTA_BLING" => $idConta,
                  ":PRODUTO_ID"   => $lin->produto->id,
                  ":DEPOSITOS_ID" => $dep[ 'id' ],
                  ":SALDOFISICO" => $lin->saldoFisicoTotal,
                  ":SALDOVIRTUAL" => $lin->saldoVirtualTotal
                );

                $res =  $sql->select( $call, $value );
                
          //     print_r($res);
          //     echo "<br>";
                $cont_dados++;
              }
            }
        }

      echo "Base saldo de estoque atualizada";
      echo "<br>";
      echo "Conta: " . $Class->getConta($idConta);
      echo "<br>";
      echo "Total itens atualziados: " . $cont_dados;
      echo "<br>";
      echo "<hr>";
      echo "<br>";
      echo "<br>";

    }

    ?>
  </div>
</div>
</div>
    <div id="rodape"></div>
</body>
</html>