<?php
require_once( "session.php" );
require_once( "config.php" );
$Class = new classMasterApi();
$sql = new Sql();

?>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 
<script>

    function InNumCalc($v) {
        return parseFloat($v.replace(",", "."));
    }


    function OutNumCalc($v) {
        return $v.replace(".", ",");
    }

    /* FUNÇÃO PRINCIPAL PARA CALCULO DO PREÇO *************************** */

function calcPreco(id) {
    let mkp       = InNumCalc($('input[id="MrkUp"][tabindex="'+id+'"]').val());
    let custo     = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    let preco     = InNumCalc($('input[id="preco"][tabindex="'+id+'"]').val());
    let frete     = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
    let comissao  = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val()); 
    let comissao2 = InNumCalc($('input[id="comissao2"][tabindex="'+id+'"]').val()); 
    let txFixa    = InNumCalc($('input[id="txFixa"][tabindex="'+id+'"]').val());
    let vPedMin   = InNumCalc($('input[id="vPedMin"][tabindex="'+id+'"]').val());
    let imp       = InNumCalc($('input[id="aliq"][tabindex="'+id+'"]').val());
    let LojaTipo  = InNumCalc($('input[id="LojaTipo"][tabindex="'+id+'"]').val());

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
    $('input[id="preco"][tabindex="'+id+'"]').val(preco);
    $('input[id="marg"][tabindex="'+id+'"]').val(marg);
    $('input[id="lucro"][tabindex="'+id+'"]').val(lucro);
    $('input[id="repasse"][tabindex="'+id+'"]').val(repasse);
    $('input[id="confirm"][tabindex="'+id+'"]').val(""); // Limpeza do campo "confirm"
}

    /******  CALCULO PREÇO POR MARK-UP ***********************************************************/

function calPorMkp(id) {
    
    // Captura dos valores de entrada
    let custo    = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    let mkp      = InNumCalc($('input[id="MrkUp"][tabindex="'+id+'"]').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex="'+id+'"]').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex="'+id+'"]').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex="'+id+'"]').val());
    

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
    $('input[id="preco"][tabindex="'+id+'"]').val(preco);
    $('input[id="marg"][tabindex="'+id+'"]').val(marg);
    $('input[id="lucro"][tabindex="'+id+'"]').val(lucro);
    $('input[id="repasse"][tabindex="'+id+'"]').val(repasse);
    $('input[id="confirm"][tabindex="'+id+'"]').val(""); // Limpeza do campo "confirm"

    
}

    /*************** QUE RECALCULO PREÇO QUANDO ALTERA O FRETE A COMISSÃO OU A TAXA FIXA ****************************/

  function recalculaPreco(id) {
    // Captura dos valores de entrada
    let custo    = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    let mkp      = InNumCalc($('input[id="MrkUp"][tabindex="'+id+'"]').val());
    let preco    = InNumCalc($('input[id="preco"][tabindex="'+id+'"]').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex="'+id+'"]').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex="'+id+'"]').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex="'+id+'"]').val());

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
    $('input[id="preco"][tabindex="'+id+'"]').val(preco);
    $('input[id="marg"][tabindex="'+id+'"]').val(marg);
    $('input[id="lucro"][tabindex="'+id+'"]').val(lucro);
    $('input[id="repasse"][tabindex="'+id+'"]').val(repasse);
    $('input[id="confirm"][tabindex="'+id+'"]').val(""); // Limpeza do campo "confirm"
}


    /*********************  CALCULO PREÇO POR MARGEM ****************************/


