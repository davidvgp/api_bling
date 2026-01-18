<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql   = new Sql();

$contas = $Class->getContas(1);  // 1 nesse caso é o id_user_app


//buscando no banco a data mais antigo com dados para continuar buscando arquivos até a data limite.  

$dataInicial = "";
$dataFinal = "";
$idConta = "";

// Laço 1 : REPETE O CÓDIGO PARA TODAS DAS CONTAS Bling

    foreach ( $contas as $conta ) {  //

        $idConta = $conta[ 'id' ];
        
        $accessToken = $Class->AccessToken($conta[ 'id' ]);

        $verif_cron = $sql->select( "SELECT `venda` FROM `tb_cron_refresh` WHERE venda = 'S' AND id_conta = " . $idConta );


        if ( count( $verif_cron ) > 0 ) {

            echo "Tarefa Cron REFRESH de venda e pedidos ativada";
            echo "<br>";

            $conta = $idConta;
            $idContato = '';
            $dataInicial = Date( 'Y-m-d', strtotime( -1 . 'days' ) );
            $dataFinal =  Date( 'Y-m-d' );
            $numero = '';
            $idLoja = '';
            $atualizaPedido = true;

         $param = array( 'idContato' => $idContato,
                '$numero' => $numero,
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'numero' => $numero,
                'idLoja' => $idLoja );
            
            
        echo $Class->atualizaVenda( $conta, $param, $atualizaPedido );
           
            echo "<Br>";
            
            

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
