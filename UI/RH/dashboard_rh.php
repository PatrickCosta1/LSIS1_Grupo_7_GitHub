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

// Tempo médio na empresa por equipa
$tempo_medio_empresa = [];
if ($equipas_labels) {
    $tempo_medio_raw = $rhBLL->getTempoMedioEmpresaPorEquipa();
    foreach ($equipas_labels as $nome_equipa) {
        $tempo_medio_empresa[] = isset($tempo_medio_raw[$nome_equipa]) ? round($tempo_medio_raw[$nome_equipa], 1) : 0;
    }
}

// Adicionar cálculo da remuneração média por equipa
$equipas_remuneracao_media = [];
if ($equipas_labels) {
    // Obter remuneração média por equipa (em euros)
    $remuneracao_media_raw = $rhBLL->getRemuneracaoMediaPorEquipa();
    foreach ($equipas_labels as $nome_equipa) {
        $equipas_remuneracao_media[] = isset($remuneracao_media_raw[$nome_equipa]) ? round($remuneracao_media_raw[$nome_equipa], 2) : 0;
    }
}

// Percentagem de masculino/feminino/outro por equipa
$genero_equipa_raw = $rhBLL->getDistribuicaoGeneroPorEquipa();
$percent_masculino = [];
$percent_feminino = [];
$percent_outro = [];
$total_masc = 0;
$total_fem = 0;
$total_outro = 0;
$total_colab = 0;
foreach ($equipas_labels as $nome_equipa) {
    $masc = 0;
    $fem = 0;
    $outro = 0;
    $total = 0;
    if (isset($genero_equipa_raw[$nome_equipa])) {
        foreach ($genero_equipa_raw[$nome_equipa] as $genero => $count) {
            $genero_norm = strtolower(trim($genero));
            if ($genero_norm === 'm' || $genero_norm === 'masculino') $masc += $count;
            elseif ($genero_norm === 'f' || $genero_norm === 'feminino') $fem += $count;
            else $outro += $count;
            $total += $count;
        }
    }
    $percent_masculino[] = $total > 0 ? round($masc / $total * 100, 1) : 0;
    $percent_feminino[] = $total > 0 ? round($fem / $total * 100, 1) : 0;
    $percent_outro[] = $total > 0 ? round($outro / $total * 100, 1) : 0;
    $total_masc += $masc;
    $total_fem += $fem;
    $total_outro += $outro;
    $total_colab += $total;
}
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
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
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
    <h1 style="margin-bottom: 0;">Gestão RH - Dashboard</h1>
    <!-- Dropdown para seleção de equipa -->
    <div style="margin-bottom: 18px;">
        <label for="equipaSelect" style="font-weight:bold;">Escolher Equipa:</label>
        <select id="equipaSelect" style="margin-left:8px; padding:4px 8px;">
            <option value="all">Todas</option>
            <?php foreach ($equipas_labels as $idx => $nome_equipa): ?>
                <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($nome_equipa); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- DASHBOARD GRID -->
    <div class="dashboard-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px;">
        <!-- KPIs -->
        <div class="kpi-card" style="background:#f7f8fa;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">Total Colaboradores</div>
            <div id="kpiTotalColab" style="font-size:2.1em;color:#667eea;font-weight:bold;"><?php echo array_sum($equipas_membros); ?></div>
        </div>
        <div class="kpi-card" style="background:#f7f8fa;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">Média de Idade</div>
            <div id="kpiMediaIdade" style="font-size:2.1em;color:#764ba2;font-weight:bold;">
                <?php echo count($equipas_idade_media) && array_sum($equipas_idade_media) > 0 ? round(array_sum($equipas_idade_media)/count(array_filter($equipas_idade_media)),1) : '-'; ?>
            </div>
        </div>
        <div class="kpi-card" style="background:#f7f8fa;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">Tempo Médio Empresa</div>
            <div id="kpiTempoMedio" style="font-size:2.1em;color:#ff9f40;font-weight:bold;">
                <?php echo count($tempo_medio_empresa) && array_sum($tempo_medio_empresa) > 0 ? round(array_sum($tempo_medio_empresa)/count(array_filter($tempo_medio_empresa)),1).' anos' : '-'; ?>
            </div>
        </div>
        <div class="kpi-card" style="background:#f7f8fa;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">Remuneração Média</div>
            <div id="kpiRemuneracao" style="font-size:2.1em;color:#cddc39;font-weight:bold;">
                <?php echo isset($equipas_remuneracao_media) && count($equipas_remuneracao_media) && array_sum($equipas_remuneracao_media) > 0 ? round(array_sum($equipas_remuneracao_media)/count(array_filter($equipas_remuneracao_media)),2).' €' : '-'; ?>
            </div>
        </div>
        <!-- Percentagem Masculino -->
        <div class="kpi-card" style="background:#fff;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">% Masculino</div>
            <div id="kpiPercentMasc" style="font-size:2.1em;color:#36a2eb;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    $masc_percent = ($total_masc + $total_fem + $total_outro) > 0 ? round($total_masc / ($total_masc + $total_fem + $total_outro) * 100, 1) : 0;
                    echo $masc_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- Percentagem Feminino -->
        <div class="kpi-card" style="background:#fff;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">% Feminino</div>
            <div id="kpiPercentFem" style="font-size:2.1em;color:#ff6384;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    $masc_percent = ($total_masc + $total_fem + $total_outro) > 0 ? round($total_masc / ($total_masc + $total_fem + $total_outro) * 100, 1) : 0;
                    $fem_percent = ($total_masc + $total_fem + $total_outro) > 0 ? round($total_fem / ($total_masc + $total_fem + $total_outro) * 100, 1) : 0;
                    echo $fem_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- Percentagem Outro -->
        <div class="kpi-card" style="background:#fff;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">% Outro</div>
            <div id="kpiPercentOutro" style="font-size:2.1em;color:#b2dfdb;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    $outro_percent = ($total_masc + $total_fem + $total_outro) > 0 ? round($total_outro / ($total_masc + $total_fem + $total_outro) * 100, 1) : 0;
                    echo $outro_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- % Retenção ao lado de % Outro -->
        <div class="kpi-card" style="background:#fff;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">% Retenção</div>
            <div id="kpiRetencao" style="font-size:2.1em;color:#4bc0c0;font-weight:bold;">
                <!-- Valor de retenção a ser preenchido via JS ou PHP -->
                -
            </div>
        </div>
        <!-- GAUGES -->
        <!-- Remover o antigo gauge-card de % Retenção abaixo -->
        <!--
        <div class="gauge-card" style="grid-column:3/4;background:#fff;padding:18px;border-radius:10px;text-align:center;">
            <div style="font-size:15px;color:#888;">% Retenção</div>
            <canvas id="gaugeRetencao" width="120" height="70"></canvas>
            <div id="gaugeRetencaoVal" style="font-size:1.2em;font-weight:bold;"></div>
        </div>
        -->
    </div>
    <!-- GRÁFICOS PRINCIPAIS -->
    <div class="dashboard-main-charts" style="display:grid;grid-template-columns:2fr 2fr;gap:18px;">
        <div style="background:#fff;padding:18px;border-radius:10px;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;">Colaboradores por Equipa</div>
            <div id="chartContainer" class="chart-equipa"></div>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;">Idades dos Colaboradores</div>
            <div id="chartIdadeMedia" class="chart-idade"></div>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;">Tempo Médio na Empresa</div>
            <div id="chartTempoMedio" class="chart-tempo"></div>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;">Nível Hierárquico/Cargo</div>
            <div id="chartNivelHierarquico" class="chart-nivel"></div>
        </div>
    </div>
    <!-- Estatísticas -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:24px;">
        <div id="statsContainer" class="stats-equipa"></div>
        <div id="statsIdadeMedia" class="stats-idade"></div>
        <div id="statsTempoMedio" class="stats-tempo"></div>
        <div id="statsNivelHierarquico" class="stats-nivel"></div>
    </div>
