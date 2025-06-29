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
            
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            
        </nav>
    </header>
    <main>
        <h1>Dashboards</h1>
        <div class="dashboard-graph-tabs">
            <button class="tab-btn active" data-target="card-equipa">Pessoas por Equipa</button>
            <button class="tab-btn" data-target="card-idade">Idade Média por Equipa</button>
            <button class="tab-btn" data-target="card-nivel">Nível Hierárquico/Cargo</button>
        </div>
        
        <section class="dashboard-cards">
            <div class="card dashboard-equipa" id="card-equipa" style="display: flex; flex-direction: column; align-items: center;">
                <h2><i style="color:#667eea;"></i>Pessoas por Equipa</h2>
                <div class="dashboard-desc">
                    Aqui podes analisar o número de Colaboradores por Equipa. Para além dessa informação, tens ainda acesso ao número mínimo, máximo, a média, mediana destes valores.
                </div>
                <div id="chartContainer" class="chart-equipa"></div>
                <div id="statsContainer" class="stats-equipa"></div>
            </div>

            <!-- Idade Média por Equipa -->
            <div class="card dashboard-idade" id="card-idade" style="display:none; flex-direction: column; align-items: center;">
                <h2><i style="color:#764ba2;"></i>Idade Média por Equipa</h2>
                <div class="dashboard-desc">
                    Aqui podes analisar a idade média por Equipa. Para além dessa informação, tens ainda acesso ao mínimo, máximo, a média, mediana destes valores.
                </div>
                <div id="chartIdadeMedia" class="chart-idade"></div>
                <div id="statsIdadeMedia" class="stats-idade"></div>
            </div>

            <!-- Nível Hierárquico/Cargo -->
            <div class="card dashboard-nivel" id="card-nivel" style="display:none; flex-direction: column; align-items: center;">
                <h2><i class="fa fa-sitemap" style="color:#36a2eb;"></i>Nível Hirárquico/Cargo</h2>
                <div class="dashboard-desc">
                    Aqui podes analisar a distribuição dos níveis hirárquicos e a sua legenda, bem como o cargo com menos trabalhadores (mínimo) e o que tem mais trabalhadores (máximo)
                </div>
                <div class="nivel-legenda legenda-nivel">
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
                <div id="chartNivelHierarquico" class="chart-nivel"></div>
                <div id="statsNivelHierarquico" class="stats-nivel"></div>
            </div>
        </section>

        <script>
            // Tabs dos gráficos - garantir display: flex SEMPRE
            
            // Ativar a primeira aba por padrão
            document.querySelector('.tab-btn.active').click();

            // Gráfico de Nível Hierárquico
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                var dataPointsNivel = [];
                for (let i = 0; i < nivelLabels.length; i++) {
                    dataPointsNivel.push({
                        label: nivelLabels[i],
                        y: nivelData[i],
                        color: pieColors[i % pieColors.length],
                        indexLabel: String(nivelData[i]) // mostra o valor em cima da barra
                    });
                }
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
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
                        dataPoints: dataPointsNivel
                    }]
                });
                chartNivel.render();
            }

            
        </script>
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

        // Tamanho fixo para todos os gráficos
        const CHART_WIDTH = 335;
        const CHART_HEIGHT = 230;

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
                        indexLabel: String(equipasMembros[i])
                    });
                }
                // Forçar tamanho do canvas
                document.getElementById("chartContainer").style.width = CHART_WIDTH + "px";
                document.getElementById("chartContainer").style.height = CHART_HEIGHT + "px";
                var chart = new CanvasJS.Chart("chartContainer", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
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
                        title: "",
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

            // Idade média por equipa
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

                var maxY = Math.max.apply(null, axisYLabels.concat([0]));
                var yPadding = Math.ceil(maxY * 0.30);
                var yMaxFinal = maxY + (yPadding > 0 ? yPadding : 1);

                // Forçar tamanho do canvas
                document.getElementById("chartIdadeMedia").style.width = CHART_WIDTH + "px";
                document.getElementById("chartIdadeMedia").style.height = CHART_HEIGHT + "px";
                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
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

            // Gráfico circular para Nível Hierárquico
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                var dataPointsNivel = [];
                for (let i = 0; i < nivelLabels.length; i++) {
                    dataPointsNivel.push({
                        label: nivelLabels[i],
                        y: nivelData[i],
                        color: pieColors[i % pieColors.length]
                    });
                }
                // Forçar tamanho do canvas
                document.getElementById("chartNivelHierarquico").style.width = CHART_WIDTH + "px";
                document.getElementById("chartNivelHierarquico").style.height = CHART_HEIGHT + "px";
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
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

        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.dashboard-cards .card').forEach(c => c.style.display = 'none');
                document.getElementById(this.dataset.target).style.display = 'flex';
                this.classList.add('active');
                // Scroll automático para o gráfico
                setTimeout(() => {
                    document.getElementById(this.dataset.target).scrollIntoView({ behavior: 'smooth', block: 'end' });
                }, 100);
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>