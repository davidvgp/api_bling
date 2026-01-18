<?php
session_start();

class classMasterApi {

 public function __construct() {
  // Chama a função que você deseja executar automaticamente
  if ( isset( $_SESSION[ "idUsuario" ] ) && !empty( $_SESSION[ "idUsuario" ] ) ) {
   $this->setIdContaToken( $_SESSION[ "idUsuario" ] );
  }
 }

 //Usuário do APP, que por sua vêz pode conter mais de uma conta bling.
 private $idUserApp;
 // Nome do usuários que aparecerá na página quanto ele estiver logado. 
 private $NomeUserApp;
 // ids das contas que o UserApp possui no Bling
 private $idConta;
 // id de uma conta que o UserApp possui no Bling 
 private $idsContas;

 // nome da contas que o usário atribiu para contas Bling
 private $nomeContas;

 // array que vai armazenar os ids da constas api e os respectivos token de access
 private $idContaToken = array();

 // variáveis relacionadas a autenticação com api bling
 private $code; //
 private $state;
 private $AcessToken;
 private $refresh_token;
 private $cliente_id;
 private $cliente_secret;


 public function getIdUserApp() {
  return $this->idUserApp;
 }

 public function setIdUserApp( $value ) {
  $this->idUserApp = $value;
  $this->getDadosUserApp( $value );
 }

 public function getNomeUserApp() {

  return $this->NomeUserApp;
 }

 public function setNomeUserApp( $value ) {

  $this->getDadosUserApp( $value );

 }

 public function getCode() {
  return $this->code;
 }

 public function setCode( $value ) {
  $this->code = $value;
 }

 public function getState() {
  return $this->state;
 }

 public function setState( $value ) {
  $this->state = $value;
 }

 public function getAccess_Token() {
  return $this->AcessToken;
 }
 public function setAccess_Token( $value ) {
  $this->AcessToken = $value;
 }

 public function get_IdConta() {
  return $this->idConta;
 }
 public function set_IdConta( $value ) {
  $this->idConta = $value;
 }
 /*
     public function get_IdsContas() {
         return $this->idsContas;
     }

     public function set_IdsContas( $value ) {
         $this->idsContas = $value;
     }
 */
 public function getCliente_Id() {
  return $this->cliente_id;
 }

 public function setCliente_Id( $value ) {
  $this->cliente_id = $value;
 }

 public function getCliente_Secret() {
  return $this->cliente_secret;
 }

 public function setCliente_Secret( $value ) {
  $this->cliente_secret = $value;
 }

 public function getRefresh_token() {
  return $this->refresh_token;
 }

 public function setRefresh_token( $value ) {
  $this->refresh_token = $value;
 }

 // traz os token em array associativos com a chave idConta
 public function getIdContaToken() {

  // Array ( [1] => 03e8950e6288901ecf4ed3fdffb60d461992f54e [2] => 911be36ac2cd326fcc34a1f1551ad7ddb5b00a3c )
  return $this->idContaToken;
 }


 public function setIdContaToken( $idUsuario ) {

  $sql = new Sql();
  $res = $sql->select( "SELECT 
        tb_token_access.id_conta as id,
        tb_token_access.access_token as token
        FROM tb_token_access 
        JOIN tb_user_api on tb_user_api.id = tb_token_access.id_conta
        WHERE tb_user_api.id_user_app= " . $idUsuario );

  // Variável para armazenar o mapeamento de IDs para tokens
  $idTokenMap = array();

  // Preencher o array associativo com os IDs e tokens correspondentes
  // dados{[conta1]=>'token1'; [conta2]='tokne2')
  foreach ( $res as $dd ) {

   $idTokenMap[ $dd[ 'id' ] ] = $dd[ 'token' ];
  }

  $this->idContaToken = $idTokenMap;

 }


 public function SalvaCodeAutorizacao( $idconta, $code, $state ) {

  $sql = new Sql();

  $this->setCode( $code );
  $this->setState( $state );

  $stmt = $sql->run( "call p_set_autorizacao_token (:IDCONTA, :CODE, :STATE)", array(
   ":IDCONTA" => $idconta,
   ":CODE" => $this->getCode(),
   ":STATE" => $this->getState(),

  ) );

 }

 public function upAccessToken( $idconta, $access_token, $expires_in, $token_type, $scope, $refresh_token ) {
  $sql = new Sql();
  $stmt = $sql->run( "call p_set_token_access (
        :ID_CONTA,
        :ACCESS_TOKEN,
        :EXPIRES_IN,
        :TOKEN_TYPE,
        :SCOPE,
        :REFRESH_TOKEN        
        )", array(

   ":ID_CONTA" => $idconta,
   ":ACCESS_TOKEN" => $access_token,
   ":EXPIRES_IN" => $expires_in,
   ":TOKEN_TYPE" => $token_type,
   ":SCOPE" => $scope,
   ":REFRESH_TOKEN" => $refresh_token

  ) );


