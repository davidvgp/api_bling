  
/*
$('input[type="text"]').keypress(function (e) {
    
    if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão
        var currentTabIndex = parseInt($(this).attr('tabindex'), 10); // Captura o tabindex atual
        var $next = $('input[tabindex="' + (currentTabIndex + 1) + '"]'); // Seleciona o próximo input com o tabindex seguinte
        if ($next.length) {
            $next.focus(); // Move o foco para o próximo campo
        }
    }
});

*/

$('input[type="text"]').keypress(function (e) {
    if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão
        var currentId = $(this).attr('id'); // Captura o id atual do elemento
        var currentName = $(this).attr('name'); // Captura o name atual do elemento
        var $next = $('input[name="' + currentName + '"]').filter(function () {
            var nextId = $(this).attr('id');
            return nextId > currentId; // Seleciona o próximo elemento com id maior
        }).first(); // Obtém o primeiro elemento que corresponde
        if ($next.length) {
            $next.focus(); // Move o foco para o próximo campo
        }
    }
});




    function InNumCalc($v) {
        return parseFloat($v.replace(",", "."));
    }


    function OutNumCalc($v) {
        return $v.replace(".", ",");
    }

    /* FUNÇÃO PRINCIPAL PARA CALCULO DO PREÇO *************************** */

    function calcPreco(id) {

        let custo = InNumCalc($("#custo" + id).val());
        let mkp   = InNumCalc($("#MrkUp" + id).val());
        let preco = InNumCalc($("#preco" + id).val());
        let frete = InNumCalc($("#frete" + id).val());
        let comissao = InNumCalc($("#comissao" + id).val()); 
        let comissao2 = InNumCalc($("#comissao2" + id).val());
        let txFixa   = InNumCalc($("#txFixa" + id).val());
        let vPedMin  = InNumCalc($("#vPedMin" + id).val());
        let imp      = InNumCalc($("#aliq" + id).val());
        let LojaTipo = parseInt($("#LojaTipo" + id).val());


        if (preco < vPedMin) {
            frete = 0;
        } else {
            txFixa = 0;
        }

        preco = (custo * mkp);


        comissao = (txFixa + ((comissao / 100) * preco));
        
        comissao = (txFixa + ((comissao / 100) * preco));
        

        imp = ((imp / 100) * preco);

        let marg = ((preco - imp - comissao - frete - custo) / preco * 100);

        let lucro = (preco - imp - comissao - frete - custo);
        let repasse = (preco - comissao - frete);

        preco   = OutNumCalc(preco.toFixed(2));
        marg    = OutNumCalc(marg.toFixed(2));
        repasse = OutNumCalc(repasse.toFixed(2));
        lucro   = OutNumCalc(lucro.toFixed(2));


        $("#preco"  + id).val(preco);
        $("#marg"   + id).val(marg);
        $("#lucro"  + id).val(lucro);
        $("#repasse" + id).val(repasse);

    }

    /******  CALCULO PREÇO POR MARK-UP ***********************************************************/

    function calPorMkp(id) {

        let custo   = InNumCalc($("#custo" + id).val());
        let mkp     = InNumCalc($("#MrkUp" + id).val());
        let frete   = InNumCalc($("#frete" + id).val());
        let comissao = InNumCalc($("#comissao" + id).val());
        let txFixa  = InNumCalc($("#txFixa" + id).val());
        let vPedMin = InNumCalc($("#vPedMin" + id).val());
        let imp     = InNumCalc($("#aliq" + id).val());
        //   let desc     = InNumCalc($("#addDesc").val());

        let marg = 0;
        let lucro = 0;
        let repasse = 0;
        //    let precoDesc =  0

        let preco = (custo * mkp);


        if (preco < vPedMin) {
            frete = 0;

        } else {
            txFixa = 0;

        }

        imp = ((imp / 100) * preco);

        comissao = (txFixa + ((comissao / 100) * preco));

        repasse = (preco - comissao - frete);

        lucro = (repasse - imp - custo);
        marg = (lucro / preco) * 100;

        //  mkp   = OutNumCalc(mkp.toFixed(2));      
        lucro = OutNumCalc(lucro.toFixed(2));
        marg = OutNumCalc(marg.toFixed(2));
        preco = OutNumCalc(preco.toFixed(2));
        repasse = OutNumCalc(repasse.toFixed(2));


        //  $("#MrkUp" + id).val(mkp);
        $("#preco" + id).val(preco);
        $("#marg" + id).val(marg);
        $("#lucro" + id).val(lucro);
        $("#repasse" + id).val(repasse);


    }

    /*************** QUE RECALCULO PREÇO QUANDO ALTERA O FRETE A COMISSÃO OU A TAXA FIXA ****************************/

    function recalculaPreco(id) {

        let custo = InNumCalc($("#custo" + id).val());
        let mkp = InNumCalc($("#MrkUp" + id).val());
        let preco = InNumCalc($("#preco" + id).val());
        let frete = InNumCalc($("#frete" + id).val());
        let comissao = InNumCalc($("#comissao" + id).val());
        let txFixa = InNumCalc($("#txFixa" + id).val());
        let vPedMin = InNumCalc($("#vPedMin" + id).val());
        let imp = InNumCalc($("#aliq" + id).val());

        /*** verificar se não é possível usar as funções criadas para cada tipo de calculo de preço.********************/

        if (preco < vPedMin) {
            frete = 0;
        } else {
            txFixa = 0
        }

        preco = (custo * mkp);

        comissao = (txFixa + ((comissao / 100) * preco));
        imp = ((imp / 100) * preco);

        let marg = ((preco - imp - comissao - frete - custo) / preco * 100);
        let lucro = (preco - imp - comissao - frete - custo);
        let repasse = (preco - comissao - frete);

        preco = OutNumCalc(preco.toFixed(2));
        marg = OutNumCalc(marg.toFixed(2));
        lucro = OutNumCalc(lucro.toFixed(2));
        repasse = OutNumCalc(repasse.toFixed(2));


        $("#repasse" + id).val(repasse);
        $("#lucro" + id).val(lucro);
        $("#preco" + id).val(preco);
        $("#marg" + id).val(marg);


    }


    /*********************  CALCULO PREÇO POR MARGEM ****************************/



