<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( 'config.php' );

$Class = new classMasterApi();
$sql = new Sql();


?>


  <div id="" class="divMiniBloco width_30p">
      <div class="div_titulos size20">Categoria Produto Lojas</div>
    <?php


    $Class = new classMasterApi();
    $sql = new Sql();

    $ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


    foreach ( $ids_user_api as $id_user ) {

      $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

      $ttl_Listados = 1;

      foreach ( $token as $ids ) {

        $accessToken = $ids[ 'access_token' ];


        $requisicao = "";


        $fullDados = array();
        $cond = true;

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "categorias/lojas";
        $nPagina = 1; //numero páginas
        $limite = 100; // linhas por página

              
        while ( $cond ) {

          //$requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;
          $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite;

           sleep(1); 
          $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

          if ( empty( $dados->data ) ) {

            if(!empty($dados->error)){
                
                print_r($dados->error);
                echo "<Br>";
            }
            $cond = false;
              

          } else {

            $fullDados[ $nPagina ] = $dados;
            $nPagina++;
          }

      }


        foreach ( $fullDados as $dd ) {

          foreach ( $dd as $lins ) {

            foreach ( $lins as $lin ) {

              if ( !empty( $lin->id ) ) {

                $instr = "CALL p_cad_categ_loja (
                                                :ID_CONTA_BLING,
                                                :ID_BLING,
                                                :LOJA_ID,
                                                :DESCRICAO,
                                                :CODIGO,
                                                :CATEGORIAPRODUTO_ID

                                                                    )";

                $dados = array(
                  ":ID_CONTA_BLING" => $id_user[ 'id' ],
                  ":ID_BLING" => $lin->id,
                  ":LOJA_ID" => $lin->loja->id,
                  ":DESCRICAO" => $lin->descricao,
                  ":CODIGO" => $lin->codigo,
                  ":CATEGORIAPRODUTO_ID" => $lin->categoriaProduto->id


                );

                $sql->run( $instr, $dados );
                $ttl_Listados++;

              }
            }
          }
        }
      }

      echo "<Br>";
      echo "Conta " . $Class->getConta( $id_user[ 'id' ] );
      echo "<Br>";
      echo "<Br>";
      echo "Numero de páginas ". $nPagina;
      echo "<Br>";
      echo "Total dados castrado/atualizados " . $ttl_Listados;
      echo "<Br>";


    }

    ?>
  </div>
</div>
