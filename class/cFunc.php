<?php

class cFunc {

function arryNumPedido($input_string) {
    // Substituir espaços por vírgulas, se necessário
    $formatted_string = str_replace(" ", ",", $input_string);

    // Dividir a string em um array
    $numbers_array = explode(",", $formatted_string);

    // Adicionar aspas simples ao redor de cada número
    $numeroPedido = array_map(function($num) {
        return "'" . trim($num) . "'";
    }, $numbers_array);

    // Retornar o array formatado
    return $numeroPedido;
}

    
    public function atualizaPedido() {

        $cMaster = new classMasterApi();

        $conta = $_POST[ 'conta' ] ?? '';
        $idContato = '';
        $dataInicial = $_POST[ 'dataInicial' ] ?? '';
        $dataFinal   = $_POST[ 'dataFinal' ] ?? '';
        $numero = '';
        $idLoja = '';
        $atualizaPedido = $_POST[ 'atualizaPedido' ] ?? ''; // (true/false)

        $param = array( 'idContato' => $idContato,
            'numero'      => $numero,
            'dataInicial' => $dataInicial,
            'dataFinal'   => $dataFinal,
            'numero'      => $numero,
            'idLoja'      => $idLoja );


        if ( empty( $conta ) ) {

            $ids_user_api = $cMaster->loadUserApi( 1 );

            foreach ( $ids_user_api as $id_user ) {

                echo $cMaster->atualizaVenda( $id_user[ 'id' ], $param, $atualizaPedido );

            }

        } else {

            echo $cMaster->atualizaVenda( $conta, $param, $atualizaPedido );

        }

    }
    
    

    public function atualizaUmPedido() {

        $cMaster = new classMasterApi();

        $conta = $_POST[ 'conta' ] ?? '';
        $idContato = '';
     //   $dataInicial = $_POST[ 'dataInicial' ] ?? '';
    //    $dataFinal = $_POST[ 'dataFinal' ] ?? '';
        $numero = $_POST[ 'numPed' ] ?? '';
        $idLoja = '';
        $atualizaPedido = $_POST[ 'atualizaPedido' ] ?? '';
        
    //    $numero = $this->arryNumPedido($numero);
        
        $param = array( 'idContato' => $idContato,
            'numero' => trim($numero),
       //     'dataInicial' => $dataInicial,
    //        'dataFinal' => $dataFinal,
            'numero' => $numero,
            'idLoja' => $idLoja );


        if ( empty( $conta ) ) {

            $ids_user_api = $cMaster->loadUserApi( 1 );

            foreach ( $ids_user_api as $id_user ) {

                echo $cMaster->atualizaVenda( $id_user[ 'id' ], $param, $atualizaPedido );

            }

        } else {

            echo $cMaster->atualizaVenda( $conta, $param, $atualizaPedido );

        }

    }


