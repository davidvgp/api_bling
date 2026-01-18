

<?php
session_start();

require_once( "config.php" );
  $Class = new classMasterApi();
    $sql = new Sql();

if ( !empty( $_POST ) ) {

 echo "Conta:" .  $conta = $_POST[ 'conta' ];
    $dt_ini = $_POST[ 'dataInicial' ];
    $dt_fim = $_POST[ 'dataFinal' ];

    echo "<br>Base de Pedidos<br>";

    echo "<br>Entre " . $dt_ini . " e " . $dt_fim . "<br>";


    if ( $conta === 'T' ) {

        $conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

        foreach ( $conta as $col ) {

            busca_vendas( $col[ 'id' ], $dt_ini, $dt_fim );

        }

    } else {

        busca_vendas( $conta, $dt_ini, $dt_fim );
    }

}


function busca_vendas( $idConta, $dt_ini, $dt_fim ) {

    $Class = new classMasterApi();
    $sql = new Sql();

    $token = $Class->AccessToken( $idConta );


    $filtro = "";
    $cond = true;
    $requisicao = "";
    $fullDados = array();

    $operador_Api = "GET";
    $recurso = "pedidos/vendas";

    $nPagina = 1;

    while ( $cond ) {

    $requisicao = $recurso . "?pagina=".$nPagina."&limite=100&dataInicial=" . Date( $dt_ini ) . "&dataFinal=" . Date( $dt_fim );

        $pedidos = json_decode( $Class->apiGET( $requisicao, $operador_Api, $token ) );

        if ( empty( $pedidos->data ) ) {

            $cond = false;

        } else {

            $fullDados[ $nPagina ] = $pedidos;

            $nPagina++;

        }
        
        usleep(333334);

    }

    $values1 = array();
    $pedAtualizado[ $idConta ] = 0;
    $pedCarregado[ $idConta ] = 0;
    $valorTotalPedidos[ $idConta ] = 0;
    $valorPedidos = 0;
    $i = 0;

    foreach ( $fullDados as $dd ) {

//print_r($dd)."<Br>";
        
        foreach ( $dd as $lins ) {
      
            foreach ( $lins as $lin ) {


                $valorPedidos += $lin->total;
                $pedCarregado[ $idConta ] = $i++;
                $valorTotalPedidos[ $idConta ] = $valorPedidos;

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
                $param = array(

                                ":ID_CONTA_BLING" => $idConta,
                                ":ID_BLING" => $lin->id,
                                ":NUMERO" => $lin->numero,
                                ":NUMEROLOJA" => $lin->numeroLoja,
                                ":DATA" => $lin->data,
                                ":DATASAIDA" => $lin->dataSaida,
                                ":DATAPREVISTA" => $lin->dataPrevista,
                                ":TOTALPRODUTOS" => $lin->totalProdutos,
                                ":TOTAL"      => $lin->total,
                                ":CONTATO_ID" => $lin->contato->id,
                                ":CONTATO_NOME"       => addslashes( $lin->contato->nome ),
                                ":CONTATO_TIPOPESSOA" => $lin->contato->tipoPessoa,
                                ":CONTATO_NUMERODOCUMENTO" => $lin->contato->numeroDocumento,
                                ":SITUACAO_ID"    => $lin->situacao->id,
                                ":SITUACAO_VALOR" => $lin->situacao->valor,
                                ":LOJA_ID"        => $lin->loja->id );

            $res = $sql->select( $inse, $param );
                
  //  print_r($res);

            }
        }
    }


    echo "<br>";
    echo "Conta: " . $Class->getConta( $idConta );
    echo "<br>";
    echo "Pedidos: " . $pedCarregado[ $idConta ];
    echo "<br>";
    echo "Valor: R$ " . number_format( $valorTotalPedidos[ $idConta ], 2, ',', '.' );
    echo "<br>";


}


?>