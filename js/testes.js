// JavaScript Document


    function InNumCalc($v) {
        return parseFloat($v.replace(",", "."));
    }


    function OutNumCalc($v) {
        return $v.replace(".", ",");
    }

    /* FUNÇÃO PRINCIPAL PARA CALCULO DO PREÇO *************************** */

function calcPreco(id) {
    let mkp       = InNumCalc($('input[id="MrkUp"][tabindex='+id+']').val());
    let custo     = InNumCalc($('input[id="custo"][tabindex='+id+']').val());
    let preco     = InNumCalc($('input[id="preco"][tabindex='+id+']').val());
    let frete     = InNumCalc($('input[id="frete"][tabindex='+id+']').val());
    let comissao  = InNumCalc($('input[id="comissao"][tabindex='+id+']').val()); 
    let comissao2 = InNumCalc($('input[id="comissao2"][tabindex='+id+']').val()); 
    let txFixa    = InNumCalc($('input[id="txFixa"][tabindex='+id+']').val());
    let vPedMin   = InNumCalc($('input[id="vPedMin"][tabindex='+id+']').val());
    let imp       = InNumCalc($('input[id="aliq"][tabindex='+id+']').val());
    let LojaTipo  = InNumCalc($('input[id="LojaTipo"][tabindex='+id+']').val());

    // Condição para ajuste de frete e taxa fixa
    if (preco < vPedMin) {
        frete = 0;
    } else {
        txFixa = 0;
    }

    // Cálculo do preço
    preco = (custo * mkp);

    // Cálculo da comissão
    comissao = (txFixa + ((comissao / 100) * preco));

    // Imposto
    imp = ((imp / 100) * preco);

    // Margem de lucro
    let marg = ((preco - imp - comissao - frete - custo) / preco * 100);

    // Lucro e repasse
    let lucro = (preco - imp - comissao - frete - custo);
    let repasse = (preco - comissao - frete);

    // Formatação dos valores para saída
    preco   = OutNumCalc(preco.toFixed(2));
    marg    = OutNumCalc(marg.toFixed(2));
    repasse = OutNumCalc(repasse.toFixed(2));
    lucro   = OutNumCalc(lucro.toFixed(2));

    // Atualização dos campos
    $('input[id="preco"][tabindex='+id+']').val(preco);
    $('input[id="marg"][tabindex='+id+']').val(marg);
    $('input[id="lucro"][tabindex='+id+']').val(lucro);
    $('input[id="repasse"][tabindex='+id+']').val(repasse);
}

    /******  CALCULO PREÇO POR MARK-UP ***********************************************************/

