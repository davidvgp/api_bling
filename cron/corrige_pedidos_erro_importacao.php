<?php

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$call = "CALL p_venda_pedidos_revisao()";


$res = $sql->select( $call );


foreach ( $res as $col ) {


    $res = $Class->atualizaPedido( $col[ 'idConta' ], $col[ 'idVendaPedido' ], '' );

    echo $res . "<br>";


}

?>