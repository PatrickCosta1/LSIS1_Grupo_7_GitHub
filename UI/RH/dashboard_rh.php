<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_dashboard_rh.php';
$rhBLL = new RHDashboardManager();

// Equipas e membros
$equipas = $rhBLL->getEquipasComMembros();
$equipas_labels = [];
$equipas_membros = [];
$equipas_idades = [];
$equipas_idade_media = [];
if ($equipas) {
    foreach ($equipas as $e) {
        $nome = $e['nome'];
        $equipas_labels[] = $nome;
        $equipas_membros[] = (int)$e['num_colaboradores'];
        $equipas_idades[$nome] = [];
    }
}

// Idades dos colaboradores por equipa
$idades = $rhBLL->getIdadesColaboradoresPorEquipa();
foreach ($idades as $row) {
    $nome = $row['equipa_nome'];
    $idade = $row['idade'];
    if (isset($equipas_idades[$nome])) {
        $equipas_idades[$nome][] = $idade;
    }
}

// Calcular idade média por equipa (mas para o gráfico, mostrar apenas o valor máximo de cada equipa)
$equipas_idade_media = [];
$equipas_idade_media_pico = [];
foreach ($equipas_labels as $nome) {
    if (!empty($equipas_idades[$nome])) {
        $media = round(array_sum($equipas_idades[$nome]) / count($equipas_idades[$nome]), 1);
        $equipas_idade_media[] = $media;
        // Para o gráfico, usar o valor máximo (pico) da idade na equipa
        $equipas_idade_media_pico[] = max($equipas_idades[$nome]);
    } else {
        $equipas_idade_media[] = 0;
        $equipas_idade_media_pico[] = 0;
    }
}
$tem_dados = array_sum($equipas_membros) > 0;

// Nível hierárquico e cargos
$nivel = $rhBLL->getDistribuicaoNivelHierarquico();
$cargosPorNivel = $rhBLL->getCargosPorNivelHierarquico();
$nivel_labels = [];
$nivel_data = [];
$nivel_cargos_count = [];
$pie_colors = [
    "#36a2eb", "#ff6384", "#ffce56", "#4bc0c0", "#9966ff", "#ff9f40", "#b2dfdb", "#cddc39", "#e57373", "#ba68c8"
];
foreach ($nivel as $i => $row) {
    $nivel_labels[] = $row['nivel_hierarquico'];
    $nivel_data[] = (int)$row['total'];
    $cargosCountArr = [];
    if (isset($cargosPorNivel[$row['nivel_hierarquico']])) {
        foreach ($cargosPorNivel[$row['nivel_hierarquico']] as $cargo => $count) {
            $cargosCountArr[] = $cargo . " (" . $count . ")";
        }
    }
    $nivel_cargos_count[] = $cargosCountArr;
}

