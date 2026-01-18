<?php
session_start();
require_once("../session.php"); 

require_once( '../config.php' );

$Class = new classMasterApi();
$sql   = new Sql();
$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

$IdContaToken = $Class->getIdContaToken();



?>
<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16">Contato Forncedores</div>    <hr> 
<?php


 $sql->run("TRUNCATE `tb_contatos`");


foreach($IdContaToken as $idConta => $token){
    
    $cont_ins = 0;  


    $tipoContato = $sql->select( "SELECT id_tipo_contatos FROM `tb_tipo_contatos` WHERE
                                        tb_tipo_contatos.status_app = 'A' 
                                        AND descricao_tipo_contatos LIKE '%Fornecedor%'
                                        AND tb_tipo_contatos.id_conta_bling =" . $idConta . ";" );
      
    foreach ( $tipoContato as $idTipoContato ) {


      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
      $recurso = "contatos";
      //$recurso = "contatos"; 

      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página

      // $filtro  = "idProduto=16107333481&";
      // $filtro .= "idFornecedor=12260862944&";
      // $filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));
      $filtro   = "idTipoContato=" . $idTipoContato[ 'id_tipo_contatos' ] . "?criterio=1";

      $fullDados  = array();
      $cond       = true;
      $requisicao = "";

      while ( $cond ) {

        $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;
       
          
          $dados = $Class->apiGET( $requisicao, $operApi, $token );

          if(empty($dados['data'])) {
            
          $cond = false;
        
            
            
        }  
          
          
        $fullDados[ $nPagina ] = json_decode( $dados );
       
        $nPagina++;

            //  print_r($dados);
      }
  


      foreach ( $fullDados as $dd ) {

        foreach ( $dd as $col ) {

          foreach ( $col as $lin ) {


            $ins = "CALL p_cad_contatos (
                                        :ID_CONTA_BLING,
                                        :ID_BLING,
                                        :ID_TIPO_CONTATO,
                                        :NOME,
                                        :CODIGO,
                                        :SITUACAO,
                                        :NUMERODOCUMENTO,
                                        :TELEFONE,
                                        :CELULAR

                                                    )";


            $dados = array(
       
                ":ID_CONTA_BLING"  => $idConta,
                ":ID_BLING"        => $lin->id,
                ":ID_TIPO_CONTATO" => $idTipoContato[ 'id_tipo_contatos' ],
                ":NOME"            => $lin->nome,
                ":CODIGO"          => $lin->codigo,
                ":SITUACAO"        => $lin->situacao,
                ":NUMERODOCUMENTO" => $lin->numeroDocumento,
                ":TELEFONE"        => $lin->telefone,
                ":CELULAR"         => $lin->celular

                    
            
            );


            $sql->run( $ins, $dados );
            ++$cont_ins;

          }

        }
      
    }

}
    echo "<br>";
    echo "Conta " . $Class-> getConta($idConta);
    echo "<br>";
    echo "<br>";
    echo "Total cadastrado/atualizado: " . $cont_ins;
    echo "<br>";
    echo "<br>";

  
}
?>
</div>