    public function excluiPedidoCompra( $conta, $fornec ) {

        $sql = new Sql();

        $exc = "DELETE FROM tb_pedidosCompras_itens WHERE idConta IN (" . $conta . ") AND cnpj_fornec IN ('" . $fornec . "')";

        $sql->run( $exc );

        echo "Pedido excluído";

    }
    public function listaFornecedores( $conta ) {

        $sql = new Sql();

        if ( $conta == 0 ) {

            $sel = "SELECT 
            tb_tipo_contatos.descricao_tipo_contatos as 'tipo',
            tb_contatos.id_bling as 'id', 
            tb_contatos.nome as 'nome',
            tb_contatos.cpf_cnpj as 'cnpj'
            FROM tb_contatos
            JOIN tb_tipo_contatos ON tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato 
            WHERE 
            tb_tipo_contatos.descricao_tipo_contatos = 'Fornecedor verificado'
            
            GROUP BY tb_contatos.cpf_cnpj
            ORDER BY tb_contatos.nome ASC";
        } else {
            
            $sel = "SELECT 
            tb_tipo_contatos.descricao_tipo_contatos as 'tipo',
            tb_contatos.id_bling as 'id', 
            tb_contatos.nome as 'nome',
            tb_contatos.cpf_cnpj as 'cnpj'
            FROM tb_contatos
            JOIN tb_tipo_contatos ON tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato 
            WHERE 
            tb_contatos.id_conta_bling IN (" . $conta . ")
            AND tb_tipo_contatos.descricao_tipo_contatos = 'Fornecedor verificado'
            GROUP BY tb_contatos.cpf_cnpj
            ORDER BY tb_contatos.nome ASC";

        }
        $res = $sql->select( $sel );

        if ( count( $res ) > 0 ) {

            foreach ( $res as $forn ) {

                echo "<option value='" . $forn[ 'cnpj' ] . "' title='" . strtoupper( $forn[ 'nome' ] ) . "'>" . strtoupper( $forn[ 'nome' ] ) . "</option>";
            }

        } else {

            echo "<option value=''>Fornecedores não locazados</option>";
        }

    }
    public function listaFornecedoresGeral( $conta = "" ) {

        $sql = new Sql();

        if (empty($conta) || $conta == 0) {

            $sel = "SELECT 
            tb_tipo_contatos.descricao_tipo_contatos as 'tipo',
            tb_contatos.id_bling as 'id', 
            tb_contatos.nome as 'nome',
            tb_contatos.cpf_cnpj as 'cnpj'
            FROM tb_contatos
            JOIN tb_tipo_contatos ON tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato 
        
            GROUP BY tb_contatos.cpf_cnpj
            ORDER BY tb_contatos.nome ASC";
        } else {
            $sel = "SELECT 
            tb_tipo_contatos.descricao_tipo_contatos as 'tipo',
            tb_contatos.id_bling as 'id', 
            tb_contatos.nome as 'nome',
            tb_contatos.cpf_cnpj as 'cnpj'
            FROM tb_contatos
            JOIN tb_tipo_contatos ON tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato 
            WHERE tb_contatos.id_conta_bling IN (" . $conta . ")
            GROUP BY tb_contatos.cpf_cnpj
            ORDER BY tb_contatos.nome ASC";

        }
        $res = $sql->select( $sel );

        if ( count( $res ) > 0 ) {

            foreach ( $res as $forn ) {

                echo "<option value='" . $forn[ 'cnpj' ] . "'>" . strtoupper( $forn[ 'nome' ] ) . "</option>";
            }

        } else {

            echo "<option value=''>Fornecedores não locazados</option>";
        }

    }

    public function carrFornEmComum( $conta = 0) {

        $sql = new Sql();

        if ( $conta == 0 ) {

            $sel = "SELECT
                        c.id_conta_bling as conta,
                        tc.descricao_tipo_contatos AS 'tipo',
                        c.id_bling AS 'id',
                        c.nome AS 'nome',
                        c.cpf_cnpj AS 'cnpj'
                    FROM
                        tb_contatos c
                    JOIN tb_tipo_contatos tc ON tc.id_tipo_contatos = c.id_tipo_contato
                    WHERE c.cpf_cnpj IN (SELECT c2.cpf_cnpj FROM tb_contatos c2 GROUP BY c2.cpf_cnpj HAVING COUNT(*) > 1)
                    AND c.id_conta_bling IN (2)
                    GROUP BY c.cpf_cnpj
                    ORDER BY
                        c.nome ASC";
        } else {
            $sel = "SELECT
                        c.id_conta_bling as conta,
                        tc.descricao_tipo_contatos AS 'tipo',
                        c.id_bling AS 'id',
                        c.nome AS 'nome',
                        c.cpf_cnpj AS 'cnpj'
                    FROM
                        tb_contatos c
                    JOIN tb_tipo_contatos tc ON tc.id_tipo_contatos = c.id_tipo_contato
                    WHERE c.cpf_cnpj IN (SELECT c2.cpf_cnpj FROM tb_contatos c2 GROUP BY c2.cpf_cnpj HAVING COUNT(*) > 1)
                    AND c.id_conta_bling IN (" . $conta . ")
                    GROUP BY c.cpf_cnpj
                    ORDER BY
                        c.nome ASC";
        }
        $res = $sql->select( $sel );

        if ( count( $res ) > 0 ) {

            foreach ( $res as $forn ) {

                echo "<option value='" . $forn[ 'cnpj' ] . "'>" . strtoupper( $forn[ 'nome' ] ) . "</option>";
            }

        } else {

            echo "<option value=''>Fornecedores não locazados</option>";
        }

    }


    public function getStatusPedidos() {

        $sql = new Sql();

        $sel = "SELECT * FROM `tb_situacoes_modulos` WHERE id_modulo = '98310' AND `idHerdado` = '0'";


        $res = $sql->select( $sel );


        if ( count( $res ) > 0 ) {


            foreach ( $res as $stts ) {

                echo "<option value='" . $stts[ 'id_bling' ] . "'>" . $stts[ 'nome' ] . "</option>";


            }

        } else {

            echo "<option value=''>Todos</option>";
        }


    }


