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
              <div class="dropdown-equipas">
                <a href="equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="relatorios.php">Relatórios</a>
                    <a href="dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="exportar.php">Exportar</a>
                </div>
            </div>
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
                    <?php echo $total_colab > 0 ? round($fem_percent, 1) . '%' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 238, 138, 0.2);">
                <div style="font-size:15px;color:#666;">% Outro</div>
                <div id="kpiPercentOutro" style="font-size:2.1em;color:#DAA520;font-weight:bold;">
                    <?php echo $total_colab > 0 ? round($outro_percent, 1) . '%' : '-'; ?>
                </div>
            </div>
        </div>
        <div class="dashboard-main-charts">
            <!-- Primeira linha -->
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
            <!-- Segunda linha -->
            <div class="chart-card">
                <div class="chart-card-title">Nível Hierárquico/Cargo</div>
                <div id="chartNivelHierarquico" class="chart-area"></div>
                <div id="statsNivelHierarquico" class="stats-nivel"></div>
                 <span style="display:block;font-size:0.95em;font-weight:400;color:#888;margin-top:4px;">
                    <em>Legenda: 1 = Colaborador, 2 = Coordenador, 3 = RH</em>
                </span>
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
            filtrarPorEquipa("all");
        });

        // Função para forçar redimensionamento dos gráficos
        function forceChartResize() {
            setTimeout(() => {
                const chartContainers = ['chartContainer', 'chartIdadeMedia', 'chartTempoMedio', 'chartNivelHierarquico', 'chartGeografia', 'chartGenero'];
                chartContainers.forEach(containerId => {
                    const container = document.getElementById(containerId);
                    if (container) {
                        // Forçar o container a ter o tamanho correto
                        container.style.width = '100%';
                        container.style.height = '300px';
                        container.style.display = 'block';
                        
                        // Encontrar o canvas dentro do container e redimensionar
                        const canvas = container.querySelector('canvas');
                        if (canvas) {
                            canvas.style.width = '100%';
                            canvas.style.height = '100%';
                            canvas.style.maxWidth = '100%';
                            canvas.style.maxHeight = '100%';
                        }
                        
                        // Forçar repaint
                        container.style.display = 'none';
                        container.offsetHeight; // trigger reflow
                        container.style.display = 'block';
                    }
                });
            }, 50);
        }

        function setChartContainerStyle(containerId) {
            const container = document.getElementById(containerId);
            if (container) {
                container.style.width = '100%';
                container.style.height = '320px';
                container.style.minHeight = '220px';
                container.style.display = 'flex';
                container.style.alignItems = 'center';
                container.style.justifyContent = 'center';
                container.style.position = 'relative';
                container.style.background = 'none';
                container.style.boxSizing = 'border-box';
            }
        }
        function setChartCanvasStyle(containerId) {
            const container = document.getElementById(containerId);
            if (container) {
                const canvas = container.querySelector('canvas');
                if (canvas) {
                    canvas.style.width = '100%';
                    canvas.style.height = '100%';
                    canvas.style.minHeight = '200px';
                    canvas.style.maxWidth = '100%';
                    canvas.style.maxHeight = '100%';
                    canvas.style.margin = '0 auto';
                    canvas.style.display = 'block';
                    canvas.style.background = 'none';
                }
            }
        }

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

        // Função para filtrar e desenhar gráficos
        function filtrarPorEquipa(idx) {
            atualizarKPIs(idx);

            // Colaboradores por Equipa - Column moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartContainer")) {
                setChartContainerStyle("chartContainer");
                let dataPoints = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: equipasMembros[i], color: "#36a2eb", indexLabel: String(equipasMembros[i]) })) : [{ label: equipasLabels[idx], y: equipasMembros[idx], color: "#36a2eb", indexLabel: String(equipasMembros[idx]) }];
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    axisX: {
                        labelFontSize: 13,
                        labelAngle: -30,
                        interval: 1,
                        labelFontColor: "#19365f",
                        labelWrap: true,
                        labelMaxWidth: 120
                    },
                    axisY: { title: "Colaboradores", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    toolTip: { enabled: false },
                    data: [{
                        type: "column",
                        color: "#36a2eb",
                        indexLabelFontColor: "#19365f",
                        indexLabelFontWeight: "bold",
                        indexLabelPlacement: "outside",
                        dataPoints: dataPoints
                    }]
                });
                chart.render();
                setChartCanvasStyle("chartContainer");
            }
            // Idades dos Colaboradores - SplineArea moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartIdadeMedia")) {
                setChartContainerStyle("chartIdadeMedia");
                let dataPointsIdade = [], axisXLabels = [], allAges = [];
                if (idx === "all") {
                    equipasLabels.forEach((l, i) => {
                        let ages = equipasIdades[l] || [];
                        ages.forEach((age, j) => {
                            let name = equipasColaboradores[l] && equipasColaboradores[l][j] ? equipasColaboradores[l][j] : `Colab ${j + 1}`;
                            dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(age), markerColor: "#764ba2", indexLabel: `${name} (${age})` });
                            axisXLabels.push(name);
                            allAges.push(Number(age));
                        });
                    });
                } else {
                    let ages = equipasIdades[equipasLabels[idx]] || [];
                    ages.forEach((age, j) => {
                        let name = equipasColaboradores[equipasLabels[idx]] && equipasColaboradores[equipasLabels[idx]][j] ? equipasColaboradores[equipasLabels[idx]][j] : `Colab ${j + 1}`;
                        dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(age), markerColor: "#764ba2", indexLabel: `${name} (${age})` });
                        axisXLabels.push(name);
                        allAges.push(Number(age));
                    });
                }
                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    axisX: { labelFontSize: 11, labelAngle: -30, interval: 1, labelFontColor: "#19365f", valueFormatString: "#", labelFormatter: function(e) { return axisXLabels[e.value - 1] || ""; } },
                    axisY: { title: "Idade", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    toolTip: { enabled: false },
                    data: [{ type: "splineArea", markerSize: 8, color: "#764ba2", fillOpacity: 0.3, toolTipContent: "{indexLabel}", dataPoints: dataPointsIdade }]
                });
                chartIdade.render();
                setChartCanvasStyle("chartIdadeMedia");
            }
            // Tempo médio na empresa - Bar moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartTempoMedio")) {
                setChartContainerStyle("chartTempoMedio");
                let dataPointsTempo = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: tempoMedioEmpresa[i], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[i]) })) : [{ label: equipasLabels[idx], y: tempoMedioEmpresa[idx], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[idx]) }];
                var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    axisX: {
                        labelFontSize: 13,
                        labelAngle: -30,
                        interval: 1,
                        labelFontColor: "#19365f",
                        labelWrap: true,
                        labelMaxWidth: 120
                    },
                    axisY: { title: "Anos", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    toolTip: { enabled: false },
                    data: [{
                        type: "bar",
                        color: "#ff9f40",
                        indexLabelFontColor: "#ff9f40",
                        indexLabelFontWeight: "bold",
                        indexLabelPlacement: "outside",
                        dataPoints: dataPointsTempo
                    }]
                });
                chartTempo.render();
                setChartCanvasStyle("chartTempoMedio");
            }
            // Nível Hierárquico/Cargo - Doughnut moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                setChartContainerStyle("chartNivelHierarquico");
                let dataPointsNivel;
                if (idx === "all") {
                    dataPointsNivel = nivelLabels.map((l, i) => ({ label: l, y: nivelData[i], color: pieColors[i % pieColors.length] }));
                } else {
                    // Filtrar por equipa selecionada
                    const equipaNome = equipasLabels[idx];
                    // Obter dados de colaboradores por equipa e nível hierárquico
                    const colabsNivel = <?php echo json_encode($rhBLL->getColaboradoresNivelHierarquicoPorEquipa()); ?>;
                    const nivelCount = {};
                    colabsNivel.forEach(row => {
                        if (row.equipa_nome === equipaNome) {
                            nivelCount[row.nivel_hierarquico] = (nivelCount[row.nivel_hierarquico] || 0) + 1;
                        }
                    });
                    dataPointsNivel = Object.keys(nivelCount).map((nivel, i) => ({
                        label: nivel,
                        y: nivelCount[nivel],
                        color: pieColors[i % pieColors.length]
                    }));
                }
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    toolTip: { enabled: false },
                    data: [{ type: "doughnut", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: dataPointsNivel, indexLabelLineThickness: 0 }]
                });
                chartNivel.render();
                setChartCanvasStyle("chartNivelHierarquico");
            }

            // Distribuição Geográfica - Pie moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                setChartContainerStyle("chartGeografia");
                let dataPointsGeo;
                if (idx === "all") {
                    let geoLabels = <?php echo json_encode($geo_labels); ?>;
                    let geoData = <?php echo json_encode($geo_data); ?>;
                    let totalGeo = geoData.reduce((a, b) => a + b, 0);
                    dataPointsGeo = geoLabels.map((label, i) => ({
                        y: totalGeo > 0 ? Math.round((geoData[i] / totalGeo) * 1000) / 10 : 0,
                        label: label,
                        color: pieColors[i % pieColors.length]
                    }));
                } else {
                    // Filtrar por equipa selecionada
                    const equipaNome = equipasLabels[idx];
                    const colabsLocalidade = <?php echo json_encode($rhBLL->getColaboradoresLocalidadePorEquipa()); ?>;
                    const locCount = {};
                    let total = 0;
                    colabsLocalidade.forEach(row => {
                        if (row.equipa_nome === equipaNome) {
                            locCount[row.localidade] = (locCount[row.localidade] || 0) + 1;
                            total++;
                        }
                    });
                    dataPointsGeo = Object.keys(locCount).map((loc, i) => ({
                        y: total > 0 ? Math.round((locCount[loc] / total) * 1000) / 10 : 0,
                        label: loc,
                        color: pieColors[i % pieColors.length]
                    }));
                }
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    toolTip: { enabled: false },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0 }]
                });
                chartGeo.render();
                setChartCanvasStyle("chartGeografia");
            }

            // Distribuição de género - Pie moderno
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                setChartContainerStyle("chartGenero");
                let masc = 0, fem = 0, outro = 0, total = 0;
                if (idx === "all") {
                    for (let i = 0; i < equipasLabels.length; i++) {
                        masc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                        fem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                        outro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                    }
                    total = masc + fem + outro;
                } else {
                    // Filtrar por equipa selecionada
                    const equipaNome = equipasLabels[idx];
                    const generoEquipa = <?php echo json_encode($rhBLL->getDistribuicaoGeneroPorEquipa()); ?>;
                    if (generoEquipa[equipaNome]) {
                        Object.entries(generoEquipa[equipaNome]).forEach(([genero, count]) => {
                            const g = genero.trim().toLowerCase();
                            if (g === 'm' || g === 'masculino') masc += count;
                            else if (g === 'f' || g === 'feminino') fem += count;
                            else outro += count;
                            total += count;
                        });
                    }
                }
                let mascPercent = total > 0 ? (masc / total * 100) : 0;
                let femPercent = total > 0 ? (fem / total * 100) : 0;
                let outroPercent = total > 0 ? (outro / total * 100) : 0;
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
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    title: { text: "" },
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    toolTip: { enabled: false },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: [
                            { y: mascPercent, label: "Masculino", color: "#36a2eb" },
                            { y: femPercent, label: "Feminino", color: "#ff6384" },
                            { y: outroPercent, label: "Outro", color: "#b2dfdb" }
                        ],
                        indexLabelLineThickness: 0
                    }]
                });
                chartGenero.render();
                setChartCanvasStyle("chartGenero");
            }
        }

        // Função para desenhar todos os gráficos modernos fora dos cards
        function renderModernCharts(idx = "all") {
            // Colaboradores por Equipa
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartColab")) {
                let dataPoints = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: equipasMembros[i], color: "#36a2eb", indexLabel: String(equipasMembros[i]) })) : [{ label: equipasLabels[idx], y: equipasMembros[idx], color: "#36a2eb", indexLabel: String(equipasMembros[idx]) }];
                let chart = new CanvasJS.Chart("modernChartColab", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    axisX: {
                        labelFontSize: 13,
                        labelAngle: -30,
                        interval: 1,
                        labelFontColor: "#19365f",
                        labelWrap: true,
                        labelMaxWidth: 120
                    },
                    axisY: { title: "Colaboradores", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    data: [{
                        type: "column",
                        color: "#36a2eb",
                        indexLabelFontColor: "#19365f",
                        indexLabelFontWeight: "bold",
                        indexLabelPlacement: "outside",
                        dataPoints: dataPoints
                    }]
                });
                chart.render();
            }
            // Idades dos Colaboradores
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartIdade")) {
                let dataPointsIdade = [], axisXLabels = [];
                if (idx === "all") {
                    equipasLabels.forEach((l, i) => {
                        let ages = equipasIdades[l] || [];
                        ages.forEach((age, j) => {
                            let name = equipasColaboradores[l] && equipasColaboradores[l][j] ? equipasColaboradores[l][j] : `Colab ${j + 1}`;
                            dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(age), markerColor: "#764ba2", indexLabel: `${name} (${age})` });
                            axisXLabels.push(name);
                        });
                    });
                } else {
                    let ages = equipasIdades[equipasLabels[idx]] || [];
                    ages.forEach((age, j) => {
                        let name = equipasColaboradores[equipasLabels[idx]] && equipasColaboradores[equipasLabels[idx]][j] ? equipasColaboradores[equipasLabels[idx]][j] : `Colab ${j + 1}`;
                        dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(age), markerColor: "#764ba2", indexLabel: `${name} (${age})` });
                        axisXLabels.push(name);
                    });
                }
                let chartIdade = new CanvasJS.Chart("modernChartIdade", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    axisX: { labelFontSize: 11, labelAngle: -30, interval: 1, labelFontColor: "#19365f", valueFormatString: "#", labelFormatter: function(e) { return axisXLabels[e.value - 1] || ""; } },
                    axisY: { title: "Idade", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    data: [{ type: "splineArea", markerSize: 8, color: "#764ba2", fillOpacity: 0.3, toolTipContent: "{indexLabel}", dataPoints: dataPointsIdade }]
                });
                chartIdade.render();
            }
            // Tempo médio na empresa
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartTempo")) {
                let dataPointsTempo = idx === "all" ? equipasLabels.map((l, i) => ({ label: l, y: tempoMedioEmpresa[i], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[i]) })) : [{ label: equipasLabels[idx], y: tempoMedioEmpresa[idx], color: "#ff9f40", indexLabel: String(tempoMedioEmpresa[idx]) }];
                let chartTempo = new CanvasJS.Chart("modernChartTempo", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    axisX: {
                        labelFontSize: 13,
                        labelAngle: -30,
                        interval: 1,
                        labelFontColor: "#19365f",
                        labelWrap: true,
                        labelMaxWidth: 120
                    },
                    axisY: { title: "Anos", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    data: [{
                        type: "bar",
                        color: "#ff9f40",
                        indexLabelFontColor: "#ff9f40",
                        indexLabelFontWeight: "bold",
                        indexLabelPlacement: "outside",
                        dataPoints: dataPointsTempo
                    }]
                });
                chartTempo.render();
            }
            // Nível Hierárquico/Cargo
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartNivel")) {
                let dataPointsNivel = nivelLabels.map((l, i) => ({ label: l, y: nivelData[i], color: pieColors[i % pieColors.length] }));
                let chartNivel = new CanvasJS.Chart("modernChartNivel", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    legend: { 
                        verticalAlign: "bottom", 
                        fontSize: 13, 
                        fontColor: "#19365f",
                        // Adiciona legenda extra
                        itemclick: null,
                        dockInsidePlotArea: false,
                        fontFamily: "inherit"
                    },
                    subtitles: [{
                        text: "Legenda: 1 = Colaborador, 2 = Coordenador, 3 = RH",
                        fontSize: 13,
                        fontColor: "#888",
                        fontStyle: "italic",
                        margin: 8,
                        verticalAlign: "bottom",
                        dockInsidePlotArea: false
                    }],
                    data: [{ type: "doughnut", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: dataPointsNivel, indexLabelLineThickness: 0 }]
                });
                chartNivel.render();
            }
            // Distribuição Geográfica
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartGeo")) {
                let geoLabels = <?php echo json_encode($geo_labels); ?>;
                let geoData = <?php echo json_encode($geo_data); ?>;
                let totalGeo = geoData.reduce((a, b) => a + b, 0);
                let dataPointsGeo = geoLabels.map((label, i) => ({
                    y: totalGeo > 0 ? Math.round((geoData[i] / totalGeo) * 1000) / 10 : 0,
                    label: label,
                    toolTipContent: `${label}: ${geoData[i]} (${Math.round((geoData[i] / totalGeo) * 1000) / 10}%)`,
                    color: pieColors[i % pieColors.length]
                }));
                let chartGeo = new CanvasJS.Chart("modernChartGeo", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0 }]
                });
                chartGeo.render();
            }
            // Distribuição de género
            if (typeof CanvasJS !== "undefined" && document.getElementById("modernChartGenero")) {
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
                let chartGenero = new CanvasJS.Chart("modernChartGenero", {
                    animationEnabled: true,
                    backgroundColor: "#fff",
                    theme: "light2",
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: [
                            { y: mascPercent, label: "Masculino", color: "#36a2eb", toolTipContent: `Masculino: ${Math.round(totalMasc)} (${mascPercent}%)` },
                            { y: femPercent, label: "Feminino", color: "#ff6384", toolTipContent: `Feminino: ${Math.round(totalFem)} (${femPercent}%)` },
                            { y: outroPercent, label: "Outro", color: "#b2dfdb", toolTipContent: `Outro: ${Math.round(totalOutro)} (${outroPercent}%)` }
                        ],
                        indexLabelLineThickness: 0,
                        toolTipContent: "{toolTipContent}"
                    }]
                });
                chartGenero.render();
            }
        }

        // Renderiza todos os gráficos ao carregar a página
        document.addEventListener("DOMContentLoaded", function () {
            filtrarPorEquipa("all");
            renderModernCharts("all");
        });

        // Atualiza gráficos ao mudar equipa
        document.getElementById("equipaSelect").addEventListener("change", function() {
            let idx = this.value === "all" ? "all" : parseInt(this.value);
            filtrarPorEquipa(idx);
            renderModernCharts(idx);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>