function calPorMkp(id) {
    // Captura dos valores de entrada
    let custo    = InNumCalc($('input[id="custo"][tabindex=' + id + ']').val());
    let mkp      = InNumCalc($('input[id="MrkUp"][tabindex=' + id + ']').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex=' + id + ']').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex=' + id + ']').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex=' + id + ']').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex=' + id + ']').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex=' + id + ']').val());

    // Variáveis para cálculos intermediários
    let preco = custo * mkp;

    // Ajuste condicional de frete e taxa fixa
    if (preco < vPedMin) {
        frete = 0;
    } else {
        txFixa = 0;
    }

    // Cálculos de imposto, comissão, repasse, lucro e margem
    imp = (imp / 100) * preco;
    comissao = txFixa + ((comissao / 100) * preco);
    let repasse = preco - comissao - frete;
    let lucro = repasse - imp - custo;
    let marg = (lucro / preco) * 100;

    // Formatação dos valores para saída
    preco   = OutNumCalc(preco.toFixed(2));
    lucro   = OutNumCalc(lucro.toFixed(2));
    marg    = OutNumCalc(marg.toFixed(2));
    repasse = OutNumCalc(repasse.toFixed(2));

    // Atualização dos campos correspondentes
    $('input[id="preco"][tabindex=' + id + ']').val(preco);
    $('input[id="marg"][tabindex=' + id + ']').val(marg);
    $('input[id="lucro"][tabindex=' + id + ']').val(lucro);
    $('input[id="repasse"][tabindex=' + id + ']').val(repasse);
}

    /*************** QUE RECALCULO PREÇO QUANDO ALTERA O FRETE A COMISSÃO OU A TAXA FIXA ****************************/

  function recalculaPreco(id) {
    // Captura dos valores de entrada
    let custo    = InNumCalc($('input[id="custo"][tabindex=' + id + ']').val());
    let mkp      = InNumCalc($('input[id="MrkUp"][tabindex=' + id + ']').val());
    let preco    = InNumCalc($('input[id="preco"][tabindex=' + id + ']').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex=' + id + ']').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex=' + id + ']').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex=' + id + ']').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex=' + id + ']').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex=' + id + ']').val());

    // Ajusta frete ou taxa fixa com base no preço mínimo
    if (preco < vPedMin) {
        frete = 0;
    } else {
        txFixa = 0;
    }

    // Cálculo principal
    preco = custo * mkp;
    comissao = txFixa + ((comissao / 100) * preco);
    imp = (imp / 100) * preco;

    // Cálculo da margem, lucro e repasse
    let marg = ((preco - imp - comissao - frete - custo) / preco) * 100;
    let lucro = preco - imp - comissao - frete - custo;
    let repasse = preco - comissao - frete;

    // Formata os valores para saída
    preco   = OutNumCalc(preco.toFixed(2));
    marg    = OutNumCalc(marg.toFixed(2));
    lucro   = OutNumCalc(lucro.toFixed(2));
    repasse = OutNumCalc(repasse.toFixed(2));

    // Atualiza os campos
    $('input[id="preco"][tabindex=' + id + ']').val(preco);
    $('input[id="marg"][tabindex=' + id + ']').val(marg);
    $('input[id="lucro"][tabindex=' + id + ']').val(lucro);
    $('input[id="repasse"][tabindex=' + id + ']').val(repasse);
}


    /*********************  CALCULO PREÇO POR MARGEM ****************************/


function calPorMargem(id) {
    let operador = 1.3; // Valor inicial do operador
    const incremento = 0.01; // Incremento para ajustar o operador
    let preco, marg, lucro, imp, xcomissao, frete, txFixa;

    // Captura dos valores iniciais
    const mrgDef   = InNumCalc($('input[id="marg"][tabindex=' + id + ']').val()) || 0;
    const custo    = InNumCalc($('input[id="custo"][tabindex=' + id + ']').val());
    const comissao = InNumCalc($('input[id="comissao"][tabindex=' + id + ']').val());
    const aliq     = InNumCalc($('input[id="aliq"][tabindex=' + id + ']').val());
    const vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex=' + id + ']').val());

    // Garantir valores válidos para margem
    $('input[id="marg"][tabindex=' + id + ']').val(Math.min(Math.max(mrgDef, 0), 100));

    let maxIteracoes = 3000; // Número máximo de iterações
    while (true) {
        operador += incremento; // Ajusta operador iterativamente
        preco = custo * operador; // Calcula preço com base no operador

        // Ajuste de frete e taxa fixa baseado no preço mínimo
        if (preco < vPedMin) {
            txFixa = InNumCalc($('input[id="txFixa"][tabindex=' + id + ']').val());
            frete = 0;
        } else {
            txFixa = 0;
            frete = InNumCalc($('input[id="frete"][tabindex=' + id + ']').val());
        }

        // Cálculos principais
        xcomissao = txFixa + (comissao / 100) * preco; // Comissão
        imp = (aliq / 100) * preco; // Impostos
        lucro = preco - imp - xcomissao - frete - custo; // Lucro
        marg = (lucro / preco) * 100; // Margem

        const markup  = preco / custo; // Markup
        const repasse = preco - xcomissao - frete; // Repasse

        // Atualizar valores no DOM
        $('input[id="MrkUp"][tabindex=' + id + ']').val(OutNumCalc(markup.toFixed(2)));
        $('input[id="preco"][tabindex=' + id + ']').val(OutNumCalc(arredondarPrecoPersonalizado(preco).toFixed(2)));
        $('input[id="lucro"][tabindex=' + id + ']').val(OutNumCalc(lucro.toFixed(2)));
        $('input[id="repasse"][tabindex=' + id + ']').val(OutNumCalc(repasse.toFixed(2)));

        // Condição de saída do loop
        if (marg.toFixed(2) > mrgDef || --maxIteracoes <= 0) {
            break;
        }
    }

    // Caso o número máximo de iterações seja atingido
    if (maxIteracoes <= 0) {
        notaRodape("Não foi possível calcular o preço com esta margem!");
    }

    // Chama função para cálculos adicionais
    calculoPrecoDefinido(id);
}


