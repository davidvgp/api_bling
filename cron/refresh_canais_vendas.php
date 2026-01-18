<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}
//***************************************************************************************************
//********************** arquivo de refresh CANAIS DE VENDA******************************************

require_once( '../config.php' );
require_once( "../class/classMasterApi.php" );

$sql = new Sql();

$Class = new classMasterApi();

$ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) {

  $cont_dados = 0;

  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

  $nPagina = 1; //numero páginas
  $limite = 100; // linhas por página

  foreach ( $token as $ids ) {

    $accessToken = $ids[ 'access_token' ];

    $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "canais-venda";
    //$recurso = "contatos"; 


    $filtro = "situacao=1"; // 1 = ativo


    $fullDados = array();
    $cond = true;
    $requisicao = "";

    $res_agrupador = $sql->select( "SELECT * FROM tb_canais_de_venda_agrupador" );


    echo "<br>";


   // foreach ( $res_agrupador as $agrupador ) {

      while ( $cond ) {

        echo $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;
        echo "<Br>";

        sleep( 1 );

        $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

        $dados = json_decode( $dados );

        if ( empty( $dados->data ) ) {

          echo "RETORNO VAZIO<BR>";

          print_r( $dados );
          echo "<hr>";
          $cond = false;


          if ( !empty( $dados->error ) ) {
            echo "GEROU ERROR<BR>";
            print_r( $dados );
            echo "<hr>";
            $cond = false;
          }


        } else {

          $fullDados[ $nPagina ] = $dados;
          $nPagina++;
        }
      }
        
   // }


    foreach ( $fullDados as $dd ) {

      foreach ( $dd as $col ) {

        foreach ( $col as $lin ) {

          $cad = "CALL p_cad_canais_de_vendas (
            :ID_BLING,
            :DESCRICAO,
            :TIPO,
            :SITUACAO,
            :ID_CONTA_BLING)";

          $value = array(
            ":ID_BLING" => $lin->id,
            ":DESCRICAO" => $lin->descricao,
            ":TIPO" => $lin->tipo,
            ":SITUACAO" => $lin->situacao,
            ":ID_CONTA_BLING" => $ids[ 'id' ] );

          /*

          if ( isset($lin->filiais) || count( $lin->filiais ) > 0 ) {

              print_r( $lin->filiais);
              
            foreach ( $lin->filiais as $filias ) {

              $cad_filiais = "CALL p_canais_de_venda_filiais (
            :CNPJ,
            :UNIDADENEGOCIO,
            :DEPOSITO,
            :ID_BLING,
            :ID_CONTA_BLING                                 )";

              $value_filiais = array(
                ":CNPJ" => $filias->cnpj,
                ":UNIDADENEGOCIO" => $filias->unidadeNegocio,
                ":DEPOSITO" => $filias->deposito,
                ":ID_BLING" => $filias->id,
                ":ID_CONTA_BLING" => $filias[ 'id' ] );


              $sql->run( $cad_filiais, $value_filiais );

            }

          }    */

          $sql->run( $cad, $value );

          $cont_dados++;

        }

      }

    }
    echo "Base Canais de Venda";
    echo "<br>";
    echo "Conta: " . $ids[ 'id' ];
    echo "<br>";
    echo "Total cadastrado/atualziados: " . $cont_dados;
    echo "<br>";
    echo "<hr>";


  }
}


?>