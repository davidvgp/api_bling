<?php

require_once( "config.php" );


$Class = new classMasterApi();
$sql   = new Sql();


if ( !empty( $_POST[ 'func' ] ) ) {

  switch ( $_POST[ 'func' ] ) {


    case 'listDescFrete':
      {
        listDescFrete( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      }

    case 'listaComissao':
      {
        listaComissao( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      }
      case 'listaTxFixa1':
      {
        listaTxFixa1( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      } 
      case 'listaPedMin1':
      {
        listaPedMin1( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      }   
      
      case 'listaTxFixa2':
      {
        listaTxFixa2( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      } 
      case 'listaPedMin2':
      {
        listaPedMin2( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      } 
          
          
      case 'listaMargLucro':
      {
        listaMargLucro( trim( $_POST[ 'conta' ] ), trim( $_POST[ 'lojas' ] ) );
        break;
      }


    case 'excluiUmFrete':
      {
        excluiUmFrete($_POST[ 'idfrete' ]);
        break;
      }

    case 'aliqImpostoMes':
      {
        aliqImpostoMes( $_POST[ 'conta' ], $_POST[ 'month' ] );
        break;
      }
  }

}


function listDescFrete( $conta, $loja ) {


  $sql = new Sql();


  $res = $sql->select( "SELECT COALESCE(descFrete,0) as 'descFrete' FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'descFrete' ],2);


}


function listaComissao( $conta, $loja ) {


  $sql = new Sql();

  $res = $sql->select( "SELECT comissao FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'comissao' ],2);


}

function listaTxFixa1( $conta, $loja ) {


  $sql = new Sql();

  $res = $sql->select( "SELECT txFixa1 FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'txFixa1' ],2);


}

function listaPedMin1( $conta, $loja ) {

  $sql = new Sql();

  $res = $sql->select( "SELECT vPedMin1 FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'vPedMin1' ],2);

}

function listaTxFixa2( $conta, $loja ) {


  $sql = new Sql();

  $res = $sql->select( "SELECT txFixa2 FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'txFixa2' ],2);


}

function listaPedMin2( $conta, $loja ) {

  $sql = new Sql();

  $res = $sql->select( "SELECT vPedMin2 FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'vPedMin2' ],2);

}

function listaMargLucro( $conta, $loja ) {


  $sql = new Sql();

  $res = $sql->select( "SELECT COALESCE(margLucro,0) as 'MargLucro' FROM `tb_canais_de_venda` WHERE `id_conta_bling` = " . $conta . " AND `id_bling` = " . $loja );

  echo number_format($res[ 0 ][ 'MargLucro' ],2);


}


function aliqImpostoMes( $conta, $mes ) {


  $sql = new Sql();

 $seleciona = "SELECT TRUNCATE(aliq,2) as 'aliq' FROM tb_aliq_simples WHERE id_conta_bling = ".$conta." AND DATE_FORMAT(exercicio, '%Y-%m') = '" .$mes."'";
    
  $res = $sql->select( $seleciona );
    
  if(count($res) > 0)  {
      
   echo number_format($res[0]['aliq'],2);
      
      
  }
    echo "";
}



function excluiUmFrete( $idFrete ) {

  $sql = new Sql();

  $sql->run( "DELETE FROM `tb_custo_frete` WHERE `id` = " . $idFrete );


}

?>