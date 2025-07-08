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

$colabs = $rhBLL->getNomesColaboradoresPorEquipaComGenero();
$colaboradores_unicos = [];
$equipas_colaboradores = [];
$ids_processados_global = []; // Array para garantir IDs únicos globalmente

foreach ($colabs as $row) {
    $nome_equipa = $row['equipa_nome'];
    $nome_colab = $row['colaborador_nome'];
    $id_colab = isset($row['colaborador_id']) ? $row['colaborador_id'] : null;
    
    if (!isset($equipas_colaboradores[$nome_equipa])) $equipas_colaboradores[$nome_equipa] = [];
    $equipas_colaboradores[$nome_equipa][] = $nome_colab;
    
    // Verificação de ID único: só adiciona se ainda não foi processado
    if ($id_colab !== null && !isset($ids_processados_global[$id_colab])) {
        $colaboradores_unicos[$id_colab] = $row;
        $ids_processados_global[$id_colab] = true; // Marca como processado
    }
}
$total_colaboradores_unicos = count($colaboradores_unicos);

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

// Percentagem de masculino/feminino/outro por equipa - CORRIGIR CÁLCULO
$genero_equipa_raw = $rhBLL->getDistribuicaoGeneroPorEquipa();
$percent_masculino = [];
$percent_feminino = [];
$percent_outro = [];
$total_masc_global = 0;
$total_fem_global = 0;
$total_outro_global = 0;

// Calcular percentuais globais usando IDs únicos
foreach ($colaboradores_unicos as $id => $colab) {
    $genero_norm = strtolower(trim($colab['sexo'] ?? ''));
    if ($genero_norm === 'm' || $genero_norm === 'masculino') {
        $total_masc_global++;
    } elseif ($genero_norm === 'f' || $genero_norm === 'feminino') {
        $total_fem_global++;
    } else {
        $total_outro_global++;
    }
}

// Calcular percentuais por equipa
foreach ($equipas_labels as $nome_equipa) {
    $masc = 0; $fem = 0; $outro = 0; $total = 0;
    $ids_processados_equipa = [];
    
    foreach ($colabs as $colab) {
        if ($colab['equipa_nome'] === $nome_equipa && 
            isset($colab['colaborador_id']) && 
            !isset($ids_processados_equipa[$colab['colaborador_id']])) {
            
            $ids_processados_equipa[$colab['colaborador_id']] = true;
            $genero_norm = strtolower(trim($colab['sexo'] ?? ''));
            
            if ($genero_norm === 'm' || $genero_norm === 'masculino') $masc++;
            elseif ($genero_norm === 'f' || $genero_norm === 'feminino') $fem++;
            else $outro++;
            $total++;
        }
    }
    
    $percent_masculino[] = $total > 0 ? round($masc / $total * 100, 1) : 0;
    $percent_feminino[] = $total > 0 ? round($fem / $total * 100, 1) : 0;
    $percent_outro[] = $total > 0 ? round($outro / $total * 100, 1) : 0;
}

// Calcular percentuais globais finais
$masc_percent_global = $total_colaboradores_unicos > 0 ? ($total_masc_global / $total_colaboradores_unicos * 100) : 0;
$fem_percent_global = $total_colaboradores_unicos > 0 ? ($total_fem_global / $total_colaboradores_unicos * 100) : 0;
$outro_percent_global = $total_colaboradores_unicos > 0 ? ($total_outro_global / $total_colaboradores_unicos * 100) : 0;

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

// Taxa de retenção
$ano_selecionado = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
$retencao_por_equipa_assoc = $rhBLL->getTaxaRetencaoPorEquipa($ano_selecionado);
$retencao_global = $rhBLL->getTaxaRetencaoGlobal($ano_selecionado);
// Garantir que a ordem dos arrays corresponde à ordem de $equipas_labels
$retencao_por_equipa = [];
foreach ($equipas_labels as $nome_equipa) {
    $retencao_por_equipa[] = isset($retencao_por_equipa_assoc[$nome_equipa]) ? $retencao_por_equipa_assoc[$nome_equipa] : 0;
}

