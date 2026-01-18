<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$ids_user_api = $Class->loadUserApi( 1 ); // carrega o id_user_api, pelo id_user_app



$dataInicial = "";
$dataFinal = "";
$idConta = "";

// Laço 1 : REPETE O CÓDIGO PARA TODAS DAS CONTAS Bling
foreach ( $ids_user_api as $id_user ) {

  // carrega os tokens pelo id_user_api
  $token = $Class->carregaAccessToken( $id_user[ 'id' ] );

  //  Laço 2 : EXECUTA A CHAMADA A API
  foreach ( $token as $ids ) {


    $idConta = $ids[ 'id' ];
    $accessToken = $ids[ 'access_token' ];


    $verif_cron = $sql->select( "SELECT `venda` FROM `tb_cron_refresh` WHERE venda = 'S' AND id_conta = " . $idConta );


    if ( count( $verif_cron ) > 0 ) {

      echo "Tarefa Cron REFRESH de venda e pedidos ativada";
      echo "<br>";


      $dataInicial = "dataInicial=" . Date( 'Y-m-d', strtotime( -30 . 'days' ) );
      $dataFinal = "dataFinal=" . Date( 'Y-m-d' );

      $filtro = "";
      $cond = true;
      $requisicao = "";
      $fullDados = array();

      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
      $recurso = "pedidos/vendas";
      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página     

        $idsSituacoes = array(9,12);
        
       $filtro = http_build_query(array("idsSituacoes"=>$idsSituacoes));
        
        echo    $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $dataInicial . "&" . $dataFinal."&" . $filtro;
        
        echo "<Br>";
        
      while ( $cond ) { //  LAÇO 3 : PERCORRE TODAS AS LINHAS DENTRO DO INTERVÁLO SOLCITADO NA BUSCA


    echo    $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $dataInicial . "&" . $dataFinal."&" . $filtro;
    echo "<Br>";
          
          
        $pedidos = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

        usleep( 55000 );


        if ( empty( $pedidos->data ) ) {

          $cond = false;

        } else {

          $fullDados[ $nPagina ] = $pedidos; // ARMAZENA TODOS OS DADOS RETORNANA EM UM ARRAY, FORMANDO UM ARRAY DE 3 NÍVEIS

          $nPagina++;

        }

      } // FIM while 


      $values1 = 0;
      $pedCarregado = 0;
      $valorPedidos = 0;

      $sleep = 1;
      foreach ( $fullDados as $Dados ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3
        foreach ( $Dados as $lins ) {    // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3
          foreach ( $lins as $lin ) {    // LAÇO 6 : PERCORRE O 3º NÍVEL DO ARRAY PREENCHIDO NO LAÇO 3


            $pedCarregado++;
            $valorPedidos += $lin->total;


            $values1 = array(

              ":ID_CONTA_BLING" => $idConta,
              ":ID_BLING" => $lin->id,
              ":NUMERO" => $lin->numero,
              ":NUMEROLOJA" => $lin->numeroLoja,
              ":DATA" => $lin->data,
              ":DATASAIDA" => $lin->dataSaida,
              ":DATAPREVISTA" => $lin->dataPrevista,
              ":TOTALPRODUTOS" => $lin->totalProdutos,
              ":TOTAL" => $lin->total,
              ":CONTATO_ID" => $lin->contato->id,
              ":CONTATO_NOME" => addslashes( $lin->contato->nome ),
              ":CONTATO_TIPOPESSOA" => $lin->contato->tipoPessoa,
              ":CONTATO_NUMERODOCUMENTO" => $lin->contato->numeroDocumento,
              ":SITUACAO_ID" => $lin->situacao->id,
              ":SITUACAO_VALOR" => $lin->situacao->valor,
              ":LOJA_ID" => $lin->loja->id );


            $inse = "CALL p_cad_vendas (
                                    :ID_CONTA_BLING,
                                    :ID_BLING,
                                    :NUMERO,
                                    :NUMEROLOJA,
                                    :DATA,
                                    :DATASAIDA,
                                    :DATAPREVISTA,
                                    :TOTALPRODUTOS,
                                    :TOTAL,
                                    :CONTATO_ID,
                                    :CONTATO_NOME,
                                    :CONTATO_TIPOPESSOA,
                                    :CONTATO_NUMERODOCUMENTO,
                                    :SITUACAO_ID,
                                    :SITUACAO_VALOR,
                                    :LOJA_ID)";


            $sql->run( $inse, $values1 );

            /*********  cadastrastrando pedidos detalhes ***************************************************************/

/*
            $operadorApi = "GET";
            $recurso = "pedidos/vendas/" . $lin->id;
              
            $pedido = $Class->apiGET( $recurso, $operadorApi, $accessToken );

            usleep( 50000 );

            $pedido = json_decode( $pedido );

            if ( isset( $pedido->error ) ) {

              $sql->run( "INSERT INTO tb_PedidoErro (idConta, idPedidoErro) VALUES (" . $idConta . ", " . $lin->id . ")" );

              switch ( $pedido->error->type ) {

                case "RESOURCE_NOT_FOUND":

                  echo $pedido->error->message;
                  echo "<hr>";
                  echo $recurso;
                  echo "<Br>";
                  break;

                  $sql->run( "DELETE FROM `tb_vendas` WHERE `id_bling` =" . $lin->id );

                  echo "<Br>";
                case "TOO_MANY_REQUESTS":


                  echo $pedido->error->message;
                  echo "<hr>";
                  echo $recurso;
                  echo "<Br>";
                  break;
              }
              sleep( 2 );


            } else { ///   if ( isset( $pedido->error ) ) {


              foreach ( $pedido as $detalhes => $lin ) {

                $inse = "CALL p_cad_pedidos (   :ID_CONTA_BLING,
                                    :ID_BLING,
                                    :NUMERO,
                                    :NUMEROLOJA,
                                    :DATAPEDIDO,
                                    :TOTALPRODUTOS,
                                    :OUTRASDESPESAS,
                                    :DESCONTO_VALOR,
                                    :CATEGORIA_ID,
                                    :NOTAFISCAL_ID,
                                    :VENDEDOR_ID,
                                    :INTERMEDIADOR_CNPJ,
                                    :INTERMEDIADOR_NOMEUSUARIO,
                                    :TAXAS_TAXACOMISSAO,
                                    :TAXAS_CUSTOFRETE,
                                    :TAXAS_VALORBASE

                                   )";


                $value = array(

                  ":ID_CONTA_BLING" => $idConta,
                  ":ID_BLING" => $lin->id,
                  ":NUMERO" => $lin->numero,
                  ":NUMEROLOJA" => $lin->numeroLoja,
                  ":DATAPEDIDO" => $lin->data,
                  ":TOTALPRODUTOS" => $lin->totalProdutos,
                  ":OUTRASDESPESAS" => $lin->outrasDespesas,
                  ":DESCONTO_VALOR" => $lin->desconto->valor,
                  ":CATEGORIA_ID" => $lin->categoria->id,
                  ":NOTAFISCAL_ID" => $lin->notaFiscal->id,
                  ":VENDEDOR_ID" => $lin->vendedor->id,
                  ":INTERMEDIADOR_CNPJ" => $lin->intermediador->cnpj,
                  ":INTERMEDIADOR_NOMEUSUARIO" => $lin->intermediador->nomeUsuario,
                  ":TAXAS_TAXACOMISSAO" => $lin->taxas->taxaComissao,
                  ":TAXAS_CUSTOFRETE" => $lin->taxas->custoFrete,
                  ":TAXAS_VALORBASE" => $lin->taxas->valorBase );


                $sql->run( $inse, $value );

                //   print_r($lin->itens);


                $descPonderado = 0;
                $ponderado = 0;
                $desconto = 0;
                $frete_ponderado = 0;
                $frete_proporcional = 0;
                $taxa_ponderado = 0;
                $taxa_proporcional = 0;
                $sleep = 0;

                foreach ( $lin->itens as $item ) {


                  if ( $lin->desconto->valor > 0 ) {

                    $ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

                    $desconto = $ponderado * $lin->desconto->valor;

                  }

                  if ( $item->desconto > 0 ) {

                    $descPonderado = $item->desconto;
                  } else {

                    $descPonderado = $desconto;

                  }


                  if ( $lin->taxas->custoFrete > 0 ) {

                    $frete_ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

                    $frete_proporcional = $frete_ponderado * $lin->taxas->custoFrete;

                  }

                  if ( $lin->taxas->taxaComissao > 0 ) {

                    $taxa_ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

                    $taxa_proporcional = $taxa_ponderado * $lin->taxas->taxaComissao;

                  }


                  $itens = array(
                    ":ID_CONTA_BLING" => $idConta,
                    ":ID_PEDIDO_BLING" => $lin->id,
                    ":NUMERO_PEDIDO" => $lin->numero,
                    ":ID_BLING" => $item->id,
                    ":CODIGO" => $item->codigo,
                    ":UNIDADE" => $item->unidade,
                    ":QUANTIDADE" => $item->quantidade,
                    ":DESCONTO" => $descPonderado,
                    ":VALOR" => $item->valor,
                    ":TAXA_PROP" => $taxa_proporcional,
                    ":FRETE_PROP" => $frete_proporcional,
                    ":DESCRICAO" => $item->descricao,
                    ":PRODUTO_ID" => $item->produto->id
                  );


                  //      print_r($itens);
                  //     echo "<hr>";

                  $insItens = "CALL p_cad_pedidos_itens (

            :ID_CONTA_BLING,
            :ID_PEDIDO_BLING,
            :NUMERO_PEDIDO,
            :ID_BLING,
            :CODIGO,
            :UNIDADE,
            :QUANTIDADE,
            :DESCONTO,
            :VALOR,
            :TAXA_PROP,
            :FRETE_PROP,
            :DESCRICAO,
            :PRODUTO_ID )";

                  $sql->run( $insItens, $itens );

                }

              }

            }

*/
            /************ fim cadastro pedido detalhes *****************************************************************/


          } //  FIM LAÇO 6 : foreach ( $lins as $lin ) {

        }

      } //  FIM LAÇO 4 : foreach ( $fullDados as $dd ) 


      echo "<hr>";
      echo "<br>";
      echo "<strong>Pedidos Carregados/Atualizados</strong><br>";
      echo "<br>";
      echo "Conta " . $idConta . " : " . $pedCarregado;
      echo "<br>";
      echo "Valor Total: R$ " . number_format( $valorPedidos, 2, ',', '.' );
      echo "<br>";
      echo "<br>";

      echo "Período: " . $dataInicial . " a " . $dataFinal;
      echo "<br>";
      //    echo "intevaldo de ". $dataFinal - $dataInicial;


      $up = "UPDATE tb_cron_historico SET script = 'historico_pedidos_itens' WHERE id = 1";
      $sql->run( $up );


    } else {

      echo "Tarefa Cron histórico de venda esta desativada";
      echo "<Br>";
      $cmd = "DELETE FROM `tb_vendas` WHERE  dataVenda < '" . $dataLimite . "'";
      $sql->run( $cmd );


      echo "<Br>";
      echo "Base atualizda até: " . $dataLimite;
      echo "<Br>";
    }
  }
}
?>
