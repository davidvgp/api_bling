<?php
require_once( "session.php" );
require_once( "config.php" );


$contas = $_POST[ 'contas' ];
$func = $_POST[ 'func' ];
$produtos = $_POST[ 'produtos' ];


if ( !is_array( $contas ) ) {

    $contas = [ $contas ];

}

if ( !is_array( $produtos ) ) {

    $produtos = [ $produtos ];
}

switch ( $func ) {
        
    case "atualiza_cadastro":
        AtualizaCadastro( $contas, $produtos );
        break;
    case "atualiza_estoque":
        AtualizaEstoque( $contas, $produtos );
        break;
}

function AtualizaCadastro( $contas, $produtos ) {

    $Class = new classMasterApi();

    // traz o array associativo com os IDs e tokens access correspondentes 
    $dados = $Class->getIdContaToken();
    $sql = new Sql();

    foreach ( $contas as $idConta ) {
       
        // Verifica se o token correspondente ao idConta existe
        if ( isset( $dados[ $idConta ] ) ) {
           
            // pega o token correspondente a contaApi pelo idConta
            $token = $dados[ $idConta ];

            if ( !is_array( $produtos ) ) {

                $produtos = [ $produtos ];
            }

            
            $produtos = implode( "','", $produtos );
            
            $ProdutosFornecedor = $sql->select( "SELECT produto_id as 'ids' FROM `tb_produto_fornecedor` WHERE `codigo`  in ('" . $produtos . "') AND `id_conta_bling` =  " . $idConta );

            foreach ( $ProdutosFornecedor as $idsProd ) {
                
                $resDet = $Class->atualizaDetalhesProd( $idsProd[ 'ids' ], $idConta, $token );

                if ( $resDet[ 0 ] ) {
                    foreach ( $resDet[ 1 ] as $li ) {
                        foreach ( $li as $msg ) {
                            echo $msg . "<br>";
                        }
                    }
                    echo "<br>";
                } else {
                    echo "algo deu errado<br>";
                }
            }
        } else {
            echo "Token não encontrado para Conta ID: " . $idConta . "<br>";
        }
    }
}

function AtualizaEstoque( $contas, $produtos ) {

    $Class = new classMasterApi();
    // traz o array associativo com os IDs e tokens access correspondentes 
    $dados = $Class->getIdContaToken();

    echo "<br>";
    $sql = new Sql();

    foreach ( $contas as $idConta ) {
        // Verifica se o token correspondente ao idConta existe

        if ( isset( $dados[ $idConta ] ) ) {
            // pega o token correspondente a contaApi pelo idConta
            $token = $dados[ $idConta ];

            if ( !is_array( $produtos ) ) {

                $produtos = [ $produtos ];
            }
            $produtos = implode( "','", $produtos );

            $ProdutosFornecedor = $sql->select( "SELECT produto_id as 'ids' FROM `tb_produto_fornecedor` WHERE `codigo` in ('" . $produtos . "') AND `id_conta_bling` = " . $idConta );

            foreach ( $ProdutosFornecedor as $idsProd ) {

                $rtn = $Class->atualizaEstoqProd( $idsProd, $idConta, $token );

                foreach ( $rtn as $key ) {
                    foreach ( $key as $row ) {
                        echo implode( ', ', $row );
                    }
                }
                echo "<br>";
            }
        } else {
            echo "Token não encontrado para Conta ID: " . $idConta . "<br>";
        }
    }
}
?>
