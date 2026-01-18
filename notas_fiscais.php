<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

    header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();
$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<title>Nostas Fiscal</title>
</head>
<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2">
    <?php require_once("menu_api.php");   ?>
</div>
<div id="divContainer" class="col-10">
    <div id="" class="divBlocoGrande">
        <?php


        $dados = $Class->getIdContaToken();

        //      print_r($dados);

        foreach ( $dados as $idConta => $token ) {


            $requisicao = "";


            $fullDados = array();
            $cond = true;

            $operApi    = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
            $recurso    = "nfe";
            $nPagina    = 1;     //numero páginas
            $limite     = 100;   // linhas por página
            $cont_dados = 1;

            $filtro  = "situacao=7"; // 7 registrada, 
            $filtro .= "&";
            $filtro .= "tipo=0";     // 0 entrada; 1 saída, 
            $filtro .= "&";
            $filtro .= "dataEmissaoInicial=2025-01-01 00:00:01";
            $filtro .= "&";
            $filtro .= "dataEmissaoFinal=2025-12-31 23:59:59";

            $natOperacao = $sql->select("SELECT `id_bling` FROM `tb_naturezasOperacao` WHERE `id_conta` = ".$idConta." AND `padrao` = 2 LIMIT 1");
             
            $idNatOper = !empty($natOperacao) ? $natOperacao[0]['id_bling'] : null;

           
            while ( $cond ) {

                $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

                usleep( 333334 );

                $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

                if ( empty( $dados->data ) ) {

                    $cond = false;
                    //print_r($dados);

                } else {

                    $fullDados[] = $dados;

                    $nPagina++;

                }

            }


            $call = "call p_cad_nfe  (  :ID_CONTA,
                                        :ID_NFE,
                                        :TIPO_NFE,
                                        :SITUACAO,
                                        :NUMERO,
                                        :DATAEMISSAO,
                                        :DATAOPERACAO,
                                        :CHAVEACESSO,
                                        :CONTATO_ID,
                                        :NATUREZAOPERACAO_ID,
                                        :LOJA_ID

                                         )";

         //   print_r($fullDados);
            
            
            foreach ( $fullDados as $dd ) {
                
                foreach ( $dd  as $col ) {
                    
                    foreach ( $col as $lin ) {

                      if($lin->naturezaOperacao->id == $idNatOper){      
           
                                     $value = array(    ":ID_CONTA" => $idConta,
                                                        ":ID_NFE" => $lin->id,
                                                        ":TIPO_NFE" => $lin->tipo,
                                                        ":SITUACAO" => $lin->situacao,
                                                        ":NUMERO" => $lin->numero,
                                                        ":DATAEMISSAO" => $lin->dataEmissao,
                                                        ":DATAOPERACAO" => $lin->dataOperacao,
                                                        ":CHAVEACESSO" => $lin->chaveAcesso,
                                                        ":CONTATO_ID" => $lin->contato->id,
                                                        ":NATUREZAOPERACAO_ID" => $lin->naturezaOperacao->id,
                                                        ":LOJA_ID" => $lin->loja->id    );
                          
                        $sql->run( $call, $value );
                          
                        $cont_dados++;
                        }
                        
                    }
                }
            }
            echo "<br>";
            echo "Conta " . $Class->getConta( $idConta );
            echo "<br>";
            echo "NFes cadastrada/atualizadas: " . $cont_dados;
            echo "<br>";
            echo "<hr>";

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