</main>
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
        const tempoMedioEmpresa = <?php echo json_encode($tempo_medio_empresa); ?>;
        // Passar idades individuais para JS
        const equipasIdades = <?php echo json_encode($equipas_idades); ?>;
        const equipasRemuneracaoMedia = <?php echo json_encode($equipas_remuneracao_media); ?>;
        const percentMasc = <?php echo json_encode($percent_masculino); ?>;
        const percentFem = <?php echo json_encode($percent_feminino); ?>;
        const percentOutro = <?php echo json_encode($percent_outro); ?>;

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

            // Tempo médio na empresa por equipa
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartTempoMedio")) {
                var dataPointsTempo = [];
                for (let i = 0; i < equipasLabels.length; i++) {
                    dataPointsTempo.push({
                        label: equipasLabels[i],
                        y: tempoMedioEmpresa[i],
                        color: "#ff9f40",
                        indexLabel: String(tempoMedioEmpresa[i])
                    });
                }
                document.getElementById("chartTempoMedio").style.width = CHART_WIDTH + "px";
                document.getElementById("chartTempoMedio").style.height = CHART_HEIGHT + "px";
                var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    axisX: {
                        labelFontSize: 14,
                        labelAngle: -20,
                        interval: 1,
                        labelFontColor: "#3a366b"
                    },
                    axisY: { 
                        title: "Anos",
                        minimum: 0,
                        labelFontColor: "#3a366b",
                        gridColor: "#ecebfa"
                    },
                    data: [{
                        type: "column",
                        dataPoints: dataPointsTempo
                    }]
                });
                chartTempo.render();
                // Estatísticas
                if (typeof ss !== "undefined" && tempoMedioEmpresa.length > 0 && tempoMedioEmpresa.some(x => x > 0)) {
                    const min = ss.min(tempoMedioEmpresa.filter(x => x > 0));
                    const max = ss.max(tempoMedioEmpresa.filter(x => x > 0));
                    const avg = ss.mean(tempoMedioEmpresa.filter(x => x > 0));
                    const med = ss.median(tempoMedioEmpresa.filter(x => x > 0));
                    document.getElementById('statsTempoMedio').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#ff9f40;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsTempoMedio').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
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
    <script>
        // Função para atualizar KPIs conforme equipa selecionada
    function atualizarKPIs(idx) {
        if (idx === "all") {
            // Total colaboradores
            let total = equipasMembros.reduce((a,b)=>a+b,0);
            document.getElementById("kpiTotalColab").innerText = total;
            // Média idade
            let medias = equipasIdadeMedia.filter(x=>x>0);
            let mediaIdade = medias.length ? (medias.reduce((a,b)=>a+b,0)/medias.length).toFixed(1) : '-';
            document.getElementById("kpiMediaIdade").innerText = mediaIdade;
            // Tempo médio empresa
            let tempos = tempoMedioEmpresa.filter(x=>x>0);
            let tempoMedio = tempos.length ? (tempos.reduce((a,b)=>a+b,0)/tempos.length).toFixed(1)+' anos' : '-';
            document.getElementById("kpiTempoMedio").innerText = tempoMedio;
            // Remuneração média
            if (typeof equipasRemuneracaoMedia !== "undefined") {
                let rems = equipasRemuneracaoMedia.filter(x=>x>0);
                let rem = rems.length ? (rems.reduce((a,b)=>a+b,0)/rems.length).toFixed(2)+' €' : '-';
                document.getElementById("kpiRemuneracao").innerText = rem;
            }
            // Percentagem masculino/feminino/outro - corrigido para cálculo global
            if (typeof percentMasc !== "undefined" && typeof percentFem !== "undefined" && typeof percentOutro !== "undefined") {
                // Calcular totais globais
                let totalMasc = 0, totalFem = 0, totalOutro = 0, totalAll = 0;
                for (let i = 0; i < equipasLabels.length; i++) {
                    let membros = equipasMembros[i] || 0;
                    let masc = percentMasc[i] ? (percentMasc[i]/100)*membros : 0;
                    let fem = percentFem[i] ? (percentFem[i]/100)*membros : 0;
                    let outro = percentOutro[i] ? (percentOutro[i]/100)*membros : 0;
                    totalMasc += masc;
                    totalFem += fem;
                    totalOutro += outro;
                    totalAll += membros;
                }
                let mascVal = totalAll > 0 ? (totalMasc/totalAll*100).toFixed(1)+'%' : '-';
                let femVal = totalAll > 0 ? (totalFem/totalAll*100).toFixed(1)+'%' : '-';
                let outroVal = totalAll > 0 ? (totalOutro/totalAll*100).toFixed(1)+'%' : '-';
                document.getElementById("kpiPercentMasc").innerText = mascVal;
                document.getElementById("kpiPercentFem").innerText = femVal;
                document.getElementById("kpiPercentOutro").innerText = outroVal;
            }
        } else {
            idx = parseInt(idx);
            document.getElementById("kpiTotalColab").innerText = equipasMembros[idx] ?? '-';
            document.getElementById("kpiMediaIdade").innerText = equipasIdadeMedia[idx] && equipasIdadeMedia[idx]>0 ? equipasIdadeMedia[idx] : '-';
            document.getElementById("kpiTempoMedio").innerText = tempoMedioEmpresa[idx] && tempoMedioEmpresa[idx]>0 ? tempoMedioEmpresa[idx]+' anos' : '-';
            if (typeof equipasRemuneracaoMedia !== "undefined") {
                document.getElementById("kpiRemuneracao").innerText = equipasRemuneracaoMedia[idx] && equipasRemuneracaoMedia[idx]>0 ? equipasRemuneracaoMedia[idx]+' €' : '-';
            }
            if (typeof percentMasc !== "undefined") {
                document.getElementById("kpiPercentMasc").innerText = percentMasc[idx] && percentMasc[idx]>0 ? percentMasc[idx]+'%' : '-';
            }
            if (typeof percentFem !== "undefined") {
                document.getElementById("kpiPercentFem").innerText = percentFem[idx] && percentFem[idx]>0 ? percentFem[idx]+'%' : '-';
            }
            if (typeof percentOutro !== "undefined") {
                document.getElementById("kpiPercentOutro").innerText = percentOutro[idx] && percentOutro[idx]>0 ? percentOutro[idx]+'%' : '-';
            }
        }
    }

    // Atualizar gráficos e KPIs ao filtrar equipa
    function filtrarPorEquipa(idx) {
        atualizarKPIs(idx);

        // Pessoas por equipa
        if (typeof CanvasJS !== "undefined" && document.getElementById("chartContainer")) {
            let dataPoints = [];
            if (idx === "all") {
                for (let i = 0; i < equipasLabels.length; i++) {
                    dataPoints.push({
                        label: equipasLabels[i],
                        y: equipasMembros[i],
                        color: "#667eea",
                        indexLabel: String(equipasMembros[i])
                    });
                }
            } else {
                dataPoints.push({
                    label: equipasLabels[idx],
                    y: equipasMembros[idx],
                        color: "#667eea",
                        indexLabel: String(equipasMembros[idx])
                });
            }
            document.getElementById("chartContainer").innerHTML = "";
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
        }

        // Idades dos colaboradores (scatter)
        if (typeof CanvasJS !== "undefined" && document.getElementById("chartIdadeMedia")) {
            let dataPointsIdade = [];
            let todasIdades = [];
            if (idx === "all") {
                for (let i = 0; i < equipasLabels.length; i++) {
                    let idades = equipasIdades[equipasLabels[i]] || [];
                    for (let j = 0; j < idades.length; j++) {
                        dataPointsIdade.push({
                            label: equipasLabels[i],
                            y: Number(idades[j]),
                            color: "#764ba2"
                        });
                        todasIdades.push(Number(idades[j]));
                    }
                }
            } else {
                let idades = equipasIdades[equipasLabels[idx]] || [];
                for (let j = 0; j < idades.length; j++) {
                    dataPointsIdade.push({
                        label: "Colaborador " + (j + 1),
                        y: Number(idades[j]),
                        color: "#764ba2"
                    });
                    todasIdades.push(Number(idades[j]));
                }
            }
            document.getElementById("chartIdadeMedia").innerHTML = "";
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
                    title: "Idade",
                    minimum: 0,
                    labelFontColor: "#3a366b",
                    gridColor: "#ecebfa"
                },
                data: [{
                    type: "scatter",
                    markerSize: 12,
                    dataPoints: dataPointsIdade
                }]
            });
            chartIdade.render();

            // Estatísticas para idades
            if (typeof ss !== "undefined" && todasIdades.length > 0) {
                const min = ss.min(todasIdades);
                const max = ss.max(todasIdades);
                const avg = ss.mean(todasIdades);
                const med = ss.median(todasIdades);
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

        // Tempo médio na empresa por equipa
        if (typeof CanvasJS !== "undefined" && document.getElementById("chartTempoMedio")) {
            let dataPointsTempo = [];
            if (idx === "all") {
                for (let i = 0; i < equipasLabels.length; i++) {
                    dataPointsTempo.push({
                        label: equipasLabels[i],
                        y: tempoMedioEmpresa[i],
                        color: "#ff9f40",
                        indexLabel: String(tempoMedioEmpresa[i])
                    });
                }
            } else {
                dataPointsTempo.push({
                    label: equipasLabels[idx],
                    y: tempoMedioEmpresa[idx],
                    color: "#ff9f40",
                    indexLabel: String(tempoMedioEmpresa[idx])
                });
            }
            document.getElementById("chartTempoMedio").innerHTML = "";
            var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                width: CHART_WIDTH,
                height: CHART_HEIGHT,
                animationEnabled: true,
                backgroundColor: "transparent",
                theme: "light2",
                axisX: {
                    labelFontSize: 14,
                    labelAngle: -20,
                    interval: 1,
                    labelFontColor: "#3a366b"
                },
                axisY: { 
                    title: "Anos",
                    minimum: 0,
                    labelFontColor: "#3a366b",
                    gridColor: "#ecebfa"
                },
                data: [{
                    type: "column",
                    dataPoints: dataPointsTempo
                }]
            });
            chartTempo.render();
            // Estatísticas
            let tempos = [];
            if (idx === "all") {
                tempos = tempoMedioEmpresa.filter(x=>x>0);
            } else {
                tempos = tempoMedioEmpresa[idx] > 0 ? [tempoMedioEmpresa[idx]] : [];
            }
            if (typeof ss !== "undefined" && tempos.length > 0) {
                const min = ss.min(tempos);
                const max = ss.max(tempos);
                const avg = ss.mean(tempos);
                const med = ss.median(tempos);
                document.getElementById('statsTempoMedio').innerHTML =
                    `<strong>Estatísticas:</strong><br>
                    <span style="color:#ff9f40;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                    <span style="color:#ff9f40;">Máximo:</span> ${max} &nbsp; | &nbsp;
                    <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                    <span style="color:#ff9f40;">Mediana:</span> ${med}`;
            } else {
                document.getElementById('statsTempoMedio').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
            }
        }

        // Nível Hierárquico/Cargo (não filtra por equipa, permanece igual)
        if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
            var dataPointsNivel = [];
            for (let i = 0; i < nivelLabels.length; i++) {
                dataPointsNivel.push({
                    label: nivelLabels[i],
                    y: nivelData[i],
                    color: pieColors[i % pieColors.length],
                    indexLabel: String(nivelData[i])
                });
            }
            document.getElementById("chartNivelHierarquico").innerHTML = "";
            var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
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
                    dataPoints: dataPointsNivel
                }]
            });
            chartNivel.render();
        }
    }

    // Inicializar com "Todas"
    document.addEventListener("DOMContentLoaded", function () {
        filtrarPorEquipa("all");
    });

    // Dropdown listener
    document.getElementById("equipaSelect").addEventListener("change", function() {
        filtrarPorEquipa(this.value === "all" ? "all" : parseInt(this.value));
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>
