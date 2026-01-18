<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


?>

<!doctype html>
<html>
<head>
<?php require_once("conteudo_header.php");  ?>
<title>Ajuste Estoque</title>
</head>
<body>
<div id="header">
  <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2">
  <?php require_once("menu.php");   ?>
</div>
<div id="divContainer" class="col-10">
  <div id="" class="divBlocoGrande">
    <?php

    $res1 = $sql->select( "SELECT 
tb_contatos.id_bling as 'idcontado',
tb_produto_fornecedor.produto_id as 'idprod',
tb_produto_fornecedor.codigo as 'codid',
tb_produto_fornecedor.descricao as 'descricao',
tb_saldo_estoque.saldoFisico as 'estq'
FROM tb_produto_fornecedor
JOIN tb_contatos ON tb_contatos.id_bling = tb_produto_fornecedor.fornecedor_id
JOIN tb_saldo_estoque on tb_saldo_estoque.produto_id = tb_produto_fornecedor.produto_id
WHERE 
tb_saldo_estoque.saldoFisico >0 AND
tb_produto_fornecedor.codigo <> ''" );


    foreach ( $res1 as $k ) {
      foreach ( $k as $v ) {

        echo $v . "<br>";
      }
    }


    ?>
  </div>
</div>

<!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
<div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>