    public function Get_Fornecedor_Entre_Lojas() {

        $sql = new Sql();

        $sel_fornecedor = $sql->select( "SELECT tb_contatos.numeroDocumento as 'cnpj', tb_contatos.nome as 'nome', COUNT(*) FROM tb_contatos GROUP BY tb_contatos.numeroDocumento HAVING COUNT(*) = 2" );

        if ( count( $sel_fornecedor ) > 0 ) {


            foreach ( $sel_fornecedor as $forn ) {

                echo "<option value='" . $forn[ 'cnpj' ] . "'>" . strtoupper( $forn[ 'nome' ] ) . "</option>";


            }

        } else {

            echo "<option value=''>Fornecedores não encontrado</option>";
        }

    }

    public function getNomeLojas( $valor ) {

        $sql = new Sql();


        $sel = "SELECT id_bling as 'id', descricao as 'nome', tipo FROM tb_canais_de_venda WHERE situacao = 1 AND id_conta_bling IN (" . $valor . ") GROUP BY descricao ORDER BY nome ASC";


        $res = $sql->select( $sel );

        echo "<option value=''>Selecione</option>";

        if ( count( $res ) > 0 ) {

            foreach ( $res as $col ) {

                echo "<option value='" . $col[ 'id' ] . "'>" . $col[ 'nome' ] . "</option>";

            }

        } else {

            echo "<option value=''>Dado não encontrado</option>";
        }

    }


   public function getIdNomeLojas($idConta) {
    $sql = new Sql();

    $query = ($idConta == "") 
        ? "SELECT id_bling as 'id', descricao as 'nome', tipo 
           FROM tb_canais_de_venda 
           WHERE situacao = 1 
           GROUP BY id 
           ORDER BY nome ASC"
        : "SELECT id_bling as 'id', descricao as 'nome', tipo 
           FROM tb_canais_de_venda 
           WHERE situacao = 1 AND id_conta_bling IN ($idConta) 
           GROUP BY descricao 
           ORDER BY nome ASC";

    $res = $sql->select($query);

    if (count($res) > 0) {
        foreach ($res as $col) {
            echo "<option value='" . $col['id'] . "'>" . $col['nome'] . "</option>";
        }
    } else {
        echo "<option value=''>Dado não encontrado</option>";
    }
   }

    public function getNomeLojasTipo( $valor ) {

        $sql = new Sql();


        $sel1 = "SELECT id_bling as 'id', descricao as 'nome', tipo FROM tb_canais_de_venda WHERE situacao = 1 AND id_conta_bling IN (" . $valor . ") GROUP BY descricao ORDER BY nome ASC";

        $sel2 = "SELECT id_bling as 'id', descricao as 'nome', tipo FROM tb_canais_de_venda WHERE situacao = 1 GROUP BY descricao ORDER BY nome ASC";


        if ( $valor == "" ) {
            $sel = $sel2;
        } else {
            $sel = $sel1;
        }

        $res = $sql->select( $sel );


        if ( count( $res ) > 0 ) {

            foreach ( $res as $col ) {

                echo "<option value='" . $col[ 'tipo' ] . "'>" . $col[ 'nome' ] . "</option>";

            }

        } else {

            echo "<option value=''>Dado não encontrado</option>";
        }

    }


    public function getDescFreteLojas( $param ) {

        $sql = new Sql();

        $sel = $sql->select( "SELECT id_bling as 'id', descFrete FROM tb_canais_de_venda WHERE id_bling = " . $param );


        if ( count( $sel ) > 0 ) {

            foreach ( $sel as $col ) {

                echo number_format( $col[ 'descFrete' ], 0, ',', '.' );

            }

        } else {

            echo "<option value=''>Dado não encontrado</option>";
        }

    }


    public function getProdForn( $conta, $fornec ) {

        $sql = new Sql();


        $CALL = "SELECT 
tb_produtos.id_bling as 'IdProd',
tb_produtos.nome     as 'Produto',
tb_produto_fornecedor.descricao as 'Descricao',
tb_produtos.codigo   as 'SKU',
tb_produtos.preco    as 'Precço'
FROM tb_produtos
JOIN tb_produto_fornecedor on tb_produto_fornecedor.produto_id      = tb_produtos.id_bling
JOIN tb_contatos           on tb_contatos.id_bling                  = tb_produto_fornecedor.fornecedor_id
WHERE
tb_produtos.id_conta_bling IN(" . $conta . ") AND
(tb_contatos.id_bling IN('" . $fornec . "') OR tb_contatos.cpf_cnpj IN ('" . $fornec . "'))
group by tb_produtos.nome
order by tb_produto_fornecedor.descricao ASC";


        //   echo $CALL;

        $sel = $sql->select( $CALL );


        if ( count( $sel ) > 0 ) {


            foreach ( $sel as $col ) {

                echo "<option value='" . $col[ 'IdProd' ] . "' title='" . $col[ 'Produto' ] . "'>" . $col[ 'Produto' ] . "</option>";

            }

        } else {

            echo "<option value=''>Não possui PRODUTOS anunciados</option>";
        }

    }

