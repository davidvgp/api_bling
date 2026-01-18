<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$contas = $Class->getContas( 1 ); // 1 nesse caso é o id_user_app


//buscando no banco a data mais antigo com dados para continuar buscando arquivos até a data limite.  


$dataInicial = "";
$dataFinal = "";
$idConta = "";

// Laço 1 : REPETE O CÓDIGO PARA TODAS DAS CONTAS Bling

foreach ( $contas as $conta ) { //

    $idConta = $conta[ 'id' ]; 

    $accessToken = $Class->AccessToken( $idConta );
    

    $verif_cron = $sql->select( "SELECT `venda` FROM `tb_cron_historico` WHERE venda = 'S' AND id_conta = " . $idConta );


    $dataLimite = Date( 'Y-01-01', strtotime( -2 . 'year' ) );


    if ( count( $verif_cron ) > 0 ) {

        echo "Tarefa Cron HISTÓRICO de venda e pedidos ativada<br>";


        $inicio_data = $sql->select( "SELECT COALESCE(MIN(dataVenda),0) as inicio_data FROM tb_vendas where id_conta_bling = " . $idConta );

        //print_r($inicio_data);

        $dataBusca = "";

        if ( $inicio_data[ 0 ][ "inicio_data" ] == 0 ) {

            $dataBusca = Date( 'Y-m-d' );

        } else {

            $dataBusca = Date( 'Y-m-d', strtotime( $inicio_data[ 0 ][ "inicio_data" ] ) );

        }

        echo "<br>";
        echo "Ultima data do Banco: " . $dataBusca;
        echo "<br>";
        echo "Data limite da busca " . $dataLimite;
        echo "<br>";


        if ( $dataBusca <= $dataLimite ) {

            $up = "UPDATE tb_cron_historico SET venda = 'N' WHERE id = 1";
            $sql->run( $up );

            $cmd = "DELETE FROM `tb_vendas` WHERE  dataVenda < '" . $dataLimite . "'";
            $sql->run( $cmd );

            echo "<Br>Base atualizda até: " . $dataLimite . "<Br>";

            $dataBusca = Date( 'Y-m-d' );


        } else {

            $dataInicial = Date( 'Y-m-d', strtotime( $dataBusca . '-3 days' ) );
            $dataFinal   = Date( 'Y-m-d', strtotime( $dataBusca ) );


            if ( $dataInicial == $dataFinal ) {

                // não faz nada. 


            } else {


                $conta = $idConta;
                $idContato = '';
                $numero = '';
                $idLoja = '';
                $atualizaPedido = true; // (true/false)

                $param = array( 'idContato' => $idContato,
                    'numero' => $numero,
                    'dataInicial' => $dataInicial,
                    'dataFinal' => $dataFinal,
                    'numero' => $numero,
                    'idLoja' => $idLoja );


                if ( empty( $conta ) ) {

                    $ids_user_api = $Class->loadUserApi( 1 );

                    foreach ( $ids_user_api as $id_user ) {

                        echo $Class->atualizaVenda( $id_user[ 'id' ], $param, $atualizaPedido );

                    }

                } else {

                    echo $Class->atualizaVenda( $conta, $param, $atualizaPedido );

                }

            }

        }


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

?>
