<?php
session_start();
require_once( "config.php" );
$Class = new classMasterApi();
$sql = new Sql();
$dados = $Class->getIdContaToken();






if ( $_POST[ 'func' ] == 'registroEsotque' ) {
    
     $idConta     = $_POST[ 'idConta' ];
     $contaDePara = $_POST[ 'contaDePara' ];
     $prodId      = $_POST[ 'idProd' ];
     $prodCodigo  = $_POST[ 'codProd' ];
     $operador    = $_POST[ 'operador' ];
     $DePara      = $_POST[ 'DePara' ];
     $Custo       = $_POST[ 'Custo' ];
     $qtdeAjuste  = $_POST[ 'qtdeAjuste' ];
     $idDeposito  = $_POST[ 'idDep' ];

    $token = $dados[$idConta];

$Class->alteraSaldoEsoque($idConta, $token, $idDeposito, $prodId, $prodCodigo, $qtdeAjuste, $Custo, $operador, $DePara, $contaDePara);
    
    
}
    
if ( $_POST[ 'func' ] == 'salvaPreco' ) {

  $idConta       = $_POST[ 'conta' ];
  $idAnuncio = $_POST[ 'idAnuncio' ] ?? "";
  $novoPreco     = $_POST[ 'preco' ];
   $idLoja        = $_POST[ 'loja' ] ?? "";


   $up = $sql->run( "UPDATE tb_produto_lojas SET novoPreco = '" .number_format($novoPreco, 3, ".", "" ). "' WHERE  id_conta = {$idConta} AND id_bling = {$idAnuncio}");

 
    $dd = $sql->select( "SELECT id_conta,id_bling,codigo,preco,precoPromocional,produto_id,loja_id,fornecedorLoja_id,marcaLoja_id,TRUNCATE(novoPreco,3) as novoPreco FROM tb_produto_lojas 
    WHERE id_bling = " . $idAnuncio );


    // $dd2 = $sql->select( "SELECT  `categoriasProdutos_id` as id FROM `tb_produto_categoria_lojas` WHERE `id_produto_loja` =" . $dd[ 0 ][ 'produto_id' ] );

    //   print_r($dd);
    
    $token = $dados[$idConta];
    
    if ( count( $dd ) > 0 ) {

        foreach ( $dd as $col ) {

            $codigo = $col[ 'codigo' ];
            $novoPreco = $dd[ 0 ][ 'novoPreco' ];
            $PrecoPromo = $col[ 'novoPreco' ];
            $idProduto = $col[ 'produto_id' ];
            $idLoja = $col[ 'loja_id' ];
    
       alteraProdutoLoja( $idAnuncio, $idConta, $codigo, $novoPreco, $PrecoPromo, $idProduto, $idLoja, $token  );

       $Class->getAtualizaUmAnuncio( $idConta, $idAnuncio, $token ); // atualiza a base com informações recem atualizadas no bling
        
        }


    } else {

        //  echo  vinculaProdutoLoja($idConta, $codigo, $novoPreco, $PrecoPromo, $idProduto, $idLoja );

    }
}

function alteraProdutoLoja( $idAnuncio, $idConta, $Codigo, $Preco, $PrecoPromo, $idProduto, $idLoja, $accessToken ) {

    $Class = new classMasterApi();
    $sql = new Sql();


    //echo "<br>";

    $requisicao = "";
    $operApi = "PUT"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "produtos/lojas/" . $idAnuncio;

    // $filtro  = "idFornecedor=" . $idForn[ 'id_bling' ];
    // $filtro  = "idFornecedor=16615253563";
    // $filtro  = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

    $requisicao = $recurso;

    $dadosBody = array(
        "codigo" => $Codigo,
        "preco" => $Preco,
        "precoPromocional" => $PrecoPromo,
        "produto" => array( "id" => $idProduto ),
        "loja" => array( "id" => $idLoja ) );


    /*        
      $dadosBody = array(
     "fornecedorLoja"     => array( "id"=> $dd[0]['fornecedorLoja_id']),        
      "marcaLoja"          => array( "id"=> $dd[0]['marcaLoja_id']),        
      "categoriasProdutos" => array( $dd2[0])
     );
     */

    // echo "<hr>";

    $dadosBody = json_encode( $dadosBody );

   //  print_r( json_decode( $dadosBody ) );

  //  usleep( 333334 );


    $resp = json_decode( $Class->RequestApi( $requisicao, $operApi, $accessToken, $dadosBody ) );


    if ( !empty( $resp->data ) ) {

        /* echo "Id Produto ". $idAnuncio."<br>";
           echo "Preço sincronizado com Bling ";*/

        echo "<span style='color:blue'>&#10004;</span>";

    } else {

        echo "<span style='color:red'>&#10007;</span>";

         //  print_r( $resp->error);    

    }


}

function vinculaProdutoLoja( $idAnuncio, $idConta, $Codigo, $novoPreco, $PrecoPromo, $idProduto, $idLoja ) {

    $Class = new classMasterApi();
    $sql = new Sql();

    $accessToken = $Class->AccessToken( $idConta );


    $operApi = "POST"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "produtos/lojas";

    // $filtro  = "idFornecedor=" . $idForn[ 'id_bling' ];
    // $filtro  = "idFornecedor=16615253563";
    // $filtro  = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


    $dadosBody = array(
        "id" => 0,
        "codigo" => $Codigo,
        "preco" => $novoPreco,
        "precoPromocional" => $PrecoPromo,
        "produto" => array( "id" => $idProduto ),
        "loja" => array( "id" => $idLoja ),
        "fornecedorLoja" => array( "id" => 0 ),
        "marcaLoja" => array( "id" => 0 ),
        "categoriasProdutos" => array( "id" => 0 )
    );

    // echo "<hr>";

    $dadosBody = json_encode( $dadosBody );

    //    print_r( json_decode( $dadosBody ) );

    usleep( 333334 );

    $resp = json_decode( $Class->RequestApi( $recurso, $operApi, $accessToken, $dadosBody ) );


    if ( !empty( $resp->data ) ) {

        /* echo "Id Produto ". $idAnuncio."<br>";
           echo "Preço sincronizado com Bling ";*/

        echo "&#10004;";

    } else {

        echo "( -.- )";

        echo "Id Produto " . $idAnuncio . "<br>";

        print_r( $resp->error );


    }


}



/***********************************************************************************************************************************/

if ( $_POST[ 'func' ] == 'salvaComissao' ) {

    $categ = $_POST[ 'post_categoria' ];
    $comis = $_POST[ 'post_comissao' ];
    $comis2 = $_POST[ 'post_comissao2' ];
    $conta = $_POST[ 'post_idconta' ];
    $idloja = $_POST[ 'post_idloja' ];


    $call = "CALL p_cad_comissao_prod_lojas (
:IDCONTA,
:IDLOJA,
:IDCATEG,
:CASSICO,
:PREMIUM,
:COMISSAO2
)";


    $param = array(
        ":IDCONTA" => $conta,
        ":IDLOJA" => $idloja,
        ":IDCATEG" => $categ,
        ":CASSICO" => $comis,
        ":PREMIUM" => $comis,
        ":COMISSAO2" => $comis2
    );


    $res = $sql->run( $call, $param );


    if ( $res ) {

         echo "Valor comissão da categoria atualizada com sucesso!";

        //   print_r( $res );

    } else {

        //    print_r( $res );
    }


}


if ( $_POST[ 'func' ] == 'salvaFrete' ) {

    $frete = $_POST[ 'post_frete' ];
    $idprod = $_POST[ 'post_idprod' ];
    $conta = $_POST[ 'post_idconta' ];
    $idloja = $_POST[ 'post_idloja' ];


    $up = $sql->run( "UPDATE `tb_produto_lojas` SET `frete` = " . number_format( $frete, 2, ".", "" ) . " WHERE  `id_conta` = " . $conta . " AND `id_bling` = " . $idprod );
    echo "<br>";
    echo "Valor de frete salvo";

}


if ( $_POST[ 'func' ] == 'ExcluiAnuncio' ) {


    $idsProdLojas = implode( ',', $_POST[ 'chkbxExc' ] );

    $sel = "DELETE FROM tb_produto_lojas WHERE id_bling IN (" . $idsProdLojas . ")";

    $del = $sql->run( $sel );

   // print_r( $del );

    if ( $del->rowCount() > 0 ) {
        echo 'produtos excluídos!';
    } else {
        echo 'nenhum produto foi excluído.';
    }


}


?>
