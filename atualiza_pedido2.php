<?php

require_once( 'config.php' );

$Class = new classMasterApi();
$sql = new Sql();

$ids_user_api = $Class->loadUserApi( 1 ); // carrega o id_user_api, pelo id_user_app
$pedidosListados = 0;
$pedidosAtualizados = 0;

/*VERICA SE FOI SELECIONADO ALGUMA DATA PARA PESQUIA**********/
if ( isset( $_POST[ 'dataInicial' ] ) ) {

  $dtInici = Date( $_POST[ 'dataInicial' ] );
  $dtFinal = Date( $_POST[ 'dataFinal' ] );

  /********** LAÇO QUE EXECUTA O CÓDIGO PARA TODA AS CONTAS BLING CADASTRADA DO CLINENTE BLING ************/
  foreach ( $ids_user_api as $id_user ) {

      
      
    /********** CARREGANDO OS DADOS DE ACESSO CONFORME CADA ID CLIENTE BLING ************************/
      
    $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

      
    /********** PERCORRENDO OS TOKEN ACCESS PARA ATUALIZAR A BASE COM INFOMAÇÕES DE CADA CONTA CLIENTE LBING ***************/
      
    foreach ( $token as $ids ) {

      $accessToken = $ids[ 'access_token' ];
        
        $ind  = 0;
        $ind1 = 0;
        $ind2 = 0;
        $ind3 = 0;
        $values = array();
        $itens = array();
        $itensUpdate = array(); 
        $itensApi = array();
        

/************ BUSCANDO OS PEDIDOS NO BD PARA BUSCAR OS ITENS NA BASE BLING E SALVAR OU ATUALIZAR NO BD *****************/
        
      $sqlNumPedido = "SELECT tb_pedidos_venda.id_bling, tb_pedidos_venda.data FROM tb_pedidos_venda WHERE 
      id_conta_bling=:IDCB AND 
      data >=:DTINI AND 
      data <=:DTFIN";
      $numPedParam  = array(":IDCB" => $ids[ 'id' ],
                            ":DTINI" => $dtInici,
                            ":DTFIN" => $dtFinal );   
  
   //     echo $sqlNumPedido ."<br>";
     //   print_r($numPedParam);
        
     $resNumPeiddo = $sql->select( $sqlNumPedido, $numPedParam);

        
      foreach ( $resNumPeiddo as $idPedido ) {

         
        $operadorApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "pedidos/vendas/" . $idPedido[ "id_bling" ];

        $pedido = $Class->apiGET( $recurso, $operadorApi, $accessToken );

        $pedido = json_decode( $pedido );

       $itensApi[$ind] = $pedido; 
       $ind++; 
          
      }
        
    //    print_r($pedido);
        echo "<hr>";
  
        
//************* PERCORRENTE O ARRAY PREENCHIDO COM OS DADOS IMPORTADOS DA API****************************///
  
    foreach($itensApi as $iApi){    
        
        foreach ( $iApi as $lin ) {

    $sqlverifica = "SELECT id_bling FROM tb_pedidos WHERE id_bling=:ID";
    $verifiParam = array (":ID" => $lin->id);

          $verifica = $sql->select( $sqlverifica, $verifiParam );

       
     if ( count( $verifica ) == 0 ) {


            $values[ $ind1 ] = array(
              ":ID_BLING" => $lin->id,
              ":NUMERO" => $lin->numero,
              ":NUMEROLOJA" => $lin->numeroLoja,
              ":DATA" => $lin->data,
              ":DATASAIDA" => $lin->dataSaida,
              ":TOTALPRODUTOS" => $lin->totalProdutos,
              ":TOTAL" => $lin->total,
              ":CONTATOID" => $lin->contato->id,
              ":SITUACAO" => $lin->situacao->id,
              ":LOJAID" => $lin->loja->id,
              ":OUTRASDESPESAS" => $lin->outrasDespesas,
              ":DESCONTOVALOR" => $lin->desconto->valor,
              ":CATEGORIAID" => $lin->categoria->id,
              ":NOTAFISCALID" => $lin->notaFiscal->id,
              ":VENDEDORID" => $lin->vendedor->id,
              ":INTERMEDIADORID" => $lin->vendedor->id,
              ":INTERMEDIADORNOMEUSUARIO" => $lin->intermediador->nomeUsuario,
              ":TAXASCOMISSAO" => $lin->taxas->taxaComissao,
              ":TAXASCUSTOFRETE" => $lin->taxas->custoFrete,
              ":TAXASVALORBASE" => $lin->taxas->valorBase );

            $ind1++;
            $pedidosListados++;
              
            foreach ( $lin->itens as $item ) {

              $itens[ $ind2 ] = array(
                ":A" => $lin->numero,
                ":B" => $item->id,
                ":C" => $item->codigo,
                ":D" => $item->unidade,
                ":E" => $item->quantidade,
                ":F" => $item->desconto,
                ":G" => $item->valor,
                ":H" => $item->descricao,
                ":I" => $item->produto->id );
              $ind2++;

            }
            
              
          } else {
            $itensUpdate[ $ind3 ] = array(
              ":DATASAIDA" => $lin->dataSaida,
              ":TOTALPRODUTOS" => $lin->totalProdutos,
              ":TOTAL" => $lin->total,
              ":CONTATOID" => $lin->contato->id,
              ":SITUACAO" => $lin->situacao->id,
              ":LOJAID" => $lin->loja->id,
              ":OUTRASDESPESAS" => $lin->outrasDespesas,
              ":DESCONTOVALOR" => $lin->desconto->valor,
              ":CATEGORIAID" => $lin->categoria->id,
              ":NOTAFISCALID" => $lin->notaFiscal->id,
              ":VENDEDORID" => $lin->vendedor->id,
              ":INTERMEDIADORID" => $lin->vendedor->id,
              ":INTERMEDIADORNOMEUSUARIO" => $lin->intermediador->nomeUsuario,
              ":TAXASCOMISSAO" => $lin->taxas->taxaComissao,
              ":TAXASCUSTOFRETE" => $lin->taxas->custoFrete,
              ":TAXASVALORBASE" => $lin->taxas->valorBase,
              ":ID_BLING" => $lin->id );

            $ind3++;
            $pedidosAtualizados++;

          }
    }
        
        
 if(count($values) > 0) {

    
        $insPedidos = 'INSERT INTO  tb_pedidos ( id_bling ,  numero ,  numeroLoja , data, dataSaida ,  totalProdutos ,  total ,  contato_id ,  situacao_id ,  loja_id ,  outrasDespesas ,  desconto_valor ,  categoria_id ,  notaFiscal_id , vendedor_id ,  intermediador_cnpj ,  intermediador_nomeUsuario ,  taxas_taxaComissao ,  taxas_custoFrete ,  taxas_valorBase )
    VALUES(
    :ID_BLING,
    :NUMERO,
    :NUMEROLOJA,
    :DATA,
    :DATASAIDA,
    :TOTALPRODUTOS,
    :TOTAL,
    :CONTATOID,
    :SITUACAO,
    :LOJAID,
    :OUTRASDESPESAS,
    :DESCONTOVALOR,
    :CATEGORIAID,
    :NOTAFISCALID,
    :VENDEDORID,
    :INTERMEDIADORID,
    :INTERMEDIADORNOMEUSUARIO,
    :TAXASCOMISSAO,
    :TAXASCUSTOFRETE,
    :TAXASVALORBASE)';
        
        
        $sql->run( $insPedidos, $values );
          
    
        $insItens = 'INSERT INTO tb_pedido_itens( numero_pedido, id_bling, codigo, unidade, quantidade, desconto, valor, descricao, produto_id) VALUES (:A,:B,:C,:D,:E,:F,:G,:H,:I)';
        $sql->run( $insItens, $itens );
   }
        
        
 if(count($itensUpdate) > 0) {
        $update = "UPDATE tb_pedidos SET              
                                  dataSaida=:DATASAIDA,
                                  totalProdutos=:TOTALPRODUTOS,
                                  total=:TOTAL,
                                  contato_id=:CONTATOID,
                                  situacao_id=:SITUACAO,
                                  loja_id=:LOJAID,
                                  outrasDespesas=:OUTRASDESPESAS,
                                  desconto_valor=:DESCONTOVALOR,
                                  categoria_id=:CATEGORIAID,
                                  notaFiscal_id=:NOTAFISCALID,
                                  vendedor_id=:VENDEDORID,
                                  intermediador_cnpj=:INTERMEDIADORID,
                                  intermediador_nomeUsuario=:INTERMEDIADORNOMEUSUARIO,
                                  taxas_taxaComissao=:TAXASCOMISSAO,
                                  taxas_custoFrete=:TAXASCUSTOFRETE,
                                  taxas_valorBase=:TAXASVALORBASE
                                  WHERE id_bling =:ID_BLING";

        $sql->run( $update, $itensUpdate );

 }
      }
    }
  }
}

