<?php
session_start();

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
<title>Custo Produto</title>
</head>
<body>
<body>
<div class="header">
  <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2 menu">
  <?php require_once( "menu.php" ); ?>
</div>
<div id="divContainer" class="col-10">
<?php


$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );


if ( isset( $_POST[ 'conta' ] ) ) {

  $conta = $_POST[ 'conta' ];
  $busca = $_POST[ 'busca' ];

} else {
  $busca = "";
}
?>
<div class='divBlocoGrande'>
  <div class="divMiniBloco width_50p">
    <form action="custo_prod_forn.php" class="formPadrao1" method="post" >
      <div class="div_titulos size16"> Selecionar fornecedor </div>
      <label>Contas</label>
      <select name="conta" class="input" >
        <option value="T" >Todos</option>
        <?php foreach($sel_conta as $id_conta ) { ?>
        <option  value="<?php echo $id_conta['id'];  ?>"> <?php echo $id_conta['conta'];  ?> </option>
        <?php } ?>
      </select>
      <label> Busca </label>
      <input type="text" name="busca" class="input" value="<?php echo $busca ?>" size="30">
      <input type="submit" class="input" value="Buscar">
    </form>
  </div>
</div>
<div class='divBlocoGrande'>
  <?php


    
  if ( isset( $_POST[ 'conta' ] ) && $_POST[ 'conta' ] != "T" ) {
      
              
   $token = $Class->AccessToken( $_POST[ 'conta' ]);


    $id_prod_forn = "
               SELECT tb_produto_fornecedor.id_bling as ID FROM tb_produto_fornecedor
                JOIN tb_contatos ON tb_contatos.id_bling = tb_produto_fornecedor.fornecedor_id
                WHERE
                tb_contatos.id_conta_bling = :IDCONTA
                AND
                tb_contatos.nome LIKE :BUSCA";
       
       $param = array(":IDCONTA"=>$_POST[ 'conta' ], ":BUSCA"=> '%'.$busca.'%');


    $resp = $sql->select($id_prod_forn, $param);
       
       
   //   print_r($resp) ;

    $requisicao = "";
    $ttl_Listados = 0;
       
    foreach ( $resp as $id_prod_forn ) {

      $fullDados = array();
      $cond = true;
  
      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        
     $recurso = "produtos/fornecedores/".$id_prod_forn[ 'ID' ];
   
      
        //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

        $requisicao = $recurso;
        
        usleep(300000);
        
        $dados =  json_decode($Class->apiGET( $requisicao, $operApi, $token ));

         if ( empty( $dados->data )) {

             echo "<br>Nenhum dado retornado!<br>";  
             echo $recurso;
             echo "<br>";
         } 

       if (!empty($dados->error)) {

              print_r($dados->error);        
         }
      
    
        foreach ( $dados as $lin ) {

           if (!empty( $lin->id ) ) {
                
              $instr = "CALL p_cad_prod_forn (
                                                :ID_CONTA_BLING,
                                                :ID_BLING,
                                                :DESCRICAO,
                                                :CODIGO,
                                                :PRECOCUSTO,
                                                :PRECOCOMPRA,
                                                :PADRAO,
                                                :PRODUTO_ID,
                                                :FORNECEDOR_ID
                                             )";

              $dados = array(
                ":ID_CONTA_BLING" => $_POST[ 'conta' ],
                ":ID_BLING" => $lin->id,
                ":DESCRICAO" => $lin->descricao,
                ":CODIGO" => $lin->codigo,
                ":PRECOCUSTO" => $lin->precoCusto,
                ":PRECOCOMPRA" => $lin->precoCompra,
                ":PADRAO" => $lin->padrao,
                ":PRODUTO_ID" => $lin->produto->id,
                ":FORNECEDOR_ID" => $lin->fornecedor->id

              );

              $sql->run( $instr, $dados );
              $ttl_Listados++;
             
  //   echo   $Class->atualizaEstoqProd($lin->id , $_POST[ 'conta' ]);    
           
                
            }
              
          
        }
      
    }
    echo "<hr>";
    echo "Base Produtos Fornecedor";
    echo "<Br>";
    echo "<Br>";
    echo "Conta " . $_POST[ 'conta' ];

    echo "<Br>";
    echo "Total dados castrado/atualizados " . $ttl_Listados;
    echo "<Br>";



 //   $ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_ap


    echo "<div class='divBloco'>";


    $produtos = $sql->select( "CALL p_rel_prod_estoq_custo_forn (:BUSCA,:ID_CONTA)", array( ":BUSCA" => $busca, ":ID_CONTA" => $_POST[ 'conta' ] ) );


    if ( count( $produtos ) > 0 ) {


      echo "<div class='div_titulos'></div>";
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


    echo "</div>";


    echo "</div>";
    //  }
    echo "</div>";
    echo "</div>";


  } // fim if(empyt($_post))


  ?>
</div>
</body>
</html>
