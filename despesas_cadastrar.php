<?php
session_start();

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$categoriaDespesa = $sql->select( "SELECT * FROM tb_despesas_categoria where 1" );
$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM tb_user_api" );


?>
<link rel="stylesheet" href="css/style.css" /> 
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_pg_despesas.js"></script>
<script>
    
 $(document).ready(function () {
    
$("#exibirDespesa").on("click", function () {
        
        $("#divDespesas").slideUp();
      
        efeitoload('in');
    
        paginaDespeas("relatorio");
    
        $("#divCarregado").slideUP();


    });
   


    $("#conta").on("change", function () {
        
    var conta = $("#conta").val();

        if (conta.length > 1) {

            $("#tbOpcoes").fadeIn();
            
        } else {
            $("#tbOpcoes").fadeOut();
        }

    });


    $("#btCadastraDespesa").on("click", function () {
        
        var valor = $("#valor").val();
        
      

        $("#valor").val(InNumCalc(valor));
                
        var conta = $("#conta").val();

        if (conta.length > 0) {

            var post = $("#form1").serialize();

            efeitoload('in');


            $.post("salva_formularios.php", post, function (dados) {

                $("#notaRodape").html(dados).fadeIn();

            //    carregaListaDespesas();
                
                efeitoload('out');


            });
            
             $("#valor").val(OutNumCalc(valor));

        } else {

            alert("Selecione uma conta!");

        }

    });

 $("#conta").on("change", function () {

       // carregaListaDespesas();

    });

    

});   
    

    </script>
 <div class="div_titulos"> Cadastro de Despesas</div>
<div class="divMiniBloco">
   
  
    <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
        <table border="0" align="center" cellpadding="2" cellspacing="2">
            <tbody>
                <tr>
                    <td align="right">Conta</td>
                    <td align="left">
                        <select name="conta[]" size="3" required="required" multiple="MULTIPLE" class="inputSizeP"  id="conta" >
                            <?php foreach($sel_conta as $id_conta ) { ?>
                            <option value="<?php echo (int)$id_conta['id'];  ?>"> <?php echo $id_conta['conta'];  ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td align="left">
                        <table id="tbOpcoes" style="display:none; font-size:11px">
                            <tr>
                                <td>
                                    <input name="opcao1" type="radio" id="opcao1" value="S" >
                                </td>
                                <td>Dividir o valor entre as contas</td>
                                </t>
                            <tr>
                                <td>
                                    <input name="opcao1" type="radio" id="opcao1" value="N" checked="checked">
                                </td>
                                <td>Lançar o mesmo valor igual</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="right">Categoria</td>
                    <td colspan="2" align="left">
                        <select name="categoria" required="required" class="inputSizeP" id="categoria" title="Categoria de despesas">
                            <?php foreach($categoriaDespesa as $col ) { ?>
                            <option value="<?php echo $col['id'];  ?>" title="<?php echo $col['descricao'];  ?>"> <?php echo $col['categoria'];  ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">Origem</td>
                    <td colspan="2" align="left">
                        <input name="origem" type="text" class="inputSizeG" required="required" id="origem" value="">
                    </td>
                </tr>
                <tr>
                    <td align="right">Descrição</td>
                    <td colspan="2" align="left">
                        <input name="descricao" type="text" class="inputSizeG" required="required" id="descricao" value="">
                    </td>
                </tr>
                <tr>
                    <td align="right">Valor</td>
                    <th colspan="2" align="left">
                        <select name="tipo" id="tipo" class="inputSizePP">
                            <option value="R$">R$</option>
                            <option value="%">%</option>
                        </select>
                        <input name="valor" type="text" class="inputSizeP" id="valor" >
                    </th>
                </tr>
                <tr>
                    <td align="right">Forma Pgto</td>
                    <th colspan="2" align="left">
                        <select name="forma_pgto" class="inputSizeM" id="forma_pgto">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão Crédito">Cartão Crédito</option>
                            <option value="Pix">Pix</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Cartão Débito">Cartao Débito</option>
                            <option value="Transf. Bancária">Transf. Bancária</option>
                            <option value="MercadoPago">MercadoPago</option>
                            <option value="Caixa da Loja">Caixa da Loja</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <td align="right">Período</td>
                    <th align="left">
                        <input name="dataIni" type="date" id="dataIni" value="<?php echo date("Y-m-01");?>" >
                    </th>
                    <th align="left">até
                        <input type="date"  name="dataFin" id="dataFin" value="<?php echo date("Y-m-t");?>">
                    </th>
                </tr>
                <tr>
                    <td align="right">Recorência </td>
                    <td align="left">
                        <select name="recorrencia" id="recorrencia" class="inputSizeP" >
                            <option value="Unico">Unico</option>
                            <option value="Mensal">Mensal</option>
                            <option value="Diária">Diária</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Quinzenal">Quinzenal</option>
                            <option value="Bimestral">Bimestral</option>
                            <option value="Trimestral">Trimestral</option>
                            <option value="Semestral">Semestral</option>
                        </select>
                    </td>
                    <th align="left"> Dia do pgto
                        <input name="diaPgto" type="number" class="inputSizePP" id="diaPgto" value="<?php echo date("d");?>" max="<?php echo date("t");?>" min="1">
                    </th>
                </tr>
                <tr>
                    <td align="right">Observação</td>
                    <td colspan="2" align="left">
                        <textarea name="obs" rows="4" class="inputSizeG" id="obs"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="exibirDespesa" type="button" class="inputSizeP" id="exibirDespesa" value="Relatório">
                    </td>
                    <td colspan="2" align="right">
                        <input type="hidden" id="func" nome="func" value="cadastraDespesa">
                    
                        <input type="button" id="btCadastraDespesa" class="inputSizeP" value="Salvar">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

