<?php
require_once( "session.php" );
require_once( "config.php" );

?>

<!doctype html>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<html>
<head>

    
<title>Atualiza Produto Inativos</title>    
    
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


<div id="" class="divBloco">
    
    <div class="div_titulos">Base Produtos Inativos</div>
    <?php

$Class = new classMasterApi();
$sql = new Sql();


    $ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app

    
    $dados = $Class->getIdContaToken();

     
     
    foreach ( $dados as $idConta => $token ) {   // dados{[conta1]=>'token1'; [conta2]='tokne2')
        
      atualizaItensExcluido( $idConta, $token, $_GET['filtro']);  
    }

function atualizaItensExcluido($idConta, $token, $criterio) {  
    $Class = new classMasterApi();
    $sql = new Sql();

    $fullDados = [];
    $cond = true;
    $operApi = "GET"; // Tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "produtos";
    $nPagina = 1; // Número de páginas
    $limite = 100; // Linhas por página
    $ttl_Listados = 0;

  // Definição do critério com base no filtro


    // Construção do filtro
    $filtro = "criterio=" . $criterio;
    
    // Loop para requisição e coleta de dados
    while ($cond) {
        $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;
        
        usleep(333334); // Pequena pausa para evitar sobrecarga
        
        $dados = json_decode($Class->apiGET($requisicao, $operApi, $token));

        if (empty($dados->data)) {
            $cond = false; // Encerra quando não há mais dados
        } else {
            $fullDados[$nPagina] = $dados->data; // Armazena apenas os dados relevantes
            $nPagina++;
        }
    }

    // Processamento e deleção dos itens
    foreach ($fullDados as $pagina) {
        foreach ($pagina as $lin) {
            if (!empty($lin->id)) {
                $param = [":IDPROD" => $lin->id];

                // Executando instruções de deleção
                $sql->run("DELETE FROM tb_produtos WHERE id_bling = :IDPROD", $param);
                $sql->run("DELETE FROM tb_produto_fornecedor WHERE produto_id = :IDPROD", $param);
                $sql->run("DELETE FROM tb_produtos_detalhes WHERE id_bling = :IDPROD", $param);

                $ttl_Listados++;
            }
        }
    }

        // Validação do filtro recebido  1 = ùltimo incluso, 2 = Ativo, 3 = inativo, 4 Excluído, 5 Tod 
        switch ($criterio) {
        case 1:
            $criterioDescricao = "último incluso";
            break;
        case 2:
            $criterioDescricao = "ativos";
            break;
        case 3:
            $criterioDescricao = "inativos";
            break;
        case 4:
            $criterioDescricao = "excluídos";
            break;
      default:
            $criterioDescricao = "todos";
    }
    // Exibi resultados
    echo "<br>Conta: " . $Class->getConta($idConta) . "<br>";
    echo "Total produtos {$criterioDescricao}: {$ttl_Listados}<br>";
}    
    
  /*  
    function atualizaItensExcluido($idConta, $token){  
        
        $Class = new classMasterApi();

            $requisicao = "";

            $fullDados = array();
            $cond = true;

            $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
            $recurso = "produtos";
            $nPagina = 1; //numero páginas
            $limite = 100; // linhas por página
            $ttl_Listados = 0;
            
         switch($_GET['filtro']){
                 
             case 3:
                 {
                 $criterio = "inativos";
                 break;
                 }
             case 4:
                 {
                 $criterio = "excluídos";
                 break;
                 }
         }   
            
            

            $filtro = "criterio=".$_GET['filtro']; // 1 = ùltimo incluso, 2 = Ativo, 3 = inativo, 4 Excluído, 5 Todos
            $filtro .= "&";
            $filtro .= "tipo=T"; // T Todos, P Produtos, S Serviços, E Composições, PS Produtos simples, C Com variações, V Variações

            //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


           while ( $cond ) {

            $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

            usleep(333334);

            $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

            if ( empty( $dados->data ) ) {

                $cond = false;

            } else {

                $fullDados[ $nPagina ] = $dados;

                $nPagina++;

            }


         }

        $sql = new Sql();
        
            foreach ( $fullDados as $dd ) {

                foreach ( $dd as $lins ) {

                    foreach ( $lins as $lin ) {

                        if ( !empty( $lin->id ) ) {
                            
                            $instrucao1 = "DELETE FROM tb_produtos WHERE id_bling = :IDPROD";
                            $instrucao2 = "DELETE FROM tb_produtos WHERE id_bling = :IDPROD";
                            $instrucao3 = "DELETE FROM tb_produtos_detalhes WHERE id_bling = :IDPROD";
                            
                            $param = array(":IDPROD"=>$lin->id );
                            
                        //     $sql->run($instrucao1, $param);
                            $sql->run($instrucao2+$instrucao2+$instrucao2, $param);
                        //    $sql->run($instrucao3, $param);
                            $ttl_Listados++;
   
                        }
                    }
                }
            }


            echo "<Br>";

            echo "Conta " . $Class->getConta( $idConta );
            echo "<Br>";
            echo "<Br>";
            echo "Total produtos ".$criterio." " . $ttl_Listados;
            echo "<Br>";

        }
*/
    
    
    
    
    ?>
</div>
   
 
</div>
</div>
    <div id="rodape"></div>
    
   <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
 <div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape">
 </div>   
</body>
</html>
