<?php
require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();


if(!empty($_POST['selec'])){
    
    switch ($_POST['selec']){
            
        case 'nome_fornecedor':{
            echo getNomeFornecedor($_POST[ 'conta' ]);
            break;
        }
             
             
            
    }
    
}
    
function getNomeFornecedor($valor){

      $sel_fornecedor = $sql->select( "SELECT tb_contatos.nome, tb_contatos.id_conta_bling 
                                        FROM tb_contatos
                                        JOIN tb_tipo_contatos on tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato 
                                        WHERE 
                                        tb_tipo_contatos.descricao_tipo_contatos  LIKE '%Fornecedor%'
                                        AND 
                                        tb_contatos.id_conta_bling = ".$valor." ORDER BY tb_contatos.nome ASC" );




     echo'<select name="busca" >';
        foreach($sel_fornecedor as $forn ) { 
     echo '<option value="'.$forn['nome'].'">"'.$forn['nome'].'"</option>';
        } 
    echo '</select>';

}
?>   