  if ( $stmt ) {
   return true;
  } else {
   return false;
  }
 }

 public function variacaoCusto( $idconta, $idprod ) {
  $sql = new Sql();
  $sel = "SELECT COALESCE(custo,1) as 'custo' FROM tb_historico_custo WHERE id_conta = " . $idconta . " AND id_produto = " . $idprod . " ORDER BY times_tamp DESC LIMIT 2";
  $custoAntes = 0;
  $custoAtual = 0;
  $res = $sql->select( $sel );

  if ( count( $res ) > 1 && $res[ 1 ][ 'custo' ] > 0 ) {
   //   echo  $sel;
   $custoAntes = $res[ 0 ][ 'custo' ];
   $custoAtual = $res[ 1 ][ 'custo' ] ?? $res[ 0 ][ 'custo' ];
   $vc = ( $custoAntes - $custoAtual ) / $custoAtual * 100;
   return $vc;
  } else {
   return 0;
  }
 }

 public function aliqImposto( $idconta ) {
  $sql = new Sql();
  $res = $sql->select( "select tb_aliq_simples.aliq as 'aliq' from tb_aliq_simples where tb_aliq_simples.id_conta_bling = " . $idconta . " order by tb_aliq_simples.exercicio desc limit 1" );
  return $res[ 0 ][ 'aliq' ];
 }

 public function getConta( $idConta ) {
  $sql = new Sql();
  $res = $sql->select( "SELECT nome_conta_bling as 'conta' FROM `tb_user_api` WHERE `id` = " . $idConta );
  return $res[ 0 ][ 'conta' ];
 }
 public function getNomeLoja( $param ) {
  $sql = new Sql();
  $res = $sql->select( "SELECT descricao FROM tb_canais_de_venda WHERE id_bling = " . $param . " OR tipo = '" . $param . "'" );
  return $res[ 0 ][ 'descricao' ];
 }

 public function getDadosUserApp( $idConta ) {
  $sql = new Sql();
  $res = $sql->select( "SELECT nome FROM tb_user_app WHERE id = " . $idConta );

  $this->setNomeUserApp( $res[ 0 ][ "nome" ] );

 }

 public function getContas( $idUsuario ) {
  $sql = new Sql();
  $res = $sql->select( "SELECT id, nome_conta_bling, nome_conta_bling conta FROM tb_user_api WHERE id_user_app = " . $idUsuario );
  $this->setIdContaToken( $idUsuario );
  return $res;

 }

 public function getNomeConta( $idConta ) {
  $sql = new Sql();
  $res = $sql->select( "SELECT * FROM `tb_user_api` WHERE id = " . $idConta );
  return $res;
 }

 function idLojaTipo( $conta, $loja ) {
  $sql = new Sql();
  $sel = "SELECT id_bling as 'idLoja' FROM tb_canais_de_venda WHERE situacao = 1 AND id_conta_bling IN (" . $conta . ") AND  tipo IN ('" . $loja . "')";
  $res = $sql->select( $sel );
  //  return $res[ 0 ][ 'idLoja' ];
  $idsLoja = array();
  foreach ( $res as $row ) {
   $idsLoja[] = $row[ 'idLoja' ];
  }
  return $idsLoja;
 }

 public function loadUserApi( $user ) {
  $this->set_IdConta( $user );
  $sql = new Sql();
  $idUserApp = $sql->select( "SELECT * FROM tb_user_api WHERE id_user_app = " . $this->get_IdConta() );
  return $idUserApp;
 }


 public function loadRefreshToken( $idConta ) {
  //   $this->set_IdConta( $idConta );

  $sel = "SELECT 
tb_user_api.id as 'idConta',
tb_user_api.nome_conta_bling as 'nomeConta',
tb_user_api.cliente_id,
tb_user_api.cliente_secret,
tb_user_api.link_convite,
tb_token_access.access_token,
tb_token_access.expires_in,
tb_token_access.token_type,
tb_token_access.scope,
tb_token_access.refresh_token
FROM `tb_user_api`
JOIN tb_token_access ON tb_user_api.id = tb_token_access.id_conta
WHERE tb_user_api.id = " . $idConta;

  $sql = new Sql();
  $res = $sql->select( $sel );

  foreach ( $res as $col ) {
   $this->setRefresh_token( $col[ 'refresh_token' ] );
   $this->setAccess_Token( $col[ 'access_token' ] );
   $this->setCliente_Id( $col[ 'cliente_id' ] );
   $this->setCliente_Secret( $col[ 'cliente_secret' ] );
   $this->set_IdConta( 'id' );
  }
 }


 public function get_ClienteId_Secret( $idConta ) {
  $this->loadRefreshToken( $idConta );
 }


 public function loadAccessToken( $id_user_app ) {

  $this->set_IdConta( $id_user_app );
  $sql = new Sql();
  $results = $sql->select( "SELECT * FROM tb_token_access WHERE id_user_app=:ID2", array( ":ID2" => $this->get_IdConta() ) );
  if ( count( $results ) > 0 ) {
   $row = $results[ 0 ];
   $this->setAccess_Token( $row[ 'access_token' ] );
  } else {
   // echo "Usuário ou senha inválido!";
   throw new Exception( "Error ao tentar buscar access token" );
  }
 }


 public function AccessToken( $idConta ) {
  $tk = $this->carregaAccessToken( $idConta );
  return $tk[ 0 ][ 'access_token' ];
 }


 public function carregaAccessToken( $idConta ) {
  $this->set_IdConta( $idConta );
  $sql = new Sql();
  return $results = $sql->select( "SELECT * FROM tb_token_access WHERE id_conta = :ID", array( ":ID" => $this->get_IdConta() ) );

 }

 public function selDados( $selDados ) {
  $sql = new Sql();
  $conn = $this->connBanco();
  $stmt = $conn->prepare( $selDados );
  if ( $stmt->execute() ) {
   $res = $stmt->fetchAll( PDO::FETCH_ASSOC );
   return $res;

   //   foreach($res as $row => $dados){    return $dados;       }

  }
  return false;

 }


 public function apiGET( $requisicao, $operApi, $accessToken ) {
  $_url = "https://www.bling.com.br/Api/v3/";
  $url_full = $_url . $requisicao;
  Switch( $operApi ) {

   case "DELETE":
    $setHeader = array( "accept: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "GET":
    $setHeader = array( "accept: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "PATCH":
    $setHeader = array( "accept: */* ", " Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "POST":
    $setHeader = array( "accept: application/json , Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "PUT":
    $setHeader = array( "accept: application/json , Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
  }

  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_URL, $url_full );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_HTTPHEADER, $setHeader );
  $resp = curl_exec( $curl );
  curl_close( $curl );
  return $resp; // retorno 

 } // fim metodo reqDados


 public function RequestApi( $requisicao, $operApi, $accessToken, $dadosBody ) {
  $_url = "https://api.bling.com.br/Api/v3/";
  $url_full = $_url . $requisicao;
  Switch( $operApi ) {

   case "DELETE":
    $setHeader = array( "accept: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "GET":
    $setHeader = array( "accept: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "PATCH":
    $setHeader = array( "accept: */* ", " Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "POST":
    $setHeader = array( "accept: application/json , Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
   case "PUT":
    //   $setHeader = array( "accept: application/json , Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    $setHeader = array( "Content-Type: application/json", "Authorization:Bearer " . $accessToken );
    break;
  }
  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $operApi );
  curl_setopt( $curl, CURLOPT_POSTFIELDS, $dadosBody );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_URL, $url_full );
  curl_setopt( $curl, CURLOPT_POST, true );
  curl_setopt( $curl, CURLOPT_HTTPHEADER, $setHeader );
  $resp = curl_exec( $curl );
  curl_close( $curl );
  return $resp;

 }


 public function trataString( $string ) {
  $noCaracteres = array( "'", '"' );
  $nwCaracteres = array( "", "" );
  $string = str_replace( $noCaracteres, $nwCaracteres, $string );
  return $string;
 }

 public function qtdeVendMesProduto( $idConta, $codigo, $mes ) {
  $sql = new Sql();

  try {
   // Prepare e execute a chamada à procedure armazenada
   $res = $sql->select(
    "CALL p_venda_prod_ult5mes(:ID_CONTA, :CODIGO, :MES)",
    array(
     ":ID_CONTA" => $idConta,
     ":CODIGO" => $codigo,
     ":MES" => $mes
    )
   );

   // Verifique se há resultados e retorne o valor formatado
   if ( count( $res ) > 0 ) {
    return number_format( $res[ 0 ][ 'qtde' ], 0, ',', '.' );
   } else {
    return 0;
   }
  } catch ( Exception $e ) {
   // Log da exceção ou tratamento do erro
   error_log( $e->getMessage() );
   return "Erro ao obter quantidade vendida do produto";
  }
 }


 public function qtdeVendMesProdutoLojas( $idConta, $id_loja, $codigo, $mes ) {
  $sql = new Sql();
  $res = $sql->select( "call p_venda_prod_ult5mes_lojas (:ID_CONTA,:IDLOJA, :CODIGO,:MES)",
   array( ":ID_CONTA" => $idConta, ":IDLOJA" => $id_loja, ":CODIGO" => $codigo, ":MES" => $mes ) );

  if ( count( $res ) > 0 ) {

   return number_format( $res[ 0 ][ 'qtde' ], 0, ',', '.' );

  } else {

   return 0;
  }

 }

 public function taxaVendaLojas( $idConta, $id_loja, $id_categ ) {

  $sql = new Sql();


  $call = "call p_taxaVendaLojas (:IDCONTA,:IDLOJA, :IDCATEG)";

  $param = array( ":IDCONTA" => $idConta, ":IDLOJA" => $id_loja, ":IDCATEG" => $id_categ );

  $res = $sql->select( $call, $param );

  if ( count( $res ) > 0 ) {

   return $res;

  } else {

   return $res( 0, 0, 0 );
  }

 }


 public function ProdUltCusto( $idConta, $idProd ) {

  $sql = new Sql();

  $res = $sql->select( "SELECT custo FROM tb_historico_custo WHERE id_conta = " . $idConta . " AND id_produto = " . $idProd . " ORDER by times_tamp ASc LIMIT 2" );

  if ( count( $res ) > 0 ) {

   return $res[ 0 ][ 'custo' ];

  } else {
   return 0;
  }


 }

 public function ProdutoVendaUltimos5dias( $idConta, $idProd ) {

  $sql = new Sql();

  $res = $sql->select( "call p_rel_ProdutoVendaUltimos5dias (:ID_CONTA,:ID_PROD)",
   array( ":ID_CONTA" => $idConta, ":ID_PROD" => $idProd ) );

  return $res;

 }


 public function histVendProdMes( $idConta, $idProd ) {
  // Prepara a consulta SQL
  $sel = "
        SELECT
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '3',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '2',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '1',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '0',
            COALESCE(SUM(CASE WHEN tb_pedidos.dataPedido >= CURRENT_DATE - INTERVAL 30 DAY THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '30d'
        FROM
            tb_pedidos
        JOIN
            tb_itensPedido ON tb_itensPedido.id_pedido_bling = tb_pedidos.id_bling
        JOIN
            tb_vendas ON tb_vendas.id_bling = tb_pedidos.id_bling
        LEFT JOIN
            tb_produto_estrutura ON tb_itensPedido.produto_id = tb_produto_estrutura.idProdutoEstrutura
        WHERE
            tb_pedidos.dataPedido >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
            AND tb_vendas.situacao_id IN (9,15)
            AND (tb_produto_estrutura.produto_id = :ID_PROD OR tb_itensPedido.produto_id = :ID_PROD)
            AND tb_pedidos.id_conta_bling = :ID_CONTA
    ";

  // Cria uma instância da classe Sql
  $sql = new Sql();

  // Prepara os parâmetros para a consulta SQL
  $params = array(
   ":ID_PROD" => $idProd,
   ":ID_CONTA" => $idConta
  );

  try {
   // Executa a consulta SQL com os parâmetros
   $res = $sql->select( $sel, $params );

   // Retorna o resultado da consulta
   return $res;
  } catch ( Exception $e ) {
   // Log da exceção ou tratamento do erro
   error_log( $e->getMessage() );
   return "Erro ao obter histórico de vendas do produto";
  }
 }


 public function histVendProdMesLoja( $idConta, $idProd, $idLoja ) {
  // Prepara a consulta SQL
  $sel = "
        SELECT
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '3',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '2',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '1',
            COALESCE(SUM(CASE WHEN MONTH(tb_pedidos.dataPedido) = MONTH(CURRENT_DATE) THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '0',
            COALESCE(SUM(CASE WHEN tb_pedidos.dataPedido >= CURRENT_DATE - INTERVAL 30 DAY THEN TRUNCATE(COALESCE(tb_produto_estrutura.quantidade, tb_itensPedido.quantidade),0) ELSE 0 END),0) AS '30d'
        FROM
            tb_pedidos
        JOIN
            tb_itensPedido ON tb_itensPedido.id_pedido_bling = tb_pedidos.id_bling
        JOIN
            tb_vendas ON tb_vendas.id_bling = tb_pedidos.id_bling
        JOIN
            tb_canais_de_venda ON tb_canais_de_venda.id_bling = tb_vendas.loja_id
        LEFT JOIN
            tb_produto_estrutura ON tb_itensPedido.produto_id = tb_produto_estrutura.idProdutoEstrutura
        WHERE
            tb_pedidos.dataPedido >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
            AND tb_vendas.situacao_id IN (9, 15)
            AND tb_canais_de_venda.id_bling = :ID_LOJA
            AND (tb_produto_estrutura.produto_id = :ID_PROD OR tb_itensPedido.produto_id = :ID_PROD)
            AND tb_pedidos.id_conta_bling = :ID_CONTA
    ";

  // Cria uma instância da classe Sql
  $sql = new Sql();

  // Prepara os parâmetros para a consulta SQL
  $params = array(
   ":ID_PROD" => $idProd,
   ":ID_CONTA" => $idConta,
   ":ID_LOJA" => $idLoja
  );

  try {
   // Executa a consulta SQL com os parâmetros
   $res = $sql->select( $sel, $params );

   // Retorna o resultado da consulta
   return $res;
  } catch ( Exception $e ) {
   // Log da exceção ou tratamento do erro
   error_log( $e->getMessage() );
   return "Erro ao obter histórico de vendas do produto por loja";
  }
 }


 public function ProdutoVendaUltimos30dias( $idConta, $idProd ) {
  $sql = new Sql();

  try {
   // Prepara e executa a chamada à procedure armazenada
   $res = $sql->select(
    "CALL p_rel_ProdutoVendaUltimos30dias(:ID_CONTA, :ID_PROD)",
    array(
     ":ID_CONTA" => $idConta,
     ":ID_PROD" => $idProd
    )
   );

   // Verifica se há resultados e retorna o valor formatado
   if ( count( $res ) > 0 ) {
    return number_format( $res[ 0 ][ 'vnd30d' ], 0, ',', '.' );
   } else {
    return 0;
   }
  } catch ( Exception $e ) {
   // Log da exceção ou tratamento do erro
   error_log( $e->getMessage() );
   return "Erro ao obter vendas do produto nos últimos 30 dias";
  }
 }


 /**************************************************************************************************/

 public function atualizaEstoqProd( $idsProd, $idConta, $token = "" ) {

  $sql = new Sql();
  $id_Prod = "";
  $ProdIds = array();
  $CALL = "CALL p_cad_saldo_estoque (:ID_CONTA_BLING,:PRODUTO_ID,:DEPOSITOS_ID,:SALDOFISICO,:SALDOVIRTUAL)";
  $resultado = array();

  if ( empty( $token ) || $token == "" ) {
   $token = $this->AccessToken( $idConta );
  }

  if ( is_array( $idsProd ) ) {
   $id_Prod = implode( ',', $idsProd );
  } else {
   $id_Prod = $idsProd;
   $idsProd = [ $idsProd ]; // tranforma em array para ser passa na string de chamada da api
  }


  try {
   // Preparando a consulta SQL com placeholders para evitar injeção de SQL
   $selDepo = $sql->select(
    "SELECT depositos_id as 'id' FROM tb_saldo_estoque WHERE produto_id IN (:ID_PROD)",
    array( ":ID_PROD" => $id_Prod )
   );


   $operador = "GET";
   $recurso = "estoques/saldos/";
   $filtro = http_build_query( array( 'idsProdutos' => $idsProd ) );

   foreach ( $selDepo as $deposito ) {

    $requisicao = $recurso . $deposito[ 'id' ] . "?" . $filtro;

    usleep( 333334 ); // Pausa para evitar sobrecarga de requisições

    $dados = json_decode( $this->apiGET( $requisicao, $operador, $token ) );


    foreach ( $dados as $col ) {
     foreach ( $col as $lin ) {
      $value = array(
       ":ID_CONTA_BLING" => $idConta,
       ":PRODUTO_ID" => $lin->produto->id,
       ":DEPOSITOS_ID" => $deposito[ 'id' ],
       ":SALDOFISICO" => $lin->saldoFisicoTotal,
       ":SALDOVIRTUAL" => $lin->saldoVirtualTotal
      );

      // Executa a chamada à procedure armazenada e coleta o resultado
      $resultado[] = $sql->select( $CALL, $value );
     }
    }
   }

   // Retorna todos os resultados coletados
   return $resultado;

  } catch ( Exception $e ) {
   // Log da exceção ou tratamento do erro
   error_log( $e->getMessage() );
   return "Erro ao atualizar o estoque do produto";
  }
 }

 /*******************************************************************************************************************/

 public function atualizaDetalhesProd( $idProd, $idConta, $token = "" ) {

  $sql = new Sql();

  if ( empty( $token ) ) {
   $token = $this->AccessToken( $idConta );
  }

  $res1 = 0;
  $operador = "GET";
  $recurso = "produtos/" . $idProd;
  $param = array();

  usleep( 333334 );

  $dados = json_decode( $this->apiGET( $recurso, $operador, $token ) );


  if ( !empty( $dados->data ) ) {

   $lin = $dados->data;

   $MedidaCubica = ( $lin->dimensoes->largura * $lin->dimensoes->altura * $lin->dimensoes->profundidade ) / 6000;
   $PesoCubico = $lin->pesoBruto;
   $Cubagem = max( round( $MedidaCubica, 1 ), round( $PesoCubico, 2 ) );

   $param = [
    ":ID_CONTA_BLING" => $idConta,
    ":ID_BLING_PRODUTO" => $idProd,
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
   ];

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

   $res1 = $sql->select( $call, $param );

   return [ true, $res1 ];

  } else {

   return [ false, $res1 ];
  }
 }


 public function atualizaSaldoDeposito( $idConta, $idFornec, $token = "" ) {

  if ( empty( $token ) ) {

   $token = $this->AccessToken( $idConta );
  }

  $cont_dados = 0;

  $sql = new Sql();

  $operador = "GET";
  $recurso = "estoques/saldos/";
  $filtro = "";
  $fullDados = array();

  // busca os deposito cadastrado no banco, e retorna seu id
  $deposito = $sql->select( "SELECT `id_bling` as id FROM `tb_depositos` WHERE `id_conta_bling` = " . $idConta . " AND `padrao` = 1" );

  $produtos = $sql->select( "call p_select_idProduto_fornecedor (:FORNEC,:ID_CONTA)", array( ":FORNEC" => $idFornec, ":ID_CONTA" => $idConta ) );


  foreach ( $deposito as $dep ) {

   $idDeposito = $dep[ 'id' ];

   $idsProdutos = array();

   foreach ( $produtos as $codigos ) {

    foreach ( $codigos as $codigo => $cod ) {

     $idsProdutos[] = $cod;

    }

   }

   //dividindo o array $idsProdutos em lotes de 50 elementos e processar cada lote separadamente.

   $chunks = array_chunk( $idsProdutos, 50 );

   foreach ( $chunks as $chunk ) {


    $filtro = http_build_query( array( 'idsProdutos' => $chunk ) );

    $requisicao = $recurso . $idDeposito . "?" . $filtro;

    usleep( 333334 );

    $dados = $this->apiGET( $requisicao, $operador, $token );

    $dados = json_decode( $dados );

    if ( empty( $dados->data ) ) {

     return array( "Error", $dados );

    }

    $call = "CALL p_cad_saldo_estoque ( :ID_CONTA,:PRODUTO_ID,:DEPOSITOS_ID,:SALDOFISICO,:SALDOVIRTUAL  )";

    foreach ( $dados as $col ) {

     foreach ( $col as $lin ) {

      $cont_dados++;

      $value = array( ":ID_CONTA" => $idConta,
       ":PRODUTO_ID" => $lin->produto->id,
       ":DEPOSITOS_ID" => $idDeposito,
       ":SALDOFISICO" => $lin->saldoFisicoTotal,
       ":SALDOVIRTUAL" => $lin->saldoVirtualTotal );

      $sql->run( $call, $value );

     }
    }
   }

   return $rtn = array( "Itens " => $cont_dados, "Msg" => " Produtos com estoque atualizado" );

   //   array( "ok", $rtn );
  }


 }


 function mesAbrev( $numMes ) {

  $num = array( '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' );

  $meses = array( 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez' );

  $array_meses = array_combine( $num, $meses );

  return $array_meses[ $numMes ];

 }

 function limpaArray( $array ) {

  return array_filter( $array, function ( $valor ) {

   return $valor !== null && $valor !== '';

  } );


 }

 // função que pega o ID da Loja Física (padrão) se no pedido de venda não foi selecionado nenhuma loja.
 public function get_IdContaLoja_Padrao( $conta ) {

  $sql = new Sql();

  $sel = "SELECT id_bling FROM tb_canais_de_venda WHERE id_conta_bling = " . $conta . " AND tipo = 'Loja Fisica'";

  $res = $sql->select( $sel );

  return $res[ 0 ][ 'id_bling' ];

 }


 public function getAtualizaAnuncio( $conta, $parameters ) {

  /* parametros esperados 
        idProduto  (não é o id do anuncio)
        idLoja
        exemplo:
        
        $parameters = array('idProduto' => idprod
                            'idLoja'    => $idLoja );
     
        */

  $sql = new Sql();

  $parameters = $this->limpaArray( $parameters );

  $token = $this->AccessToken( $conta );

  $param = http_build_query( $parameters, '', '&' );

  $cond = true;
  $requisicao = "";
  $fullDados = array();

  $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
  $recurso = "produtos/lojas";
  $nPagina = 0; //numero páginas
  $limite = 100; // linhas por página     


  while ( $cond ) {

   $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $param;

   //    usleep( 333334 );

   $dados = json_decode( $this->apiGET( $requisicao, $operApi, $token ) );


   if ( empty( $dados->data ) ) {

    $cond = false;

   } else {

    $fullDados[ $nPagina ] = $dados;

    $nPagina++;

   }

  }


  foreach ( $fullDados as $rows ) {

   foreach ( $rows as $lins ) {

    foreach ( $lins as $lin ) {

     $call = "CALL p_cad_prod_lojas (
                                                :ID_CONTA,
                                                :ID_BLING,
                                                :CODIGO,
                                                :PRECO,
                                                :PRECOPROMOCIONAL,
                                                :PRODUTO_ID,
                                                :LOJA_ID,
                                                :FORNECEDORLOJA_ID,
                                                :MARCALOJA_ID
                                                )";

     $param = array(
      ":ID_CONTA" => $conta,
      ":ID_BLING" => $lin->id,
      ":CODIGO" => $lin->codigo,
      ":PRECO" => $lin->preco,
      ":PRECOPROMOCIONAL" => $lin->precoPromocional,
      ":PRODUTO_ID" => $lin->produto->id,
      ":LOJA_ID" => $lin->loja->id,
      ":FORNECEDORLOJA_ID" => $lin->fornecedorLoja->id,
      ":MARCALOJA_ID" => $lin->marcaLoja->id );


     $sql->run( $call, $param );

     if ( is_array( $lin->categoriasProdutos ) ) {

      foreach ( $lin->categoriasProdutos as $li2 ) {

       $val = array(
        ":ID_CONTA" => $conta,
        ":ID_PRODUTO_LOJA" => $lin->produto->id,
        ":CATEGORIASPRODUTOS_ID" => $li2->id );


       $inse2 = "call p_cad_catego_prod_lojas ( :ID_CONTA, :ID_PRODUTO_LOJA,:CATEGORIASPRODUTOS_ID )";


       $sql->run( $inse2, $val );
      }

     }

    }
   }

  }
 } // end function getAtualizaAnuncio()/ 

 public function getAtualizaUmAnuncio( $conta, $idAnuncio, $token ) {

  $sql = new Sql();

  $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
  $recurso = "produtos/lojas/" . $idAnuncio;


  // echo $token;
  usleep( 333334 );

  $dados = json_decode( $this->apiGET( $recurso, $operApi, $token ) );

  //

  if ( empty( $dados->data ) ) {

   //  print_r( $dados );
   echo "<span style='color:red'>&#10007;</span>";

  } else {

   foreach ( $dados as $lin ) {


    $call = "CALL p_cad_prod_lojas (
                                                :ID_CONTA,
                                                :ID_BLING,
                                                :CODIGO,
                                                :PRECO,
                                                :PRECOPROMOCIONAL,
                                                :PRODUTO_ID,
                                                :LOJA_ID,
                                                :FORNECEDORLOJA_ID,
                                                :MARCALOJA_ID
                                                )";

    $param = array(
     ":ID_CONTA" => $conta,
     ":ID_BLING" => $lin->id,
     ":CODIGO" => $lin->codigo,
     ":PRECO" => $lin->preco,
     ":PRECOPROMOCIONAL" => $lin->precoPromocional,
     ":PRODUTO_ID" => $lin->produto->id,
     ":LOJA_ID" => $lin->loja->id,
     ":FORNECEDORLOJA_ID" => $lin->fornecedorLoja->id,
     ":MARCALOJA_ID" => $lin->marcaLoja->id );


    $sql->run( $call, $param );

    if ( is_array( $lin->categoriasProdutos ) ) {

     foreach ( $lin->categoriasProdutos as $li2 ) {

      $val = array(
       ":ID_CONTA" => $conta,
       ":ID_PRODUTO_LOJA" => $lin->produto->id,
       ":CATEGORIASPRODUTOS_ID" => $li2->id );


      $inse2 = "call p_cad_catego_prod_lojas ( :ID_CONTA, :ID_PRODUTO_LOJA,:CATEGORIASPRODUTOS_ID )";


      $sql->run( $inse2, $val );
     }
    }
   }
  }

 } // end function getAtualizaAnuncio()/


 public function atualizaVenda( $conta, $parameters, $atualizaPedido ) {
  /* parametros esperados:
   conta
   idContato
   dataInicial
   dataFinal
   numero (numero pedido)
   idLoja  
   atualizaPedido (true/false)  se atualiza os pedios o não.
   */
  $sql = new Sql();

  $parameters = $this->limpaArray( $parameters );

  $token = $this->AccessToken( $conta );

  $param = http_build_query( $parameters, '', '&' );

  $cond = true;
  $requisicao = "";
  $fullDados = array();

  $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
  $recurso = "pedidos/vendas";
  $nPagina = 1; //numero páginas
  $limite = 100; // linhas por página     


  while ( $cond ) { //  LAÇO 3 : PERCORRE TODAS AS LINHAS DENTRO DO INTERVÁLO SOLCITADO NA BUSCA

   $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $param;

   $pedidos = json_decode( $this->apiGET( $requisicao, $operApi, $token ) );

   usleep( 333334 );

   if ( empty( $pedidos->data ) ) {

    $cond = false;

   } else {

    $fullDados[ $nPagina ] = $pedidos; // ARMAZENA TODOS OS DADOS RETORNANA EM UM ARRAY, FORMANDO UM ARRAY DE 3 NÍVEIS

    $nPagina++;

   }

  } // FIM while 


  $values1 = 0;
  $pedCarregado = 0;
  $valorPedidos = 0;


  foreach ( $fullDados as $Dados ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3
   foreach ( $Dados as $lins ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3
    foreach ( $lins as $lin ) { // LAÇO 6 : PERCORRE O 3º NÍVEL DO ARRAY PREENCHIDO NO LAÇO 3


     $pedCarregado++;
     $valorPedidos += $lin->total;

     //verifica a variável, para cadastrar o pedido com a loja padrão se no pedido de venda não foi selecionado nenhuma loja.

     if ( strlen( $lin->loja->id ) < 3 ) {

      $lojaId = $this->get_IdContaLoja_Padrao( $conta );

     } else {

      $lojaId = $lin->loja->id;
     }

     $values1 = array(

      ":ID_CONTA_BLING" => $conta,
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
      ":LOJA_ID" => $lojaId );


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


     if ( $atualizaPedido ) {

      $res = $this->atualizaPedido( $conta, $lin->id, $token );

      echo $res;
      echo "<br>";
     }

     $sql->run( $inse, $values1 );

    }
   }
  }

  echo "<hr>";
  echo "<br>";
  echo "<strong>Pedidos Carregados/Atualizados</strong><br>";
  echo "<br>";
  echo "Conta " . $conta;
  echo "<br>";
  echo "Qtde de pedido: " . $pedCarregado;
  echo "<br>";
  echo "Valor Total: R$ " . number_format( $valorPedidos, 2, ',', '.' );
  echo "<br>";
  echo "<br>";

  foreach ( $parameters as $k => $v ) {

   echo $k . " : " . $v;
   echo "<br>";
  }
  echo "<br>";

 }


 public function atualizaPedido( $idConta, $idPedido, $token ) {

  $sql = new Sql();

  $token = $token ?? $this->AccessToken( $idConta );


  $operadorApi = "GET";
  $recurso = "pedidos/vendas/" . $idPedido;


  $pedido = $this->apiGET( $recurso, $operadorApi, $token );

  usleep( 333334 );

  $pedido = json_decode( $pedido );

  if ( isset( $pedido->error ) ) {

   //    $sql->run( "INSERT INTO tb_PedidoErro (idConta, idPedidoErro) VALUES (" . $idConta . ", " . $idPedido . ")" );


   switch ( $pedido->error->type ) {

    case "RESOURCE_NOT_FOUND":

     return "error: " . $pedido->error->message . " recurso: " . $recurso;
     break;

     $sql->run( "DELETE FROM `tb_vendas` WHERE `id_bling` =" . $idPedido );


    case "TOO_MANY_REQUESTS":

     return "error: " . $pedido->error->message . " recurso: " . $recurso;
     break;
   }

  } else { ///   if ( isset( $pedido->error ) ) {


   foreach ( $pedido as $detalhes => $lin ) {
    $inse = "CALL p_cad_pedidos (   
                                    :ID_CONTA_BLING,
                                    :ID_BLING,
                                    :NUMERO,
                                    :NUMEROLOJA,
                                    :DATAPEDIDO,
                                    :TOTALPRODUTOS,
                                    :OUTRASDESPESAS,
                                    :DESCONTO_VALOR,
                                    :CATEGORIA_ID,
                                    :NOTAFISCAL_ID,
                                    :VENDEDOR_ID,
                                    :INTERMEDIADOR_CNPJ,
                                    :INTERMEDIADOR_NOMEUSUARIO,
                                    :TAXAS_TAXACOMISSAO,
                                    :TAXAS_CUSTOFRETE,
                                    :TAXAS_VALORBASE

                                   )";
    $valorBase = $lin->taxas->valorBase;

    if ( empty( $valorBase ) ) {

     $valorBase = $lin->totalProdutos;
    }


    if ( empty( $lin->taxas->custoFrete ) ) {
     $CustoFrete = $lin->totalProdutos - $valorBase;
    } else {
     $CustoFrete = $lin->taxas->custoFrete;
    }

    $value = array(

     ":ID_CONTA_BLING" => $idConta,
     ":ID_BLING" => $idPedido,
     ":NUMERO" => $lin->numero,
     ":NUMEROLOJA" => $lin->numeroLoja,
     ":DATAPEDIDO" => $lin->data,
     ":TOTALPRODUTOS" => $lin->totalProdutos,
     ":OUTRASDESPESAS" => $lin->outrasDespesas,
     ":DESCONTO_VALOR" => $lin->desconto->valor,
     ":CATEGORIA_ID" => $lin->categoria->id,
     ":NOTAFISCAL_ID" => $lin->notaFiscal->id,
     ":VENDEDOR_ID" => $lin->vendedor->id,
     ":INTERMEDIADOR_CNPJ" => $lin->intermediador->cnpj,
     ":INTERMEDIADOR_NOMEUSUARIO" => $lin->intermediador->nomeUsuario,
     ":TAXAS_TAXACOMISSAO" => $lin->taxas->taxaComissao,
     ":TAXAS_CUSTOFRETE" => $CustoFrete,
     ":TAXAS_VALORBASE" => $valorBase );


    $sql->run( $inse, $value );

    //   print_r($lin->itens);


    $descPonderado = 0;
    $ponderado = 0;
    $desconto = 0;
    $frete_ponderado = 0;
    $frete_proporcional = 0;
    $taxa_ponderado = 0;
    $taxa_proporcional = 0;
    $sleep = 0;

    echo "qtde itens" . $qtde_itens = count( $lin->itens );

    foreach ( $lin->itens as $item ) {


     if ( $lin->desconto->valor > 0 ) {

      $ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

      $desconto = $ponderado * $lin->desconto->valor;

     }

     if ( $item->desconto > 0 ) {

      $descPonderado = $item->desconto;
     } else {


      $descPonderado = $desconto;

     }

     if ( $lin->taxas->custoFrete > 0 ) {

      $frete_ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

      $frete_proporcional = $frete_ponderado * $lin->taxas->custoFrete;

     }

     if ( $lin->taxas->taxaComissao > 0 ) {

      $taxa_ponderado = $item->quantidade * $item->valor / $lin->totalProdutos;

      $taxa_proporcional = $taxa_ponderado * $lin->taxas->taxaComissao;

     }


     $itens = array(
      ":ID_CONTA_BLING" => $idConta,
      ":ID_PEDIDO_BLING" => $idPedido,
      ":NUMERO_PEDIDO" => $lin->numero,
      ":ID_BLING" => $item->id,
      ":CODIGO" => $item->codigo,
      ":UNIDADE" => $item->unidade,
      ":QUANTIDADE" => $item->quantidade,
      ":DESCONTO" => $descPonderado,
      ":VALOR" => $item->valor,
      ":TAXA_PROP" => $taxa_proporcional,
      ":FRETE_PROP" => $frete_proporcional,
      ":DESCRICAO" => $item->descricao,
      ":PRODUTO_ID" => $item->produto->id,
      ":QTDE_ITENS" => $qtde_itens
     );


     //      print_r($itens);
     //     echo "<hr>";

     $insItens = "CALL p_cad_pedidos_itens (

            :ID_CONTA_BLING,
            :ID_PEDIDO_BLING,
            :NUMERO_PEDIDO,
            :ID_BLING,
            :CODIGO,
            :UNIDADE,
            :QUANTIDADE,
            :DESCONTO,
            :VALOR,
            :TAXA_PROP,
            :FRETE_PROP,
            :DESCRICAO,
            :PRODUTO_ID,
            :QTDE_ITENS)";

     $sql->run( $insItens, $itens );

     $this->atualizaEstoqProd( $item->id, $idConta, $token );

    }

   }

  }
  return "Atualizado Pedido " . $idPedido;

 }


 public function custoFreteCubagemLoja( $conta, $loja, $cubagem ) {

  $sql = new Sql();


  $sel = "SELECT 
TRUNCATE(
    	CASE 
            WHEN cv.descFrete = 0 THEN cf.valor
            ELSE cf.valor * (cv.descFrete / 100)
        END, 
    2) AS valor
    
FROM `tb_custo_frete` as cf
JOIN tb_canais_de_venda as cv on cv.tipo = cf.loja_tipo
WHERE loja_tipo = :LOJA_TIPO 
AND cv.id_conta_bling = :CONTA
AND :CUBAGEM >= cf.pesoDe AND :CUBAGEM < cf.pesoAte";


  $param = array( ":CONTA" => $conta, ":LOJA_TIPO" => $loja, ":CUBAGEM" => $cubagem );

  $res = $sql->select( $sel, $param );

  if ( empty( $res ) || !isset( $res[ 0 ][ 'valor' ] ) ) {

   return 0;

  } else {

   return $res[ 0 ][ 'valor' ];

  }

 }

 public function calcMargemVenda( $conta, $lojaTipo, $itens, $qtde, $frete, $valor, $custo, $custoTotal, $desc, $TotalProdutos, $total_NF, $taxaFixa, $taxaMKP, $ValorBase, $aliq, $cubagem = 1 ) {

  if ( empty( $ValorBase ) || $ValorBase > $total_NF ) {

   $ValorBase = $total_NF;
  }


  $difValBase = $total_NF - $ValorBase;

  $freteCliente = $total_NF - $qtde * $valor;

  $xtotal_NF = ( $qtde * $valor ) / $total_NF;

  $ValorBase = ( $xtotal_NF * $ValorBase );

  $v_ponderado = ( $qtde * $valor / $TotalProdutos ); // 4 * 66,96 / 581,06  = 


  switch ( $lojaTipo ) {

   case 'IntegraCommerce': // Magalu

    $v_imp = ( $total_NF * $aliq / 100 ) * $v_ponderado;

    if ( $valor < 79.00 ) {
     $frete = 0;
    } else {

     $frete = $this->custoFreteCubagemLoja( $conta, $lojaTipo, $cubagem );
    }

    if ( $ValorBase < $valor ) {
     $ValorBase = $valor;
    }
    // $desc = $desc / 2;   

    $taxaMKP += $taxaFixa;
    $lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );
    $repas = ( $ValorBase - $desc - $taxaMKP - $frete );

    break;

   case 'Amazon':

    $frete = $this->custoFreteCubagemLoja( $conta, $lojaTipo, $cubagem );

    $v_imp = ( $total_NF * $aliq / 100 ) * $v_ponderado;

    if ( $ValorBase < $valor ) {
     $ValorBase = $valor;
    }
    // $desc = $desc / 2;   

    $taxaMKP += $taxaFixa;
    $lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );
    $repas = ( $ValorBase - $desc - $taxaMKP - $frete );

    break;

   case 'MercadoLivre':

    $v_imp = ( $total_NF * $aliq / 100 ) * $v_ponderado;
    $lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );
    $repas = ( $total_NF * $v_ponderado - $desc - $taxaMKP - $frete );

    break;

   case 'LojaIntegrada':


    $v_imp = ( $total_NF * $aliq / 100 ) * $v_ponderado;

    $lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );

    $repas = ( $total_NF - $desc - $taxaMKP - $frete ) * $v_ponderado;

    //$lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );
    //$repas = ( $ValorBase - $desc - $taxaMKP - $frete );
    break;

   case 'SkyHub':
    if ( $valor < 89.00 ) {
     $frete = 0;
    } else {
     $taxaFixa = 0;
    }
    $taxaMKP += $taxaFixa;
    $lucro = ( $ValorBase - $custoTotal - $frete - $desc - $taxaMKP - $v_imp );
    $repas = ( $ValorBase - $desc - $taxaMKP - $frete );
    break;

   case 'Shopee':

    $v_imp = ( $total_NF * $aliq / 100 ) * $v_ponderado;
    $lucro = ( $ValorBase - $custoTotal - $frete - $taxaMKP - $v_imp );
    $repas = ( $ValorBase - $taxaMKP - $frete );
    break;

   default:

    if ( $valor < 79.00 ) {
     $frete = 0;
    } else {
     $taxaFixa = 0;
    }

    $v_imp = ( $valor * $qtde * $aliq / 100 ) * $v_ponderado;
    $lucro = ( $qtde * $valor ) - ( $qtde * $custo ) - $frete - $taxaMKP - $v_imp * $v_ponderado;
    $repas = ( $ValorBase - $taxaMKP - $frete ) * $v_ponderado;
    break;
  }

  $marg = ( $lucro / ( $qtde * $valor ) * 100 );

  return array(
   "valor" => number_format( $valor, 2, ',', '.' ),
   "custoTotal" => number_format( $custoTotal, 2, ',', '.' ),
   "desc" => number_format( $desc, 2, ',', '.' ),
   "total_NF" => number_format( $total_NF, 2, ',', '.' ),
   "valorBase" => number_format( $ValorBase, 2, ',', '.' ),
   "taxaMKP" => number_format( $taxaMKP, 2, ',', '.' ),
   "frete" => number_format( $frete, 2, ',', '.' ),
   "repas" => number_format( $repas, 2, ',', '.' ),
   "v_imp" => number_format( $v_imp, 2, ',', '.' ),
   "aliq" => number_format( $aliq, 2, ',', '.' ),
   "lucro" => number_format( $lucro, 2, ',', '.' ),
   "marg" => number_format( $marg, 1, ',', '.' )


  );
 }


 public function getNfes( $idConta, $token, $dataIncial, $dataFinal ) {

  $sql = new Sql();

  $requisicao = "";

  $fullDados = array();
  $cond = true;

  $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
  $recurso = "nfe";
  $nPagina = 1; //numero páginas
  $limite = 100; // linhas por página
  $cont_dados = 1;

  $filtro = "situacao=7"; // 7 registrada, 
  $filtro .= "&";
  $filtro .= "tipo=0"; // 0 entrada; 1 saída, 
  $filtro .= "&";
  $filtro .= "dataEmissaoInicial=" . $dataIncial;
  $filtro .= "&";
  $filtro .= "dataEmissaoFinal=" . $dataFinal;

  $natOperacao = $sql->select( "SELECT `id_bling` FROM `tb_naturezasOperacao` WHERE `id_conta` = " . $idConta . " AND `padrao` = 2 LIMIT 1" );

  $idNatOper = !empty( $natOperacao ) ? $natOperacao[ 0 ][ 'id_bling' ] : null;


  while ( $cond ) {

   $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

   usleep( 333500 );

   $dados = json_decode( $this->apiGET( $requisicao, $operApi, $token ) );

   if ( empty( $dados->data ) ) {

    $cond = false;
    //print_r( $dados );

   } else {

    $fullDados[] = $dados;

    $nPagina++;
   }
  }


  $call = "call p_cad_nfe  (  :ID_CONTA, :ID_NFE, :TIPO_NFE, :SITUACAO,:NUMERO,:DATAEMISSAO,:DATAOPERACAO, :CHAVEACESSO,  :CONTATO_ID, :NATUREZAOPERACAO_ID,:LOJA_ID)";

  //   print_r($fullDados);


  foreach ( $fullDados as $dd ) {

   foreach ( $dd as $col ) {

    foreach ( $col as $lin ) {

     if ( $lin->naturezaOperacao->id == $idNatOper ) {

      $value = array( ":ID_CONTA" => $idConta,
       ":ID_NFE" => $lin->id,
       ":TIPO_NFE" => $lin->tipo,
       ":SITUACAO" => $lin->situacao,
       ":NUMERO" => $lin->numero,
       ":DATAEMISSAO" => $lin->dataEmissao,
       ":DATAOPERACAO" => $lin->dataOperacao,
       ":CHAVEACESSO" => $lin->chaveAcesso,
       ":CONTATO_ID" => $lin->contato->id,
       ":NATUREZAOPERACAO_ID" => $lin->naturezaOperacao->id,
       ":LOJA_ID" => $lin->loja->id );

      $sql->run( $call, $value );

      $cont_dados++;
     }

    }
   }
  }
  echo "<br>";
  echo "<br>";
  echo "NFes cadastrada/atualizadas: " . $cont_dados;
  echo "<br>";
  echo "<hr>";

 }


 public function getDetalhesNfes( $idConta, $token = "" ) {
     
 $token = $token ?? $this->AccessToken( $idConta );

  $sql = new Sql();

  $idNfe = "CALL  p_busca_id_nfe (" . $idConta . ")";
  $idsNfe = $sql->select( $idNfe );


  //   print_r($idsNfe);
  //   echo "<hr>";

  if ( !empty( $idNfe ) ) {

   echo "<hr>";


   foreach ( $idsNfe as $coll ) {
    foreach ( $coll as $id_nfe ) {
    $this->atualizaNFes($idConta, $id_nfe, $token );
    }
   }
  }
 }

function atualizaNFes($idConta, $id_nfe, $token) {
     
    $sql = new Sql();
/*
    echo "conta: " . $idConta . "<br>";
    echo "id nf: " . $id_nfe . "<br>";
    echo "token: " . $token. "<br>";
*/
  
   $call = "CALL p_cad_itens_nfe ( :ID_CONTA, :ID_NFE,:NUMERO,:DATAEMISSAO,:DATAOPERACAO, :ITENS_CODIGO, :ITENS_DESCRICAO,:ITENS_UNIDADE, :ITENS_QUANTIDADE,:ITENS_VALOR, :ITENS_VALORTOTAL, :ITEN_GTIN )";

   $call_parcelas = "CALL p_cad_nfe_parcelas ( :ID_CONTA,	:ID_NFE,	:NUM_NFE,	:VENCIMENTO,	:VALOR,	:PARCELA,:PARCELAS,:OBS )";

   $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT   
   $requisicao = "nfe/" . $id_nfe;

  $dados = json_decode( $this->apiGET( $requisicao, $operApi, $token ) );

  usleep( 335000 );

  if ( isset( $dados->error ) ) {
   echo "Erro na requisição da nf <br>";
   print_r( $dados );

  } else {

   if ( !empty( $dados->data ) ) {

    $ttl_Listados = 0;
    foreach ( $dados as $lin ) {

     foreach ( $lin->itens as $item ) {

      $ttl_Listados++;
      $n_parcela = 1;

      $detalhes = array(
       ":ID_CONTA" => $idConta,
       ":ID_NFE" => $lin->id,
       ":NUMERO" => $lin->numero,
       ":DATAEMISSAO" => $lin->dataEmissao,
       ":DATAOPERACAO" => $lin->dataOperacao,
       ":ITENS_CODIGO" => $item->codigo,
       ":ITENS_DESCRICAO" => $item->descricao,
       ":ITENS_UNIDADE" => $item->unidade,
       ":ITENS_QUANTIDADE" => $item->quantidade,
       ":ITENS_VALOR" => $item->valor,
       ":ITENS_VALORTOTAL" => $item->valorTotal,
       ":ITEN_GTIN" => $item->gtin );

      $sql->run( $call, $detalhes );

      $qtdeParcelas = count( $lin->parcelas );

      foreach ( $lin->parcelas as $parc ) {

       $parcelas = array(
        ":ID_CONTA" => $idConta,
        ":ID_NFE" => $lin->id,
        ":NUM_NFE" => $lin->numero,
        ":VENCIMENTO" => $parc->data,
        ":VALOR" => $parc->valor,
        ":PARCELA" => $n_parcela++,
        ":PARCELAS" => $qtdeParcelas,
        ":OBS" => $parc->observacoes );

       $sql->run( $call_parcelas, $parcelas );
      }
     }
    // echo "Conta {$idConta} ";
     echo "Nfe {$lin->numero} ";
     echo "Data {$lin->dataEmissao} ";
     echo "Itens {$ttl_Listados} <br>";
    }
   }
  }
 }


 public function getEstoqDataCompra( $idconta, $gtin, $sku ) {

  $call1 = "SELECT
	i.numero as nf,
    i.codigo AS codigo,
    i.gtin as gtin,
    i.descricao as descricao,
    COALESCE(i.qtde, 0) AS compra,
    v.parcelas as parcelas,
    COALESCE(DATE(i.dataEmissao), '0000-00-00') AS dtEmissao,
    DATEDIFF(CURDATE(), i.dataEmissao) AS diasCorrido,
    CASE 
        WHEN TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0) > v.parcelas 
        THEN v.parcelas
        ELSE TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0)
    END AS parcPgs
FROM
    tb_itens_nfe AS i 
JOIN tb_nfe_parcelas as v  on i.id_nfe  = v.id_nfe 

WHERE
i.id_conta = :IDCONTA     
AND i.codigo = :SKU
GROUP BY v.id_nfe
ORDER BY
    i.dataEmissao DESC
   LIMIT 2;";

  $call2 = "SELECT
	i.numero as nf,
    i.codigo AS codigo,
    i.gtin as gtin,
    i.descricao as descricao,
    COALESCE(i.qtde, 0) AS compra,
    v.parcelas as parcelas,
    COALESCE(DATE(i.dataEmissao), '0000-00-00') AS dtEmissao,
    DATEDIFF(CURDATE(), i.dataEmissao) AS diasCorrido,
    CASE 
        WHEN TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0) > v.parcelas 
        THEN v.parcelas
        ELSE TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0)
    END AS parcPgs
