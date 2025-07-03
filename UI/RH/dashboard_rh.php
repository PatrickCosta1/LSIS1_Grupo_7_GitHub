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

$equipas_colaboradores = [];
$colabs = $rhBLL->getNomesColaboradoresPorEquipa(); // Precisas de criar este método na tua BLL
foreach ($colabs as $row) {
    $nome_equipa = $row['equipa_nome'];
    $nome_colab = $row['colaborador_nome'];
    if (!isset($equipas_colaboradores[$nome_equipa])) $equipas_colaboradores[$nome_equipa] = [];
    $equipas_colaboradores[$nome_equipa][] = $nome_colab;
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
// --- Distribuição geográfica ---
$geo_labels = [];
$geo_data = [];
$distribuicao_geografica = $rhBLL->getDistribuicaoGeografica(); // array: [localidade => total]
if ($distribuicao_geografica && is_array($distribuicao_geografica)) {
    foreach ($distribuicao_geografica as $localidade => $total) {
        $geo_labels[] = $localidade;
        $geo_data[] = (int)$total;
    }
}

// Novo: localidades dos colaboradores por equipa (para gráfico geográfico por equipa)
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

    <!-- Marca colorida acima do título -->
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        
    <h1 style="margin-bottom: 0;">Gestão RH - Dashboard</h1>
    <!-- Dropdown para seleção de equipa -->
    <div style="margin-bottom: 18px;">
        <label for="equipaSelect" style="font-weight:bold; margin-bottom:8px; display:inline-block;">Escolher Equipa:</label>
        <select id="equipaSelect" style="margin-left:8px; padding:4px 8px; margin-bottom:8px; border-radius:6px; border:1px solid #ccd; background:#f7f8fa;">
            <option value="all">Todas</option>
            <?php foreach ($equipas_labels as $idx => $nome_equipa): ?>
                <option value="<?php echo $idx; ?>"><?php echo htmlspecialchars($nome_equipa); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- DASHBOARD GRID -->
    <div class="dashboard-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px;">
        <!-- KPIs -->
        <div class="kpi-card" style="background:rgba(92, 176, 255, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">Total Colaboradores</div>
            <div id="kpiTotalColab" style="font-size:2.1em;color:#667eea;font-weight:bold;"><?php echo array_sum($equipas_membros); ?></div>
        </div>
        <div class="kpi-card" style="background:rgba(171, 69, 255, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">Média de Idade</div>
            <div id="kpiMediaIdade" style="font-size:2.1em;color:#764ba2;font-weight:bold;">
                <?php echo count($equipas_idade_media) && array_sum($equipas_idade_media) > 0 ? round(array_sum($equipas_idade_media)/count(array_filter($equipas_idade_media)),1) : '-'; ?>
            </div>
        </div>
        <div class="kpi-card" style="background:rgba(255, 180, 118, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">Tempo Médio Empresa</div>
            <div id="kpiTempoMedio" style="font-size:2.1em;color:#ff9f40;font-weight:bold;">
                <?php echo count($tempo_medio_empresa) && array_sum($tempo_medio_empresa) > 0 ? round(array_sum($tempo_medio_empresa)/count(array_filter($tempo_medio_empresa)),1).' anos' : '-'; ?>
            </div>
        </div>
        <div class="kpi-card" style="background:rgba(130, 255, 136, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">Remuneração Média</div>
            <div id="kpiRemuneracao" style="font-size:2.1em;color:#4CAF50;font-weight:bold;">
                <?php echo isset($equipas_remuneracao_media) && count($equipas_remuneracao_media) && array_sum($equipas_remuneracao_media) > 0 ? round(array_sum($equipas_remuneracao_media)/count(array_filter($equipas_remuneracao_media)),2).' €' : '-'; ?>
            </div>
        </div>
        <!-- Percentagem Masculino -->
        <div class="kpi-card" style="background:rgba(130, 236, 255, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">% Masculino</div>
            <div id="kpiPercentMasc" style="font-size:2.1em;color:#36a2eb;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    $masc_percent = $total_colab > 0 ? ($total_masc / $total_colab * 100) : 0;
                    $fem_percent = $total_colab > 0 ? ($total_fem / $total_colab * 100) : 0;
                    $outro_percent = $total_colab > 0 ? ($total_outro / $total_colab * 100) : 0;
                    $arr = [
                        ['val' => $masc_percent, 'idx' => 0],
                        ['val' => $fem_percent, 'idx' => 1],
                        ['val' => $outro_percent, 'idx' => 2]
                    ];
                    foreach ($arr as &$a) $a['rounded'] = round($a['val'] * 10) / 10;
                    unset($a);
                    $soma = $arr[0]['rounded'] + $arr[1]['rounded'] + $arr[2]['rounded'];
                    $diff = round((100 - $soma) * 10) / 10;
                    if (abs($diff) > 0) {
                        usort($arr, function($a, $b) { return abs($b['val']) <=> abs($a['val']); });
                        $arr[0]['rounded'] = round(($arr[0]['rounded'] + $diff) * 10) / 10;
                    }
                    usort($arr, function($a, $b) { return $a['idx'] <=> $b['idx']; });
                    $masc_percent = $arr[0]['rounded'];
                    echo $masc_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- Percentagem Feminino -->
        <div class="kpi-card" style="background:rgba(255, 163, 243, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">% Feminino</div>
            <div id="kpiPercentFem" style="font-size:2.1em;color:#ff6384;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    echo $fem_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- Percentagem Outro -->
        <div class="kpi-card" style="background:rgba(255, 238, 138, 0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;">
            <div style="font-size:15px;color:#666;">% Outro</div>
            <div id="kpiPercentOutro" style="font-size:2.1em;color:#DAA520;font-weight:bold;">
                <?php
                if ($total_colab > 0) {
                    echo $outro_percent . '%';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <!-- Espaço reservado para futura "Taxa de Retenção" -->
        <div class="kpi-card" style="background:rgba(128,128,128,0.2);backdrop-filter:blur(20px);border:1px solid rgba(160,160,160,0.3);border-radius:16px;padding:24px 20px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;overflow:hidden;opacity:0.5;">
            <div style="font-size:15px;color:#666;">Taxa de Retenção</div>
            <div id="kpiRetencao" style="font-size:2.1em;color:#4bc0c0;font-weight:bold;">
                <span style="color:#999;">(em breve)</span>
            </div>
        </div>
    </div>
    <!-- GRÁFICOS PRINCIPAIS -->
    <div style="display:grid;grid-template-columns:2fr 2fr;gap:18px;margin-bottom:24px;">
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Colaboradores por Equipa</div>
            <div id="chartContainer" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Idades dos Colaboradores</div>
            <div id="chartIdadeMedia" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Tempo Médio na Empresa</div>
            <div id="chartTempoMedio" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Nível Hierárquico/Cargo</div>
            <div id="chartNivelHierarquico" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Distribuição Geográfica</div>
            <div id="chartGeografia" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
        <div style="background:rgba(200,200,200,0.3);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.4);border-radius:16px;padding:24px 20px;box-shadow:0 8px 32px rgba(0,0,0,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div style="font-size:16px;font-weight:bold;margin-bottom:8px;color:#19365f;width:100%;">Distribuição de Género</div>
            <div id="chartGenero" style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;margin:12px auto 0 auto;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;width:100%;min-height:250px;"></div>
        </div>
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
        const equipasLocalidades = <?php echo json_encode($equipas_localidades); ?>;
        // Remover variáveis relacionadas à retenção
        // const retencaoPorEquipa = <?php //echo json_encode($retencao_por_equipa); ?>;
        // const retencaoGlobal = <?php //echo json_encode($retencao_global); ?>;

        // Tamanho fixo para todos os gráficos
        const CHART_WIDTH = 335;
        const CHART_HEIGHT = 230;
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
                    //document.getElementById('statsIdadeMedia').innerHTML =
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
                        showInLegend: true,
                        legendText: "{label}",
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
                    //document.getElementById('statsNivelHierarquico').innerHTML =
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
                    //document.getElementById('statsTempoMedio').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#ff9f40;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsTempoMedio').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
                }
            }

            // Gráfico circular de distribuição geográfica (por equipa ou global)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                let geoLabels = <?php echo json_encode($geo_labels); ?>;
                let geoData = <?php echo json_encode($geo_data); ?>;
                let dataPointsGeo = [];
                if (idx === "all") {
                    // Global
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
                } else {
                    // Por equipa
                    let equipaNome = equipasLabels[idx];
                    let localidades = equipasLocalidades[equipaNome] || [];
                    let localCount = {};
                    localidades.forEach(function(loc) {
                        localCount[loc] = (localCount[loc] || 0) + 1;
                    });
                    let totalGeo = localidades.length;
                    let i = 0;
                    for (let loc in localCount) {
                        let percent = totalGeo > 0 ? Math.round((localCount[loc] / totalGeo) * 1000) / 10 : 0;
                        dataPointsGeo.push({
                            y: percent,
                            label: loc,
                            toolTipContent: loc + ": " + localCount[loc] + " (" + percent + "%)",
                            color: pieColors[i % pieColors.length]
                        });
                        i++;
                    }
                }
                document.getElementById("chartGeografia").innerHTML = "";
                document.getElementById("chartGeografia").style.width = CHART_WIDTH + "px";
                document.getElementById("chartGeografia").style.height = CHART_HEIGHT + "px";
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: {
                        verticalAlign: "bottom",
                        fontSize: 14,
                        fontColor: "#3a366b"
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: dataPointsGeo,
                        indexLabelLineThickness: 0,
                        toolTipContent: "{toolTipContent}"
                    }]
                });
                chartGeo.render();
            }

            // Gráfico circular de distribuição de género (por equipa ou global)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                let mascPercent, femPercent, outroPercent, totalMasc, totalFem, totalOutro, totalGeral;
                if (idx === "all") {
                    // Global
                    totalMasc = 0; totalFem = 0; totalOutro = 0;
                    for (let i = 0; i < equipasLabels.length; i++) {
                        totalMasc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                        totalFem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                        totalOutro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                    }
                    totalGeral = totalMasc + totalFem + totalOutro;
                    mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                    femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                    outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                } else {
                    // Por equipa
                    totalMasc = isNaN(percentMasc[idx]) ? 0 : percentMasc[idx] / 100 * equipasMembros[idx];
                    totalFem = isNaN(percentFem[idx]) ? 0 : percentFem[idx] / 100 * equipasMembros[idx];
                    totalOutro = isNaN(percentOutro[idx]) ? 0 : percentOutro[idx] / 100 * equipasMembros[idx];
                    totalGeral = totalMasc + totalFem + totalOutro;
                    mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                    femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                    outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                }
                // Corrigir percentagens para garantir soma 100% (com precisão de 1 casa decimal)
                let arr = [
                    { val: mascPercent, idx: 0 },
                    { val: femPercent, idx: 1 },
                    { val: outroPercent, idx: 2 }
                ];
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
                    {
                        y: mascPercent,
                        label: "Masculino",
                        color: "#36a2eb",
                        toolTipContent: "Masculino: " + Math.round(totalMasc) + " (" + mascPercent + "%)"
                    },
                    {
                        y: femPercent,
                        label: "Feminino",
                        color: "#ff6384",
                        toolTipContent: "Feminino: " + Math.round(totalFem) + " (" + femPercent + "%)"
                    },
                    {
                        y: outroPercent,
                        label: "Outro",
                        color: "#b2dfdb",
                        toolTipContent: "Outro: " + Math.round(totalOutro) + " (" + outroPercent + "%)"
                    }
                ];
                document.getElementById("chartGenero").innerHTML = "";
                document.getElementById("chartGenero").style.width = CHART_WIDTH + "px";
                document.getElementById("chartGenero").style.height = CHART_HEIGHT + "px";
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: {
                        verticalAlign: "bottom",
                        fontSize: 14,
                        fontColor: "#3a366b"
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: dataPointsGenero,
                        indexLabelLineThickness: 0,
                        toolTipContent: "{toolTipContent}"
                    }]
                });
                chartGenero.render();
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
            // Percentagem masculino/feminino/outro - corrigido para percentagem global
            if (typeof percentMasc !== "undefined" && typeof percentFem !== "undefined" && typeof percentOutro !== "undefined") {
                // Calcular totais globais
                let totalMasc = 0, totalFem = 0, totalOutro = 0, totalGeral = 0;
                for (let i = 0; i < equipasLabels.length; i++) {
                    let masc = isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                    let fem = isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                    let outro = isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                    totalMasc += masc;
                    totalFem += fem;
                    totalOutro += outro;
                    totalGeral += equipasMembros[i];
                }
                let mascVal = totalGeral > 0 ? (totalMasc / totalGeral * 100).toFixed(1) + '%' : '-';
                let femVal = totalGeral > 0 ? (totalFem / totalGeral * 100).toFixed(1) + '%' : '-';
                let outroVal = totalGeral > 0 ? (totalOutro / totalGeral * 100).toFixed(1) + '%' : '-';
                document.getElementById("kpiPercentMasc").innerText =  mascVal;
                document.getElementById("kpiPercentFem").innerText = femVal;
                document.getElementById("kpiPercentOutro").innerText = outroVal;
            }
            // % Retenção global
            // document.getElementById("kpiRetencao").innerText = typeof retencaoGlobal !== "undefined" ? retencaoGlobal + "%" : "-";
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
            // % Retenção por equipa
            // document.getElementById("kpiRetencao").innerText = typeof retencaoPorEquipa !== "undefined" && retencaoPorEquipa[idx] !== undefined
            //     ? retencaoPorEquipa[idx] + "%" : "-";
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
            let axisXLabels = [];
            if (idx === "all") {
                for (let i = 0; i < equipasLabels.length; i++) {
                    let idades = equipasIdades[equipasLabels[i]] || [];
                    for (let j = 0; j < idades.length; j++) {
                        // Buscar nome do colaborador se disponível
                        let nomeColab = "Colab " + (j + 1);
                        if (window.equipasColaboradores && window.equipasColaboradores[equipasLabels[i]] && window.equipasColaboradores[equipasLabels[i]][j]) {
                            nomeColab = window.equipasColaboradores[equipasLabels[i]][j];
                        }
                        dataPointsIdade.push({
                            x: dataPointsIdade.length + 1,
                            y: Number(idades[j]),
                            markerColor: "#764ba2",
                            indexLabel: nomeColab + " (" + idades[j] + ")"
                        });
                        axisXLabels.push(nomeColab);
                        todasIdades.push(Number(idades[j]));
                    }
                }
            } else {
                let idades = equipasIdades[equipasLabels[idx]] || [];
                for (let j = 0; j < idades.length; j++) {
                    let nomeColab = "Colab " + (j + 1);
                    if (window.equipasColaboradores && window.equipasColaboradores[equipasLabels[idx]] && window.equipasColaboradores[equipasLabels[idx]][j]) {
                        nomeColab = window.equipasColaboradores[equipasLabels[idx]][j];
                    }
                    dataPointsIdade.push({
                        x: dataPointsIdade.length + 1,
                        y: Number(idades[j]),
                        markerColor: "#764ba2",
                        indexLabel: nomeColab + " (" + idades[j] + ")"
                    });
                    axisXLabels.push(nomeColab);
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
                    labelFontSize: 11,
                    labelAngle: -45,
                    interval: 1,
                    labelFontColor: "#3a366b",
                    valueFormatString: "#",
                    labelFormatter: function(e) {
                        return axisXLabels[e.value - 1] ? axisXLabels[e.value - 1] : "";
                    }
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
                    toolTipContent: "{indexLabel}",
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
                //document.getElementById('statsIdadeMedia').innerHTML =
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
                    //document.getElementById('statsTempoMedio').innerHTML =
                        `<strong>Estatísticas:</strong><br>
                        <span style="color:#ff9f40;">Mínimo:</span> ${min} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Máximo:</span> ${max} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Média:</span> ${avg.toFixed(2)} &nbsp; | &nbsp;
                        <span style="color:#ff9f40;">Mediana:</span> ${med}`;
                } else {
                    document.getElementById('statsTempoMedio').innerHTML = "<span style='color:#888;'>Sem dados para estatísticas.</span>";
                }
            }

            // Gráfico circular de distribuição geográfica (por equipa ou global)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGeografia")) {
                let geoLabels = <?php echo json_encode($geo_labels); ?>;
                let geoData = <?php echo json_encode($geo_data); ?>;
                let dataPointsGeo = [];
                if (idx === "all") {
                    // Global
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
                } else {
                    // Por equipa
                    let equipaNome = equipasLabels[idx];
                    let localidades = equipasLocalidades[equipaNome] || [];
                    let localCount = {};
                    localidades.forEach(function(loc) {
                        localCount[loc] = (localCount[loc] || 0) + 1;
                    });
                    let totalGeo = localidades.length;
                    let i = 0;
                    for (let loc in localCount) {
                        let percent = totalGeo > 0 ? Math.round((localCount[loc] / totalGeo) * 1000) / 10 : 0;
                        dataPointsGeo.push({
                            y: percent,
                            label: loc,
                            toolTipContent: loc + ": " + localCount[loc] + " (" + percent + "%)",
                            color: pieColors[i % pieColors.length]
                        });
                        i++;
                    }
                }
                document.getElementById("chartGeografia").innerHTML = "";
                document.getElementById("chartGeografia").style.width = CHART_WIDTH + "px";
                document.getElementById("chartGeografia").style.height = CHART_HEIGHT + "px";
                var chartGeo = new CanvasJS.Chart("chartGeografia", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: {
                        verticalAlign: "bottom",
                        fontSize: 14,
                        fontColor: "#3a366b"
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: dataPointsGeo,
                        indexLabelLineThickness: 0,
                        toolTipContent: "{toolTipContent}"
                    }]
                });
                chartGeo.render();
            }

            // Gráfico circular de distribuição de género (por equipa ou global)
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartGenero")) {
                let mascPercent, femPercent, outroPercent, totalMasc, totalFem, totalOutro, totalGeral;
                if (idx === "all") {
                    // Global
                    totalMasc = 0; totalFem = 0; totalOutro = 0;
                    for (let i = 0; i < equipasLabels.length; i++) {
                        totalMasc += isNaN(percentMasc[i]) ? 0 : percentMasc[i] / 100 * equipasMembros[i];
                        totalFem += isNaN(percentFem[i]) ? 0 : percentFem[i] / 100 * equipasMembros[i];
                        totalOutro += isNaN(percentOutro[i]) ? 0 : percentOutro[i] / 100 * equipasMembros[i];
                    }
                    totalGeral = totalMasc + totalFem + totalOutro;
                    mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                    femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                    outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                } else {
                    // Por equipa
                    totalMasc = isNaN(percentMasc[idx]) ? 0 : percentMasc[idx] / 100 * equipasMembros[idx];
                    totalFem = isNaN(percentFem[idx]) ? 0 : percentFem[idx] / 100 * equipasMembros[idx];
                    totalOutro = isNaN(percentOutro[idx]) ? 0 : percentOutro[idx] / 100 * equipasMembros[idx];
                    totalGeral = totalMasc + totalFem + totalOutro;
                    mascPercent = totalGeral > 0 ? (totalMasc / totalGeral * 100) : 0;
                    femPercent = totalGeral > 0 ? (totalFem / totalGeral * 100) : 0;
                    outroPercent = totalGeral > 0 ? (totalOutro / totalGeral * 100) : 0;
                }
                // Corrigir percentagens para garantir soma 100% (com precisão de 1 casa decimal)
                let arr = [
                    { val: mascPercent, idx: 0 },
                    { val: femPercent, idx: 1 },
                    { val: outroPercent, idx: 2 }
                ];
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
                    {
                        y: mascPercent,
                        label: "Masculino",
                        color: "#36a2eb",
                        toolTipContent: "Masculino: " + Math.round(totalMasc) + " (" + mascPercent + "%)"
                    },
                    {
                        y: femPercent,
                        label: "Feminino",
                        color: "#ff6384",
                        toolTipContent: "Feminino: " + Math.round(totalFem) + " (" + femPercent + "%)"
                    },
                    {
                        y: outroPercent,
                        label: "Outro",
                        color: "#b2dfdb",
                        toolTipContent: "Outro: " + Math.round(totalOutro) + " (" + outroPercent + "%)"
                    }
                ];
                document.getElementById("chartGenero").innerHTML = "";
                document.getElementById("chartGenero").style.width = CHART_WIDTH + "px";
                document.getElementById("chartGenero").style.height = CHART_HEIGHT + "px";
                var chartGenero = new CanvasJS.Chart("chartGenero", {
                    width: CHART_WIDTH,
                    height: CHART_HEIGHT,
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "light2",
                    title: { text: "" },
                    legend: {
                        verticalAlign: "bottom",
                        fontSize: 14,
                        fontColor: "#3a366b"
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{label}: {y}%",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: dataPointsGenero,
                        indexLabelLineThickness: 0,
                        toolTipContent: "{toolTipContent}"
                    }]
                });
                chartGenero.render();
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