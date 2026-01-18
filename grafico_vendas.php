<?php
require_once( "session.php" );
require_once( "config.php" );

   $Class = new classMasterApi();
   $sql = new Sql();


// Execute a consulta SQL
$sql = "SELECT mes, loja, total, margem FROM tb_vendas WHERE dataVenda BETWEEN '2023-01-01' AND '2023-12-31'";
$result = $conn->query($sql);

// Organize os dados em arrays
$dados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
}

// Converta para JSON para uso no Chart.js
echo "<script>const dados = " . json_encode($dados) . ";</script>";
?>
<script src="js/jquery-3.7.1.js"></script> 

<script src="js/datatables.js"></script> 

<script src="js/js_geral.js"></script>    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="myChart" width="800" height="400"></canvas>
    <script>
        // Dados do PHP
        const dados = window.dados;

        // Organize os dados para o gráfico
        const meses = [...new Set(dados.map(item => item.mes))];
        const lojas = [...new Set(dados.map(item => item.loja))];

        const totalVendasPorMes = meses.map(mes => 
            dados.filter(item => item.mes === mes).reduce((sum, curr) => sum + parseFloat(curr.total), 0)
        );

        const margemPorMes = meses.map(mes => 
            dados.filter(item => item.mes === mes).reduce((sum, curr) => sum + parseFloat(curr.margem), 0)
        );

        // Configuração do gráfico
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line', // Use 'line' ou outro tipo, conforme necessário
            data: {
                labels: meses, // Eixos x
                datasets: [
                    {
                        label: 'Total de Vendas',
                        data: totalVendasPorMes, // Dados de vendas
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4
                    },
                    {
                        label: 'Margem',
                        data: margemPorMes, // Dados de margem
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
