<?php
session_start();

require_once( 'config.php' );


if ( empty( $_POST ) ) {

    echo "Nenhum dado encontrado";
    exit;
}

function atualizaBaseFornecedor( $idConta, $idFornec ) {
    
    $Class = new classMasterApi();
    


    // traz o array associativo com os IDs e tokens access correspondentes 
    $dados = $Class->getIdContaToken();
    $token = $dados[$idConta];

  
    $sql = new Sql();
    
    $cond = true;
    $ttl_Listados = 0;
    $parameters = [];

    $operador = "GET";
    $recurso = "produtos/fornecedores";
    $nPagina = 0;
    $limite = 100;
    $filtro = "idFornecedor=" . $idFornec;

    while ( $cond ) {

        $requisicao = $recurso . "?pagina=" . $nPagina++ . "&limite=" . $limite . "&" . $filtro;

        usleep( 333334 );

        $dados = json_decode( $Class->apiGET( $requisicao, $operador, $token ) );

        if ( empty( $dados->data ) ) {

            $cond = false;

        } elseif ( !empty( $dados->error ) ) {

            echo "<br>";
            print_r( $dados );
            $cond = false;

        } else {


            foreach ( $dados as $row ) {

                foreach ( $row as $lin ) {

                    $parameters = [
                        ":ID_CONTA_BLING" => $idConta,
                        ":ID_BLING" => $lin->id,
                        ":DESCRICAO" => $lin->descricao,
                        ":CODIGO" => ltrim( $lin->codigo, '0' ),
                        ":PRECOCUSTO" => $lin->precoCusto,
                        ":PRECOCOMPRA" => $lin->precoCompra,
                        ":PADRAO" => $lin->padrao,
                        ":PRODUTO_ID" => $lin->produto->id,
                        ":FORNECEDOR_ID" => $lin->fornecedor->id
                    ];

                  $ttl_Listados++;
                    
                        $call = "CALL p_cad_prod_forn(
                        :ID_CONTA_BLING,
                        :ID_BLING,
                        :DESCRICAO,
                        :CODIGO,
                        :PRECOCUSTO,
                        :PRECOCOMPRA,
                        :PADRAO,
                        :PRODUTO_ID,
                        :FORNECEDOR_ID
                    )";

          $res = $sql->select( $call, $parameters );
            
       // print_r($res);
                    
                }
            }


        }
    }

    echo "<br><strong>" . $Class->getConta( $idConta ) . "</strong><br>";

    echo $ttl_Listados . " Itens importados do fornecedor<br>";

    $rtn = $Class->atualizaSaldoDeposito( $idConta, $idFornec, $token );

  //  print_r($rtn);
     
    foreach ( $rtn as $key => $val ) {

       echo $val;
   
    }
  
    
}



    function idFornecedorPorCnpj( $conta, $cnpj ) {

        $sql = new Sql();
        $sel = "SELECT id_bling as 'idFornec' FROM `tb_contatos` WHERE `id_conta_bling` IN(:conta) AND `cpf_cnpj` IN(:cnpj) LIMIT 1";
        $res = $sql->select( $sel, [ ':conta' => $conta, ':cnpj' => $cnpj ] );

        if ( count( $res ) > 0 ) {

            return $res[ 0 ][ 'idFornec' ];

        } else {

            return null;
        }
    }

    $idConta  = $_POST[ 'conta' ];
    $idFornec = $_POST[ 'fornec' ];

    if ( !is_array( $idConta ) ) {

        $idConta = [ $idConta ];
    }

    if ( !is_array( $idFornec ) ) {

        $idFornec = [ $idFornec ];
    }

    foreach ( $idConta as $conta ) {

        foreach ( $idFornec as $fornec ) {

            $idForn = idFornecedorPorCnpj( $conta, $fornec );

            if ( $idForn ) {

                atualizaBaseFornecedor( $conta, $idForn );

            } else {

                echo "Fornecedor não encontrado para CNPJ:" . $fornec . "<br>";
            }
        }
    }

?>