    public function getProdFornCodigo( $conta, $fornec ) {

        $sql = new Sql();

if ( empty($conta) && empty($fornec)) {

            $call = "SELECT 
tb_produtos.id_bling as IdProd,
tb_produto_fornecedor.codigo as codigo,
tb_produtos.nome     as Produto,
tb_produto_fornecedor.descricao as Descricao,
tb_produtos.codigo   as SKU,
tb_produtos.preco    as Precço

FROM tb_produtos
JOIN tb_produto_fornecedor on tb_produto_fornecedor.produto_id      = tb_produtos.id_bling
JOIN tb_contatos           on tb_contatos.id_bling                  = tb_produto_fornecedor.fornecedor_id
group by Descricao
order by tb_produtos.nome ASC";

        } else {

            $call = "SELECT 
tb_produtos.id_bling as IdProd,
tb_produto_fornecedor.codigo as codigo,
tb_produtos.nome     as Produto,
tb_produto_fornecedor.descricao as Descricao,
tb_produtos.codigo   as SKU,
tb_produtos.preco    as Preco

FROM tb_produtos
JOIN tb_produto_fornecedor on tb_produto_fornecedor.produto_id      = tb_produtos.id_bling
JOIN tb_contatos           on tb_contatos.id_bling                  = tb_produto_fornecedor.fornecedor_id
WHERE
tb_produtos.id_conta_bling IN(" . $conta . ") AND
(tb_contatos.id_bling IN('" . $fornec . "') OR tb_contatos.cpf_cnpj IN ('" . $fornec . "'))
group by Descricao
order by tb_produtos.nome ASC";

        }

        $sel = $sql->select( $call );


        if ( count( $sel ) > 0 ) {

            foreach ( $sel as $col ) {

                echo "<option value='" . $col[ 'codigo' ] . "' title='" . $col[ 'Produto' ] . "'>" . $col[ 'Produto' ] . "</option>";

            }

        } else {

            echo "<option value=''>Não possui anuncios</option>";
        }

    } 
    
    
      public function ProdNotaEntrada( $conta, $idnota, $fornec ) {

        $sql = new Sql();

            $call = "SELECT
                    f.codigo codForn,
                    p.id_bling idProd,
                    p.codigo SKU,
                    p.gtin EAN,
                    p.nome produto,
                    f.descricao descricao
                FROM
                    tb_itens_nfe nf
                JOIN tb_produtos_detalhes  p ON p.codigo     = nf.codigo
                JOIN tb_produto_fornecedor f ON f.produto_id = p.id_bling
                JOIN tb_contatos c ON c.id_bling = f.fornecedor_id
            WHERE nf.id_conta = {$conta}  AND nf.id_nfe = {$idnota} AND c.cpf_cnpj = {$fornec} GROUP BY SKU, EAN";

echo $call;
          
    $sel = $sql->select( $call );


        if ( count( $sel ) > 0 ) {

            foreach ( $sel as $col ) {

                echo "<option value='{$col[ 'codForn' ]}' title='{$col[ 'produto' ]}'>{$col[ 'produto' ]}</option>";

            }

        } else {
            echo "<option value=''>Nota fiscal com cadastro de produto incompleto</option>";
        }

    }    
    
    
    public function getContaPara($conta) {

        $sql = new Sql();

if ( empty($conta)) {

            $call = "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1";

        } else {

            $call = "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE id NOT IN ({$conta})";

        }

        $sel = $sql->select( $call );


        if ( count( $sel ) > 0 ) {

            foreach ( $sel as $col ) {

                echo "<option value='" . $col[ 'id' ] . "' title='" . $col[ 'conta' ] . "'>" . $col[ 'conta' ] . "</option>";

            }

        } else {

            echo "<option value=''>Conta não encontrada</option>";
        }

    }


