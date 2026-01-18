<?php
require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$conta  = $_POST[ 'conta' ];
$fornec = $_POST[ 'cnpjFornec' ];
$idProd = $_POST[ 'idProd' ];
$qtde   = $_POST[ 'qtde' ];


$sel= "SELECT 
tb_produto_fornecedor.codigo as   'codigo',
tb_produto_fornecedor.precoCusto as 'custo'
FROM tb_produto_fornecedor
JOIN tb_contatos ON tb_contatos.id_bling = tb_produto_fornecedor.fornecedor_id
WHERE
tb_produto_fornecedor.produto_id IN ('" . $idProd . "')";


$res = $sql->select($sel);
$codigo = $res[ 0 ][ 'codigo' ];
$custo  = $res[ 0 ][ 'custo' ];


$call = "call p_cadPedidoCompra(:IDCONTA, :IDFORN, :IDPROD, :CODIGO, :QTDE, :CUSTO, :DATAPEDIDO)";

$dados = array(
    ":IDCONTA" => $conta,
    ":IDFORN" => $fornec,
    ":IDPROD" => $idProd,
    ":CODIGO" => $codigo,
    ":QTDE"   => $qtde,
    ":CUSTO" => $custo,
    ":DATAPEDIDO" => date( 'Y-m-d' ) );


$ins = $sql->run( $call, $dados );


if ( $ins ) {

    echo "Quantidade " . $qtde . " salva";
}

?>