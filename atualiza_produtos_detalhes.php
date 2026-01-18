<?php
session_start();
require_once("session.php"); 

require_once( 'config.php' );

$Class = new classMasterApi();
$sql   = new Sql();
$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

$IdContaToken = $Class->getIdContaToken();



?>

  <div id="" class="divMiniBloco width_50p">
      <div class="div_titulos size20">Produtos detalhes</div>      
    <?php

foreach($IdContaToken as $idConta => $token){

    
        $requisicao = "";
   echo    $idProduto = "CALL p_busca_prod_detalhe (".$idConta.")"; // retorna o 'id_bling' do produto;
        $idProduto = $sql->select( $idProduto );
      
        echo "<hr>";
        $ttl_Listados = 0;
        $nPagina = 0;

        $fullDados = array();
          
  
        foreach ( $idProduto as $PROD ) {

          $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
          $recurso = "produtos/" . $PROD[ "idProd" ];
             
          $requisicao = $recurso;
          $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

          $nPagina++;

          usleep(333334);

          if ( isset( $dados->error ) ) {
            echo "Erro na requisição do produto <br>";
            print_r( $dados );

          } else {

            if ( !empty( $dados->data ) ) {

              foreach ( $dados as $lin ) {

            //    if ( !empty( $lin->id ) ) {

                  $call = "CALL p_cad_produtos_detalhes (
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
                  $PesoCubico = !empty($lin->pesoBruto) ? $lin->pesoBruto : 1;
                  $Cubagem = 0;

                  if ( $MedicaCubica > $PesoCubico ) {
                    $Cubagem = number_format($MedicaCubica,2);
                  } else {
                    $Cubagem = number_format($PesoCubico,2);
                  }
                  echo $lin->codigo." - ".$lin->nome. " cubatem: ".$Cubagem;
                  echo"<br>";
                  
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

                
                  $sql->run( $call, $detalhes );
                  $ttl_Listados++;
            //    }
              }
            }
          }
        }
        echo "<Br>";
        echo "Numero de paginas " . $nPagina;
        echo "<Br>";
        echo "Base Produtos";
        echo "<Br>";
        echo "<Br>";
        echo "Conta " . $idConta;
        echo "<Br>";
        echo "<Br>";
        echo "Total dados castrado/atualizados " . $ttl_Listados;
        echo "<Br>";
  
    }

    ?>
  </div>
</div>
