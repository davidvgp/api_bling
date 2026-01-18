<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

?>

<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16">Podutos Estrutura</div> <hr> 

<?php

require_once( 'config.php' );


$Class = new classMasterApi();
$sql = new Sql();


$ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) {


  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


  foreach ( $token as $ids ) {


    $accessToken = $ids[ 'access_token' ];


    $requisicao = "";


    $res_idProd = $sql->select(

      "SELECT `id_bling` FROM `tb_produtos_detalhes` WHERE `tipo` = 'P' AND `situacao` = 'A' and `formato` = 'E' and `id_conta_bling` =" . $ids[ 'id' ] );


    $ttl_Listados = 1;


    $sleep = 1;

    foreach ( $res_idProd as $idProd ) {


      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT

      //  $recurso = "produtos/estruturas/14554966120";
      $recurso = "produtos/estruturas/" . $idProd[ 'id_bling' ];


      //  echo "<hr>";

      $requisicao = $recurso;
        usleep(333334);
      $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

      if ( isset( $dados->error ) ) {

        print_r( $dados );
        echo "<hr>";

      } else {


        foreach ( $dados as $col ) {

          if ( isset( $col->componentes ) ) {

            foreach ( $col->componentes as $lin ) {


              $cad_prod_estr = "call p_cad_produto_estrutura (
            :ID_CONTA_BLING,
            :IDPRODUTOESTRUTURA,
            :PROTUDO_ID,
            :QUANTIDADE

          )";

              $values = array(
                ":ID_CONTA_BLING" => $ids[ 'id' ],
                ":IDPRODUTOESTRUTURA" => $idProd[ 'id_bling' ],
                ":PROTUDO_ID" => $lin->produto->id,
                ":QUANTIDADE" => $lin->quantidade

              );

                 $sql->run( $cad_prod_estr, $values );

            }
          }
        }
      }

    
    }


  }

  echo "Conta " . $ids[ 'id' ];
  echo "<Br>";
}
echo "<hr>";


?>
</div>