echo "Pedidos listados " . $pedidosListados;
echo "<br>";
echo "Pedidos atualizadoss " . $pedidosAtualizados;
echo "<br>";
if ( isset( $_POST[ 'dataInicial' ] ) ) {

  echo "Período: " . $_POST[ 'dataInicial' ] . " a " . $_POST[ 'dataFinal' ];
}
/*
//$sql->run("INSERT INTO tb_Cron (script) VALUES ('refresh_pedidos')");
$timestamp = Date( 'Y-m-d H:i:s' );
$up = "UPDATE tb_Cron SET script = 'refresh_pedidos', time_refresh = '" . $timestamp . "' WHERE id_Cron = 1";
$sql->run( $up );
*/
?>
<h3>Atualizar base de ITENS do pedidos de venda</h3>
<form id="form_loing" action="atualiza_pedido2.php" method="POST" >
  <table width="300" border="0">
    <tbody>
      <tr>
        <td>Data inicial</td>
        <td><input type="date" name="dataInicial"  value="<?php echo Date('Y-m-d');?>"></td>
      </tr>
      <tr>
        <td>Data Final</td>
        <td><input type="date" name="dataFinal"    value="<?php echo Date('Y-m-d');?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Atualizar"></td>
      </tr>
    </tbody>
  </table>
</form>