FROM
    tb_itens_nfe AS i 
JOIN tb_nfe_parcelas as v  on i.id_nfe  = v.id_nfe 

WHERE
i.id_conta = :IDCONTA     
AND i.gtin = :GTIN
GROUP BY v.id_nfe
ORDER BY
    i.dataEmissao DESC
   LIMIT 2;";

  $dados1 = array( "IDCONTA" => $idconta, ":SKU" => $sku );
  $dados2 = array( "IDCONTA" => $idconta, ":GTIN" => $gtin );


  $sql = new Sql();

  // REALIZA A BUSCA PELO SKU
  $res = $sql->select( $call1, $dados1 );

  // SE NÃO ECONTRAR BUSCANDO PELO SKU, REALIZA A BUSCA PELO GTIN
  if ( empty( $res ) ) {

   $res = $sql->select( $call2, $dados2 );

  }

  if ( empty( $res ) ) {

   $res = array( "0" => array( "nf" => 0, "codigo" => 0, "gtin" => 0, "descricao" => 0, "compra" => 0, "parcelas" => 1, "dtEmissao" => 0, "diasCorrido" => 0, "parcPgs" => 0, ) );
  }
  return $res;

 }


 public function listaGtinProdFornecedor( $cnpj ) {

  $sel = "SELECT 
f.id_conta_bling,
f.codigo,
f.descricao,
f.produto_id
FROM tb_produto_fornecedor as f
JOIN tb_contatos as c on f.fornecedor_id = c.id_bling
WHERE c.cpf_cnpj = :CNPJ";

  $paran = array( ":CNPJ" => $cnpj );

  $sql = new Sql();
  $param = array( ":CNPJ" => $cnpj );

  $res = $sql->select( $sel, $param );

  if ( count( $res ) > 0 ) {
   return $res;
  } else {
   return false;
  }

 }


 public function dataCompraProduto( $idConta, $gtinProd ) {

  $sql = new Sql();

  $sel = "SELECT
    i.id_conta AS conta,
    COALESCE(i.numero, 0) AS nf,
    f.produto_id as idProdBling,
    f.codigo as ref,
    i.codigo AS codigo,
    i.gtin as gtin,
    i.descricao as descricao,
    MAX(e.saldoFisico) as estoq,
    COALESCE(i.qtde, 0) AS compra,
    v.parcelas as parcelas,
    COALESCE(i.valor, 0) AS custo,
    COALESCE(DATE(i.dataEmissao), '0000-00-00') AS dtEmissao,
    DATEDIFF(CURDATE(), i.dataEmissao) AS diasCorrido,
    CASE 
        WHEN TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0) > v.parcelas 
        THEN v.parcelas
        ELSE TRUNCATE(DATEDIFF(CURDATE(), i.dataEmissao) / 28,0)
    END AS parcPgs