function arredondarPrecoPersonalizado(preco) {
    
  // Converte o preço para string para facilitar a manipulação
  let partes = preco.toString().split('.');
  let inteiros = parseInt(partes[0], 10); // Parte inteira do número
  let decimais = partes[1] ? parseInt(partes[1].slice(0, 2), 10) : 0; // Parte decimal (limita a 2 dígitos)

  if (inteiros >= 30) {
    // Verifica o último dígito da parte inteira
    let ultimoDigito = inteiros % 10;

    if (ultimoDigito === 0) {
      // Se o último dígito da parte inteira for 0
      inteiros -= 1; // Reduz a parte inteira em 1
      decimais = 50; // Define as casas decimais como 50
    } else if (ultimoDigito > 5) {
      // Se o último dígito da parte inteira for maior que 5
      decimais = 50; // Ajusta as casas decimais para 50
    } else {
      // Se o último dígito da parte inteira for 5 ou menor
      decimais = 0; // Ajusta as casas decimais para 00
    }
  } else {
    // Para números menores que 30
    inteiros = Math.floor(preco); // Arredonda a parte inteira para baixo
    decimais = 90; // Ajusta as casas decimais para 90
  }

  // Adiciona a regra para mais de dois dígitos na parte inteira
  if (inteiros.toString().length > 2) {
    let ultimoDigito = inteiros % 10;

    if (ultimoDigito === 1) {
      // Se o último dígito for 1, subtrai 2
      inteiros -= 2;
    }

    decimais = 0; // Define as casas decimais como 00
  }

  // Retorna o novo preço como número
  return parseFloat(`${inteiros}.${decimais.toString().padStart(2, '0')}`);
}


    
    /************ FUNCÃO CALCULA MARGEM E MARK-UP a TRAVES DO PREÇO DEFINIDO ***************************************/

  function calculoPrecoDefinido(id) {
    // Captura dos valores de entrada
    let custo    = InNumCalc($('input[id="custo"][tabindex=' + id + ']').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex=' + id + ']').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex=' + id + ']').val()); // Correção: apenas uma declaração de "imp"
    let preco    = InNumCalc($('input[id="preco"][tabindex=' + id + ']').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex=' + id + ']').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex=' + id + ']').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex=' + id + ']').val());

    // Inicializa variáveis para cálculos
    let marg = 0, lucro = 0, mkp = 0, repasse = 0, xcomissao = 0;

    // Ajuste de frete e taxa fixa baseado no preço mínimo
    if (preco < vPedMin) {
        frete = 0;
    } else {
        txFixa = 0;
    }

    // Cálculos principais
    mkp = preco / custo; // Markup
    xcomissao = txFixa + ((comissao / 100) * preco); // Comissão
    repasse = preco - xcomissao - frete; // Repasse
    imp = preco * (imp / 100); // Impostos
    lucro = preco - imp - xcomissao - frete - custo; // Lucro
    marg = (lucro / preco) * 100; // Margem

    // Formatação dos valores
    mkp     = OutNumCalc(mkp.toFixed(2));
    lucro   = OutNumCalc(lucro.toFixed(2));
    marg    = OutNumCalc(marg.toFixed(2));
    repasse = OutNumCalc(repasse.toFixed(2));

    // Atualização dos campos no DOM
    $('input[id="marg"][tabindex=' + id + ']').val(marg);
    $('input[id="MrkUp"][tabindex=' + id + ']').val(mkp);
    $('input[id="lucro"][tabindex=' + id + ']').val(lucro);
    $('input[id="repasse"][tabindex=' + id + ']').val(repasse);
    $('input[id="confirm"][tabindex=' + id + ']').val(""); // Limpeza do campo "confirm"
}
    
 function updPreco(id) {
    // Captura dos valores de entrada
    let conta    = $('input[id="IdConta"][tabindex=' + id + ']').val();
    let loja     = $('input[id="IdLoja"][tabindex=' + id + ']').val();
    let idProd   = $('input[id="IdProd"][tabindex=' + id + ']').val();
    let custo    = InNumCalc($('input[id="custo"][tabindex=' + id + ']').val());
    let preco    = InNumCalc($('input[id="preco"][tabindex=' + id + ']').val());
    let mkp      = InNumCalc($('input[id="MrkUp"][tabindex=' + id + ']').val());

    // Verificação das condições
    if (preco <= custo || mkp < 1.1 || preco === 0) {
        
        $('input[id="confirm"][tabindex=' + id + ']').html("?"); // Mensagem mais descritiva
   
    } else {
        // Envio dos dados via AJAX
        $.post("Put_PrecoProdutoLoja.php", {
            func: 'salvaPreco',
            prod: idProd,
            preco: preco,
            conta: conta,
            loja: loja
        })
        .done(function (dados) {
            // Atualiza o campo "confirm" com a resposta do servidor
            $('input[id="confirm"][tabindex=' + id + ']').html(dados);
        })
        .fail(function (erro) {
            // Tratamento de erro na chamada AJAX
            console.error("Erro ao atualizar preço:", erro);
            $('input[id="confirm"][tabindex=' + id + ']').html("?");
        });
    }
}
    /************************************* FIM DAS FUNÇÕES ******************************************/

    $(document).ready(function () { 
        
         

    $(".btupdtpreco").on("click", function () {

            let id = $(this).attr('tabindex');

            updPreco(id);

        });

     
    
  var table = new DataTable('#tblDin', {
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json',
    },
     order:[5, 'asc'],
     pageLength: 100
});  
    

  /*******************************************************************************************************************************************************/
        $(".btXDelete").on("click", function () {
            

            if (confirm("Deseja excluir este item? ") === true) {

                let id = $(this).attr('tabindex');

               let IdAnuncio = $('input[id="IdAnuncio"][tabindex='+id+']').val();
               let idConta   = $('input[id="IdConta"][tabindex='+id+']').val();
               let idProd    = $('input[id="IdProd"][tabindex='+id+']').val();
               let idLoja    = $('input[id="IdLoja"][tabindex='+id+']').val();
               let idForn    = $('input[id="IdForn"][tabindex='+id+']').val();
                
                
                $.post("Put_PrecoProdutoLoja.php", {

                    func: 'ExcluiAnuncio',
                    post_idbling: IdAnuncio,
                    post_idconta: idConta,
                    post_idprod: idProd,
                    post_idloja: idLoja,
                    post_idforn: idForn

                }, function (dados) {

                    notaRodape(dados);

                });
            }
        });

        /*******************************************************************************************************************************************************/

        $(".cComissao").on("focus", function () {

            let id = parseInt($(this).attr("tabindex"));

           });

 
    function alteraComissao(id){
        
           recalculaPreco(id);

            let categoria = InNumCalc($('input[id="categ_prod"][tabindex='+id+']').val());
            let comissao  = InNumCalc($('input[id="comissao"][tabindex='+id+']').val());
            let comissao2 = InNumCalc($('input[id="comissao2"][tabindex='+id+']').val());
            let conta     = InNumCalc($('input[id="IdConta"][tabindex='+id+']').val());
            let loja      = InNumCalc($('input[id="IdLoja"][tabindex='+id+']').val());


            $.post("Put_PrecoProdutoLoja.php", {

                func: 'salvaComissao',
                post_categoria: categoria,
                post_comissao: comissao,
                post_comissao2: comissao2,
                post_idconta: conta,
                post_idloja: loja

            }, function (dados) {

              notaRodape(dados);
           

            });  
    }    
        
        
        
        $(".cComissao").on("change", function () {

            let id = parseInt($(this).attr("tabindex"));
            
        alteraComissao(id);


        });
        
        
        
        /*******************************************************************************************************************************************************/
        $(".cFrete").on("change", function () {

          let id = parseInt($(this).attr("tabindex"));<br>

          let frete = InNumCalc($('input[id="frete"][tabindex='+id+']').val());
  
            if (confirm("Deseja sallet alteração no valor frete? " + frete)) {
 
                let conta  = $('input[id="IdConta"][tabindex='+id+']').val();
                let loja   = $('input[id="IdLoja"][tabindex='+id+']').val();
                let idProd = $('input[id="IdAnuncio"][tabindex='+id+']').val();

                $.post("Put_PrecoProdutoLoja.php", {

                    func: 'salvaFrete',
                    post_idprod: idProd,
                    post_frete: frete,
                    post_idconta: conta,
                    post_idloja: loja

                }, function (dados) {


                    notaRodape(dados);

                    

                });
            }
        });


        /*******************************************************************************************************************************************************/


        $("#btAplicaTxFixa").on("click", function () {


          let tbindex = $('input[id="ctxFixa"][tabindex='+id+']').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;

            for (let x = 1; x <= tbindex; x++) {

                $(this).val($("#addTxFixa").val());
     
                recalculaPreco(x);
            }

        });


        $("#btAplicaComissao").on("click", function () {

            
            let tbindex = $('.cComissao').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;
            

            for (let x = 1; x <= tbindex; x++) {

                $(this).val($("#addComissao").val());

                recalculaPreco(x);
                alteraComissao(x);
            }

        });        
        
        $("#btAplicaComissao2").on("click", function () {

            let tbindex = $('.cComissao2').filter(function () {

                return $(this).attr('tabindex') !== undefined;

            }).length;

            for (let x = 1; x <= tbindex; x++) {

              $('input[id="comissao2"][tabindex='+id+']').val($("#addComissao2").val());

                recalculaPreco(x);
                alteraComissao(x);
            }

        });


        $("#btAplicaMarg").on("click", function () {

            let tbindex = $('.cMarg').filter(function () {

            return $(this).attr('tabindex') !== undefined;

            }).length;


            for (let x = 1; x <= tbindex; x++) {

                $(this).val($("#addMarg").val());

                calPorMargem(x);

            }


        });


        $("#btAplicaMrkup").on("click", function () {

            let tbindex = $('.cMkp').filter(function () {
                
                return $(this).attr('tabindex') !== undefined;
                
            }).length;

            for (let x = 1; x <= tbindex; x++) {

               $('input[id="MrkUp"][tabindex='+id+']').val($("#addMrkUp").val());

                calPorMkp(x);
            }
        });

        /**********************************************************************************************************************************************************/

        $(".cPreco").on("focus", function () {

            let id = parseInt($(this).attr("tabindex"));

            let preco = $(this).val();

            if (!$.trim(preco)) {

                calcPreco(id);
            }


        });


        $(".cPreco").on("change", function () {

            let id = parseInt($(this).attr("tabindex"));

            calculoPrecoDefinido(id);


        });

        

        $(".cMkp").on("focus", function () {

            let id = parseInt($(this).attr("tabindex"));

            $(".cMkp").on("change", function () {

                calPorMkp(id);
            });
        });


        
        $(".cMarg").on("focus", function () {

            let id = parseInt($(this).attr("tabindex"));

        
        $(this).on("change", function () {

        //    $(".cMarg").on("change", function () {
     
            let valor = parseFloat($(this).val());
                
            if (valor > 70) {
                
            $(this).val(70); // Define o valor máximo como 70
            }
                
            let id = parseInt($(this).attr("tabindex"));
                
             calPorMargem(id);

            });
        });


        $(".ctxFixa").on("focus", function () {

            let id = parseInt($(this).attr("tabindex"));

            $(".ctxFixa").on("change", function () {

                recalculaPreco(id);

            });
        });
    
/********************************************************************************************************************/
   
    
    $('.soNumero').on('input', function() {
        // Remove caracteres não permitidos (qualquer coisa que não seja número ou vírgula)
        let valor = $(this).val().replace(/[^0-9,]/g, '');
        
        $(this).val(valor);
    });

    
        
    $("#btExcliAnuncio").on("click", function () {
        
           var idAnuncios = []; 
      
        $('input[id="chkbxExc[]"]:checked').each(function() { idAnuncios.push($(this).val()); });
        
        
        if (confirm("Deseja excluir este item? ") === true) {

            var id = $(this).attr('tabindex');
 
            var IdAnuncio = $('input[id="IdAnuncio"][tabindex='+id+']').val();
            var idConta = $('input[id="IdConta"][tabindex='+id+']').val();
            var idProd = $('input[id="IdProd"][tabindex='+id+']').val();
            var idLoja = $('input[id="IdLoja"][tabindex='+id+']').val();
            var idForn = $('input[id="IdForn"][tabindex='+id+']').val();
 


            $.post("Put_PrecoProdutoLoja.php", {

                func: 'ExcluiAnuncio',
                chkbxExc:idAnuncios,
                post_idbling: IdAnuncio,
                post_idconta: idConta,
                post_idprod: idProd,
                post_idloja: idLoja,
                post_idforn: idForn

            }, function (dados) {

                    notaRodape(dados);
                 carregaAnalisePreco();

            });
        }
        
        
    });
    
    $('#chkbxExcPai').click(function() { 
       
       $('input[id="chkbxExc[]"]').prop('checked', this.checked); 
       
   }); 
            
    $('input[id="chkbxExc[]"]').click(function() { 
                 
                 if ($('input[id="chkbxExc[]"]:checked').length === $('input[id="chkbxExc[]"]').length) { 
                     
                     $('#chkbxExcPai').prop('checked', true); 
                 
                 } else { 
                         
                         $('#chkbxExcPai').prop('checked', false); 
                     } 
             });
         
      /*******************************************************************************************************************************************************/

    $("#btAtualizaTodosPrecos").on("click", function () {
        
    efeitoload('in');
    
    var tbindex = $('.ctxFixa').filter(function () {
        
        return $(this).attr('tabindex') !== undefined;
        
    }).length;

    function slowLoop(iterations, delay) {
        
        var i = 0;   

        function loop(){
            
            if (i < iterations){
                i++;
                setTimeout(function() {
                    updPreco(i);
                    loop();
                }, delay);
            } 
        }
        
        loop();
    }
    
    slowLoop(tbindex, 1000); 
        
    efeitoload('out'); 
});
    

 $('input[type="text"]').keypress(function (e) {
   
     
     if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão

        var currentId = $(this).attr('id'); // Captura o ID do elemento atual (exemplo: custo1, preco1, etc.)
        
        // Divide o ID em uma parte textual e numérica
        var idParts = currentId.match(/(\D+)(\d+)/); // Captura letras e números separadamente
        var idBase = idParts[1]; // Parte textual do ID (exemplo: 'custo', 'preco')
        var idNumber = parseInt(idParts[2]); // Parte numérica do ID (exemplo: 1, 2)

        var nextId = idBase + (idNumber + 1); // Calcula o próximo ID
        var nextElement = $('#' + nextId); // Seleciona o próximo elemento pelo ID

        if (nextElement.length > 0) {
            nextElement.select(); // Move o foco para o próximo elemento
        } else {
            // Caso não exista um próximo elemento, move para o primeiro da coluna
            var firstId = idBase + '1'; // Calcula o primeiro ID (exemplo: preco1)
            var firstElement = $('#' + firstId); // Seleciona o primeiro elemento pelo ID
            if (firstElement.length > 0) {
                firstElement.select(); // Move o foco para o primeiro elemento
            } else {
                console.log("Não há elementos disponíveis para focar.");
            }
        }
    }
});
    
    });
       
    
