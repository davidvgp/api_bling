<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$ids_user_api = $Class->loadUserApi( 1 ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) {


  $contas = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

  $pedidosListados = 1;


  foreach ( $contas as $dados_conta ) {

    $accessToken = $dados_conta[ 'access_token' ];

    $idConta = $dados_conta[ 'id' ];


    $verif_cron = $sql->select( "SELECT `pedido` FROM `tb_cron_historico` WHERE pedido = 'S' AND id_conta = " . $idConta );

    $dataLimite = Date( 'Y-01-01', strtotime( -2 . 'year' ) );


    if ( count( $verif_cron ) > 0 ) {
      echo "<hr>";
      echo $Class->getConta( $idConta );
      echo "<br>";
      echo "Tarefa Cron Histórico Pedidos Itens ATIVADO";
      echo "<br>";


      //buscando no banco a data mais antiga com dados para continuar buscando arquivos até a data limite.  

      //$inicio_data = $sql->select( "SELECT MIN(dataPedido) as inicio_data FROM tb_pedidos WHERE id_conta_bling = " . $idConta . " LIMIT 1" );
      
        $inicio_data = $sql->select( "SELECT
                                        tb_vendas.numero,
                                        tb_vendas.dataVenda AS 'data'
                                    FROM
                                        `tb_vendas`
                                    WHERE
                                        id_conta_bling = " . $idConta . "
                                        AND numero < (
                                        SELECT
                                            MIN(numero)
                                        FROM
                                            tb_pedidos
                                        WHERE
                                            id_conta_bling = " . $idConta . "
                                        LIMIT 1
                                    ) LIMIT 20");
      
           
      
   //    $inicio_data = $sql->select( SELECT numero, dataPedido FROM `tb_pedidos` WHERE id_conta_bling = " . $idConta . " ORDER BY `numero` ASC LIMIT 20" );


      //print_r($inicio_data);
      //echo "<hr>";

      $dataBusca = "";


      if ( count( $inicio_data ) == 0 or $inicio_data[ 0 ][ "data" ] == 0 ) {

        $dataBusca = Date( 'Y-m-d' );

      } else {

        $dataBusca = Date( 'Y-m-d', strtotime( $inicio_data[ 0 ][ "data" ] ) );
}


      echo "Ultima data do Banco: " . $dataBusca;
      echo "<br>";
     
     
     echo "Data limite da busca " . $dataLimite;
      echo "<br>";





      if ( $dataBusca <= $dataLimite ) {

        $up = "UPDATE tb_cron_historico SET pedido = 'N' WHERE id_conta = " . $idConta;
        $sql->run( $up );


        $sql->run( "DELETE FROM `tb_vendas` WHERE  dataVenda < '" . $dataLimite . "' AND id_conta_bling = " . $idConta );
        echo "<Br>";
        echo "Base atualizda até: " . $dataLimite;
        echo "<Br>";

        $dataBusca = Date( 'Y-m-d' );


      } else {


        $sel_ultimo_pedido = "
       SELECT 
       tb_vendas.numero, 
       tb_vendas.id_bling 
       FROM `tb_vendas` 
       WHERE 
       tb_vendas.id_conta_bling = :ID_CONTA_BLING 
       AND tb_vendas.numero < (
       SELECT MIN(tb_pedidos.numero)
       FROM tb_pedidos 
       WHERE tb_pedidos.id_conta_bling = :ID_CONTA_BLING 
       LIMIT 1) 
       LIMIT 24";

        $where = array(
          ":ID_CONTA_BLING" => $idConta,
          ":DT_LIMIT" => $dataLimite );


        $sel_ultimo_pedido2 = "
        SELECT 
        tb_vendas.id_bling, 
        tb_vendas.numero 
        FROM  tb_vendas 
        WHERE tb_vendas.id_conta_bling = :ID_CONTA_BLING 
        ORDER BY tb_vendas.numero DESC LIMIT 24";

        //   $sqlNumPedido = "SELECT id_bling FROM tb_vendas WHERE id_conta_bling =:ID_CONTA_BLING AND dataVenda >=:DT_INI AND dataVenda <=:DT_FIM";

          
          
        $where2 = array( ":ID_CONTA_BLING" => $idConta );


        $resNumPeiddo = $sql->select( $sel_ultimo_pedido, $where );


        if ( count( $resNumPeiddo ) == 0 ) {

          $resNumPeiddo = $sql->select( $sel_ultimo_pedido2, $where2 );
        }


        //   print_r( $resNumPeiddo );
        //   echo "<br>";




       
      $PedidosDados = array();
          
        foreach ( $resNumPeiddo as $idPedido ) {

          $operadorApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
          $recurso     = "pedidos/vendas/" . $idPedido[ "id_bling" ];

          // echo "<Br>";
          $pedido = $Class->apiGET( $recurso, $operadorApi, $accessToken );

          $pedido = json_decode( $pedido );

     usleep(333334);

          if ( !isset( $pedido->error ) ) {

            $PedidosDados[] = $pedido;

          } else {
              
     $sql->run( "INSERT INTO tb_PedidoErro (idConta, idPedidoErro) VALUES (" . $idConta . ", " . $idPedido[ "id_bling" ] . ")" );


            switch ( $pedido->error->type ) {

              case "RESOURCE_NOT_FOUND":

               echo $pedido['error'][ 'message' ] ;
                echo "<hr>";
                echo $recurso;
                echo "<Br>";
                break;

            $sql->run( "DELETE FROM `tb_vendas` WHERE `id_bling` =" . $idPedido[ "id_bling" ] );
                
              echo "<Br>";
              case "TOO_MANY_REQUESTS":
                echo $pedido['error'][ 'message' ] ;
                echo "<hr>";
                echo $recurso;
                echo "<Br>";
                break;
            }

            // aguarda 1 segundo para executar novamente, ou seja continuar o laço  
            echo "Contador laço error 1 " . $sleep++;
            if ( $sleep == 3 ) {
              sleep( 3 );
              $sleep = 0;
            }


          }

        }


        foreach ( $PedidosDados as $pedido ) {

          foreach ( $pedido as $lin ) {

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

            $pedidosListados++;

            $sql->run( $inse, $value );

            //   print_r($lin->itens);


            $descPonderado = 0;
            $ponderado = 0;
            $desconto = 0;
            $frete_ponderado = 0;
            $frete_proporcional = 0;
            $taxa_ponderado = 0;
            $taxa_proporcional = 0;

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
        echo "<br>";
        echo "Histórico de pedido altualizado ";
        echo "<br>";
        echo "Pedidos listados " . $pedidosListados;
        echo "<br>";

        echo "<hr>";

      }

    } else {

      echo "Tarefa Cron histórico pedidos desativada";
      echo "<Br>";
      echo "<Br>";
      $sql->run( "DELETE FROM `tb_vendas` WHERE  dataVenda < " . $dataLimite . "AND id_conta = " . $idConta );
      echo "<Br>";
      echo "Base atualizda até: " . $dataLimite;
      echo "<Br>";


    }


  }

  $up = "UPDATE tb_cron_historico SET script = 'historico_pedidos_itens' WHERE id_conta = " . $idConta;
  $sql->run( $up );


}


?>