FROM
    tb_itens_nfe AS i 
JOIN tb_nfe_parcelas as v       on i.id_nfe     = v.id_nfe          
JOIN tb_produto_fornecedor as f on f.descricao  = i.descricao    AND f.id_conta_bling = :IDCONTA 
JOIN tb_produtos_detalhes as d  on d.gtin       = i.gtin         AND d.id_conta_bling = :IDCONTA
JOIN tb_saldo_estoque as e      on e.produto_id = f.produto_id  
JOIN tb_depositos as dp         on dp.id_bling  = e.depositos_id AND dp.padrao = 1  
WHERE
i.id_conta = :IDCONTA
AND i.gtin = ':GTINPRODUTO'
GROUP BY v.id_nfe, i.gtin
ORDER BY
    i.dataEmissao DESC
   LIMIT 2;";

  $param = array( ":IDCONTA" => $idConta, ":GTINPRODUTO" => $gtinProd );
  $res = $sql->select( $sel, $param );

  if ( count( $res ) > 0 ) {
   return $res;
  } else {
   return false;
  }

 }


 function alteraSaldoEsoque( $idConta, $token, $idDeposito, $prodId, $prodCodigo, $qtdeAjuste, $Custo, $operador, $DePara, $contaDePara ) {

  /* ref API:
  {
    "produto": {
      "id": 12345678,
      "codigo": "12345678"
    },
    "deposito": {
      "id": 12345678
    },
    "operacao": "B",
    "preco": 1500.75,
    "custo": 1500.75,
    "quantidade": 50.75,
    "observacoes": "Observações de estoque"
  }

  */

  $sql = new Sql();
  $NomeConta = $this->getConta( $idConta );

  //echo "<br>";

  $requisicao = "";
  $operApi = "POST"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
  $recurso = "estoques";

  // $filtro  = "idFornecedor=" . $idForn[ 'id_bling' ];
  // $filtro  = "idFornecedor=16615253563";
  // $filtro  = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

  $requisicao = $recurso;

  $dadosBody = array(

   "produto" => array( "id" => $prodId, "codigo" => $prodCodigo ),
   "deposito" => array( "id" => $idDeposito ),
   "operacao" => $operador,
   "preco" => $Custo,
   "custo" => $Custo,
   "quantidade" => $qtdeAjuste,
   "observacoes" => "Lançamento via API [MV+] " . $DePara . ": " . $NomeConta );


  $dadosBody = http_build_query( $dadosBody );

  print_r( json_decode( $dadosBody ) );

  //  usleep( 333334 );

  $resp = json_decode( $this->RequestApi( $requisicao, $operApi, $token, $dadosBody ) );

  if ( !empty( $resp->data ) ) {

   //  print_r( $resp->data );  

   $this->atualizaEstoqProd( $prodId, $idConta, $token );

   $idRegistro = $resp->data->id;

   $this->SalvaRegistroEstoque( $idConta, $idRegistro, $prodId, $prodCodigo, $qtdeAjuste, $operador, $DePara, $contaDePara );

   echo "<span style='color:blue'>&#10004;</span>";

  } else {

   echo "<span style='color:red'>&#10007;</span>";

   //  print_r( $resp->error);    

  }


 }

 public function SalvaRegistroEstoque( $idConta, $idRegistro, $prodId, $prodCodigo, $qtdeAjuste, $operador, $DePara, $contaDePara ) {

  $sql = new Sql();

  $call = "call p_cad_registro_estoque(:ID_CONTA,:ID_REGISTRO,:ID_PROD,:CODIGO,:QTDE,:OPERADOR,:DEPARA,:CONTADEPRA)";
  $dados = array(
   ":ID_CONTA" => $idConta,
   ":ID_REGISTRO" => $idRegistro,
   ":ID_PROD" => $prodId,
   ":CODIGO" => $prodCodigo,
   ":QTDE" => $qtdeAjuste,
   ":OPERADOR" => $operador,
   ":DEPARA" => $DePara,
   ":CONTADEPRA" => $contaDePara );

  $res = $sql->select( $call, $dados );

  if ( count( $res ) > 0 ) {
   return true;
  } else {
   return false;
  }
 }


} //fim class
?>
