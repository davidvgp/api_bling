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
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" /> 
<link rel="stylesheet" href="css/style_menu.css" />
    
 <script src="js/jquery-3.7.1.js"></script>  
 <script src="js/js_oculta_menu.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atualiza estoque por Fornecedor</title>
</head>
<body>
<script>
    
    $(document).ready(function(){
 
  $("#conta").on("change",function() {  

   $.post("carrega_dinamicos.php", 
    {
      func: 'nome_fornecedor',   
      conta: $("#conta").val()
    
    },  function(dados){
     

    $("#fornec").html(dados); 
       
  });
    
     
}) ;    
          
        
});
    
    
    </script>
<div class="header">
  <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2 menu">
  <?php require_once("menu.php");   ?>
</div>
<div id="divContainer" class="col-10">
  <?php

  $sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );


  if ( isset( $_POST[ 'busca' ] ) ) {
     $busca = $_POST[ 'busca' ];
  } else {
    $busca = "";
  }

  if ( isset( $_POST[ 'conta' ] ) ) {

      $conta = $_POST[ 'conta' ];
    $nomeConta = $Class->getConta( $conta );
    $nomeConta = $nomeConta[ "nome_conta_bling" ];

  } else {
    $conta = "";

  }
  ?>
  <div class='divBlocoGrande'>
    <div class="divBloco">
      <form action="atualiza_estoq_forn_prod.php" method="post" >
        <label> Selecie a conta e o fornecedor para atualizar o estoque </label>
        <br>
        <select id="conta" name="conta" class="input" >
            <option value"">Conta </option>
          <?php foreach($sel_conta as $id_conta ) { ?>
          <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?> </option>
          <?php } ?>
        </select>
          <select id="fornec" name="busca" class="input" >
          <option value"">Fornecedor </option>
          
              </select>
        <input type="submit" value="Buscar" class="input">
      </form>
    </div>
  </div>
  <div class='divBlocoGrande'>
    <?php

    if ( isset( $_POST[ 'busca' ] ) ) {


      //  $ids_user_api = $Class->loadUserApi( $conta); // carrega o id_user_api, pelo id_user_ap


      echo "<div class='divBloco'>";


      $produtos = $sql->select( "CALL p_rel_prod_estoq_custo_forn (:BUSCA,:ID_CONTA_BLING)", array(
        ":BUSCA" => $busca,
        ":ID_CONTA_BLING" => $conta ) );

      //    print_r($produtos );

      //  foreach ( $ids_user_api as $id_user ) {

      $cont_dados = 0;

      $token = $Class->carregaAccessToken( $conta ); // carrega os tokens pelo id_user_api

      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página


      foreach ( $token as $ids ) {

        $accessToken = $ids[ 'access_token' ];

        $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "estoques/saldos/";
        //$recurso = "contatos"; 


        //  $filtro = "idsProdutos=14887535425 "; // 0 = inativo, 1 = ativo

        $filtro = "";
        $fullDados = array();

        $requisicao = "";


        $deposito = $sql->select( "SELECT `id_bling` as id FROM `tb_depositos` WHERE `id_conta_bling` = " . $conta . " AND `padrao` = 1" );


        foreach ( $deposito as $dep ) {

          $dep[ 'id' ];


          $produtos = $sql->select( "call p_select_idProduto_fornecedor (:BUSCA,:ID_CONTA_BLING)", array( ":BUSCA" => $busca, ":ID_CONTA_BLING" => $conta ) );


          //      print_r( $produtos);
          //     echo "<hr>";

          $sleep = 1;


          $id_prod = array();

          foreach ( $produtos as $codigos ) {

            foreach ( $codigos as $codigo => $cod ) {

              $id_prod[] = $cod;

            }

          }


          $filtro = http_build_query( array( 'idsProdutos' => $id_prod ) );


          $requisicao = $recurso . $dep[ 'id' ] . "?" . $filtro;


          $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

          $dados = json_decode( $dados );

          //    echo "<Br>"; 
          //     print_r( $dados );

          if ( empty( $dados->data ) ) {

            //     print_r( $dados );
            //      echo "<BR>";
            //     echo $ids[ 'id' ];
            //     echo "<hr>";

          }


          foreach ( $dados as $col ) {

            foreach ( $col as $lin ) {


              $cad = "CALL p_cad_saldo_estoque (
                                                :ID_CONTA_BLING,
                                                :PRODUTO_ID,
                                                :DEPOSITOS_ID,
                                                :SALDOFISICO,
                                                :SALDOVIRTUAL

                                                 )";

              $value = array(
                ":ID_CONTA_BLING" => $conta,
                ":PRODUTO_ID" => $lin->produto->id,
                ":DEPOSITOS_ID" => $dep[ 'id' ],
                ":SALDOFISICO" => $lin->saldoFisicoTotal,
                ":SALDOVIRTUAL" => $lin->saldoVirtualTotal
              );

              $sql->run( $cad, $value );

              $cont_dados++;

            }

          }


          $sleep++;

          if ( $sleep == 3 ) {
            sleep( 1 );
            $sleep = 1;
          }

        }

      }

      echo "Estoque atualizado";
      echo "<br>";
      echo "Total de itens atualziados: " . $cont_dados;
      echo "<br>";
      echo "<br>";

      echo "<div class='divBloco'>";


      $produtos = $sql->select( "CALL p_rel_prod_estoq_custo_forn (:BUSCA,:ID_CONTA_BLING)", array( ":BUSCA" => $busca, ":ID_CONTA_BLING" => $conta ) );


      if ( count( $produtos ) > 0 ) {


        echo "<div class='div_titulos'>" . $busca. "</div>";
        echo "<hr>";
        echo "<table class='tabela_padrao size12'>";

        foreach ( $produtos as $col ) {

          echo "<tr>";

          foreach ( $col as $li => $a ) {

            if ( $li <> 'Fornecedor' ) {

              echo "<th style='text-align:center;'>" . $li . "</th>";

            }

          }

          echo "</tr>";
          break;
        }

        foreach ( $produtos as $col ) {

          echo "<tr>";

          foreach ( $col as $li => $a ) {

            if ( $a == NULL ) {
              $a = 0;
            }


            if ( $li <> 'Fornecedor' ) {

              if ( $li == 'Qtde'
                or $li == 'Custo'
                or $li == 'Total' ) {

                echo "<td style='text-align:right;'>" . number_format( $a, 2, ',', '.' ) . "</td>";
              } else {
                echo "<td style='text-align:left;'>" . $a . "</td>";

              }
            }

          }
          echo "</tr>";

        }

        echo "</table>";

      } else {
        echo "nenhum resultado encontrado!";
      }


    }

    ?>
  </div>
</div>
</body>
</html>