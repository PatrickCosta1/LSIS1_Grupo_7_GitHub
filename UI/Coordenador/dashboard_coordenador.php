<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$userId = $_SESSION['user_id'];

// Buscar apenas a(s) equipa(s) do coordenador
$equipas = $coordBLL->getEquipasByCoordenador($userId);
$equipas_labels = [];
$equipas_ids = [];
foreach ($equipas as $e) {
    $equipas_labels[] = $e['nome'];
    $equipas_ids[] = $e['id'];
}
$tem_dados = count($equipas_ids) > 0;

// Membros por equipa
$equipas_membros = [];
if ($tem_dados) {
    $equipasComMembros = $coordBLL->getEquipasComMembros($userId);
    foreach ($equipas_labels as $nome) {
        $found = false;
        foreach ($equipasComMembros as $e) {
            if ($e['nome'] === $nome) {
                $equipas_membros[] = (int)$e['num_colaboradores'];
                $found = true;
                break;
            }
        }
        if (!$found) $equipas_membros[] = 0;
    }
}

// Idades dos colaboradores por equipa
$equipas_idades = [];
$equipas_idade_media = [];
$equipas_colaboradores = []; // Novo array para nomes dos colaboradores
foreach ($equipas_labels as $nome) {
    $equipas_idades[$nome] = [];
    $equipas_colaboradores[$nome] = [];
}
if ($tem_dados) {
    $idades = $coordBLL->getIdadesColaboradoresPorEquipa($userId);
    foreach ($idades as $row) {
        $nome = $row['equipa_nome'];
        $idade = $row['idade'];
        $colab_nome = isset($row['colaborador_nome']) ? $row['colaborador_nome'] : null;
        if (isset($equipas_idades[$nome])) {
            $equipas_idades[$nome][] = $idade;
            $equipas_colaboradores[$nome][] = $colab_nome;
        }
    }
    foreach ($equipas_labels as $nome) {
        if (!empty($equipas_idades[$nome])) {
            $equipas_idade_media[] = round(array_sum($equipas_idades[$nome]) / count($equipas_idades[$nome]), 1);
        } else {
            $equipas_idade_media[] = 0;
        }
    }
}

// Tempo médio na empresa por equipa
$tempos = $tem_dados ? $coordBLL->getTemposNaEmpresaPorEquipa($userId) : [];
$tempos_na_empresa = [];
$tempos_na_empresa_media = [];
foreach ($equipas_labels as $nome) {
    $tempos_na_empresa[$nome] = [];
}
foreach ($tempos as $row) {
    $nome = $row['equipa_nome'];
    $anos = $row['anos'];
    if (isset($tempos_na_empresa[$nome])) {
        $tempos_na_empresa[$nome][] = $anos;
    }
}
foreach ($equipas_labels as $nome) {
    if (!empty($tempos_na_empresa[$nome])) {
        $tempos_na_empresa_media[] = round(array_sum($tempos_na_empresa[$nome]) / count($tempos_na_empresa[$nome]), 2);
    } else {
        $tempos_na_empresa_media[] = 0;
    }
}

// Remuneração média por equipa
$equipas_remuneracao_media = [];
if (method_exists($coordBLL, 'getRemuneracaoMediaPorEquipa')) {
    $remuneracao_media_raw = $coordBLL->getRemuneracaoMediaPorEquipa($userId);
    foreach ($equipas_labels as $nome_equipa) {
        $equipas_remuneracao_media[] = isset($remuneracao_media_raw[$nome_equipa]) ? round($remuneracao_media_raw[$nome_equipa], 2) : 0;
    }
}

// Percentagem de masculino/feminino/outro por equipa
$percent_masculino = [];
$percent_feminino = [];
$percent_outro = [];
$genero_equipa_raw = [];
if (method_exists($coordBLL, 'getDistribuicaoGeneroPorEquipa')) {
    $genero_equipa_raw = $coordBLL->getDistribuicaoGeneroPorEquipa($userId);
    foreach ($equipas_labels as $nome_equipa) {
        $masc = 0; $fem = 0; $outro = 0; $total = 0;
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
    }
}

// Distribuição geográfica por equipa
$equipas_localidades = [];
$geo_labels = [];
$geo_data = [];
if (method_exists($coordBLL, 'getColaboradoresLocalidadePorEquipa')) {
    $colabs_localidade = $coordBLL->getColaboradoresLocalidadePorEquipa($userId);
    foreach ($equipas_labels as $nome_equipa) {
        $equipas_localidades[$nome_equipa] = [];
    }
    foreach ($colabs_localidade as $row) {
        $eq = $row['equipa_nome'];
        $loc = $row['localidade'];
        if (isset($equipas_localidades[$eq])) {
            $equipas_localidades[$eq][] = $loc;
        }
    }
}

