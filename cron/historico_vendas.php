<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$verif_cron = $sql->select( "SELECT `venda` FROM `tb_cron_historico` WHERE venda = 'S'" );


$dataLimite = Date( 'Y-01-01', strtotime( -2 . 'year' ) );


if ( count( $verif_cron ) > 0 ) {

  echo "Tarefa Cron histórico de venda ativada";
  echo "<br>";

$conta = $Class->getContas(1);

  //buscando no banco a data mais antigo com dados para continuar buscando arquivos até a data limite.  

$dados = $Class->getIdContaToken();

foreach ( $dados as $idConta => $token ) {   
    

      $inicio_data = $sql->select( "SELECT MIN(dataVenda) as inicio_data FROM tb_vendas where id_conta_bling = " . $idConta );

      //print_r($inicio_data);

      $dataBusca = "";

      if ( $inicio_data[ 0 ][ "inicio_data" ] == 0 || $inicio_data[ 0 ][ "inicio_data" ] === NULL ) {

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
        echo "<Br>";
        echo "Base atualizda até: " . $dataLimite;
        echo "<Br>";

        $dataBusca = Date( 'Y-m-d' );


      } else {

        $data_Inicial = Date( 'Y-m-d', strtotime( $dataBusca . '-15 days' ) );
        $data_Final = Date( 'Y-m-d', strtotime( $dataBusca ) );


        if ( $data_Inicial == $data_Final ) {

          // não faz nada. 


        } else {


          $dataInicial = "dataInicial=" . $data_Inicial;
          $dataFinal   = "dataFinal=" . $data_Final;


          $filtro = "";
          $cond   = true;
          $requisicao = "";
          $fullDados  = array();

          $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
          $recurso = "pedidos/vendas";
          $nPagina = 1; //numero páginas
          $limite  = 100; // linhas por página     

       
            
          while ( $cond ) { //  LAÇO 3 : PERCORRE TODAS AS LINHAS DENTRO DO INTERVÁLO SOLCITADO NA BUSCA


            $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $dataInicial . "&" . $dataFinal;

            $pedidos = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

            //  print_r($pedidos);

            if ( empty( $pedidos->data ) ) { // verifica retorno vazio, encerra o LAÇO 3


              $cond = false;

            } else {

              $fullDados[ $nPagina ] = $pedidos; // ARMAZENA TODOS OS DADOS RETORNANA EM UM ARRAY, FORMANDO UM ARRAY DE 3 NÍVEIS

              $nPagina++;


      usleep(333334);

            }

          } // FIM while 


          $values1 = 0;
          $pedCarregado = 0;
          $valorPedidos = 0;

          foreach ( $fullDados as $dd ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3


            foreach ( $dd as $lins ) { // LAÇO 5 : PERCORRE O 2º NÍVEL DO ARRAY REENCHIDO NO LAÇO 3

              $i = 0;
              foreach ( $lins as $lin ) { // LAÇO 6 : PERCORRE O 3º NÍVEL DO ARRAY PREENCHIDO NO LAÇO 3

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

              } //  FIM LAÇO 6 : foreach ( $lins as $lin ) {

            } //  FIM LAÇO 5 : foreach ( $dd as $lins ) 
        
        } // FIM LAÇO 1 : foreach ( $ids_user_api as $id_user ) 


        echo "<hr>";
        echo "<br>";
        echo "<strong>Pedidos Carregados/Atualizados</strong><br>";
        echo "<br>";
        echo "Conta " . $idConta . " : " . $pedCarregado;
        echo "<br>";
        echo "Valor Total: R$ " . number_format( $valorPedidos, 2, ',', '.' );
        echo "<br>";
        echo "<br>";

        echo "Período: " . $data_Inicial . " a " . $data_Final;
        echo "<br>";
        //    echo "intevaldo de ". $dataFinal - $dataInicial;

      }
    }

  } // FIM LAÇO 7 : foreach ( $token as $ids ) 

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

?>
