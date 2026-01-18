<?php
require_once( "session.php" );
require_once( "config.php" );

$cFunc = new cFunc();
$Class = new classMasterApi();
$token = "";

if ( !empty( $_POST[ 'func' ] ) ) {

    $conta   = $_POST[ 'conta' ] ?? "";
    $contas  = $_POST[ 'contas' ] ?? "";
    $fornec = $_POST[ 'fornec' ] ?? "";
    $prod   = $_POST[ 'produtos' ] ?? "";
    $prodArry  = $_POST[ 'produtos' ] ?? "";
    $lojas  = $_POST[ 'lojas' ] ?? "";
    $numPed = $_POST[ 'numPed' ] ?? "";
    $idnota = $_POST[ 'idnota' ] ?? "";
    
    
    if ( is_array( $conta ) ) {

        $conta = implode( ',', $conta );
        $token = $Class->AccessToken($conta);

    }else{
      $token = $Class->AccessToken($conta);
    }

    if ( is_array( $fornec ) ) {

        $fornec = implode( "','", $fornec );

    }


    if ( is_array( $lojas ) ) {

        $lojas = implode( ',', $lojas );

    }  
    
    if ( is_array( $prod ) ) {

        $prod = implode( ',', $prod );

    }


    switch ( $_POST[ 'func' ] ) {

        case 'nome_fornecedor':
            {
                $cFunc->listaFornecedores( $conta );
                break;
            } 
        
        case 'atualizaUmaNota':
            {
                $Class->atualizaNFes($conta, $idnota, $token) ;
                break;
            }      
        case 'nome_fornecedorGeral':
            {
                $cFunc->listaFornecedoresGeral( $conta );
                break;
            } 
            
        case 'carrFornEmComum':
            {
                $cFunc->carrFornEmComum($conta);
                break;
            }

        case 'Get_Fornecedor_Entre_Lojas':
            {
                $cFunc->Get_Fornecedor_Entre_Lojas();
                break;
            }

        case 'produtos_fornecedor':
            {
                $cFunc->getProdForn( $conta, $fornec );
                break;
            }

        case 'prodFornLojas':
            {
                $cFunc->getProdFornLojas( $conta, $fornec );
                break;
            }
        case 'prodFornCodigo':
            {
                $cFunc->getProdFornCodigo( $conta, $fornec );
                break;
            } 
        case 'ProdNotaEntrada':
            {
                $cFunc->ProdNotaEntrada( $conta, $idnota, $fornec );
                break;
            }
        case 'contaPara':
            {
                $cFunc->getContaPara($conta);
                break;
            }

        case 'prodFornUnificado':
            {
                $cFunc->getProdFornUnificado( $fornec );
                break;
            }

        case 'FiltraProd':

            {
                $cFunc->buscaFiltraProd( $conta, $fornec, $_POST[ 'busca' ] );
                break;
            }

        case 'nome_lojas':
            {
                $cFunc->getNomeLojas( $conta );
                break;
            }
        case 'IdNomeLojas':
            {
                $cFunc->getIdNomeLojas( $conta );
                break;
            }
        case 'nome_lojas_tipo':
            {
                $cFunc->getNomeLojasTipo( $conta );
                break;
            }
        case 'UltimasNotas':
            {
                $cFunc->getUltimasNfsCompras($conta, $fornec);
                break;
            }
        case 'descFreteLoja':
            {
                $cFunc->getDescFreteLojas( $lojas );
                break;
            }


        case 'statusPedidos':
            {
                $cFunc->getStatusPedidos();
                break;
            }

        case 'atualizaPedido':

            {
                $cFunc->atualizaPedido();
                break;
            } 
        case 'atualizaUmPedido':

            {
                $cFunc->atualizaUmPedido();
                break;
            }

        case 'excluiPedidoCompra':
            {
                         
               $cFunc->excluiPedidoCompra( $conta, $fornec );
                break;

            }
            

        default:
            break;

    }


}


?>