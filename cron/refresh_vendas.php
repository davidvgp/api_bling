<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$values1 = array();
$pedCarregado = 0;
$valorTotalPedidos = 0;


$contas = $Class->getContas( 1 ); // 1 nesse caso é o id_user_app

foreach ( $contas as $col ) {

    $accessToken = $Class->AccessToken($col[ 'id' ]);

    $idConta = $col[ 'id' ];


    $verif_cron = $sql->select( "SELECT `pedido` FROM `tb_cron_historico` WHERE pedido = 'S' AND id_conta = " . $idConta );

    if ( count( $verif_cron ) > 0 ) {

        echo "Tarefa Cron ativada";
        echo "<br>";

        
        $dt_ini = date( 'Y-m-d', strtotime( -4 . 'days' ) );
        $dt_fin = date( 'Y-m-d' );

        $dataInicial = "dataInicial=" . $dt_ini;
        $dataFinal = "dataFinal=" . $dt_fin;


        $filtro = "";
        $cond = true;
        $requisicao = "";
        $fullDados = array();

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "pedidos/vendas";
        $nPagina = 1; //numero páginas
        $limite = 100; // linhas por página     


        $sleep = 1;


        while ( $cond ) { //  LAÇO 3 : PERCORRE TODAS AS LINHAS DENTRO DO INTERVÁLO SOLCITADO NA BUSCA


            $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $dataInicial . "&" . $dataFinal;


            $pedidos = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

            //    print_r($pedidos);

            if ( empty( $pedidos->data ) ) { // verifica retorno vazio, encerra o LAÇO 3

                $cond = false;
            } else {

                $fullDados[ $nPagina ] = $pedidos; // ARMAZENA TODOS OS DADOS RETORNANA EM UM ARRAY, FORMANDO UM ARRAY DE 3 NÍVEIS

                $nPagina++;

            }

        } // FIM LAÇO 3


        foreach ( $fullDados as $dd ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3

            foreach ( $dd as $lins ) { // LAÇO 5 : PERCORRE O 2º NÍVEL DO ARRAY REENCHIDO NO LAÇO 3

                foreach ( $lins as $lin ) { // LAÇO 6 : PERCORRE O 3º NÍVEL DO ARRAY PREENCHIDO NO LAÇO 3


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

                    $pedCarregado++;
                    $valorTotalPedidos += $lin->total;

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

                } //  FIM LAÇO 6 : foreach ( $lins as $lin ) {

            } //  FIM LAÇO 5 : foreach ( $dd as $lins ) 

        }
        echo "<hr>";
        echo "<br>";
        echo "Pedidos Carregados/Atualizados<br>";
        echo "<br>"; 
        echo "Período: " . $dt_ini. " a " . $dt_fin;
        echo "<br>";
        echo "Conta " . $idConta . " : " . $pedCarregado;
        echo "<br>";
        echo "Valor Total: R$ " . number_format( $valorTotalPedidos, 2, ',', '.' );
        echo "<br>";
        echo "<br>";


    } else {

        echo "Tarefa Cron esta desativada";
    }
}

?>
</body>
</html>
