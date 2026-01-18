<?php
session_start();

//print_r( $_POST );

//echo "<hr>";

require_once( "config.php" );

$Class = new classMasterApi();
$sql   = new Sql();


$conta       = $_POST[ 'conta' ] ?? "";
$categoria   = $_POST[ 'categoria' ] ?? "";
$origem      = $_POST[ 'origem' ] ?? "";
$recorrencia = $_POST[ 'recorrencia' ] ?? "";
$forma_pgto  = $_POST[ 'forma_pgto' ] ?? "";
$dataIni     = $_POST[ 'dataIni' ] ?? "";
$dataFin     = $_POST[ 'dataFin' ] ?? "";

  if ( is_array( $conta ) ) {
      $conta = implode( ',', $conta );
}

  if ( is_array( $categoria ) ) {
      $categoria = implode( ',', $categoria );
      $categoria = " AND tb_despesas_categoria.id IN (".$categoria.")";
}

  if ( is_array( $origem ) ) {
      $origem = implode( "','", $origem );
      $origem = " AND tb_despesas.origem IN ('".$origem."')";
}

  if ( is_array( $recorrencia ) ) {
      $recorrencia = implode( "','", $recorrencia );
      $recorrencia = " AND tb_despesas.recorrencia IN ('".$recorrencia."')";
}  

if ( is_array( $forma_pgto ) ) {
     $forma_pgto = implode( "','", $forma_pgto );
     $forma_pgto = " AND tb_despesas.forma_pgto IN ('".$forma_pgto."')";
}  


$sel = "SELECT 
tb_despesas.id as 'idDespesa',
tb_user_api.nome_conta_bling as 'conta',
tb_despesas_categoria.categoria as 'categoria',
tb_despesas.origem as 'origem',
tb_despesas.descricao as 'descricao',
tb_despesas.tipo_valor as 'tipo',
tb_despesas.valor as 'valor',
tb_despesas.forma_pgto as 'forma_pgto',
tb_despesas.dataIni as 'dataIni',
tb_despesas.dataFin as 'dataFin',
tb_despesas.diaPgto as 'diaPgto',
tb_despesas.recorrencia as 'recorrencia',
tb_despesas.obs as 'obs'
FROM
tb_despesas
JOIN tb_user_api           ON tb_despesas.id_conta     = tb_user_api.id
JOIN tb_despesas_categoria ON tb_despesas.id_categoria = tb_despesas_categoria.id
WHERE 
tb_despesas.id_conta IN (" . $conta . ") 
AND DATE_FORMAT(tb_despesas.dataIni, '%Y-%m') <= '" . $dataIni . "'  AND DATE_FORMAT(tb_despesas.dataFin, '%Y-%m') >= '" . $dataFin . "' ";

$sel .= $categoria; 
$sel .= $origem; 
$sel .= $recorrencia; 
$sel .= $forma_pgto; 

//echo $sel;

$res = $sql->select( $sel );

?>
<link   rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>

<!--<link rel="stylesheet" href="js/datatables.css" />-->
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

<script src="js/datatables.js"></script> 
<script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json"></script> 


<script>

$(document).ready(function(){
    
    var table = new DataTable('#tblDin', {
    responsive: true,
    
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json'
    },
    order: [[0, 'asc']],
    pageLength: 100 ,
        
    /*     
    columnDefs: [
      {
            targets: [0,7],
            className: 'text-left'
        },
        {
            targets: [1,2, 3,4,5,6],
            className: 'text-center'
        },
        {
            targets: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            className: 'text-right'
        }
    ],
    
    */
    layout: {
        bottomEnd: {
            paging: {
                firstLast: false
            }
        }
    }
});
    
    
    
    
    $("#btExcliDespesa").on("click",function(){
        
  
        var idsDespesas = []; 
      
        $('input[name="chkbxExc[]"]:checked').each(function() { idsDespesas.push($(this).val()); });
        
      //  alert("Valores selecionados: " + idsDespesas.join(", ")); 

        $.post("salva_formularios.php", {
            
            func:'excluiDespesa',
            idDespesas: idsDespesas
        } ,
            function(dados){
                
                $("#notaRodape").html(dados).fadeIn();
          
            carregaListaDespesas();
                
            });
        
      });  
        
                 
   $('#chkbxExcPai').click(function() { 
       
       $('input[name="chkbxExc[]"]').prop('checked', this.checked); 
       
   }); 
            
    $('input[name="chkbxExc[]"]').click(function() { 
                 
                 if ($('input[name="chkbxExc[]"]:checked').length === $('input[name="chkbxExc[]"]').length) { 
                     
                     $('#chkbxExcPai').prop('checked', true); 
                 
                 } else { 
                         
                         $('#chkbxExcPai').prop('checked', false); 
                     } 
             });
      
});

</script>
         <div class="div_titulos">Despesas cadastradas</div>
         <div class="divMiniBloco">
            
            <table id='tblDin' align="center" cellpadding="2" style="width:100%"  class="tabela_padrao display">
                <thead>
                <tr>
                    <th align="left"><input type='checkbox' id='chkbxExcPai' title="Selecionar todos" value='' name='chkbxExc[]'></th>
                    <th align="left">Conta</th>
                    <th align="left">Categoria</th>
                    <th align="left">Origem</th>
                    <th align="left">Descrição</th>
                   
                    <th>Início</th>
                    <th>Fim</th>
                    <th align="center">Dia pgto</th>
                    <th align="center">Recorrencia</th>
                    <th>Form.Pgto</th>
                    <th>Valor</th>
                </tr>
                </thead>
                <tbody>
                
     
                <?php
                $i=0;
                $total = 0;
                foreach ( $res as $col ) {
                   $i++; 
                    
                    echo "<tr class='trClick'>";
                    echo "<td>";
                    echo $i;
                    echo "<input type='checkbox' id='chkbxExc' value='".$col['idDespesa']."' name='chkbxExc[]'>";
                   
                    echo "</td>";
                    echo "<td>".$col['conta']."</td>";
                    echo "<td>".$col['categoria']."</td>";
                    echo "<td>".$col['origem']."</td>";
                    echo "<td title='".$col['obs']."'>".$col['descricao']."</td>";
                   
                    echo "<td>".date("d/m/y",strtotime($col['dataIni']))."</td>";
                    echo "<td>".date("d/m/y",strtotime($col['dataFin']))."</td>";
                    echo "<td align='center'>".$col['diaPgto']."</td>";
                    echo "<td align='center'0>".$col['recorrencia']."</td>";
                    echo "<td>".$col['forma_pgto']."</td>";
                    echo "<td align='right'>".number_format($col['valor'],2,',','.')."</td>";
                    echo "</tr>";

                    $total +=$col['valor'];
                }
                ?>
               
                </tbody>
                <tr class="trSubtotal">
                    <td align="left"></td>
                    <td></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                     <td></td>
                     <td></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center">Total</td>
                    <td align="right"><strong><?php echo number_format($total,2,',','.'); ?></strong></td>
                </tr>
                        <tr>
                        <td colspan="3" align="left">
                            <label align="right"  class="size12 divBotaoM" id="btExcliDespesa">Excluir Selecionados</label>
                        </td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td></td>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="right">&nbsp;</td>
                    </tr>
    
            </table>
        </div>