// Nome do RH
$nome = htmlspecialchars($rhBLL->getRHName($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RH - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/dashboard_rh.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-statistics@7.8.3/dist/simple-statistics.min.js"></script>
</head>

<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Dashboards</h1>
        <section class="dashboard-cards">
            <div class="card">
                <h2><i class="fa fa-users" style="color:#667eea;"></i>Pessoas por Equipa</h2>
                <div id="chartContainer" style="height: 320px;"></div>
                <div id="statsContainer"></div>
            </div>
            <div class="card">
                <h2><i class="fa fa-birthday-cake" style="color:#764ba2;"></i>Idade Média por Equipa</h2>
                <div id="chartIdadeMedia" style="height: 320px;"></div>
                <div id="statsIdadeMedia"></div>
            </div>
            <div class="card">
                <h2><i class="fa fa-sitemap" style="color:#36a2eb;"></i>Nível Hirárquico/Cargo</h2>
                <div class="nivel-legenda">
                    <strong>Legenda:</strong>
                    <ul>
                        <?php foreach ($nivel_labels as $i => $nivel): ?>
                            <li>
                                <span class="pie-color" style="background:<?php echo $pie_colors[$i % count($pie_colors)]; ?>"></span>
                                <span style="font-weight:bold;"><?php echo htmlspecialchars($nivel); ?></span>
                                <?php if (!empty($nivel_cargos_count[$i])): ?>
                                    - <?php
                                        $cargosOnly = array_map(function($str) {
                                            return preg_replace('/\s*\(\d+\)$/', '', $str);
                                        }, $nivel_cargos_count[$i]);
                                        echo htmlspecialchars(implode(', ', $cargosOnly));
                                    ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div id="chartNivelHierarquico" style="height: 320px;"></div>
                <div id="statsNivelHierarquico"></div>
            </div>
        </section>
    </main>
    <script>
        const equipasLabels = <?php echo json_encode($equipas_labels); ?>;
        const equipasMembros = <?php echo json_encode($equipas_membros); ?>;
        const equipasIdadeMedia = <?php echo json_encode($equipas_idade_media); ?>;
        const equipasIdadeMediaPico = <?php echo json_encode($equipas_idade_media_pico); ?>;
        const nivelLabels = <?php echo json_encode($nivel_labels); ?>;
        const nivelData = <?php echo json_encode($nivel_data); ?>;
        const nivelCargosCount = <?php echo json_encode($nivel_cargos_count); ?>;
        const pieColors = <?php echo json_encode($pie_colors); ?>;
        const temDados = <?php echo $tem_dados ? 'true' : 'false'; ?>;

        document.addEventListener("DOMContentLoaded", function () {
            // Pessoas por equipa
            if (!temDados) {
                document.getElementById('chartContainer').innerHTML = "<div style='color:#888;padding:24px;text-align:center;'>Sem dados para mostrar o gráfico.</div>";
                document.getElementById('statsContainer').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
            } else if (typeof CanvasJS !== "undefined" && document.getElementById("chartContainer")) {
                var dataPoints = [];
                for (let i = 0; i < equipasLabels.length; i++) {
                    dataPoints.push({
                        label: equipasLabels[i],
                        y: equipasMembros[i],
                        color: "#667eea",
                        indexLabel: String(equipasMembros[i]) // mostra o valor em cima da barra
                    });
                }
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: {
                        labelFontSize: 14,
                        labelAngle: -20,
                        interval: 1,
                        labelFontColor: "#3a366b"
                    },
                    axisY: { 
                        title: "", // sem escala
                        interval: null,
                        minimum: 0,
                        labelFontColor: "#3a366b",
                        gridColor: "#ecebfa",
                        labelFormatter: function() { return ""; }
                    },
                    data: [{
                        type: "column",
                        dataPoints: dataPoints
                    }]
                });
                chart.render();

                // Estatísticas
                if (typeof ss !== "undefined" && equipasMembros.length > 0) {
                    const min = ss.min(equipasMembros);
                    const max = ss.max(equipasMembros);
                    const avg = ss.mean(equipasMembros);
                    const med = ss.median(equipasMembros);
                    document.getElementById('statsContainer').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#3a366b;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#3a366b;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#3a366b;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#3a366b;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsContainer').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
                }
            }

            // Idade média por equipa (mostrar apenas o valor do pico da coluna)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartIdadeMedia")) {
                var dataPointsIdade = [];
                for (let i = 0; i < equipasLabels.length; i++) {
                    dataPointsIdade.push({
                        label: equipasLabels[i],
                        y: Number(equipasIdadeMediaPico[i]),
                        color: "#764ba2",
                        indexLabel: String(equipasIdadeMediaPico[i])
                    });
                }
                var axisYLabels = equipasIdadeMedia.map(function(val) {
                    return Number(val);
                }).filter(function(val, idx, arr) {
                    return arr.indexOf(val) === idx && val > 0;
                }).sort(function(a, b) { return a - b; });

                // Definir o máximo da escala como o maior valor de idade média + 30%
                var maxY = Math.max.apply(null, axisYLabels.concat([0]));
                var yPadding = Math.ceil(maxY * 0.30);
                var yMaxFinal = maxY + (yPadding > 0 ? yPadding : 1);

                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: {
                        labelFontSize: 14,
                        labelAngle: -20,
                        interval: 1,
                        labelFontColor: "#3a366b"
                    },
                    axisY: { 
                        title: "Idade média", 
                        minimum: 0,
                        maximum: yMaxFinal,
                        interval: null,
                        labelFontColor: "#3a366b",
                        gridColor: "#ecebfa",
                        labelFormatter: function(e) {
                            // Só mostra os valores da idade média calculada
                            if (axisYLabels.includes(e.value)) {
                                return e.value;
                            }
                            return "";
                        }
                    },
                    data: [{
                        type: "column",
                        dataPoints: dataPointsIdade
                    }]
                });
                chartIdade.render();

                // Estatísticas para idade média (pico)
                if (typeof ss !== "undefined" && equipasIdadeMediaPico.length > 0 && equipasIdadeMediaPico.some(x => x > 0)) {
                    const min = ss.min(equipasIdadeMediaPico.filter(x => x > 0));
                    const max = ss.max(equipasIdadeMediaPico.filter(x => x > 0));
                    const avg = ss.mean(equipasIdadeMediaPico.filter(x => x > 0));
                    const med = ss.median(equipasIdadeMediaPico.filter(x => x > 0));
                    document.getElementById('statsIdadeMedia').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#764ba2;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#764ba2;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#764ba2;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#764ba2;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsIdadeMedia').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
                }
            }

            // Gráfico circular para Nível Hierárquico (apenas fatias, sem cargos/contagem no label)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                var dataPointsNivel = [];
                for (let i = 0; i < nivelLabels.length; i++) {
                    dataPointsNivel.push({
                        label: nivelLabels[i],
                        y: nivelData[i],
                        color: pieColors[i % pieColors.length]
                    });
                }
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: {
                        verticalAlign: "bottom",
                        fontSize: 14,
                        fontColor: "#3a366b",
                        cursor: "pointer",
                        itemclick: function () {}
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}",
                        showInLegend: false,
                        dataPoints: dataPointsNivel,
                        indexLabelLineThickness: 0
                    }]
                });
                chartNivel.render();

                // Estatísticas para nível hierárquico
                if (typeof ss !== "undefined" && nivelData.length > 0 && nivelData.some(x => x > 0)) {
                    const min = ss.min(nivelData.filter(x => x > 0));
                    const max = ss.max(nivelData.filter(x => x > 0));
                    const avg = ss.mean(nivelData.filter(x => x > 0));
                    const med = ss.median(nivelData.filter(x => x > 0));
                    document.getElementById('statsNivelHierarquico').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#36a2eb;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#36a2eb;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#36a2eb;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsNivelHierarquico').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
                }
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>