// Calcular média simples das taxas de retenção para usar no perfil médio (DEPOIS de definir $retencao_por_equipa)
$taxas_retencao_validas = array_filter($retencao_por_equipa, function($taxa) {
    return $taxa !== null && !is_nan($taxa) && is_numeric($taxa) && $taxa > 0;
});
$retencao_media_equipas = count($taxas_retencao_validas) > 0 ? (array_sum($taxas_retencao_validas) / count($taxas_retencao_validas)) : 0;
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
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="gerir_formacoes.php">Gerir Formações</a>
                    <a href="gerir_recibos.php">Submeter Recibos</a>
                    <a href="campos_personalizados.php">Campos Personalizados</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Perfil Colaborador</a>
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
                <div id="kpiTotalColab" style="font-size:2.1em;color:#667eea;font-weight:bold;"><?php echo $total_colaboradores_unicos; ?></div>
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
                    <?php echo $total_colaboradores_unicos > 0 ? round($masc_percent_global, 1) . '%' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 163, 243, 0.2);">
                <div style="font-size:15px;color:#666;">% Feminino</div>
                <div id="kpiPercentFem" style="font-size:2.1em;color:#ff6384;font-weight:bold;">
                    <?php echo $total_colaboradores_unicos > 0 ? round($fem_percent_global, 1) . '%' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 238, 138, 0.2);">
                <div style="font-size:15px;color:#666;">% Outro</div>
                <div id="kpiPercentOutro" style="font-size:2.1em;color:#DAA520;font-weight:bold;">
                    <?php echo $total_colaboradores_unicos > 0 ? round($outro_percent_global, 1) . '%' : '-'; ?>
                </div>
            </div>
            <div class="kpi-card" style="background:rgba(255, 210, 90, 0.18);">
                <div style="font-size:15px;color:#666;">Taxa de Retenção</div>
                <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                    <div id="kpiRetencao" style="font-size:2.1em;color:#DAA520;font-weight:bold;">
                        <?php echo $retencao_global !== null ? $retencao_global.'%' : '-'; ?>
                    </div>
                    <div>
                        <select id="anoRetencaoSelect" style="margin-left:6px;padding:2px 8px;border-radius:6px;border:1px solid #ccd;background:#f7f8fa;font-size:1em;">
                            <?php
                            // Gera anos de 2018 até o ano atual, selecionando o atual por padrão
                            $anoAtual = date('Y');
                            for ($ano = $anoAtual; $ano >= 2018; $ano--) {
                                echo '<option value="'.$ano.'"'.($ano == $anoAtual ? ' selected' : '').'>'.$ano.'</option>';
                            }
                            ?>
                        </select>
                    </div>
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
                <div class="chart-card-title">Nível Hierárquico/Perfil</div>
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
            <div class="chart-card">
                <div class="chart-card-title">Taxa de Retenção por Equipa</div>
                <div id="chartRetencao" class="chart-area"></div>
            </div>
        </div>
        <!-- Perfil Médio do Colaborador -->
        <div class="kpi-card" style="background:rgba(255,255,255,0.95);border:2px solid #299cf3;grid-column: span 2;box-shadow:0 4px 24px rgba(41,156,243,0.08);margin:32px auto 0;max-width:900px;">
            <div style="font-size:17px;color:#299cf3;font-weight:bold;margin-bottom:8px;">
                Perfil Médio da Empresa
            </div>
            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;gap:12px;">
                <div style="flex:1 1 120px;">
                    <div style="font-size:13px;color:#888;">Remuneração Média</div>
                    <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                        <?php
                        $media_salario = isset($equipas_remuneracao_media) && count($equipas_remuneracao_media) && array_sum($equipas_remuneracao_media) > 0
                            ? round(array_sum($equipas_remuneracao_media)/count(array_filter($equipas_remuneracao_media)),2).' €'
                            : '-';
                        echo $media_salario;
                        ?>
                    </div>
                </div>
                <div style="flex:1 1 120px;">
                    <div style="font-size:13px;color:#888;">Idade Média</div>
                    <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                        <?php
                        $media_idade = count($equipas_idade_media) && array_sum($equipas_idade_media) > 0
                            ? round(array_sum($equipas_idade_media)/count(array_filter($equipas_idade_media)),1)
                            : '-';
                        echo $media_idade;
                        ?>
                    </div>
                </div>
                <div style="flex:1 1 120px;">
                    <div style="font-size:13px;color:#888;">Tempo Médio Empresa</div>
                    <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                        <?php
                        $media_tempo = count($tempo_medio_empresa) && array_sum($tempo_medio_empresa) > 0
                            ? round(array_sum($tempo_medio_empresa)/count(array_filter($tempo_medio_empresa)),1).' anos'
                            : '-';
                        echo $media_tempo;
                        ?>
                    </div>
                </div>
                
                <div style="flex:1 1 120px;">
                    <div style="font-size:13px;color:#888;">Taxa de Retenção</div>
                    <div id="perfilRetencao" style="font-size:1.3em;color:#19365f;font-weight:bold;">
                        <?php echo $retencao_media_equipas > 0 ? round($retencao_media_equipas, 1).'%' : '-'; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Comparação Equipa vs Perfil Médio (Todos os Indicadores) -->
        <div class="kpi-card" style="background:rgba(255,255,255,0.98);border:2px solid #764ba2;grid-column: span 2;box-shadow:0 4px 24px rgba(118,75,162,0.08);margin:32px auto 0;max-width:900px;">
            <div style="font-size:17px;color:#764ba2;font-weight:bold;margin-bottom:8px;">
                Comparação Equipa Selecionada vs Perfil Médio
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:32px;justify-content:space-between;">
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">Total Colaboradores</div>
                    <div id="chartCompTotalColab" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">Idade Média</div>
                    <div id="chartCompIdade" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">Tempo Médio Empresa</div>
                    <div id="chartCompTempo" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">Remuneração Média</div>
                    <div id="chartCompRemuneracao" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">% Masculino</div>
                    <div id="chartCompMasc" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">% Feminino</div>
                    <div id="chartCompFem" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">% Outro</div>
                    <div id="chartCompOutro" style="width:100%;height:180px;"></div>
                </div>
                <div style="flex:1 1 180px;min-width:160px;">
                    <div style="font-size:13px;color:#888;margin-bottom:4px;">Taxa de Retenção</div>
                    <div id="chartCompRetencao" style="width:100%;height:180px;"></div>
                </div>
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
        const retencaoPorEquipa = <?php echo json_encode($retencao_por_equipa); ?>;
        const retencaoLabels = <?php echo json_encode($equipas_labels); ?>;
        const retencaoGlobal = <?php echo json_encode($retencao_global); ?>;
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

        // Função para animar valores numéricos dos KPIs
        function animateKPIValue(elementId, finalValue, sufix = '', decimals = 0, duration = 1000) {
            const el = document.getElementById(elementId);
            if (!el) return;
            let start = 0;
            let end = Number(finalValue);
            if (isNaN(end)) {
                el.innerText = finalValue; // fallback para string
                return;
            }
            let startTimestamp = null;
            let current = 0;
            function step(timestamp) {
                if (!startTimestamp) startTimestamp = timestamp;
                let progress = Math.min((timestamp - startTimestamp) / duration, 1);
                current = start + (end - start) * progress;
                el.innerText = decimals > 0 ? current.toFixed(decimals) + sufix : Math.round(current) + sufix;
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.innerText = decimals > 0 ? end.toFixed(decimals) + sufix : Math.round(end) + sufix;
                }
            }
            requestAnimationFrame(step);
        }

        function atualizarKPIs(idx) {
            if (idx === "all") {
                // Total Colaboradores - usar contagem de IDs únicos
                let totalColab = <?php echo $total_colaboradores_unicos; ?>;
                animateKPIValue("kpiTotalColab", totalColab, '', 0);

                // Média de Idade
                let medias = equipasIdadeMedia.filter(x=>x>0);
                let mediaIdade = medias.length ? (medias.reduce((a,b)=>a+b,0)/medias.length) : '-';
                if (mediaIdade !== '-') animateKPIValue("kpiMediaIdade", mediaIdade, '', 1);
                else document.getElementById("kpiMediaIdade").innerText = '-';

                // Tempo Médio Empresa
                let tempos = tempoMedioEmpresa.filter(x=>x>0);
                let tempoMedio = tempos.length ? (tempos.reduce((a,b)=>a+b,0)/tempos.length) : '-';
                if (tempoMedio !== '-') animateKPIValue("kpiTempoMedio", tempoMedio, ' anos', 1);
                else document.getElementById("kpiTempoMedio").innerText = '-';

                // Remuneração Média
                let rems = equipasRemuneracaoMedia.filter(x=>x>0);
                let remMedia = rems.length ? (rems.reduce((a,b)=>a+b,0)/rems.length) : '-';
                if (remMedia !== '-') animateKPIValue("kpiRemuneracao", remMedia, ' €', 2);
                else document.getElementById("kpiRemuneracao").innerText = '-';

                // Percentuais - usar valores globais corretos
                let mascVal = <?php echo round($masc_percent_global, 1); ?>;
                let femVal = <?php echo round($fem_percent_global, 1); ?>;
                let outroVal = <?php echo round($outro_percent_global, 1); ?>;
                
                if (mascVal >= 0) animateKPIValue("kpiPercentMasc", mascVal, '%', 1);
                else document.getElementById("kpiPercentMasc").innerText = '-';
                if (femVal >= 0) animateKPIValue("kpiPercentFem", femVal, '%', 1);
                else document.getElementById("kpiPercentFem").innerText = '-';
                if (outroVal >= 0) animateKPIValue("kpiPercentOutro", outroVal, '%', 1);
                else document.getElementById("kpiPercentOutro").innerText = '-';

                // Retenção - calcular média simples das taxas de cada equipa
                let taxasValidas = retencaoPorEquipa.filter(taxa => taxa !== undefined && taxa !== null && !isNaN(taxa));
                let retencaoMedia = taxasValidas.length > 0 ? (taxasValidas.reduce((a,b) => a+b, 0) / taxasValidas.length) : 0;
                
                if (retencaoMedia > 0) 
                    animateKPIValue("kpiRetencao", retencaoMedia, '%', 1);
                else
                    document.getElementById("kpiRetencao").innerText = '-';
            } else {
                idx = parseInt(idx);
                // Total Colaboradores
                let totalColab = equipasMembros[idx] ?? '-';
                if (totalColab !== '-') animateKPIValue("kpiTotalColab", totalColab, '', 0);
                else document.getElementById("kpiTotalColab").innerText = '-';

                // Média de Idade
                let mediaIdade = equipasIdadeMedia[idx] > 0 ? equipasIdadeMedia[idx] : '-';
                if (mediaIdade !== '-') animateKPIValue("kpiMediaIdade", mediaIdade, '', 1);
                else document.getElementById("kpiMediaIdade").innerText = '-';

                // Tempo Médio Empresa
                let tempoMedio = tempoMedioEmpresa[idx] > 0 ? tempoMedioEmpresa[idx] : '-';
                if (tempoMedio !== '-') animateKPIValue("kpiTempoMedio", tempoMedio, ' anos', 1);
                else document.getElementById("kpiTempoMedio").innerText = '-';

                // Remuneração Média
                let remMedia = equipasRemuneracaoMedia[idx] > 0 ? equipasRemuneracaoMedia[idx] : '-';
                if (remMedia !== '-') animateKPIValue("kpiRemuneracao", remMedia, ' €', 2);
                else document.getElementById("kpiRemuneracao").innerText = '-';

                // Percentuais
                let masc = percentMasc[idx] >= 0 ? percentMasc[idx] : '-';
                let fem = percentFem[idx] >= 0 ? percentFem[idx] : '-';
                let outro = percentOutro[idx] >= 0 ? percentOutro[idx] : '-';
                if (masc !== '-') animateKPIValue("kpiPercentMasc", masc, '%', 1);
                else document.getElementById("kpiPercentMasc").innerText = '-';
                if (fem !== '-') animateKPIValue("kpiPercentFem", fem, '%', 1);
                else document.getElementById("kpiPercentFem").innerText = '-';
                if (outro !== '-') animateKPIValue("kpiPercentOutro", outro, '%', 1);
                else document.getElementById("kpiPercentOutro").innerText = '-';

                // Retenção
                let ret = retencaoPorEquipa[idx] !== undefined ? retencaoPorEquipa[idx] : '-';
                if (ret !== '-') animateKPIValue("kpiRetencao", ret, '%', 1);
                else document.getElementById("kpiRetencao").innerText = '-';
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
                    // Para "todas", usar apenas colaboradores únicos baseados em IDs
                    let processedIds = new Set();
                    let uniqueCollaborators = [];
                    
                    // Processar todas as equipas e coletar colaboradores únicos
                    equipasLabels.forEach((l, i) => {
                        let ages = equipasIdades[l] || [];
                        let colaboradores = equipasColaboradores[l] || [];
                        
                        ages.forEach((age, j) => {
                            let name = colaboradores[j] || `Colab ${j + 1}`;
                            // Criar um ID único baseado no nome e idade para evitar duplicatas
                            let uniqueId = `${name}_${age}`;
                            
                            if (!processedIds.has(uniqueId)) {
                                processedIds.add(uniqueId);
                                uniqueCollaborators.push({ name: name, age: Number(age) });
                            }
                        });
                    });
                    
                    // Criar dataPoints apenas com colaboradores únicos
                    uniqueCollaborators.forEach((colab, index) => {
                        dataPointsIdade.push({ 
                            x: index + 1, 
                            y: colab.age, 
                            markerColor: "#764ba2", 
                            indexLabel: `${colab.name} (${colab.age})` 
                        });
                        axisXLabels.push(colab.name);
                        allAges.push(colab.age);
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
            // Nível Hierárquico/Perfil - Doughnut moderno
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

            // Taxa de Retenção por Equipa
            if (typeof CanvasJS !== "undefined" && document.getElementById("chartRetencao")) {
                setChartContainerStyle && setChartContainerStyle("chartRetencao");
                let dataPoints;
                if (idx === "all") {
                    dataPoints = retencaoLabels.map((l, i) => ({
                        label: l,
                        y: retencaoPorEquipa[i],
                        color: "#DAA520",
                        indexLabel: retencaoPorEquipa[i] + '%'
                    }));
                } else {
                    dataPoints = [{
                        label: retencaoLabels[idx],
                        y: retencaoPorEquipa[idx],
                        color: "#DAA520",
                        indexLabel: retencaoPorEquipa[idx] + '%'
                    }];
                }
                var chartRetencao = new CanvasJS.Chart("chartRetencao", {
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
                    axisY: { title: "Retenção (%)", minimum: 0, maximum: 100, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                    toolTip: { enabled: false },
                    data: [{
                        type: "column",
                        color: "#DAA520",
                        indexLabelFontColor: "#DAA520",
                        indexLabelFontWeight: "bold",
                        indexLabelPlacement: "outside",
                        dataPoints: dataPoints
                    }]
                });
                chartRetencao.render();
                setChartCanvasStyle && setChartCanvasStyle("chartRetencao");
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
                    // Para "todas", usar apenas colaboradores únicos baseados em IDs
                    let processedIds = new Set();
                    let uniqueCollaborators = [];
                    
                    // Processar todas as equipas e coletar colaboradores únicos
                    equipasLabels.forEach((l, i) => {
                        let ages = equipasIdades[l] || [];
                        let colaboradores = equipasColaboradores[l] || [];
                        
                        ages.forEach((age, j) => {
                            let name = colaboradores[j] || `Colab ${j + 1}`;
                            // Criar um ID único baseado no nome e idade para evitar duplicatas
                            let uniqueId = `${name}_${age}`;
                            
                            if (!processedIds.has(uniqueId)) {
                                processedIds.add(uniqueId);
                                uniqueCollaborators.push({ name: name, age: Number(age) });
                            }
                        });
                    });
                    
                    // Criar dataPoints apenas com colaboradores únicos
                    uniqueCollaborators.forEach((colab, index) => {
                        dataPointsIdade.push({ 
                            x: index + 1, 
                            y: colab.age, 
                            markerColor: "#764ba2", 
                            indexLabel: `${colab.name} (${colab.age})` 
                        });
                        axisXLabels.push(colab.name);
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

        // Função para desenhar os gráficos de comparação Equipa vs Perfil Médio (todos os indicadores)
        function renderComparacaoEquipa(idx) {
            // Médias globais
            let mediasIdade = equipasIdadeMedia.filter(x=>x>0);
            let mediaIdadeEmpresa = mediasIdade.length ? (mediasIdade.reduce((a,b)=>a+b,0)/mediasIdade.length) : 0;
            let mediasRem = equipasRemuneracaoMedia.filter(x=>x>0);
            let mediaRemEmpresa = mediasRem.length ? (mediasRem.reduce((a,b)=>a+b,0)/mediasRem.length) : 0;
            let tempos = tempoMedioEmpresa.filter(x=>x>0);
            let mediaTempoEmpresa = tempos.length ? (tempos.reduce((a,b)=>a+b,0)/tempos.length) : 0;
            
            // Usar contagem de colaboradores únicos
            let totalColabEmpresa = <?php echo $total_colaboradores_unicos; ?>;

            // Usar percentuais globais corretos
            let mascEmpresa = <?php echo round($masc_percent_global, 1); ?>;
            let femEmpresa = <?php echo round($fem_percent_global, 1); ?>;
            let outroEmpresa = <?php echo round($outro_percent_global, 1); ?>;

            // Usar média das taxas de retenção das equipas
            let retencaoEmpresa = <?php echo round($retencao_media_equipas, 1); ?>;

            // Valores da equipa selecionada
            let equipaNome = idx === "all" ? "Todas" : equipasLabels[idx];
            let idadeEquipa = idx === "all" ? mediaIdadeEmpresa : (equipasIdadeMedia[idx] > 0 ? equipasIdadeMedia[idx] : 0);
            let remEquipa = idx === "all" ? mediaRemEmpresa : (equipasRemuneracaoMedia[idx] > 0 ? equipasRemuneracaoMedia[idx] : 0);
            let tempoEquipa = idx === "all" ? mediaTempoEmpresa : (tempoMedioEmpresa[idx] > 0 ? tempoMedioEmpresa[idx] : 0);
            let totalColabEquipa = idx === "all" ? totalColabEmpresa : (equipasMembros[idx] ?? 0);
            let mascEquipa = idx === "all" ? mascEmpresa : (percentMasc[idx] ?? 0);
            let femEquipa = idx === "all" ? femEmpresa : (percentFem[idx] ?? 0);
            let outroEquipa = idx === "all" ? outroEmpresa : (percentOutro[idx] ?? 0);
            
            // Para retenção, usar média quando "all", ou valor individual da equipa
            let retencaoEquipa = idx === "all" ? retencaoEmpresa : (retencaoPorEquipa[idx] !== undefined ? retencaoPorEquipa[idx] : 0);

            // Helper para gráfico de barras simples
            function renderBarChart(container, label, empresa, equipa, sufixo = "", decimals = 1) {
                if (typeof CanvasJS !== "undefined" && document.getElementById(container)) {
                    let chart = new CanvasJS.Chart(container, {
                        animationEnabled: true,
                        backgroundColor: "transparent",
                        axisX: {
                            labelFontSize: 13,
                            labelFontColor: "#19365f",
                            interval: 1,
                            labelWrap: true,
                            labelMaxWidth: 120
                        },
                        axisY: { minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                        data: [{
                            type: "column",
                            dataPoints: [
                                { label: "Perfil Médio", y: Number(empresa), color: "#299cf3", indexLabel: String(Number(empresa).toFixed(decimals)) + sufixo },
                                { label: equipaNome, y: Number(equipa), color: "#764ba2", indexLabel: String(Number(equipa).toFixed(decimals)) + sufixo }
                            ]
                        }]
                    });
                    chart.render();
                }
            }

            renderBarChart("chartCompTotalColab", "Total Colaboradores", totalColabEmpresa, totalColabEquipa, "", 0);
            renderBarChart("chartCompIdade", "Idade Média", mediaIdadeEmpresa, idadeEquipa, "", 1);
            renderBarChart("chartCompTempo", "Tempo Médio Empresa", mediaTempoEmpresa, tempoEquipa, " anos", 1);
            renderBarChart("chartCompRemuneracao", "Remuneração Média", mediaRemEmpresa, remEquipa, " €", 2);
            renderBarChart("chartCompMasc", "% Masculino", mascEmpresa, mascEquipa, "%", 1);
            renderBarChart("chartCompFem", "% Feminino", femEmpresa, femEquipa, "%", 1);
            renderBarChart("chartCompOutro", "% Outro", outroEmpresa, outroEquipa, "%", 1);
            renderBarChart("chartCompRetencao", "Taxa de Retenção", retencaoEmpresa, retencaoEquipa, "%", 1);
        }

        // Renderiza todos os gráficos ao carregar a página
        document.addEventListener("DOMContentLoaded", function () {
            filtrarPorEquipa("all");
            renderModernCharts("all");
            renderComparacaoEquipa("all");
        });

        // Atualiza gráficos ao mudar equipa
        document.getElementById("equipaSelect").addEventListener("change", function() {
            let idx = this.value === "all" ? "all" : parseInt(this.value);
            filtrarPorEquipa(idx);
            renderModernCharts(idx);
            renderComparacaoEquipa(idx);
        });
        document.getElementById("anoRetencaoSelect").addEventListener("change", function() {
            let anoSelecionado = this.value;
            
            // Fazer AJAX para buscar dados do ano selecionado  
            fetch(`dashboard_rh_ajax.php?ano=${anoSelecionado}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizar variáveis globais
                        window.retencaoPorEquipa = data.retencao_por_equipa;
                        window.retencaoGlobal = data.retencao_global;
                        
                        // Calcular média simples das taxas de retenção
                        let taxasValidas = data.retencao_por_equipa.filter(taxa => taxa !== undefined && taxa !== null && !isNaN(taxa) && taxa > 0);
                        let retencaoMedia = taxasValidas.length > 0 ? (taxasValidas.reduce((a,b) => a+b, 0) / taxasValidas.length) : 0;
                        
                        // Atualizar KPI de retenção com a média
                        document.getElementById("kpiRetencao").innerText = retencaoMedia > 0 ? retencaoMedia.toFixed(1) + '%' : '-';
                        
                        // Atualizar perfil médio com a média
                        document.getElementByElementById("perfilRetencao").innerText = retencaoMedia > 0 ? retencaoMedia.toFixed(1) + '%' : '-';
                        
                        // Atualizar gráficos
                        let equipaIdx = document.getElementById("equipaSelect").value;
                        atualizarKPIs(equipaIdx === "all" ? equipaIdx : parseint(this.value));
                        
                        // Atualizar gráficos de comparação
                        renderComparacaoEquipa(equipaIdx === "all" ? equipaIdx : parseInt(equipaIdx));
                        
                        // Atualizar gráfico de retenção
                        if (typeof CanvasJS !== "undefined" && document.getElementById("chartRetencao")) {
                            let dataPoints;
                            if (equipaIdx === "all") {
                                dataPoints = retencaoLabels.map((l, i) => ({
                                    label: l,
                                    y: data.retencao_por_equipa[i],
                                    color: "#DAA520",
                                    indexLabel: data.retencao_por_equipa[i] + '%'
                                }));
                            } else {
                                dataPoints = [{
                                    label: retencaoLabels[equipaIdx],
                                    y: data.retencao_por_equipa[equipaIdx],
                                    color: "#DAA520",
                                    indexLabel: data.retencao_por_equipa[equipaIdx] + '%'
                                }];
                            }
                            
                            var chartRetencao = new CanvasJS.Chart("chartRetencao", {
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
                                axisY: { title: "Retenção (%)", minimum: 0, maximum: 100, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                                toolTip: { enabled: false },
                                data: [{
                                    type: "column",
                                    color: "#DAA520",
                                    indexLabelFontColor: "#DAA520",
                                    indexLabelFontWeight: "bold",
                                    indexLabelPlacement: "outside",
                                    dataPoints: dataPoints
                                }]
                            });
                            chartRetencao.render();
                        }
                    } else {
                        console.error('Erro na resposta:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar dados de retenção:', error);
                });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../../assets/chatbot.js"></script>
</body>
<div style="height:100px;"></div>
</html>