// Nível hierárquico e cargos
$nivel = $tem_dados ? $coordBLL->getDistribuicaoNivelHierarquico($userId) : [];
$cargosPorNivel = $tem_dados ? $coordBLL->getCargosPorNivelHierarquico($userId) : [];
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

// Nome do Coordenador
$nome = htmlspecialchars($coordBLL->getCoordenadorName($userId));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/dashboard_coordenador.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-statistics@7.8.3/dist/simple-statistics.min.js"></script>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_coordenador.php';">
        <nav>
            <?php
                $equipaLink = "equipa.php";
                if (!empty($equipas) && isset($equipas[0]['id'])) {
                    $equipaLink = "equipa.php?id=" . urlencode($equipas[0]['id']);
                }
            ?>
            <div class="dropdown-equipa">
                <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                    Equipa
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="dashboard_coordenador.php">Dashboard</a>
                    <a href="relatorios_equipa.php">Relatórios Equipa</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                </div>
            </div>
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
        <h1>Gestão Coordenador - Dashboard</h1>
        <div style="margin-bottom: 18px; text-align: center;">
            <label for="equipaSelect" style="font-weight:bold; margin-bottom:8px; display:inline-block;">Escolher Equipa:</label>
            <select id="equipaSelect" style="margin-left:8px; padding:4px 8px; border-radius:6px; border:1px solid #ccd; background:#f7f8fa;">
                <?php foreach ($equipas_labels as $idx => $nome_equipa): ?>
                    <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($nome_equipa); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="dashboard-grid">
            <div class="kpi-card" style="background:rgba(92, 176, 255, 0.2);">
                <div style="font-size:15px;color:#666;">Total Colaboradores</div>
                <div id="kpiTotalColab" style="font-size:2.1em;color:#667eea;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(171, 69, 255, 0.2);">
                <div style="font-size:15px;color:#666;">Média de Idade</div>
                <div id="kpiMediaIdade" style="font-size:2.1em;color:#764ba2;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 180, 118, 0.2);">
                <div style="font-size:15px;color:#666;">Tempo Médio Empresa</div>
                <div id="kpiTempoMedio" style="font-size:2.1em;color:#ff9f40;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(130, 255, 136, 0.2);">
                <div style="font-size:15px;color:#666;">Remuneração Média</div>
                <div id="kpiRemuneracao" style="font-size:2.1em;color:#4CAF50;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(130, 236, 255, 0.2);">
                <div style="font-size:15px;color:#666;">% Masculino</div>
                <div id="kpiPercentMasc" style="font-size:2.1em;color:#36a2eb;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 163, 243, 0.2);">
                <div style="font-size:15px;color:#666;">% Feminino</div>
                <div id="kpiPercentFem" style="font-size:2.1em;color:#ff6384;font-weight:bold;"></div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 238, 138, 0.2);">
                <div style="font-size:15px;color:#666;">% Outro</div>
                <div id="kpiPercentOutro" style="font-size:2.1em;color:#DAA520;font-weight:bold;"></div>
            </div>
        </div>
        <div class="dashboard-main-charts">
            <div class="chart-card">
                <div class="chart-card-title">Colaboradores por Equipa</div>
                <div id="chartContainer" class="chart-area"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Idades dos Colaboradores</div>
                <div id="chartIdadeMedia" class="chart-area"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Tempo Médio na Empresa</div>
                <div id="chartTempoMedio" class="chart-area"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Nível Hierárquico/Cargo</div>
                <div id="chartNivelHierarquico" class="chart-area"></div>
                <span style="display:block;font-size:0.95em;font-weight:400;color:#888;margin-top:4px;">
                    <em>Legenda: 1 = Colaborador, 2 = Coordenador, 3 = RH</em>
                </span>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Distribuição Geográfica</div>
                <div id="chartGeografia" class="chart-area"></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Distribuição de Género</div>
                <div id="chartGenero" class="chart-area"></div>
            </div>
        </div>
    </main>
    <script>
        const equipasLabels = <?php echo json_encode($equipas_labels); ?>;
        const equipasMembros = <?php echo json_encode($equipas_membros); ?>;
        const equipasIdadeMedia = <?php echo json_encode($equipas_idade_media); ?>;
        const temposNaEmpresaMedia = <?php echo json_encode($tempos_na_empresa_media); ?>;
        const nivelLabels = <?php echo json_encode($nivel_labels); ?>;
        const nivelData = <?php echo json_encode($nivel_data); ?>;
        const pieColors = <?php echo json_encode($pie_colors); ?>;
        const equipasIdades = <?php echo json_encode($equipas_idades); ?>;
        const equipasColaboradores = <?php echo json_encode($equipas_colaboradores); ?>;
        const equipasRemuneracaoMedia = <?php echo json_encode($equipas_remuneracao_media); ?>;
        const percentMasc = <?php echo json_encode($percent_masculino); ?>;
        const percentFem = <?php echo json_encode($percent_feminino); ?>;
        const percentOutro = <?php echo json_encode($percent_outro); ?>;
        const equipasLocalidades = <?php echo json_encode($equipas_localidades); ?>;

        function atualizarKPIs(idx) {
            idx = parseInt(idx);
            document.getElementById("kpiTotalColab").innerText = equipasMembros[idx] ?? '-';
            document.getElementById("kpiMediaIdade").innerText = equipasIdadeMedia[idx] > 0 ? equipasIdadeMedia[idx] : '-';
            document.getElementById("kpiTempoMedio").innerText = temposNaEmpresaMedia[idx] > 0 ? temposNaEmpresaMedia[idx]+' anos' : '-';
            document.getElementById("kpiRemuneracao").innerText = equipasRemuneracaoMedia[idx] > 0 ? equipasRemuneracaoMedia[idx]+' €' : '-';
            document.getElementById("kpiPercentMasc").innerText = percentMasc[idx] > 0 ? percentMasc[idx]+'%' : '-';
            document.getElementById("kpiPercentFem").innerText = percentFem[idx] > 0 ? percentFem[idx]+'%' : '-';
            document.getElementById("kpiPercentOutro").innerText = percentOutro[idx] > 0 ? percentOutro[idx]+'%' : '-';
        }

        function filtrarPorEquipa(idx) {
            atualizarKPIs(idx);

            // Colaboradores por Equipa (apenas a equipa selecionada)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartContainer")) {
                let dataPoints = [{ label: equipasLabels[idx], y: equipasMembros[idx], color: "#36a2eb", indexLabel: String(equipasMembros[idx]) }];
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
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
            }

            

            // Idades dos Colaboradores
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartIdadeMedia")) {
                let dataPointsIdade = [], axisXLabels = [];
                let ages = equipasIdades[equipasLabels[idx]] || [];
                let nomes = equipasColaboradores[equipasLabels[idx]] || [];
                ages.forEach((age, j) => {
                    let name = nomes[j] ? nomes[j] : `Colab ${j + 1}`;
                    dataPointsIdade.push({ x: dataPointsIdade.length + 1, y: Number(age), markerColor: "#764ba2", indexLabel: `${name} (${age})` });
                    axisXLabels.push(name);
                });
                var chartIdade = new CanvasJS.Chart("chartIdadeMedia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    axisX: { labelFontSize: 11, labelAngle: -30, interval: 1, labelFontColor: "#19365f", valueFormatString: "#", labelFormatter: function(e) { return axisXLabels[e.value - 1] || ""; } },
                    axisY: { title: "Idade", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    toolTip: { enabled: false },
                    data: [{ type: "splineArea", markerSize: 8, color: "#764ba2", fillOpacity: 0.3, toolTipContent: "{indexLabel}", dataPoints: dataPointsIdade }]
                });
                chartIdade.render();
            }
            // Tempo médio na empresa
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartTempoMedio")) {
                let dataPointsTempo = [{ label: equipasLabels[idx], y: temposNaEmpresaMedia[idx], color: "#ff9f40", indexLabel: String(temposNaEmpresaMedia[idx]) }];
                var chartTempo = new CanvasJS.Chart("chartTempoMedio", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
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
            }
            // Nível Hierárquico/Cargo
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartNivelHierarquico")) {
                let dataPointsNivel = nivelLabels.map((l, i) => ({ label: l, y: nivelData[i], color: pieColors[i % pieColors.length] }));
                var chartNivel = new CanvasJS.Chart("chartNivelHierarquico", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    toolTip: { enabled: false },
                    data: [{ type: "doughnut", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: dataPointsNivel, indexLabelLineThickness: 0 }]
                });
                chartNivel.render();
            }
            // Distribuição Geográfica
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                let localidades = equipasLocalidades[equipasLabels[idx]] || [];
                let locCount = {};
                localidades.forEach(loc => {
                    locCount[loc] = (locCount[loc] || 0) + 1;
                });
                let total = localidades.length;
                let dataPointsGeo = Object.keys(locCount).map((loc, i) => ({
                    y: total > 0 ? Math.round((locCount[loc] / total) * 1000) / 10 : 0,
                    label: loc,
                    color: pieColors[i % pieColors.length]
                }));
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
                    legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                    toolTip: { enabled: false },
                    data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0 }]
                });
                chartGeo.render();
            }
            // Distribuição de género
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                let mascPercent = percentMasc[idx];
                let femPercent = percentFem[idx];
                let outroPercent = percentOutro[idx];
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light1",
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
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let idx = 0;
            document.getElementById("equipaSelect").selectedIndex = 0;
            filtrarPorEquipa(idx);
        });

        document.getElementById("equipaSelect").addEventListener("change", function() {
            let idx = parseInt(this.value);
            filtrarPorEquipa(idx);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>