function calPorMargem(id) {
    let operador = 1.3;
    const incremento = 0.01;
    let preco, marg, lucro, imp, xcomissao, frete, txFixa;
    
    const mrgDef = InNumCalc($("#marg" + id).val()) || 0;
    const custo = InNumCalc($("#custo" + id).val());
    const comissao = InNumCalc($("#comissao" + id).val());
    const aliq = InNumCalc($("#aliq" + id).val());
    const vPedMin = InNumCalc($("#vPedMin" + id).val());
    
    // Garantir valores válidos para margem
    $("#marg" + id).val(Math.min(Math.max(mrgDef, 0), 100));

    let maxIteracoes = 1000; // Prevenir loops infinitos
    while (true) {
        operador += incremento;
        preco = custo * operador;

        if (preco < vPedMin) {
            txFixa = InNumCalc($("#txFixa" + id).val());
            frete = 0;
        } else {
            txFixa = 0;
            frete = InNumCalc($("#frete" + id).val());
        }

        xcomissao = txFixa + (comissao / 100) * preco;
        imp = (aliq / 100) * preco;
        lucro = preco - imp - xcomissao - frete - custo;
        marg = (lucro / preco) * 100;

        const markup = preco / custo;
        const repasse = preco - xcomissao - frete;

        // Atualizar valores no DOM
        $("#MrkUp" + id).val(OutNumCalc(markup.toFixed(2)));
        $("#preco" + id).val(OutNumCalc(preco.toFixed(2)));
        $("#lucro" + id).val(OutNumCalc(lucro.toFixed(2)));
        $("#repasse" + id).val(OutNumCalc(repasse.toFixed(2)));

        // Condição de saída
        if (marg.toFixed(2) > mrgDef || --maxIteracoes <= 0) {
            break;
        }
    }

    if (maxIteracoes <= 0) {
        
         alert("Loop foi interrompido devido ao número máximo de iterações.");
         $("#divCarregado").val("Loop foi interrompido devido ao número máximo de iterações.");
    }
}



    /************ FUNCÃO CALCULA MARGEM E MARK-UP TRAVES DO PREÇO DEFINIDO ***************************************/

    function calculoPrecoDefinido(id) {


        let custo = InNumCalc($("#custo" + id).val());
        let comissao = InNumCalc($("#comissao" + id).val());
        let imp = InNumCalc($("#aliq" + id).val());
        let preco = InNumCalc($("#preco" + id).val());
        let txFixa = InNumCalc($("#txFixa" + id).val());
        let frete = InNumCalc($("#frete" + id).val());
        let vPedMin = InNumCalc($("#vPedMin" + id).val());

        let marg = 0;
        let lucro = 0;
        let mkp = 0;
        let repasse = 0;
        let xcomissao = 0;


        if (preco < vPedMin) {
            frete = 0;
        } else {
            txFixa = 0;
        }


        mkp = (preco / custo);

        xcomissao = (txFixa + ((comissao / 100) * preco));

        repasse = (preco - xcomissao - frete);

        imp = (preco * (imp / 100));

        lucro = (preco - imp - xcomissao - frete - custo);

        marg = (lucro / preco) * 100;

        mkp = OutNumCalc(mkp.toFixed(2));
        lucro = OutNumCalc(lucro.toFixed(2));
        marg = OutNumCalc(marg.toFixed(2));
        repasse = OutNumCalc(repasse.toFixed(2));

        $("#MrkUp" + id).val(mkp);
        $("#marg" + id).val(marg);
        $("#lucro" + id).val(lucro);
        $("#repasse" + id).val(repasse);
        
        $("#confirm" + id).html("");
        


    }

    function updPreco(id) {

        let conta  = $("#IdConta" + id).val();
        let loja   = $("#IdLoja" + id).val();
        let idProd = $("#IdAnuncio" + id).val();
        let custo  = InNumCalc($("#custo" + id).val());
        let preco  = InNumCalc($("#preco" + id).val());
        let mkp    = InNumCalc($("#MrkUp" + id).val());


        if (preco <= custo || mkp < 1.1 || preco === 0) {

            $("#confirm" + id).html("?");

        } else {


            $.post("Put_PrecoProdutoLoja.php", {

                func: 'salvaPreco',
                prod: idProd,
                preco: preco,
                conta: conta,
                loja: loja

            }, function (dados) {

                $("#confirm" + id).html(dados);
                /*
                      $("#notaRodape").show("slow");
                      $("#notaRodape").html(dados);
                      $("#notaRodape").delay(15000).hide("slow");
                */

            });
        }
    }


    /************************************* FIM DAS FUNÇÕES ******************************************/


    $(document).ready(function () {


        $(".btupdtpreco").on("click", function () {

            let id = $(this).attr("title");

            updPreco(id);

        });

   
        /*******************************************************************************************************************************************************/
        $(".btXDelete").on("click", function () {

            if (confirm("Deseja excluir este item? ") === true) {

                let id = $(this).attr("title");

                let IdAnuncio = $("#IdAnuncio" + id).val();
                let idConta = $("#IdConta" + id).val();
                let idProd = $("#IdProd" + id).val();
                let idLoja = $("#IdLoja" + id).val();
                let idForn = $("#IdForn" + id).val();


                $.post("Put_PrecoProdutoLoja.php", {

                    func: 'ExcluiAnuncio',
                    post_idbling: IdAnuncio,
                    post_idconta: idConta,
                    post_idprod: idProd,
                    post_idloja: idLoja,
                    post_idforn: idForn

                }, function (dados) {

                    $("#notaRodape").show();
                    $("#notaRodape").html(dados);

                });
            }
        });

        /*******************************************************************************************************************************************************/

        $(".cComissao").on("focus", function () {

            let id = parseInt($(this).attr("title"));

            $("#comissao" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {

                    let x = (id + 1);

                    $("#comissao" + x).select();
                    
                   
                }
            });
        
            $("#comissao2" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {

                    let x = (id + 1);

                    $("#comissao2" + x).select();
                    
                    
                }
            });
            
                   
        });

        /*******************************************************************************************************************************************************/
     
        
        
        
    function alteraComissao(id){
        
           recalculaPreco(id);

            let categoria = $("#categ_prod" + id).val();
            let comissao = InNumCalc($("#comissao" + id).val());
            let comissao2 = InNumCalc($("#comissao2" + id).val());


            let conta = $("#IdConta" + id).val();
            let loja = $("#IdLoja" + id).val();


            $.post("Put_PrecoProdutoLoja.php", {

                func: 'salvaComissao',
                post_categoria: categoria,
                post_comissao: comissao,
                post_comissao2: comissao2,
                post_idconta: conta,
                post_idloja: loja

            }, function (dados) {

              $("#notaRodape").html(dados).fadeIn();
           

            });  
    }    
        
        
        
        $(".cComissao").on("change", function () {

            let id = parseInt($(this).attr("title"));
            
        alteraComissao(id);


        });
        
        
        
        /*******************************************************************************************************************************************************/
        $(".cFrete").on("change", function () {

            let id = parseInt($(this).attr("title"));

            let frete = InNumCalc($("#frete" + id).val());

            if (confirm("Deseja sallet alteração no valor frete? " + frete)) {


                let conta = parseInt($("#IdConta" + id).val());
                let loja = parseInt($("#IdLoja" + id).val());
                let idProd = $("#IdAnuncio" + id).val();

                $.post("Put_PrecoProdutoLoja.php", {

                    func: 'salvaFrete',
                    post_idprod: idProd,
                    post_frete: frete,
                    post_idconta: conta,
                    post_idloja: loja

                }, function (dados) {


                    $("#notaRodape").show("slow");
                    $("#notaRodape").html(dados);

                    $("#notaRodape").delay(7000).hide("slow");

                });
            }
        });


        /*******************************************************************************************************************************************************/

        $("#btAplicaTxFixa").on("click", function () {


            let tbindex = $('.ctxFixa').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $("#txFixa" + x).val($("#addTxFixa").val());

                recalculaPreco(x);
            }

        });


        $("#btAplicaComissao").on("click", function () {

            let tbindex = $('.cComissao').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $("#comissao" + x).val($("#addComissao").val());

                recalculaPreco(x);
                alteraComissao(x);
            }

        });        
        
        $("#btAplicaComissao2").on("click", function () {

            let tbindex = $('.cComissao').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $("#comissao2" + x).val($("#addComissao2").val());

                recalculaPreco(x);
                alteraComissao(x);
            }

        });


        $("#btAplicaMarg").on("click", function () {


            let tbindex = $('.cMarg').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;


            for (let x = 1; x <= tbindex; x++) {

                $("#marg" + x).val($("#addMarg").val());

                calPorMargem(x);

            }


        });


        $("#btAplicaMrkup").on("click", function () {

            let tbindex = $('.cMkp').filter(function () {
                return $(this).attr('tabindex') !== undefined;
            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $("#MrkUp" + x).val($("#addMrkUp").val());

                calPorMkp(x);
            }
        });

   
        
        /*$("#btAplicaComissao").on("click", function () {

            let tbindex = $('.cComissao').filter(function () {
                
                return $(this).attr('tabindex') !== undefined;
                
            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $("#comissao" + x).val($("#addComissao").val());

                calPorMkp(x);
            }
        });
*/
        /**********************************************************************************************************************************************************/


        $(".cPreco").on("focus", function () {

            let id = parseInt($(this).attr("title"));

            let preco = $("#preco" + id).val();

            if (!$.trim(preco)) {

                calcPreco(id);
            }

            $("#preco" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {
                    let x = (id + 1);
                    $("#preco" + x).select();
                }
            });


        });


        $(".cPreco").on("change", function () {

            let id = parseInt($(this).attr("title"));

            calculoPrecoDefinido(id);

            $("#preco" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {
                    let x = (id + 1);
                    $("#preco" + x).select();
                }
            });
        });


        $(".cMkp").on("focus", function () {

            let id = parseInt($(this).attr("title"));

            $("#MrkUp" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {

                    let x = (id + 1);

                    $("#MrkUp" + x).select();
                }
            });

            $(".cMkp").on("change", function () {

                calPorMkp(id);
            });
        });


        $(".cMarg").on("focus", function () {

            let id = parseInt($(this).attr("title"));

            $("#marg" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {
                    let x = (id + 1);
                    $("#marg" + x).select();
                }

            });
            
            $(this).on("change", function () {

        //    $(".cMarg").on("change", function () {
     
            let valor = parseFloat($(this).val());
                
            if (valor > 50) {
                
            $(this).val(50); // Define o valor máximo como 50
            }
                
            let id = parseInt($(this).attr("title"));

             calPorMargem(id);

            });
        });


        $(".ctxFixa").on("focus", function () {

            let id = parseInt($(this).attr("title"));

            $("#txFixa" + id).on("keyup", function (e) {

                if (e.keyCode == '13') {

                    let x = (id + 1);

                    $("#txFixa" + x).select();
                }
            });

            $(".ctxFixa").on("change", function () {

                recalculaPreco(id);

            });
        });


    }); // FECHA  $(document).ready
