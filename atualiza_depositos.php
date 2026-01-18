<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( 'config.php' );

$Class = new classMasterApi();
$sql = new Sql();


?>


<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16">Atualiza Depositos</div>    <hr> 


    
      
    <?php


    $ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


    foreach ( $ids_user_api as $id_user ) {

      $cont_dados = 0;

      $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página

      foreach ( $token as $ids ) {

        $accessToken = $ids[ 'access_token' ];

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "depositos";
        //$recurso = "contatos"; 


        $filtro = "situacao=1"; // 0 = inativo, 1 = ativo


        $fullDados = array();
        $cond = true;
        $requisicao = "";


        echo "<br>";

        $contaSegundo = 1;

        while ( $cond ) {


          $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

          $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

          $dados = json_decode( $dados );

          $fullDados[ $nPagina ] = $dados;

          $nPagina++;

    //      print_r( $dados );
    //      echo "<hr>";

          if ( empty( $dados->data ) ) {

      //      print_r( $dados );
      //      echo "<BR>";
      //      echo $ids[ 'id' ];
      //      echo "<hr>";
            $cond = false;
          }

          $contaSegundo++;
          if ( $contaSegundo == 3 ) {
            sleep( 1 );
            $contaSegundo = 1;
          }

        }


        foreach ( $fullDados as $dados ) {

          foreach ( $dados as $col ) {

            foreach ( $col as $lin ) {

              $cad = "CALL p_cad_depositos (
                                              :ID_CONTA_BLING,
                                              :ID_BLING,
                                              :DESCRICAO,
                                              :SITUACAO,
                                              :PADRAO,
                                              :DESCONSIDERARSALDO )";

                            $value = array(
                                            ":ID_CONTA_BLING" => $ids[ 'id' ],
                                            ":ID_BLING" => $lin->id,
                                            ":DESCRICAO" => $lin->descricao,
                                            ":SITUACAO" => $lin->situacao,
                                            ":PADRAO" => $lin->padrao,
                                            ":DESCONSIDERARSALDO" => $lin->desconsiderarSaldo );


              $sql->run( $cad, $value );

              $cont_dados++;

            }

          }

        }
      
        echo "<br>";
        echo "Conta " . $Class-> getConta($id_user[ 'id' ]);
        echo "<br>";
        echo "<br>";
        echo "Total cadastrado/atualziados: " . $cont_dados;
        echo "<br>";
        echo "<hr>";


      }
    }


    ?>
  </div>

