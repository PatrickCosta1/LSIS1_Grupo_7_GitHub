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

$equipas_colaboradores = [];
$colabs = $rhBLL->getNomesColaboradoresPorEquipa();
foreach ($colabs as $row) {
    $nome_equipa = $row['equipa_nome'];
    $nome_colab = $row['colaborador_nome'];
    if (!isset($equipas_colaboradores[$nome_equipa])) $equipas_colaboradores[$nome_equipa] = [];
    $equipas_colaboradores[$nome_equipa][] = $nome_colab;
}

// Calcular idade média por equipa
$equipas_idade_media = [];
$equipas_idade_media_pico = [];
foreach ($equipas_labels as $nome) {
    if (!empty($equipas_idades[$nome])) {
        $media = round(array_sum($equipas_idades[$nome]) / count($equipas_idades[$nome]), 1);
        $equipas_idade_media[] = $media;
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
$pie_colors = ["#36a2eb", "#ff6384", "#ffce56", "#4bc0c0", "#9966ff"];
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

// Remuneração média por equipa
$equipas_remuneracao_media = [];
if ($equipas_labels) {
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

// Distribuição geográfica
$geo_labels = [];
$geo_data = [];
$distribuicao_geografica = $rhBLL->getDistribuicaoGeografica();
if ($distribuicao_geografica && is_array($distribuicao_geografica)) {
    foreach ($distribuicao_geografica as $localidade => $total) {
        $geo_labels[] = $localidade;
        $geo_data[] = (int)$total;
    }
}

// Localidades por equipa
$equipas_localidades = $rhBLL->getLocalidadesPorEquipa();
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
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        <h1>Gestão RH - Dashboard</h1>
        <div style="margin-bottom: 18px; text-align: center;">
            <label for="equipaSelect" style="font-weight:bold; margin-bottom:8px; display:inline-block;">Escolher Equipa:</label>
            <select id="equipaSelect" style="margin-left:8px; padding:4px 8px; border-radius:6px; border:1px solid #ccd; background:#f7f8fa;">
                <option value="all">Todas</option>
                <?php foreach ($equipas_labels as $idx => $nome_equipa): ?>
                    <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($nome_equipa); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="dashboard-grid">
            <div class="kpi-card" style="background:rgba(92, 176, 255, 0.2);">
                <div style="font-size:15px;color:#666;">Total Colaboradores</div>
                <div id="kpiTotalColab" style="font-size:2.1em;color:#667eea;font-weight:bold;"><?php echo array_sum($equipas_membros); ?></div>
            </div>
            <div class="kpi-card" style="background:rgba(171, 69, 255, 0.2);">
                <div style="font-size:15px;color:#666;">Média de Idade</div>
                <div id="kpiMediaIdade" style="font-size:2.1em;color:#764ba2;font-weight:bold;">
                    <?php echo count($equipas_idade_media) && array_sum($equipas_idade_media) > 0 ? round(array_sum($equipas_idade_media)/count(array_filter($equipas_idade_media)),1) : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 180, 118, 0.2);">
                <div style="font-size:15px;color:#666;">Tempo Médio Empresa</div>
                <div id="kpiTempoMedio" style="font-size:2.1em;color:#ff9f40;font-weight:bold;">
                    <?php echo count($tempo_medio_empresa) && array_sum($tempo_medio_empresa) > 0 ? round(array_sum($tempo_medio_empresa)/count(array_filter($tempo_medio_empresa)),1).' anos' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(130, 255, 136, 0.2);">
                <div style="font-size:15px;color:#666;">Remuneração Média</div>
                <div id="kpiRemuneracao" style="font-size:2.1em;color:#4CAF50;font-weight:bold;">
                    <?php echo isset($equipas_remuneracao_media) && count($equipas_remuneracao_media) && array_sum($equipas_remuneracao_media) > 0 ? round(array_sum($equipas_remuneracao_media)/count(array_filter($equipas_remuneracao_media)),2).' €' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(130, 236, 255, 0.2);">
                <div style="font-size:15px;color:#666;">% Masculino</div>
                <div id="kpiPercentMasc" style="font-size:2.1em;color:#36a2eb;font-weight:bold;">
                    <?php
                    if ($total_colab > 0) {
                        $masc_percent = $total_colab > 0 ? ($total_masc / $total_colab * 100) : 0;
                        $fem_percent = $total_colab > 0 ? ($total_fem / $total_colab * 100) : 0;
                        $outro_percent = $total_colab > 0 ? ($total_outro / $total_colab * 100) : 0;
                        $arr = [['val' => $masc_percent, 'idx' => 0], ['val' => $fem_percent, 'idx' => 1], ['val' => $outro_percent, 'idx' => 2]];
                        foreach ($arr as &$a) $a['rounded'] = round($a['val'] * 10) / 10;
                        unset($a);
                        $soma = $arr[0]['rounded'] + $arr[1]['rounded'] + $arr[2]['rounded'];
                        $diff = round((100 - $soma) * 10) / 10;
                        if (abs($diff) > 0) {
                            usort($arr, fn($a, $b) => abs($b['val']) <=> abs($a['val']));
                            $arr[0]['rounded'] = round(($arr[0]['rounded'] + $diff) * 10) / 10;
                        }
                        usort($arr, fn($a, $b) => $a['idx'] <=> $b['idx']);
                        $masc_percent = $arr[0]['rounded'];
                        echo $masc_percent . '%';
                    } else {
                        echo '-';
                    }
                    ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 163, 243, 0.2);">
                <div style="font-size:15px;color:#666;">% Feminino</div>
                <div id="kpiPercentFem" style="font-size:2.1em;color:#ff6384;font-weight:bold;">
                    <?php echo $total_colab > 0 ? $fem_percent . '%' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 238, 138, 0.2);">
                <div style="font-size:15px;color:#666;">% Outro</div>
                <div id="kpiPercentOutro" style="font-size:2.1em;color:#DAA520;font-weight:bold;">
                    <?php echo $total_colab > 0 ? $outro_percent . '%' : '-'; ?>
                </div>
            </div>
        </div>
        <div class="dashboard-main-charts">
            <div class="chart-card">
                <div class="chart-card-title">Colaboradores por Equipa</div>
                <div id="chartContainer" class="chart-area"></div>
                <div id="statsContainer" class="stats-equipa"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Idades dos Colaboradores</div>
                <div id="chartIdadeMedia" class="chart-area"></div>
                <div id="statsIdadeMedia" class="stats-idade"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Tempo Médio na Empresa</div>
                <div id="chartTempoMedio" class="chart-area"></div>
                <div id="statsTempoMedio" class="stats-idade"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Nível Hierárquico/Cargo</div>
                <div id="chartNivelHierarquico" class="chart-area"></div>
                <div id="statsNivelHierarquico" class="stats-nivel"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Distribuição Geográfica</div>
                <div id="chartGeografia" class="chart-area"></div>
                <div id="statsGeografia" class="stats-nivel"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Distribuição de Género</div>
                <div id="chartGenero" class="chart-area"></div>
                <div id="statsGenero" class="stats-nivel"></div>
            </div>
        </div>
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
        const equipasIdades = <?php echo json_encode($equipas_idades); ?>;
        const equipasRemuneracaoMedia = <?php echo json_encode($equipas_remuneracao_media); ?>;
        const percentMasc = <?php echo json_encode($percent_masculino); ?>;
        const percentFem = <?php echo json_encode($percent_feminino); ?>;
        const percentOutro = <?php echo json_encode($percent_outro); ?>;
        const equipasLocalidades = <?php echo json_encode($equipas_localidades); ?>;
        const equipasColaboradores = <?php echo json_encode($equipas_colaboradores); ?>;
        window.equipasColaboradores = equipasColaboradores;

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
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: { labelFontSize: 14, labelAngle: -20, interval: 1, labelFontColor: "#3a366b" },
                    axisY: { title: "", interval: null, minimum: 0, labelFontColor: "#3a366b", gridColor: "#ecebfa", labelFormatter: function() { return ""; } },
                    data: [{ type: "column", dataPoints: dataPoints }]
                });
                chart.render();
                if (typeof ss !== "undefined" && equipasMembros.length > 0) {
                    const min = ss.min(equipasMembros);
                    const max = ss.max(equipasMembros);
                    const avg = ss.mean(equipasMembros);
                    const med = ss.median(equipasMembros);
                    document.getElementById('statsContainer').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#667eea;">Mínimo:</span> ${min} | <span style="color:#667eea;">Máximo:</span> ${max} | <span style="color:#667eea;">Média:</span> ${avg.toFixed(2)} | <span style="color:#667eea;">Mediana:</span> ${med};
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
                var axisYLabels = equipasIdadeMedia.filter(val => val > 0).sort((a, b) => a - b);
                var maxY = Math.max(...axisYLabels, 0);
                var yPadding = Math.ceil(maxY * 0.30);
                var yMaxFinal = maxY + (yPadding > 0 ? yPadding : 1);
                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: { labelFontSize: 14, labelAngle: -20, interval: 1, labelFontColor: "#3a366b" },
                    axisY: { title: "Idade média", minimum: 0, maximum: yMaxFinal, interval: null, labelFontColor: "#3a366b", gridColor: "#ecebfa", labelFormatter: function(e) { return axisYLabels.includes(e.value) ? e.value : ""; } },
                    data: [{ type: "column", dataPoints: dataPointsIdade }]
                });
                chartIdade.render();
                if (typeof ss !== "undefined" && equipasIdadeMediaPico.some(x => x > 0)) {
                    const min = ss.min(equipasIdadeMediaPico.filter(x => x > 0));
                    const max = ss.max(equipasIdadeMediaPico.filter(x => x > 0));
                    const avg = ss.mean(equipasIdadeMediaPico.filter(x => x > 0));
                    const med = ss.median(equipasIdadeMediaPico.filter(x => x > 0));
                    document.getElementById('statsIdadeMedia').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#764ba2;">Mínimo:</span> ${min} | <span style="color:#764ba2;">Máximo:</span> ${max} | <span style="color:#764ba2;">Média:</span> ${avg.toFixed(2)} | <span style="color:#764ba2;">Mediana:</span> ${med};
                }
            }

            // Tempo médio na empresa
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
                var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    axisX: { labelFontSize: 14, labelAngle: -20, interval: 1, labelFontColor: "#3a366b" },
                    axisY: { title: "Anos", minimum: 0, labelFontColor: "#3a366b", gridColor: "#ecebfa" },
                    data: [{ type: "column", dataPoints: dataPointsTempo }]
                });
                chartTempo.render();
                if (typeof ss !== "undefined" && tempoMedioEmpresa.some(x => x > 0)) {
                    const min = ss.min(tempoMedioEmpresa.filter(x => x > 0));
                    const max = ss.max(tempoMedioEmpresa.filter(x => x > 0));
                    const avg = ss.mean(tempoMedioEmpresa.filter(x => x > 0));
                    const med = ss.median(tempoMedioEmpresa.filter(x => x > 0));
                    document.getElementById('statsTempoMedio').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#ff9f40;">Mínimo:</span> ${min} | <span style="color:#ff9f40;">Máximo:</span> ${max} | <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} | <span style="color:#ff9f40;">Mediana:</span> ${med};
                }
            }

            // Nível hierárquico
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
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: dataPointsNivel, indexLabelLineThickness: 0 }]
                });
                chartNivel.render();
                if (typeof ss !== "undefined" && nivelData.some(x => x > 0)) {
                    const min = ss.min(nivelData.filter(x => x > 0));
                    const max = ss.max(nivelData.filter(x => x > 0));
                    const avg = ss.mean(nivelData.filter(x => x > 0));
                    const med = ss.median(nivelData.filter(x => x > 0));
                    document.getElementById('statsNivelHierarquico').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }

            // Distribuição geográfica
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                let geoLabels = <?php echo json_encode($geo_labels); ?>;
                let geoData = <?php echo json_encode($geo_data); ?>;
                let dataPointsGeo = [];
                let totalGeo = geoData.reduce((a, b) => a + b, 0);
                for (let i = 0; i < geoLabels.length; i++) {
                    let percent = totalGeo > 0 ? Math.round((geoData[i] / totalGeo) * 1000) / 10 : 0;
                    dataPointsGeo.push({
                        y: percent,
                        label: geoLabels[i],
                        toolTipContent: geoLabels[i] + ": " + geoData[i] + " (" + percent + "%)",
                        color: pieColors[i % pieColors.length]
                    });
                }
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0, toolTipContent: "{toolTipContent}" }]
                });
                chartGeo.render();
                if (typeof ss !== "undefined" && geoData.some(x => x > 0)) {
                    const min = ss.min(geoData.filter(x => x > 0));
                    const max = ss.max(geoData.filter(x => x > 0));
                    const avg = ss.mean(geoData.filter(x => x > 0));
                    const med = ss.median(geoData.filter(x => x > 0));
                    document.getElementById('statsGeografia').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }

            // Distribuição de género
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                let totalMasc = 0, totalFem = 0, totalOutro = 0;
                for (let i = 0; i < equipasLabels.length; i++) {
                    totalMasc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                    totalFem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                    totalOutro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                }
                let totalGeral = totalMasc + totalFem + totalOutro;
                let mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                let femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                let outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                let arr = [{ val: mascPercent, idx: 0 }, { val: femPercent, idx: 1 }, { val: outroPercent, idx: 2 }];
                arr.forEach(a => a.rounded = Math.round(a.val * 10) / 10);
                let soma = arr[0].rounded + arr[1].rounded + arr[2].rounded;
                let diff = Math.round((100 - soma) * 10) / 10;
                if (Math.abs(diff) > 0) {
                    arr.sort((a, b) => Math.abs(b.val) - Math.abs(a.val));
                    arr[0].rounded = Math.round((arr[0].rounded + diff) * 10) / 10;
                }
                arr.sort((a, b) => a.idx - b.idx);
                mascPercent = arr[0].rounded;
                femPercent = arr[1].rounded;
                outroPercent = arr[2].rounded;
                let dataPointsGenero = [
                    { y: mascPercent, label: "Masculino", color: "#36a2eb", toolTipContent: "Masculino: " + Math.round(totalMasc) + " (" + mascPercent + "%)" },
                    { y: femPercent, label: "Feminino", color: "#ff6384", toolTipContent: "Feminino: " + Math.round(totalFem) + " (" + femPercent + "%)" },
                    { y: outroPercent, label: "Outro", color: "#b2dfdb", toolTipContent: "Outro: " + Math.round(totalOutro) + " (" + outroPercent + "%)" }
                ];
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGenero, indexLabelLineThickness: 0, toolTipContent: "{toolTipContent}" }]
                });
                chartGenero.render();
                if (typeof ss !== "undefined" && [totalMasc, totalFem, totalOutro].some(x => x > 0)) {
                    const data = [totalMasc, totalFem, totalOutro].filter(x => x > 0);
                    const min = ss.min(data);
                    const max = ss.max(data);
                    const avg = ss.mean(data);
                    const med = ss.median(data);
                    document.getElementById('statsGenero').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }
        });

        function atualizarKPIs(idx) {
            if (idx === "all") {
                document.getElementById("kpiTotalColab").innerText = equipasMembros.reduce((a,b)=>a+b,0);
                let medias = equipasIdadeMedia.filter(x=>x>0);
                document.getElementById("kpiMediaIdade").innerText = medias.length ? (medias.reduce((a,b)=>a+b,0)/medias.length).toFixed(1) : '-';
                let tempos = tempoMedioEmpresa.filter(x=>x>0);
                document.getElementById("kpiTempoMedio").innerText = tempos.length ? (tempos.reduce((a,b)=>a+b,0)/tempos.length).toFixed(1)+' anos' : '-';
                let rems = equipasRemuneracaoMedia.filter(x=>x>0);
                document.getElementById("kpiRemuneracao").innerText = rems.length ? (rems.reduce((a,b)=>a+b,0)/rems.length).toFixed(2)+' €' : '-';
                let totalMasc = 0, totalFem = 0, totalOutro = 0, totalGeral = 0;
                for (let i = 0; i < equipasLabels.length; i++) {
                    totalMasc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                    totalFem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                    totalOutro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                    totalGeral += equipasMembros[i];
                }
                let mascVal = totalGeral > 0 ? (totalMasc / totalGeral * 100).toFixed(1) + '%' : '-';
                let femVal = totalGeral > 0 ? (totalFem / totalGeral * 100).toFixed(1) + '%' : '-';
                let outroVal = totalGeral > 0 ? (totalOutro / totalGeral * 100).toFixed(1) + '%' : '-';
                document.getElementById("kpiPercentMasc").innerText = mascVal;
                document.getElementById("kpiPercentFem").innerText = femVal;
                document.getElementById("kpiPercentOutro").innerText = outroVal;
            } else {
                idx = parseInt(idx);
                document.getElementById("kpiTotalColab").innerText = equipasMembros[idx] ?? '-';
                document.getElementById("kpiMediaIdade").innerText = equipasIdadeMedia[idx] > 0 ? equipasIdadeMedia[idx] : '-';
                document.getElementById("kpiTempoMedio").innerText = tempoMedioEmpresa[idx] > 0 ? tempoMedioEmpresa[idx]+' anos' : '-';
                document.getElementById("kpiRemuneracao").innerText = equipasRemuneracaoMedia[idx] > 0 ? equipasRemuneracaoMedia[idx]+' €' : '-';
                document.getElementById("kpiPercentMasc").innerText = percentMasc[idx] > 0 ? percentMasc[idx]+'%' : '-';
                document.getElementById("kpiPercentFem").innerText = percentFem[idx] > 0 ? percentFem[idx]+'%' : '-';
                document.getElementById("kpiPercentOutro").innerText = percentOutro[idx] > 0 ? percentOutro[idx]+'%' : '-';
            }
        }

        function filtrarPorEquipa(idx) {
            atualizarKPIs(idx);
            // Pessoas por equipa
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartContainer")) {
                let dataPoints = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: equipasMembros[i], color: "#667eea", indexLabel: String(equipasMembros[i]) })) : [{ label: equipasLabels[idx], y: equipasMembros[idx], color: "#667eea", indexLabel: String(equipasMembros[idx]) }];
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: { labelFontSize: 14, labelAngle: -20, interval: 1, labelFontColor: "#3a366b" },
                    axisY: { title: "", interval: null, minimum: 0, labelFontColor: "#3a366b", gridColor: "#ecebfa", labelFormatter: function() { return ""; } },
                    data: [{ type: "column", dataPoints: dataPoints }]
                });
                chart.render();
                if (typeof ss !== "undefined" && (idx === "all" ? equipasMembros : [equipasMembros[idx]]).some(x => x > 0)) {
                    const data = idx === "all" ? equipasMembros : [equipasMembros[idx]];
                    const min = ss.min(data.filter(x => x > 0));
                    const max = ss.max(data.filter(x => x > 0));
                    const avg = ss.mean(data.filter(x => x > 0));
                    const med = ss.median(data.filter(x => x > 0));
                    document.getElementById('statsContainer').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#667eea;">Mínimo:</span> ${min} | <span style="color:#667eea;">Máximo:</span> ${max} | <span style="color:#667eea;">Média:</span> ${avg.toFixed(2)} | <span style="color:#667eea;">Mediana:</span> ${med};
                }
            }

            // Idades dos colaboradores
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartIdadeMedia")) {
                let dataPointsIdade = [], axisXLabels = [], todasIdades = [];
                if (idx === "all") {
                    equipasLabels.forEach((l, i) => {
                        let idades = equipasIdades[l] || [];
                        idades.forEach((idade, j) => {
                            let nomeColab = equipasColaboradores[l] && equipasColaboradores[l][j] ? equipasColaboradores[l][j] : "Colab " + (j + 1);
                            dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(idade), markerColor: "#764ba2", indexLabel: nomeColab + " (" + idade + ")" });
                            axisXLabels.push(nomeColab);
                            todasIdades.push(Number(idade));
                        });
                    });
                } else {
                    let idades = equipasIdades[equipasLabels[idx]] || [];
                    idades.forEach((idade, j) => {
                        let nomeColab = equipasColaboradores[equipasLabels[idx]] && equipasColaboradores[equipasLabels[idx]][j] ? equipasColaboradores[equipasLabels[idx]][j] : "Colab " + (j + 1);
                        dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(idade), markerColor: "#764ba2", indexLabel: nomeColab + " (" + idade + ")" });
                        axisXLabels.push(nomeColab);
                        todasIdades.push(Number(idade));
                    });
                }
                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    axisX: { labelFontSize: 11, labelAngle: -45, interval: 1, labelFontColor: "#3a366b", valueFormatString: "#", labelFormatter: function(e) { return axisXLabels[e.value - 1] || ""; } },
                    axisY: { title: "Idade", minimum: 0, labelFontColor: "#3a366b", gridColor: "#ecebfa" },
                    data: [{ type: "scatter", markerSize: 12, toolTipContent: "{indexLabel}", dataPoints: dataPointsIdade }]
                });
                chartIdade.render();
                if (typeof ss !== "undefined" && todasIdades.length > 0) {
                    const min = ss.min(todasIdades);
                    const max = ss.max(todasIdades);
                    const avg = ss.mean(todasIdades);
                    const med = ss.median(todasIdades);
                    document.getElementById('statsIdadeMedia').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#764ba2;">Mínimo:</span> ${min} | <span style="color:#764ba2;">Máximo:</span> ${max} | <span style="color:#764ba2;">Média:</span> ${avg.toFixed(2)} | <span style="color:#764ba2;">Mediana:</span> ${med};
                }
            }

            // Tempo médio na empresa
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartTempoMedio")) {
                let dataPointsTempo = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: tempoMedioEmpresa[i], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[i]) })) : [{ label: equipasLabels[idx], y: tempoMedioEmpresa[idx], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[idx]) }];
                var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    axisX: { labelFontSize: 14, labelAngle: -20, interval: 1, labelFontColor: "#3a366b" },
                    axisY: { title: "Anos", minimum: 0, labelFontColor: "#3a366b", gridColor: "#ecebfa" },
                    data: [{ type: "column", dataPoints: dataPointsTempo }]
                });
                chartTempo.render();
                let tempos = idx === "all" ? tempoMedioEmpresa.filter(x => x > 0) : [tempoMedioEmpresa[idx]].filter(x => x > 0);
                if (typeof ss !== "undefined" && tempos.length > 0) {
                    const min = ss.min(tempos);
                    const max = ss.max(tempos);
                    const avg = ss.mean(tempos);
                    const med = ss.median(tempos);
                    document.getElementById('statsTempoMedio').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#ff9f40;">Mínimo:</span> ${min} | <span style="color:#ff9f40;">Máximo:</span> ${max} | <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} | <span style="color:#ff9f40;">Mediana:</span> ${med};
                }
            }

            // Nível hierárquico
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                var dataPointsNivel = nivelLabels.map((l, i) => ({ label: l, y: nivelData[i], color: pieColors[i % pieColors.length] }));
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: dataPointsNivel, indexLabelLineThickness: 0 }]
                });
                chartNivel.render();
                if (typeof ss !== "undefined" && nivelData.some(x => x > 0)) {
                    const min = ss.min(nivelData.filter(x => x > 0));
                    const max = ss.max(nivelData.filter(x => x > 0));
                    const avg = ss.mean(nivelData.filter(x => x > 0));
                    const med = ss.median(nivelData.filter(x => x > 0));
                    document.getElementById('statsNivelHierarquico').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }

            // Distribuição geográfica
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                let geoLabels = <?php echo json_encode($geo_labels); ?>;
                let geoData = <?php echo json_encode($geo_data); ?>;
                let dataPointsGeo = [];
                let totalGeo = geoData.reduce((a, b) => a + b, 0);
                for (let i = 0; i < geoLabels.length; i++) {
                    let percent = totalGeo > 0 ? Math.round((geoData[i] / totalGeo) * 1000) / 10 : 0;
                    dataPointsGeo.push({
                        y: percent,
                        label: geoLabels[i],
                        toolTipContent: geoLabels[i] + ": " + geoData[i] + " (" + percent + "%)",
                        color: pieColors[i % pieColors.length]
                    });
                }
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0, toolTipContent: "{toolTipContent}" }]
                });
                chartGeo.render();
                if (typeof ss !== "undefined" && geoData.some(x => x > 0)) {
                    const min = ss.min(geoData.filter(x => x > 0));
                    const max = ss.max(geoData.filter(x => x > 0));
                    const avg = ss.mean(geoData.filter(x => x > 0));
                    const med = ss.median(geoData.filter(x => x > 0));
                    document.getElementById('statsGeografia').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }

            // Distribuição de género
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                let totalMasc = 0, totalFem = 0, totalOutro = 0;
                for (let i = 0; i < equipasLabels.length; i++) {
                    totalMasc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                    totalFem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                    totalOutro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                }
                let totalGeral = totalMasc + totalFem + totalOutro;
                let mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                let femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                let outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                let arr = [{ val: mascPercent, idx: 0 }, { val: femPercent, idx: 1 }, { val: outroPercent, idx: 2 }];
                arr.forEach(a => a.rounded = Math.round(a.val * 10) / 10);
                let soma = arr[0].rounded + arr[1].rounded + arr[2].rounded;
                let diff = Math.round((100 - soma) * 10) / 10;
                if (Math.abs(diff) > 0) {
                    arr.sort((a, b) => Math.abs(b.val) - Math.abs(a.val));
                    arr[0].rounded = Math.round((arr[0].rounded + diff) * 10) / 10;
                }
                arr.sort((a, b) => a.idx - b.idx);
                mascPercent = arr[0].rounded;
                femPercent = arr[1].rounded;
                outroPercent = arr[2].rounded;
                let dataPointsGenero = [
                    { y: mascPercent, label: "Masculino", color: "#36a2eb", toolTipContent: "Masculino: " + Math.round(totalMasc) + " (" + mascPercent + "%)" },
                    { y: femPercent, label: "Feminino", color: "#ff6384", toolTipContent: "Feminino: " + Math.round(totalFem) + " (" + femPercent + "%)" },
                    { y: outroPercent, label: "Outro", color: "#b2dfdb", toolTipContent: "Outro: " + Math.round(totalOutro) + " (" + outroPercent + "%)" }
                ];
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 14, fontColor: "#3a366b" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGenero, indexLabelLineThickness: 0, toolTipContent: "{toolTipContent}" }]
                });
                chartGenero.render();
                if (typeof ss !== "undefined" && [totalMasc, totalFem, totalOutro].some(x => x > 0)) {
                    const data = [totalMasc, totalFem, totalOutro].filter(x => x > 0);
                    const min = ss.min(data);
                    const max = ss.max(data);
                    const avg = ss.mean(data);
                    const med = ss.median(data);
                    document.getElementById('statsGenero').innerHTML = <strong>Estatísticas:</strong><br><span style="color:#36a2eb;">Mínimo:</span> ${min} | <span style="color:#36a2eb;">Máximo:</span> ${max} | <span style="color:#36a2eb;">Média:</span> ${avg.toFixed(2)} | <span style="color:#36a2eb;">Mediana:</span> ${med};
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            filtrarPorEquipa("all");
        });

        document.getElementById("equipaSelect").addEventListener("change", function() {
            filtrarPorEquipa(this.value === "all" ? "all" : parseInt(this.value));
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>