<div class="divMiniBloco width_50p">   


<?php
session_start();
//***************************************************************************************************
//********************** arquivo de refresh CANAIS DE VENDA******************************************

require_once( 'config.php' );
require_once( "class/classMasterApi.php" );

$sql = new Sql();

$Class = new classMasterApi();

$ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) {

  $cont_dados = 0;

  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


  $limite = 100; // linhas por página

  foreach ( $token as $ids ) {

    $accessToken = $ids[ 'access_token' ];

    $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "canais-venda";
    //$recurso = "contatos"; 


    $filtro = "agrupador=";


    $fullDados = array();
    $nomeGrupo = array();

    $requisicao = "";

    $res_agrupador = $sql->select( "SELECT * FROM tb_canais_de_venda_agrupador" );


    echo "<br>";

    foreach ( $res_agrupador as $agrupador ) {        
        
      $nPagina = 1; //numero páginas
      $cond = true;

      while ( $cond ) {


    $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro . $agrupador[ "agrupador" ];

        sleep( 1 );

        $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

        $dados = json_decode( $dados );


        if ( !empty( $dados->data ) ) {
        //  echo "data<Br>";
        //  print_r( $dados );
        //  echo "<hr>";

          $fullDados[ $nPagina ] = $dados;
          $nPagina++;

        } else {


          if ( !empty( $dados->error ) ) {

            echo "Error<Br>";
            print_r( $dados );
            echo "<hr>";

            $cond = false;
          }
            
          $cond = false;
        }
      }
   

      
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
    echo "Canais de Venda agrupador: " . $agrupador[ "nome" ];
    echo "<br>";
    echo "Conta " . $Class->getConta(  $ids[ 'id' ] );
    echo "<br>";
    echo "Total cadastrado/atualziados: " . $cont_dados;
    echo "<br>";
    echo "<hr>";


  }
}
}
?>
</div>