    public function getProdFornLojas( $conta, $fornec ) {

        $sql = new Sql();


        $call = "SELECT 
tb_produtos.id_bling as 'IdProd',
tb_produtos.nome     as 'Produto',
tb_produto_fornecedor.descricao as 'Descricao',
tb_produtos.codigo   as 'SKU',
tb_produtos.preco    as 'Precço'

FROM tb_produtos
JOIN tb_produto_fornecedor on tb_produto_fornecedor.produto_id      = tb_produtos.id_bling
JOIN tb_contatos           on tb_contatos.id_bling                  = tb_produto_fornecedor.fornecedor_id
WHERE
tb_produtos.id_conta_bling IN('" . $conta . "')  AND
(tb_contatos.id_bling      IN('" . $fornec . "') OR tb_contatos.cpf_cnpj IN ('" . $fornec . "'))
group by tb_produtos.nome
order by tb_produto_fornecedor.descricao ASC";

        //echo $sel;

        $sel = $sql->select( $call );


        if ( count( $sel ) > 0 ) {

            foreach ( $sel as $col ) {

                echo "<option value='" . $col[ 'IdProd' ] . "' title='" . $col[ 'Produto' ] . "'>" . $col[ 'Produto' ] . "</option>";

            }

        } else {

            echo "<option value=''>Não possui anuncios</option>";
        }

    }

    public function getProdFornUnificado( $param ) {

        $sql = new Sql();

        $sel = $sql->select( "call p_prod_forn_unificado (" . $param . ")" );


        if ( count( $sel ) > 0 ) {


            foreach ( $sel as $col ) {

                echo "<option value='" . $col[ 'codigo' ] . "' title='" . $col[ 'descricao' ] . "'>" . $col[ 'descricao' ] . "</option>";

            }

        } else {

            echo "<option value=''>Produtos não encontrados</option>";
        }

    } 
    
    public function getUltimasNfsCompras( $conta, $fornec) {

        $sql = new Sql();

        $sel = "SELECT
	n.id_conta conta,
	c.cpf_cnpj cnpj,
    n.id_nfe idnf,
    n.numero nf,
    n.contato_id id_contato,
    n.dataEmissao emissao
FROM
    `tb_nfe` n
JOIN tb_contatos c ON c.id_bling = n.contato_id
WHERE
    n.id_conta = :CONTA AND (c.id_bling = :FORNEC OR c.cpf_cnpj = :FORNEC2)
ORDER BY
    n.numero
DESC
LIMIT 20;";
        
        $param = array(":CONTA" => $conta, ":FORNEC" => $fornec, ":FORNEC2" => $fornec);
        
        
        $res = $sql->select( $sel, $param);

        if ( count( $res ) > 0 ) {

            foreach ( $res as $col ) {

                echo "<option value='" . $col[ 'idnf' ] . "'>";
                echo $col[ 'nf' ];
                echo " - ";
                echo date('d/m/y', strtotime($col[ 'emissao' ]));
                echo "</option>";

            }

        } else {

            echo "<option value=''>Não encontrada</option>";
        }

    }


    public function buscaFiltraProd( $conta, $forn, $busca ) {

        $sql = new Sql();

        $filtro = "";

        if ( !empty( $forn ) ) {

            $filtro .= " AND tb_contatos.cpf_cnpj  IN ('" . $forn . "') ";
        }

        $sel = "SELECT 
tb_produto_fornecedor.codigo   as 'codigo',
tb_produtos_detalhes.nome      as 'Produto'
FROM tb_produtos_detalhes
JOIN tb_produto_fornecedor on tb_produto_fornecedor.produto_id      = tb_produtos_detalhes.id_bling
JOIN tb_contatos           on tb_contatos.id_bling                  = tb_produto_fornecedor.fornecedor_id 
WHERE tb_produto_fornecedor.id_conta_bling IN(" . $conta . ") ";

        $sel .= $filtro;

        $sel .= " AND (
tb_produtos_detalhes.codigo 	IN(  '" . $busca . "' ) OR
tb_produtos_detalhes.gtin   	IN(  '" . $busca . "' ) OR
tb_produto_fornecedor.codigo    LIKE '%" . $busca . "%' OR 
tb_produtos_detalhes.nome       LIKE '%" . $busca . "%' OR 
tb_produto_fornecedor.descricao LIKE '%" . $busca . "%' 
)

group by tb_produtos_detalhes.nome 
order by tb_produto_fornecedor.descricao ASC;";

      //  echo $sel;

        $res = $sql->select( $sel );


        if ( count( $res ) > 0 ) {

            foreach ( $res as $col ) {

                echo "<option value='" . $col[ 'codigo' ] . "' title='" . $col[ 'Produto' ] . "'>" . $col[ 'Produto' ] . "</option>";

            }

        } else {

            echo "<option value=''>nenhum dados encontrado</option>";


        }


    }

}

?>