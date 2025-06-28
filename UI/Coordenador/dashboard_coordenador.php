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

// Se não tem equipa, mostra dashboard vazia
$tem_dados = count($equipas_ids) > 0;

// Membros por equipa (apenas as equipas do coordenador)
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

// Idades dos colaboradores por equipa (apenas as equipas do coordenador)
$equipas_idades = [];
$equipas_idade_media = [];
foreach ($equipas_labels as $nome) {
    $equipas_idades[$nome] = [];
}
if ($tem_dados) {
    $idades = $coordBLL->getIdadesColaboradoresPorEquipa($userId);
    foreach ($idades as $row) {
        $nome = $row['equipa_nome'];
        $idade = $row['idade'];
        if (isset($equipas_idades[$nome])) {
            $equipas_idades[$nome][] = $idade;
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

// Nível hierárquico e cargos (apenas as equipas do coordenador)
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

// Tempo médio na empresa por equipa (apenas as equipas do coordenador)
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

// Nome do Coordenador
$nome = htmlspecialchars($coordBLL->getCoordenadorName($userId));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/dashboard_coordenador.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-statistics@7.8.3/dist/simple-statistics.min.js"></script>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_coordenador.php">Dashboard</a>
            <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
            <a href="equipa.php">Minha Equipa</a>
            <a href="relatorios_equipa.php">Relatórios Equipa</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Olá, <?php echo $nome; ?></h1>
        <section class="dashboard-cards">
            <div style="width:100%;text-align:center;font-size:1.1rem;margin-bottom:18px;">
                É coordenador da equipa: <?php echo implode(', ', $equipas_labels); ?>
            </div>
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
                <h2><i class="fa fa-sitemap" style="color:#36a2eb;"></i>Nível Hierárquico/Cargo</h2>
                <div class="nivel-legenda">
                    <strong>Legenda:</strong>
                    <ul>
                        <?php foreach ($nivel_labels as $i => $nivel): ?>
                            <li>
                                <span class="pie-color" style="background:<?php echo $pie_colors[$i % count($pie_colors)]; ?>"></span>
                                <?php
                                    // Mostrar apenas os cargos (sem número)
                                    if (!empty($nivel_cargos_count[$i])) {
                                        $cargosOnly = array_map(function($str) {
                                            // Remove o número entre parênteses no final
                                            return preg_replace('/\s*\(\d+\)$/', '', $str);
                                        }, $nivel_cargos_count[$i]);
                                        echo htmlspecialchars(implode(', ', $cargosOnly));
                                    } else {
                                        echo htmlspecialchars($nivel);
                                    }
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div id="chartNivelHierarquico" style="height: 320px;"></div>
                <div id="statsNivelHierarquico"></div>
            </div>
            <!-- Removido o card do tempo médio na empresa -->
        </section>
    </main>
    <script>
        const equipasLabels = <?php echo json_encode($equipas_labels); ?>;
        const equipasMembros = <?php echo json_encode($equipas_membros); ?>;
        const equipasIdadeMedia = <?php echo json_encode($equipas_idade_media); ?>;
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
                        indexLabel: String(equipasMembros[i])
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
                        y: Number(equipasIdadeMedia[i]),
                        color: "#764ba2",
                        indexLabel: String(equipasIdadeMedia[i])
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

                if (typeof ss !== "undefined" && axisYLabels.length > 0) {
                    const min = Math.min.apply(null, axisYLabels);
                    const max = ss.max(axisYLabels);
                    const avg = ss.mean(axisYLabels);
                    const med = ss.median(axisYLabels);
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

            // Nível Hierárquico/Cargo
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
                        // Mostra apenas o valor (quantidade), não o label
                        indexLabel: "{y}",
                        showInLegend: false,
                        dataPoints: dataPointsNivel,
                        indexLabelLineThickness: 0
                    }]
                });
                chartNivel.render();

                if (typeof ss !== "undefined" && nivelData.length > 0 && nivelData.some(x => x > 0)) {
                    const min = ss.min(nivelData.filter(x => x >= 0));
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
</body>
</html>