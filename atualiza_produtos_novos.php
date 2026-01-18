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
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<html>
<head>
<?php require_once("conteudo_header.php");  ?>
<title>Base últimos produtos inclusos</title>
</head>
<body>
<div id="header">
 <?php require_once("header.php");  ?>
</div>
<div id="main">
<div id="divMenu">
 <?php require_once("menu.php");   ?>
</div>
<div id="divContainer">
 <div id="" class="divBlocoGrande">
  <div class="div_titulos">Produtos novos</div>
  <?php

$ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app
$dados = $Class->getIdContaToken();
     
     
function criarTabelaDinamica($produtos) {
    
    // Decodificando o JSON para um array de objetos
 //   $produtos = json_decode($produtosJson, true);

    if (empty($produtos)) {
        echo "Nenhum dado disponível.";
        return;
    }

    echo "<table border='1'>";
    
    // Cabeçalho da tabela (baseado nas chaves do primeiro produto)
    echo "<tr>";
    
    foreach (array_keys($produtos[0]) as $coluna) {
        
        echo "<th>" . htmlspecialchars($coluna) . "</th>";
    }
    echo "</tr>";
    
    // Linhas da tabela
    foreach ($produtos as $produto) {
        echo "<tr>";
        foreach ($produto as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}



 $call = "CALL p_produtos ( 
              :ID_CONTA_BLING,
              :ID_BLING,
              :NOME,
              :CODIGO,
              :PRECO,
              :TIPO,
              :SITUACAO,
              :FORMATO,
              :IMAGEMURL  )";

     
     foreach ( $dados as $idConta => $token ) {   // dados{[conta1]=>'token1'; [conta2]='tokne2')
 
            $requisicao = "";


            $fullDados = array();
            $cond = true;

            $operApi      = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
            $recurso      = "produtos";
            $nPagina      = 1; //numero páginas
            $limite       = 5; // linhas por página
            $ttl_Listados = 0;

            $filtro = "criterio=1"; // 1 = ùltimo incluso, 2 = Ativo, 3 = inativo, 4 Excluído, 5 Todos
            $filtro .= "&";
            $filtro .= "tipo=T"; // T Todos, P Produtos, S Serviços, E Composições, PS Produtos simples, C Com variações, V Variações

            //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


            //   while ( $cond ) {

            $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

            usleep( 333334 );

            $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

            if ( empty( $dados->data ) ) {

                $cond = false;

            } else {

                $fullDados[ $nPagina ] = $dados;

                $nPagina++;

            }


            //    }

            foreach ( $fullDados as $dd ) {
                   
                foreach ( $dd as $lins ) {
                    
        
                    foreach ( $lins as $lin ) {

                        if ( !empty( $lin->id ) ) {

    
                            $param = array(
                                ":ID_CONTA_BLING" => $idConta,
                                ":ID_BLING" => $lin->id,
                                ":NOME" => $lin->nome,
                                ":CODIGO" => $lin->codigo,
                                ":PRECO" => $lin->preco,
                                ":TIPO" => $lin->tipo,
                                ":SITUACAO" => $lin->situacao,
                                ":FORMATO" => $lin->formato,
                                ":IMAGEMURL" => $lin->imagemURL

                            );
                            $sql->run( $call, $param );
                            $ttl_Listados++;

                            $resPD = $Class->atualizaDetalhesProd( $lin->id, $idConta );

                            if ( $resPD ) {

                                echo "<Br>" . $lin->nome . " Cadastrado.";
                            } else {
                                echo "<Br>" . $lin->nome . "Error";
                            }
                        }
                    }
                }
            }


            echo "<Br>";

            echo "Conta " . $Class->getConta( $idConta );
            echo "<Br>";
            echo "<Br>";
            echo "Total dados castrado/atualizados " . $ttl_Listados;
            echo "<Br>";

        
    }

    ?>
 </div>
</div>
</div>
    <div id="rodape"></div>
<!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
<div id="absolut">
 <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>