<?php
session_start();

//require_once("../session.php"); 


//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( "../config.php" );


$Class = new classMasterApi();
$sql = new Sql();

// Ajuste do cálculo de data para evitar problemas com operadores
$dataLimite = date('Y-m-d H:i:s', strtotime('-2 years'));

// Obtenção de contas 
$conta = $Class->getContas(1);
$dados = $Class->getIdContaToken();

foreach ($dados as $idConta => $token) {
    
    $dataMaisAntiga = $sql->select("SELECT dataEmissao FROM tb_nfe WHERE id_conta = {$idConta} ORDER BY dataEmissao ASC LIMIT 1;");

    // Validação para evitar acesso indevido a índices inexistentes
    $dataFinal = "";

    if (empty($dataMaisAntiga) || !isset($dataMaisAntiga[0]['dataEmissao']) || $dataMaisAntiga[0]['dataEmissao'] == "0") {
        $dataFinal = date('Y-m-d H:i:s');
    } else {
        $dataFinal = date('Y-m-d H:i:s', strtotime($dataMaisAntiga[0]['dataEmissao']));
    }
    echo "<br>";
    echo "Data limite da busca: " . $dataLimite;
    echo "<br>";
    echo "Data mais antiga: " . $dataFinal;
    echo "<br>";

    // Comparação entre datas para lógica de atualização/deleção
   if (date('Y-m', strtotime($dataFinal)) <= date('Y-m', strtotime($dataLimite))) {
        $up = "UPDATE tb_cron_historico SET nfe = 'N' WHERE id_conta = {$idConta}";
        $sql->run($up);

        $cmd = "DELETE FROM tb_nfe WHERE dataEmissao < '{$dataLimite}'";
        $sql->run($cmd);
        echo "<br>";
        echo "Base atualizada até: " . $dataLimite;
        echo "<br>";

        $dataFinal = date('Y-m-d H:i:s');
        
    } else {
        
        $data_Inicial = date('Y-m-d 00:00:00', strtotime($dataFinal." -90 days"));
        $data_Final   = date('Y-m-d 23:59:59', strtotime($dataFinal ));

        if ($data_Inicial != $data_Final) {
            echo "<hr>";
            echo "Intervaldo atualizado de: " . $data_Inicial;
            echo "<br>";
            echo "até: " . $data_Final;
            echo "<hr>";

            $resNfe = $Class->getNfes($idConta, $token, $data_Inicial, $data_Final);
            echo $resNfe;
        }
    }

    echo "<hr>";
    // echo $Class->getDetalhesNfes($idConta, $token);
}

