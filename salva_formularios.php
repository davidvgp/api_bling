<?php

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


switch ( $_POST[ 'func' ] ) {


    case 'formSalvAliq':
        {

            $conta = $_POST[ 'conta' ];

            $month = Date( "Y-m-01", strtotime( $_POST[ 'month' ] ) );

            $aliqImp = str_replace(',','.',trim($_POST[ 'aliqImp' ]));
            
            $res = $sql->run( "call p_cad_aliqImposto(:CONTA,:MONTH,:ALIQ)", array( ":CONTA" => $conta, ":MONTH" => $month, ":ALIQ" => $aliqImp ) );
            echo "Dados salvos!<br>";
            break;
        }


    case 'salvaDescFrete':
        {

            $sql->run( "UPDATE tb_canais_de_venda SET descFrete= " . $_POST[ 'desc' ] . " WHERE id_bling = " . $_POST[ 'lojas' ] . " AND id_conta_bling = " . $_POST[ 'conta' ] );
            break;
        }


    case 'salvaTxFixaPedMin':
        {

            $x = $_POST[ 'nivel' ];
            $taxa = $_POST[ 'taxa' ];
            $PedMin = $_POST[ 'vPedMin' ];


            $sql->run( "UPDATE tb_canais_de_venda SET txFixa" . $x . "=" . $taxa . ",vPedMin" . $x . "=" . $PedMin . " WHERE id_bling = " . $_POST[ 'lojas' ] . " AND id_conta_bling = " . $_POST[ 'conta' ] );
            break;


        } 
    
    case 'salvaComissao':
        {

            $comissao = $_POST[ 'comissao' ];
            $sql->run( "UPDATE tb_canais_de_venda SET comissao=" . $comissao . " WHERE id_bling = " . $_POST[ 'lojas' ] . " AND id_conta_bling = " . $_POST[ 'conta' ] );
            break;


        }

    case 'cadastraDespesa':
        {

            $conta = $_POST[ 'conta' ] ??  " ";
            $categoria = $_POST[ 'categoria' ] ??  " ";
            $origem = $_POST[ 'origem' ] ??  " ";
            $descricao = $_POST[ 'descricao' ] ??  " ";
            $tipo = $_POST[ 'tipo' ] ??  " ";
            $valor = $_POST[ 'valor' ] ??  0.0;
            $opcao1 = $_POST[ 'opcao1' ] ??  0;            
            $forma_pgto = $_POST[ 'forma_pgto' ] ?? "";
            $diaPgto = $_POST[ 'diaPgto' ] ??  1;
            $recorrencia = $_POST[ 'recorrencia' ] ?? " ";
            $obs = $_POST[ 'obs' ] ??  " ";

    
            if($opcao1 == "S"){ $valor = $valor / 2;}
            
            
            foreach( $_POST['conta'] as $contas) {
            
            $ins = "INSERT INTO `tb_despesas`(`id_conta`, `id_categoria`, origem, `descricao`,  `tipo_valor`, `valor`, forma_pgto, `dataIni`, `dataFin`, diaPgto, `recorrencia`, `obs`)";

            $ins .= " VALUES (
            " . $contas . ",
            '" .$categoria. "',
            '" . $origem . "',
            '" . $descricao. "',
            '" . $tipo . "',
            " . str_replace(',','.',$valor). ",
            '" . $_POST[ 'forma_pgto' ] . "',
            '" . $_POST[ 'dataIni' ] . "',
            '" . $_POST[ 'dataFin' ] . "',
            " . $diaPgto . ",
            '" . $recorrencia . "',
            '" . $obs. "')";

            $sql->run( $ins );

            }

            echo "Despesa cadastrada!";

            break;


        }

    case 'excluiDespesa':
        {

            if ( is_array( $_POST[ 'idDespesas' ] ) ) {

                $ids = implode( ',', $_POST[ 'idDespesas' ] );

            }
            $sql->run( "DELETE FROM tb_despesas WHERE id IN(" . $ids . ") " );
            echo "Lançamentos excluídos.";
            break;
        }


    case 'salvaMargLucro':
        {

            $sql->run( "UPDATE tb_canais_de_venda SET margLucro = " . $_POST[ 'MargLucro' ] . " WHERE id_bling = " . $_POST[ 'lojas' ] . " AND id_conta_bling = " . $_POST[ 'conta' ] );


            break;
        }


    case 'salvaFrete':
        {

            $call = "Call p_cad_custo_frete ( :LOJA, :PESODE,:PESOATE,:VALOR)";

            $dados = array(
                ":LOJA" => $_POST[ 'lojas' ],
                ":PESODE" => $_POST[ 'pesoDe' ],
                ":PESOATE" => $_POST[ 'pesoAte' ],
                ":VALOR" => $_POST[ 'custoFrete' ]
            );

            $res = $sql->select( $call, $dados );

            foreach ( $res[ 0 ] as $key ) {

                echo $key;

            }
            break;
        }


}

?>