function calPorMargem(id) {
    let operador = 1.3; // Valor inicial do operador
    const incremento = 0.01; // Incremento para ajustar o operador
    let preco, marg, lucro, imp, xcomissao, frete, txFixa;

    // Captura dos valores iniciais
    const mrgDef   = InNumCalc($('input[id="marg"][tabindex="'+id+'"]').val()) || 0;
    const custo    = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    const comissao = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val());
    const aliq     = InNumCalc($('input[id="aliq"][tabindex="'+id+'"]').val());
    const vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex="'+id+'"]').val());

    // Garantir valores válidos para margem
    $('input[id="marg"][tabindex="'+id+'"]').val(Math.min(Math.max(mrgDef, 0), 100));

    let maxIteracoes = 3000; // Número máximo de iterações
    while (true) {
        operador += incremento; // Ajusta operador iterativamente
        preco = custo * operador; // Calcula preço com base no operador

        // Ajuste de frete e taxa fixa baseado no preço mínimo
        if (preco < vPedMin) {
            txFixa = InNumCalc($('input[id="txFixa"][tabindex="'+id+'"]').val());
            frete = 0;
        } else {
            txFixa = 0;
            frete = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
        }

        // Cálculos principais
        xcomissao = txFixa + (comissao / 100) * preco; // Comissão
        imp = (aliq / 100) * preco; // Impostos
        lucro = preco - imp - xcomissao - frete - custo; // Lucro
        marg = (lucro / preco) * 100; // Margem

        const markup  = preco / custo; // Markup
        const repasse = preco - xcomissao - frete; // Repasse

        // Atualizar valores no DOM
        $('input[id="MrkUp"][tabindex="'+id+'"]').val(OutNumCalc(markup.toFixed(2)));
        $('input[id="preco"][tabindex="'+id+'"]').val(OutNumCalc(arredondarPrecoPersonalizado(preco).toFixed(2)));
        $('input[id="lucro"][tabindex="'+id+'"]').val(OutNumCalc(lucro.toFixed(2)));
        $('input[id="repasse"][tabindex="'+id+'"]').val(OutNumCalc(repasse.toFixed(2)));

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
    let custo    = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    let comissao = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val());
    let imp      = InNumCalc($('input[id="aliq"][tabindex="'+id+'"]').val()); // Correção: apenas uma declaração de "imp"
    let preco    = InNumCalc($('input[id="preco"][tabindex="'+id+'"]').val());
    let txFixa   = InNumCalc($('input[id="txFixa"][tabindex="'+id+'"]').val());
    let frete    = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
    let vPedMin  = InNumCalc($('input[id="vPedMin"][tabindex="'+id+'"]').val());

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
    $('input[id="marg"][tabindex="'+id+'"]').val(marg);
    $('input[id="MrkUp"][tabindex="'+id+'"]').val(mkp);
    $('input[id="lucro"][tabindex="'+id+'"]').val(lucro);
    $('input[id="repasse"][tabindex="'+id+'"]').val(repasse);
    $('input[id="confirm"][tabindex="'+id+'"]').val(""); // Limpeza do campo "confirm"
}
 
    
 function updPreco(id) {
     
    // Captura dos valores de entrada
    let conta     = $('input[id="IdConta"][tabindex="'+id+'"]').val();
    let loja      = $('input[id="IdLoja"][tabindex="'+id+'"]').val();
    let idAnuncio = $('input[id="IdAnuncio"][tabindex="'+id+'"]').val();
    let custo     = InNumCalc($('input[id="custo"][tabindex="'+id+'"]').val());
    let preco     = InNumCalc($('input[id="preco"][tabindex="'+id+'"]').val());
    let mkp       = InNumCalc($('input[id="MrkUp"][tabindex="'+id+'"]').val());

    // Verificação das condições<br>
     

    if (preco <= custo || mkp < 1.1 || preco === 0) {
        
        $('#confirm'+id).html("?"); // Mensagem mais descritiva
   
            } else {

            $.post("Put_PrecoProdutoLoja.php", {

                func: 'salvaPreco',
                idAnuncio: idAnuncio,
                preco: preco,
                custo: custo,
                conta: conta,
                loja: loja

            }, function (dados) {
                
                $('#confirm'+id).html(dados);
                
            });
      }
}
  

