<?php
session_start();
require_once( "config.php" );


if ( isset( $_POST[ 'lojas' ] ) ) {

    function atualizaBaseLojas( $idConta, $idLoja ) {

        $Class = new classMasterApi();

        $dados = $Class->getIdContaToken();
        
        $sql   = new Sql();

        $accessToken = $dados[$idConta];
      
        $requisicao = "";

        $fullDados = array();
        $cond = true;
        $ttl_Listados = 0;

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "produtos/lojas";
        $nPagina = 0; //numero páginas
        $limite = 100; // linhas por página

        $filtro = "idLoja=" . $idLoja;
   
        // $filtro = "idFornecedor=" . $idForn[ 'id_bling' ];
        // $filtro = "idFornecedor=16615253563";
        //$filtro  = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


        while ( $cond ) {

            usleep( 333334 );

            $requisicao = $recurso . "?pagina=" . $nPagina++ . "&limite=" . $limite . "&" . $filtro;

            $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );


            if ( $nPagina == 50 ) {
              
                $cond = false;
            }

            if ( count( $dados->data ) < 100 ) {

                $cond = false;
            }

            if ( isset( $dados->error ) or empty( $dados->data ) ) {

                //   print_r( $dados );
                //   echo "<hr>";

                $cond = false;

            } else {


                foreach ( $dados as $lins ) {

                    foreach ( $lins as $lin ) {

                        $call1 = "CALL p_cad_prod_lojas (
                                                :ID_CONTA,
                                                :ID_BLING,
                                                :CODIGO,
                                                :PRECO,
                                                :PRECOPROMOCIONAL,
                                                :PRODUTO_ID,
                                                :LOJA_ID,
                                                :FORNECEDORLOJA_ID,
                                                :MARCALOJA_ID)";

                        $param1 = array(
                            ":ID_CONTA" => $idConta,
                            ":ID_BLING" => $lin->id,
                            ":CODIGO" => $lin->codigo,
                            ":PRECO" => $lin->preco,
                            ":PRECOPROMOCIONAL" => $lin->precoPromocional,
                            ":PRODUTO_ID" => $lin->produto->id,
                            ":LOJA_ID" => $lin->loja->id,
                            ":FORNECEDORLOJA_ID" => $lin->fornecedorLoja->id,
                            ":MARCALOJA_ID" => $lin->marcaLoja->id );


                        //      print_r($param1)."<br>";

                        $res = $sql->run( $call1, $param1 );

                        $ttl_Listados++;

                        if ( is_array( $lin->categoriasProdutos ) ) {

                            foreach ( $lin->categoriasProdutos as $li2 ) {

                                $call = " call p_cad_catego_prod_lojas ( :ID_CONTA, :ID_PRODUTO_LOJA,:CATEGORIASPRODUTOS_ID )";
                                $param = array(
                                    ":ID_CONTA" => $idConta,
                                    ":ID_PRODUTO_LOJA" => $lin->produto->id,
                                    ":CATEGORIASPRODUTOS_ID" => $li2->id );

                                $sql->run( $call, $param );
                            }
                        }
                    }
                }
            }
        } // end while
     //   echo "<Br>";
    //    echo "<strong>" . $Class->getConta( $conta ) . "</strong>";
     //   echo "<Br>";
     //   echo "Páginas retornadas " . $nPagina;
        echo "<Br>";
        echo $ttl_Listados. " Anúncios atualizados";
       
    }


    $contas     = $_POST[ 'contas' ] ?? "";
    $lojasTipo   = $_POST[ 'lojas' ] ?? "";
    $idProdutos = $_POST[ 'produtos' ] ?? "";
    
    
  
    $Class = new classMasterApi();
    $sql   = new Sql();


    foreach ( $contas as $conta ) {

        foreach ( $lojasTipo as $lojaTipo ) {
            
            $resIdloja = $Class->idLojaTipo($conta, $lojaTipo);
            
            foreach( $resIdloja as $idLojas){
             
                atualizaBaseLojas( $conta, $idLojas );  
            
            echo "<Br>";
            echo "<strong>" . $Class->getConta( $conta ) . "</strong>";
            echo "<Br>";
            echo "<Br>";
         //   echo "<strong>" .$Class->getNomeLoja($lojaTipo) . "</strong>";
            
            }
                
        
        

        }

    }


}

?>
