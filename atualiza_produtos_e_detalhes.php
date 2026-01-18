<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


?>

<!doctype html>

<link rel="stylesheet" href="css/style.css" />

<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16">Podutos e detalhes</div> <hr> 
  <?php


  $ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


  foreach ( $ids_user_api as $id_user ) {


    $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


    foreach ( $token as $ids ) {

      $accessToken = $ids[ 'access_token' ];

      $requisicao = "";


      $fullDados = array();
      $cond = true;

      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
      $recurso = "produtos";
      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página
      $ttl_Listados = 1;

      $filtro = "criterio=2"; // 1 = ùltimo incluso, 2 = Ativo, 3 = inativo, 4 Excluído, 5 Todos
      $filtro .= "&";
      $filtro .= "tipo=T"; // T Todos, P Produtos, S Serviços, E Composições, PS Produtos simples, C Com variações, V Variações

      //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


      while ( $cond ) {

        $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

        usleep( 300000 );

        $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

        if ( isset( $dados->error ) ) {
          echo "Erro na requisição do produto <br>";
          print_r( $dados );

        } else {

          if ( empty( $dados->data ) ) {

            echo "Fim da requisão a API <br>";
            $cond = false;

          } else {

            $fullDados[ $nPagina ] = $dados;
            $nPagina++;

          }

        }
      }

      /********************* CADASTRO PRODUTO ********************************/

      foreach ( $fullDados as $dd ) {

        foreach ( $dd as $lins ) {

          foreach ( $lins as $li ) {

            $instr = "CALL p_produtos_e_detalhes ( 
              :ID_CONTA_BLING,
              :ID_BLING,
              :NOME,
              :CODIGO,
              :PRECO,
              :TIPO,
              :SITUACAO,
              :FORMATO,
              :IMAGEMURL

         )";

            $param = array(
              ":ID_CONTA_BLING" => $id_user[ 'id' ],
              ":ID_BLING" => $li->id,
              ":NOME" => $li->nome,
              ":CODIGO" => $li->codigo,
              ":PRECO" => $li->preco,
              ":TIPO" => $li->tipo,
              ":SITUACAO" => $li->situacao,
              ":FORMATO" => $li->formato,
              ":IMAGEMURL" => $li->imagemURL

            );

            $sql->run( $instr, $param );
            $ttl_Listados++;

          }
        }
      }

      echo "<hr>";
      echo "Base Produtos";
      echo "<Br>";
      echo "<Br>";
      echo "Conta " . $id_user[ 'id' ];

      echo "<Br>";
      echo "Total dados castrado/atualizados " . $ttl_Listados;
      echo "<Br>";

    }
  }

  ?>
</div>