//************************************* FIM DAS FUNÇÕES ******************************************

    
    $(document).ready(function () { 
        
     $(".btupdtpreco").on("click", function () {

            let id = $(this).attr('tabindex');

         updPreco(id);

        });

          
    $("#btAtualizaTodosPrecos").on("click", function () {
        
    efeitoload('in');
    
        
    // Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.ctxFixa').filter(function () {
        return $(this).attr('tabindex') !== undefined;
     }).length, 10);    
        

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
          
        

  //*******************************************************************************************************************************************************
      
    function alteraComissao(id){
        
           recalculaPreco(id);

            let categoria = InNumCalc($('input[id="categ_prod"][tabindex="'+id+'"]').val());
            let comissao  = InNumCalc($('input[id="comissao"][tabindex="'+id+'"]').val());
            let comissao2 = InNumCalc($('input[id="comissao2"][tabindex="'+id+'"]').val());
            let conta     = InNumCalc($('input[id="IdConta"][tabindex="'+id+'"]').val());
            let loja      = InNumCalc($('input[id="IdLoja"][tabindex="'+id+'"]').val());


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
        
    
   
  $(".cComissao").on("focus", function () {

        let id = parseInt($(this).attr("tabindex"));

   });     
        
        
        $(".cComissao").on("change", function () {

            let id = parseInt($(this).attr("tabindex"));
            
        alteraComissao(id);


        });
        
        
     
    //*******************************************************************************************************************************************************
    
        $(".cFrete").on("change", function () {

          let id = parseInt($(this).attr("tabindex"));

          let frete = InNumCalc($('input[id="frete"][tabindex="'+id+'"]').val());
  
            if (confirm("Deseja sallet alteração no valor frete? " + frete)) {
 
                let conta  = $('input[id="IdConta"][tabindex="'+id+'"]').val();
                let loja   = $('input[id="IdLoja"][tabindex="'+id+'"]').val();
                let idProd = $('input[id="IdAnuncio"][tabindex="'+id+'"]').val();

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


        //*******************************************************************************************************************************************************


 $("#btAplicaTxFixa").on("click", function () {

        // Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.ctxFixa').filter(function () {
        return $(this).attr('tabindex') !== undefined;
    }).length, 10);
        
      for (let x = 1; x <= tbindex; x++) {

            $('input[id="txFixa"][tabindex="'+x+'"]').val($("#addTxFixa").val());
     
                recalculaPreco(x);
            }

        });


$("#btAplicaComissao").on("click", function () {
    
    // Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.cComissao').filter(function () {
        return $(this).attr('tabindex') !== undefined;
    }).length, 10);


    // Itera pelos elementos encontrados
    for (let x = 1; x <= tbindex; x++) {
        // Atualiza o valor do campo de comissão
       
        $('input[id="comissao"][tabindex="'+x+'"]').val($("#addComissao").val());

        // Executa as funções necessárias
       recalculaPreco(x);
       alteraComissao(x);
    }
});  
        
        
  $("#btAplicaComissao2").on("click", function () {
    // Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.cComissao2').filter(function () {
        return $(this).attr('tabindex') !== undefined;
    }).length, 10);


    // Itera pelos elementos encontrados
    for (let x = 1; x <= tbindex; x++) {
        // Atualiza o valor do campo de comissão
       
        $('input[id="comissao2"][tabindex="'+x+'"]').val($("#addComissao2").val());

        // Executa as funções necessárias
       recalculaPreco(x);
       alteraComissao(x);
    }
});       
  

     $("#btAplicaMarg").on("click", function () {

    // Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.cMarg').filter(function () {
        return $(this).attr('tabindex') !== undefined;
    }).length, 10);



            for (let x = 1; x <= tbindex; x++) {

                $('input[id="marg"][tabindex="'+x+'"]').val($("#addMarg").val());

                calPorMargem(x);

            }


        });


    $("#btAplicaMrkup").on("click", function () {

// Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.cMkp').filter(function () {
        return $(this).attr('tabindex') !== undefined;
     }).length, 10);


            for (let x = 1; x <= tbindex; x++) {

               $('input[id="MrkUp"][tabindex="'+x+'"]').val($("#addMrkUp").val());

                calPorMkp(x);
            }
        });
        
        
    $("#btAplicaPreco").on("click", function () {

// Conta o número total de elementos com a classe cComissao e o atributo tabindex definido
    let tbindex = parseInt($('.cPreco').filter(function () {
        return $(this).attr('tabindex') !== undefined;
     }).length, 10);

     for (let x = 1; x <= tbindex; x++) {

               $('input[id="preco"][tabindex="'+x+'"]').val($("#addPreco").val());
               
               calculoPrecoDefinido(x) ; 
            }
       
    });

   
