<?php
require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();



$idApi = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


?>

<!doctype html>
<html>
<head>
<title>Importações Automáticas</title>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 

</head>
<script>
    
    $(document).ready(function(){
        
        
 $(".bt_salvar").on("click", function(){
    
     var c = $(this).attr("id");
     
   
     $('#notaRodape').slideDown(800, function(){
         
        notaRodape("Carregando..."); 
    
    var post = $("#"+c).serialize(); 
     
   $.post("salva_config_cron.php", post,  function(dados){
     
     notaRodape(dados); 
    
     });
  
    });
     
     
       });
     
  $('.link').on('click',function() {  
   
    var lk = $(this).attr('value');
    notaRodape(lk); 
         
}) ;    
      
        
        
$("#bt_corrigir").on("click", function(){
    
     efeitoload('in');
  
     
    $.get("cron/corrige_pedidos_erro_importacao.php", function(dados){ 
     
      
     notaRodape(dados);   
         
    efeitoload('out');
    
    }); 
    
    });
});
         
    
    </script>

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
        <div class="div_titulos">Gerenciar Atividades Cron</div>

        <?php
        foreach ( $idApi as $id_user ) {
            $contas = $Class->getContas( $id_user[ 'id' ] ); // carrega os dados pelo id_user_api
            foreach ( $contas as $dados_conta ) {

                $id_conta = $dados_conta[ 'id' ];
                $nome_conta = $dados_conta[ 'nome_conta_bling' ];


                ?>
        <div id="" class="divBloco">
            <?php

            $cronHistorico = $sql->select( "SELECT venda FROM `tb_cron_historico` WHERE id_conta = " . $id_conta );

            $cronRefresh   = $sql->select( "SELECT venda FROM `tb_cron_refresh` WHERE id_conta   = " . $id_conta );


            ?>
            <div class="div_titulos">Ativar ou desativar atualização</div>
            <div class="subTitulos"> <?php echo $nome_conta; ?></div>
            <hr>
            <form id="<?php echo $id_conta; ?>" action="#" name="form1" method="POST" class="formPadrao1">
                <table>
                    <tr>
                        <td>
                            <label for="cars"><a class="link" href="#" value="cron/historico_vendas_pedidos.php">Histórido de Vendas e pedidos:</a> </label>
                        </td>
                        <td>
                            <select class="inputSizePP"  name="chv">
                                <option value="<?php echo $cronHistorico[0]['venda']??"";  ?>"> <?php echo $cronHistorico[0]['venda']??""; ?> </option>
                                <option value="S"> S </option>
                                <option value="N"> N </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cars"><a class="link" href="#" value="cron/refresh_vendas_pedidos.php">Refresh Vendas e Pedidos: </a></label>
                        </td>
                        <td>
                            <select class="inputSizePP"  name="crv">
                                <option value="<?php echo $cronRefresh[0]['venda']??"";  ?>"> <?php echo $cronRefresh[0]['venda']??"";  ?> </option>
                                <option value="S"> S </option>
                                <option value="N"> N </option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <input type="hidden" name="conta" value="<?php echo $id_conta; ?>">
                <input type="reset"  value="Redefinir" class="inputSizeP">
                <input type="button" value="Salvar" id="<?php echo $id_conta; ?>" class="inputSizeP">
            </form>
        </div>
        <?php
        }
        }

        ?>
        <div class="divBloco">
            <div class="div_titulos">Erros de Inportação</div>
            <br>
            <?php

            $sel = $sql->select( "SELECT idConta as 'conta', COUNT(*) as 'qtde' FROM `tb_PedidoErro` GROUP BY idConta" );

            if ( count( $sel ) > 0 ) {

                foreach ( $sel as $col ) {

                    echo $Class->getConta( $col[ 'conta' ] );
                    echo "<br>";
                    echo "<br>";

                    echo $col[ 'qtde' ] . " pedidos com erro na impotação";
                    echo "<hr>";
                    echo "<br>";
                }

                echo '<input type="button" id="bt_corrigir" value="Corrigir" class="formPadrao1">';


            } else {

                echo "Nenhum erro encontrado.";
            }

            ?>

        </div>
   
    </div>
    </div>
    

</div>
</div>
    <div id="rodape"></div>
    <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
    <div id="absolut">
        <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
    </div>
    <div id="notaRodape"> </div>