<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

    // header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$contas = $Class->getContas( 1 ); // 1 nesse caso é o id_user_app


foreach ( $contas as $conta ) { //

    $idConta = $conta['id'];

    $accessToken = $Class->AccessToken( $idConta );


    $requisicao = "";

    $idProduto = "CALL p_busca_prod_detalhe (" . $idConta . ")"; // retorna o 'id_bling' do produto;

    $idProduto = $sql->select( $idProduto );

    $ttl_Listados = 0;
    $nPagina = 0;
    $fullDados = array();


    foreach ( $idProduto as $PROD ) {

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "produtos/" . $PROD[ "idProd" ];
        $dados = json_decode( $Class->apiGET( $recurso, $operApi, $accessToken ) );

        $nPagina++;

        usleep( 333334 );

        if ( isset( $dados->error ) ) {
            echo "Erro na requisição do produto <br>";
            print_r( $dados );

        } else {

            if ( !empty( $dados->data ) ) { // se não estiver vazia

                foreach ( $dados as $lin ) {

                    $instr = "CALL p_cad_produtos_detalhes (
                                                          :ID_CONTA_BLING,
                                                          :ID_BLING_PRODUTO,
                                                          :ID_BLING,
                                                          :NOME,
                                                          :CODIGO,
                                                          :PRECO,
                                                          :TIPO,
                                                          :SITUACAO,
                                                          :FORMATO,
                                                          :DATAVALIDADE,
                                                          :UNIDADE,
                                                          :PESOLIQUIDO,
                                                          :PESOBRUTO,
                                                          :VOLUMES,
                                                          :ITENSPORCAIXA,
                                                          :GTIN,
                                                          :GTINEMBALAGEM,
                                                          :MARCA,
                                                          :CATEGORIA_ID,
                                                          :DIMENSOES_LARGURA,
                                                          :DIMENSOES_ALTURA,
                                                          :DIMENSOES_PROFUNDIDADE,
                                                          :CUBAGEM,
                                                          :DIMENSOES_UNIDADEMEDIDA
                                                        )";


                    $MedicaCubica = ( $lin->dimensoes->largura * $lin->dimensoes->altura * $lin->dimensoes->profundidade / 6000 );
                    $PesoCubico = $lin->pesoBruto;
                    $Cubagem = 0;

                    if ( $MedicaCubica > $PesoCubico ) {
                        $Cubagem = round( $MedicaCubica, 1 );
                    } else {
                        $Cubagem = round( $PesoCubico, 2 );
                    }


                    $detalhes = array(
                        ":ID_CONTA_BLING" => $idConta,
                        ":ID_BLING_PRODUTO" => $PROD[ "idProd" ],
                        ":ID_BLING" => $lin->id,
                        ":NOME" => $lin->nome,
                        ":CODIGO" => $lin->codigo,
                        ":PRECO" => $lin->preco,
                        ":TIPO" => $lin->tipo,
                        ":SITUACAO" => $lin->situacao,
                        ":FORMATO" => $lin->formato,
                        ":DATAVALIDADE" => $lin->dataValidade,
                        ":UNIDADE" => $lin->unidade,
                        ":PESOLIQUIDO" => $lin->pesoLiquido,
                        ":PESOBRUTO" => $lin->pesoBruto,
                        ":VOLUMES" => $lin->volumes,
                        ":ITENSPORCAIXA" => $lin->itensPorCaixa,
                        ":GTIN" => $lin->gtin,
                        ":GTINEMBALAGEM" => $lin->gtinEmbalagem,
                        ":MARCA" => $lin->marca,
                        ":CATEGORIA_ID" => $lin->categoria->id,
                        ":DIMENSOES_LARGURA" => $lin->dimensoes->largura,
                        ":DIMENSOES_ALTURA" => $lin->dimensoes->altura,
                        ":DIMENSOES_PROFUNDIDADE" => $lin->dimensoes->profundidade,
                        ":CUBAGEM" => $Cubagem,
                        ":DIMENSOES_UNIDADEMEDIDA" => $lin->dimensoes->unidadeMedida


                    );

                    $sql->run( $instr, $detalhes );
                    $ttl_Listados++;
                }
            }
        }
    }

    echo "<Br>";
    echo "Base Produtos detalhes";
    echo "<Br>";
    echo "<Br>";
    echo "Conta " . $idConta;
    echo "<Br>";
    echo "<Br>";
    echo "Total dados castrado/atualizados " . $ttl_Listados;
    echo "<Br>";


}


?>