//**********************************************************************************************************************************************************

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
            
           $(this).on("change", function () {

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
   
        
//********************************************************************************************************************
   
    
    $('.soNumero').on('input', function() {
        // Remove caracteres não permitidos (qualquer coisa que não seja número ou vírgula)
        let valor = $(this).val().replace(/[^0-9,]/g, '');
        
        $(this).val(valor);
    });

    
        
  $("#btExcliAnuncio").on("click", function () {
      
    var idAnuncios = [];

    // Captura os valores marcados no checkbox
    let check = $('input[name="chkbxExc[]"]:checked').each(function() {
        idAnuncios.push($(this).val());
    });

    if (idAnuncios.length === 0) {
        alert("Nenhum item selecionado para exclusão!");
        return; // Interrompe o processo se nenhum checkbox estiver marcado
    }

    if (confirm("Deseja excluir este item? ") === true) {
       
        var id = $(this).attr('tabindex');
        $.post("Put_PrecoProdutoLoja.php", {
           
            func: 'ExcluiAnuncio',
            chkbxExc: idAnuncios   
      
        })
        .done(function (dados) {
            notaRodape(dados);
            carregaAnalisePreco();
        })
        .fail(function () {
            alert("Erro ao tentar excluir o item.");
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
 //*******************************************************************************************************************************************************

        
$('input[type="text"]').keypress(function (e) {
    if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão

        var currentId = $(this).attr('id'); // Captura o ID do elemento atual
        var currentTabindex = parseInt($(this).attr('tabindex')); // Captura o tabindex atual e o converte para inteiro

        if (!currentId || isNaN(currentTabindex)) {
            notaRodape("ID ou tabindex inválido.");
            return; // Interrompe caso o ID ou tabindex sejam inválidos
        }

        // Calcula o próximo tabindex
        var nextTabindex = currentTabindex + 1;

        // Seleciona o próximo elemento com o mesmo ID, mas tabindex incrementado
        var nextElement = $('input[id="' + currentId + '"][tabindex="' + nextTabindex + '"]');

        if (nextElement.length > 0) {
            nextElement.select(); // Move o foco para o próximo elemento
        } else {
         // volta para o primeiro elemento com tabindex = 1;   
            var nextElement = $('input[id="' + currentId + '"][tabindex="1"]');
            nextElement.select();
           
        }
    }
});
    
      
});
       
    
   
</script>
<?php

//print_r($_POST);

if ( empty( $_POST ) ) {
 echo "Nenhum dado encontrado";
 exit;
}

$conta = $_POST[ 'conta' ] ?? "";
$fornec = $_POST[ 'fornec' ] ?? "";
$prod = $_POST[ 'produtos' ] ?? "";
$lojaTipo = $_POST[ 'lojas' ] ?? "";
$opcEstoque = $_POST[ 'opcEstoque' ] ?? "";


$filtro = " WHERE ";

if ( is_array( $conta ) ) {
 $conta = implode( ',', $conta );
}
$filtro .= " tb_produto_lojas.id_conta IN ({$conta}) ";


//print_r($prod);

if ( !empty( $prod ) ) {

 if ( is_array( $prod ) ) {

  $prod = implode( "','", $prod );

  $filtro .= " AND tb_produto_fornecedor.codigo IN ('{$prod}') ";

 } else {

  $filtro .= " AND (tb_produto_fornecedor.codigo IN ('{$prod}') OR tb_produtos_detalhes.codigo IN ('{$prod}')) ";
 }
}

if ( !empty( $fornec ) ) {

 if ( is_array( $fornec ) ) {
     
  $fornec = implode( "', '", $fornec );
 }

 $filtro .= " AND tb_contatos.cpf_cnpj IN ('{$fornec}')";
    
}

if ( !empty( $lojaTipo ) ) {

 if ( is_array( $lojaTipo ) ) {

  $lojaTipo = implode( "','", $lojaTipo );
 }
 $filtro .= " AND tb_canais_de_venda.tipo IN ('{$lojaTipo}') ";
}

$exibHistVenda = $_POST[ 'histVenda' ] ?? "";

switch ( $opcEstoque ) {
 case "0":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico = 0.0 ";
  break;
 case "1":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico > 0.0 ";
  break;
 case "-1":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico < 0.0 ";
  break;
 case "T":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico > -1000 ";
  break;
 case "+5":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico > 5 ";
  break;
 case "+10":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico > 10 ";
  break;
 case "+20":
  $opcEstoque = " AND tb_saldo_estoque.saldoFisico > 20 ";
  break;
 default:
  $opcEstoque = "";
}

$call = "SELECT
    tb_produto_lojas.id_conta AS 'IdConta',
    tb_user_api.nome_conta_bling AS 'conta',
    tb_contatos.id_bling AS 'IdForn',
    tb_contatos.nome AS 'Fornecedor',
    tb_produto_lojas.id_bling AS 'IdAnuncio',
    tb_produto_lojas.loja_id AS 'IdLoja',
    tb_canais_de_venda.id_bling AS 'IdLoja',
    tb_canais_de_venda.descricao AS 'Loja',
    tb_canais_de_venda.tipo AS 'LojaTipo',
    tb_produto_lojas.produto_id AS 'IdProd',
    tb_produto_lojas.codigo AS 'CodAnuncio',
    tb_produtos_detalhes.codigo AS 'SKU',
    tb_produtos_detalhes.gtin AS 'EAN',
    tb_produtos_detalhes.nome AS 'Produto',
    tb_produto_fornecedor.descricao AS 'descricao',
    tb_depositos.descricao AS 'Deposito',
    TRUNCATE(tb_saldo_estoque.saldoFisico, 0) AS 'Estoque',
    COALESCE(tb_produto_fornecedor.precoCusto,0) AS 'Custo',
    tb_produto_fornecedor.custoVariacao AS 'CustoVar',
    tb_produto_lojas.preco AS 'Preço',
    tb_produto_lojas.precoPromocional AS 'Promocional',
    tb_produtos_detalhes.cubagem AS 'Cubage',
    tb_produtos_detalhes.categoria_id AS 'Categoria_Produto',
    tb_canais_de_venda.txFixa1 AS 'txFixa1',
    tb_canais_de_venda.vPedMin1 AS 'vPedMin1',
    tb_canais_de_venda.txFixa2 AS 'txFixa2',
    tb_canais_de_venda.vPedMin2 AS 'vPedMin2',
    tb_canais_de_venda.comissao AS 'comissao',
    tb_produto_lojas.novoPreco AS 'novoPreco',
    COALESCE(tb_produto_lojas.frete, 0) AS 'freteLoja',
    TRUNCATE(COALESCE(tb_custo_frete.valor * (1 - (tb_canais_de_venda.descFrete / 100)), 0), 2) AS 'Frete'
FROM tb_produto_lojas
JOIN tb_produtos_detalhes ON tb_produtos_detalhes.id_bling = tb_produto_lojas.produto_id
JOIN tb_produto_fornecedor ON tb_produto_fornecedor.produto_id = tb_produto_lojas.produto_id
JOIN tb_saldo_estoque ON tb_saldo_estoque.produto_id = tb_produto_lojas.produto_id
JOIN tb_canais_de_venda ON tb_canais_de_venda.id_bling = tb_produto_lojas.loja_id
LEFT JOIN tb_custo_frete ON tb_custo_frete.loja_tipo = tb_canais_de_venda.tipo
LEFT JOIN tb_depositos ON tb_depositos.id_bling = tb_saldo_estoque.depositos_id
JOIN tb_contatos ON tb_contatos.id_bling = tb_produto_fornecedor.fornecedor_id
JOIN tb_user_api ON tb_user_api.id = tb_produto_lojas.id_conta
";

$call .= $filtro;
$call .= $opcEstoque;
$call .= " AND (tb_produtos_detalhes.cubagem >= tb_custo_frete.pesoDe AND tb_produtos_detalhes.cubagem < tb_custo_frete.pesoAte) 
GROUP BY tb_produto_lojas.id_bling 
ORDER BY tb_produtos_detalhes.nome ASC, tb_user_api.nome_conta_bling ASC";

/*
echo "<hr>";
echo $call;
echo "<hr>";
*/

$res = $sql->select( $call );

if ( count( $res ) > 0 ) {


 ?>

<div class="divBlocoNV1">
<div class="divBlocoNV2">
 <div class="div_titulos">Formação de Preços</div>
 <table id=" " class="tblStyle1">
  <thead>
   <tr>
    <td colspan="4" valign="bottom">
     <label class="size12 divBotaoM" id="btExcliAnuncio">Excluir Selecionados</label>
    </td>
    <td valign="bottom">&nbsp;</td>
    <td valign="bottom">
     <div id="subTitulos"><?php echo $res[0]['Fornecedor'];  ?></div>
    </td>
    <?php  if($exibHistVenda == "S"){ ?>
    <td valign="bottom" >&nbsp;</td>
    <td valign="bottom" >&nbsp;</td>
    <td valign="bottom" >&nbsp;</td>
    <td valign="bottom" >&nbsp;</td>
    <?php }  ?>
    <td valign="bottom" title='Ultima Compra'>&nbsp;</td>
    <td valign="bottom" title='Dias corridos'>&nbsp;</td>
    <td valign="bottom" title='% estoque pago'>&nbsp;</td>
    <td valign="bottom" title='Estoque atual'>&nbsp;</td>
    <td valign="bottom" title='Custu atual'>&nbsp;</td>
    <td valign="bottom" title='Variação preço'>&nbsp;</td>
    <td valign="bottom" title='Preço atual'>&nbsp;</td>
    <td valign="bottom" title='Preço promoção'>&nbsp;</td>
    <td valign="bottom" >&nbsp;</td>
    <td align="center" valign="bottom" >
     <input type="text" size="1"  value="<?php echo $res[0]['comissao'];?>" class="addGeral" id="addComissao">
     <br>
        <div id="btAplicaComissao" class="divBotaoM">&darr;</div>
      </td>
    <td align="center" valign="bottom" >
     <input type="text" size="1"  value="10,0" class="addGeral" id="addComissao2">
     <br>
     <div id="btAplicaComissao2" class="divBotaoM">&darr;</div>
    </td>
    <td align="center" valign="bottom" >
     <input type="text" size="1"  value="6,00" class="addGeral" id="addTxFixa">
     <br>
       <div id="btAplicaTxFixa" class="divBotaoM">&darr;</div>    
    </td>
    <td align="center" valign="bottom" >
     <input type="text" size="1"  value="1,80" class="addGeral" id="addMrkUp">
     <br>
    <div id="btAplicaMrkup" class="divBotaoM"> &darr;</div> 
    </td>
    <td align="center" valign="bottom" >&nbsp;</td>
    <td align="center" valign="bottom" >
     <input type="text" size="3"  value="" class="addGeral" id="addPreco">
     <br>
      <div id="btAplicaPreco" class="divBotaoM">&darr;</div>
    </td>
    <td align="center" valign="bottom" >
     <input type="number" id="addMarg" style="width:50px; text-align:center;" size="2" max="50" min="-10" value="7">
     <br>
      <div id="btAplicaMarg" class="divBotaoM">&darr;</div>
    </td>
    <td valign="bottom" >&nbsp;</td>
    <td valign="bottom" >&nbsp;</td>
    <td colspan='2' valign="bottom">&nbsp;</td>
   </tr>
   <tr>
    <th valign="middle">
     <input type='checkbox' id='chkbxExcPai' title="Selecionar todos" value='' name='chkbxExcPai'>
    </th>
    <th valign="middle">Conta</th>
    <th valign="middle">Lojas</th>
    <th valign="middle">Anuncio</th>
    <th valign="middle">SKU</th>
    <th valign="middle">Descrição</th>
    <?php  if($exibHistVenda == "S"){   ?>
    <th valign="middle" > <?php echo $Class->mesAbrev(Date( 'm', strtotime( -3 . 'month' ) )); ?></th>
    <th valign="middle" > <?php echo $Class->mesAbrev(Date( 'm', strtotime( -2 . 'month' ) )); ?></th>
    <th valign="middle" > <?php echo $Class->mesAbrev(Date( 'm', strtotime( -1 . 'month' ) )); ?></th>
    <th valign="middle" > <?php echo $Class->mesAbrev(Date( 'm')); ?> </th>
    <?php }  ?>
    <th valign="middle" title='Ultima Compra'>Comp</th>
    <th valign="middle" title='Dias corridos'>Dias</th>
    <th valign="middle" title='% estoque pago'>Pago</th>
    <th valign="middle" title='Estoque atual'>Saldo</th>
    <th valign="middle" title='Custu atual'>Custo</th>
    <th valign="middle" title='Variação preço'>(%)</th>
    <th valign="middle" title='Preço atual'>Preço</th>
    <th valign="middle" title='Preço promoção'>Promo</th>
    <th valign="middle" >Frete</th>
    <th valign="middle" >%Comis</th>
    <th valign="middle" >%Comis</th>
    <th valign="middle" >Taxa Fixa</th>
    <th valign="middle" >MrkUp</th>
    <th valign="middle" >Imp%</th>
    <th valign="middle" >Novo Preço</th>
    <th valign="middle" >%Mrg</th>
    <th valign="middle" >LL</th>
    <th valign="middle" >Repasse</th>
    <th colspan='2' align="center"><img src="imgs/br-update-br.png" width="18" height="18" id="btAtualizaTodosPrecos"  alt="Atualizar"/> </th>
   </tr>
  </thead>
  <tbody>
   <?php

   $i = 0;
   $mkpAtual = 0;


   foreach ( $res as $col ) {

    $i++;

    echo "<tr class='trchave'>";
    //echo "<td>".$col['Fornecedor']."</td>";
    //echo "<td>".$col['IdLoja']."</td>";

    //echo "<td>".$col['IdProd']."</td>";
    echo "<td>";
    echo "<input type='hidden' id='IdConta' tabindex='{$i}' value='{$col[ 'IdConta' ]}'>";
    echo "<input type='hidden' id='IdLoja' tabindex='{$i}' value='{$col[ 'IdLoja' ]}'>";
    echo "<input type='hidden' id='LojaTipo' tabindex='{$i}' value='{$col[ 'LojaTipo' ]}'>";
    echo "<input type='hidden' id='IdForn' tabindex='{$i}' value='{$col[ 'IdForn' ]}'>";
    echo "<input type='hidden' id='IdProd' tabindex='{$i}' value='{$col[ 'IdProd' ]}'>";
    echo "<input type='hidden' id='IdAnuncio' tabindex='{$i}' value='{$col[ 'IdAnuncio' ]}'>";
    echo "<input type='checkbox' id='chkbxExc' tabindex='{$i}' value='{$col[ 'IdAnuncio' ]}' name='chkbxExc[]'>";
    echo "</td>";

    echo "<td>{$col[ 'conta' ]}</td>";

    echo "<td><img src='imgs/icon/{$col[ 'LojaTipo' ]}.svg' width='20' height='20'></td>";

    echo "<td>{$col[ 'CodAnuncio' ]}</td>";

    echo "<td>{$col[ 'SKU' ]}</td>";

    echo "<td style='text-align:left;'>{$col[ 'Produto' ]}</td>";

    //echo "<td style='text-align:left;'>{$col[ 'Produto' ]}</td>";
    //echo "<td>".$col['Deposito']."</td>";


    if ( $col[ 'freteLoja' ] > 0 ) {
     ( float )$frete = $col[ 'freteLoja' ];
    } else {
     ( float )$frete = $col[ 'Frete' ];
    }


    $txFixa = ( float )$col[ 'txFixa1' ];
    $custo = ( float )$col[ 'Custo' ];
    $custoVar = $Class->variacaoCusto( $col[ 'IdConta' ], $col[ 'IdProd' ] );
    $Preco = ( float )$col[ 'Preço' ];
    $vPedMin = ( float )$col[ 'vPedMin1' ];

    if ( $col[ 'vPedMin1' ] == "0" ) {

     $vPedMin = 100000;
    }


    $Categoria_Produto = $col[ 'Categoria_Produto' ];
    $aliq = ( float )$Class->aliqImposto( $col[ 'IdConta' ] );
    $Prom = number_format( ( float )$col[ 'Promocional' ], 2, ',', '' );
    $taxaClassico = 0;
    $taxaPremium = 0;
    $xfrete = 0;
    $xtxFixa = 0;
    $lucro = 0;


    $getComissaoLojas = $Class->taxaVendaLojas( $col[ 'IdConta' ], $col[ 'IdLoja' ], $Categoria_Produto );

    $taxaClassico = number_format( ( float )$getComissaoLojas[ 0 ][ 'classico' ], 2, ',', '' );
    $taxaPremium = number_format( ( float )$getComissaoLojas[ 0 ][ 'premium' ], 2, ',', '' );
    $taxaComissao2 = number_format( ( float )$getComissaoLojas[ 0 ][ 'comissao2' ], 2, ',', '' );

    if ( empty( $custo )or $custo < 0.1 ) {

     $custo = $Preco / 1.8;
    }


    $mkpAtual = ( $Preco / $custo );

    $promocao = $Prom;

    $mkpAtual = number_format( $mkpAtual, 3, ',', '' );

    $custo = number_format( $custo, 2, ',', '' );

    $custoVar = number_format( $custoVar, 2, ',', '' );

    $Preco = number_format( $Preco, 2, ',', '' );

    $txFixa = number_format( $txFixa, 2, ',', '' );

    $frete = number_format( $frete, 2, ',', '' );

    $aliq = number_format( $aliq, 2, ',', '' );

    if ( $exibHistVenda == "S" ) {
     $histVenda = $Class->histVendProdMesLoja( $col[ 'IdConta' ], $col[ 'IdProd' ], $col[ 'IdLoja' ] );

     $m3 = $m2 = $m1 = $m0 = $v30dias = 0;

     foreach ( $histVenda as $row ) {

      echo "<td >";
      echo $m3 = $row[ '3' ];
      echo "</td>";
      echo "<td >";
      echo $m2 = $row[ '2' ];
      echo "</td>";
      echo "<td >";
      echo $m1 = $row[ '1' ];
      echo "</td>";
      echo "<td >";
      echo $m0 = $row[ '0' ];
      echo "</td>";

     }
    }

    $data = $Class->getEstoqDataCompra( $col[ 'IdConta' ], $col[ 'EAN' ], $col[ 'SKU' ] );

    echo "<td title='" . Date( "d/m/y", strtotime( $data[ 0 ][ 'dtEmissao' ] ) ) . "' style='text-align:center;font-weight: bold;'>";
    echo $data[ 0 ][ 'compra' ]; // qtde da ultima compra
    echo "</td>";


    //   echo "<td style='text-align:center ;'>";
    //   echo  Date("d/m", strtotime($data[0]['dtEmissao'])); // data da ultima compra
    //   echo "</td>"; 

    echo "<td style='text-align:center;font-weight: bold; '>";
    echo $data[ 0 ][ 'diasCorrido' ]; // dias corrido da ultima compra
    echo "</td>";

    echo "<td style='text-align:center;font-weight: bold;'>"; // exibe o % do estoque que está pago
    echo number_format( $data[ 0 ][ 'parcPgs' ] / $data[ 0 ][ 'parcelas' ] * 100, 0 ) . "%";
    echo "</td>";


    echo "<td style='text-align:center; font-weight: bold;'>";
    echo number_format( $col[ 'Estoque' ], 0, ',', '.' );
    echo "</td>";


    echo "<td><input type='text' id='custo' tabindex='{$i}'  value='{$custo}'  class='cCusto soNumero' size='3'></td>";

    echo "<td style='text-align:left;'>{$custoVar}%</td>";

    echo "<td style='text-align:right;'><strong>{$Preco}</strong></td>";

    echo "<td   style='text-align:right;'>{$Prom}</td>";

    //echo "<td >{number_format( $col[ 'Cubage' ], 2, ',', '.' )}</td>";

    echo "<td><input type='text' id='frete'    tabindex='{$i}'  value='{$frete}'class='cFrete soNumero' size='2'></td>";

    echo "<td>";
    echo "<input type='hidden' id='categ_prod' tabindex='{$i}'  value='{$Categoria_Produto}'  class='soNumero' size='2'>";

    echo "<input type='text'   id='comissao'   tabindex='{$i}'  value='{$taxaClassico}'  class='cComissao soNumero' size='2'>";
    echo "</td>";

    echo "<td><input type='text' id='comissao2'    tabindex='{$i}'  value='{$taxaComissao2}'  class='cComissao2 soNumero ' size='2'></td>";

    echo "<td><input type='hidden' id='vPedMin'   tabindex='{$i}'  value='{$vPedMin}'  class='cvPedMin' size='2'>";
    echo "<input type='text'   id='txFixa'    tabindex='{$i}'  value='{$txFixa}'  class='ctxFixa soNumero' size='2'></td>";

    echo "<td><input type='text' id='MrkUp'   tabindex='{$i}'  value='{$mkpAtual}'  class='cMkp soNumero' size='2'></td>";

    echo "<td><input type='text' id='aliq'    tabindex='{$i}'  value='{$aliq}' class='cimp soNumero' size='2'></td>";

    echo "<td><input type='text' id='preco'   tabindex='{$i}'  value=''  class='cPreco inputDestaq' size='4'></td>";

    //   echo "<td><input type='text' id='promocao'    tabindex='{$i}' style='text-align:right; ' value='".$promocao."' class='' size='4'></td>";
    //    echo "<label id='calMrg{$i}'><label>";
    echo "<td><input type='text' id='marg' tabindex='{$i}'  value='' class='cMarg soNumero' size='3'></td>";
   

    echo "<td><input type='text' id='lucro'    tabindex='{$i}'  value='{$lucro}' class='soNumero' size='3'></td>";
    echo "<td><input type='text' id='repasse'  tabindex='{$i}'  value='' class='soNumero' size='3'></td>";

    echo "<td align='center'><script> calPorMkp({$i}); </script><img width='18px' src='imgs/bt-update-pr.png' id='btEnviaPreco{$i}' tabindex='{$i}' class='btupdtpreco'></td>";

    echo "<td align='center'><span id='confirm{$i}'></span></td>";


    echo "</tr>";


   }
   ?>
  </tbody>
 </table>
</div>
</div>
<?php


} else {

 echo "<script> notaRodape('Nenhum dado encontrado!')</script>";
}

?>
<div id="absolut">
 <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
