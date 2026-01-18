<?php
session_start();

if ( empty( $_SESSION[ "idUsuario" ] ) ) {

//  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( '../config.php' );

$Class = new classMasterApi();
$sql = new Sql();


$contas = $Class->getContas( 1 ); // 1 nesse caso é o id_user_app


foreach ( $contas as $conta ) { //

    $idConta = $conta['id'];

    $accessToken = $Class->AccessToken( $idConta );

      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página


        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "estoques/saldos/";
        //$recurso = "contatos"; 


        //  $filtro = "idsProdutos=14887535425 "; // 0 = inativo, 1 = ativo

        $filtro = "";
        $fullDados = array();

        $requisicao = "";


        $deposito = $sql->select( "SELECT `id_bling` as id FROM `tb_depositos` WHERE `id_conta_bling` = " .$idConta . " AND `padrao` = 1" );

        $cont_dados =0;

        foreach ( $deposito as $dep ) {

          $dep[ 'id' ];


          $produtos = $sql->select( "call p_busca_prod_saldo (:ID_CONTA_BLING)", array( ":ID_CONTA_BLING" => $idConta ) );

     
         $id_prod = array();

          foreach ( $produtos as $codigos ) {

          foreach($codigos as $codigo => $cod) {   
              
          $id_prod[] = $cod ;
              
          }
            
          }
            
            
        $filtro = http_build_query(array('idsProdutos'=>$id_prod));


            // $filtro = http_build_query(array('idsProdutos'=>'11047387181'));


            $requisicao = $recurso . $dep[ 'id' ] . "?" . $filtro;


            $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

            usleep(333334);
            
            
            $dados = json_decode( $dados );

            //     echo "<Br>"; 
            //     print_r( $dados );

            if ( empty( $dados->data ) ) {

              print_r( $dados );
              echo "<BR>";
              echo $ids[ 'id' ];
              echo "<hr>";

            }


            foreach ( $dados as $col ) {

              foreach ( $col as $lin ) {


                $cad = "CALL p_cad_saldo_estoque (
                                                :ID_CONTA_BLING,
                                                :PRODUTO_ID,
                                                :DEPOSITOS_ID,
                                                :SALDOFISICO,
                                                :SALDOVIRTUAL

                                                 )";

                $value = array(
                  ":ID_CONTA_BLING" => $idConta,
                  ":PRODUTO_ID" => $lin->produto->id,
                  ":DEPOSITOS_ID" => $dep[ 'id' ],
                  ":SALDOFISICO" => $lin->saldoFisicoTotal,
                  ":SALDOVIRTUAL" => $lin->saldoVirtualTotal
                );

                $sql->run( $cad, $value );
                    
                  $cont_dados++;

              }

            }
         
         }
      

      echo "Base saldo de estoque atualizada";
      echo "<br>";
      echo "Conta: " . $idConta;
      echo "<br>";
      echo "Total cadastrado/atualziados: " . $cont_dados;
      echo "<br>";
      echo "<hr>";


    }

    ?>
