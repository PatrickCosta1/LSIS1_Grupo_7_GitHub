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

            // Adicionar dados detalhados do colaborador
            $dados_colab = $rhBLL->getDadosColaboradorById($id_colab);
            if ($dados_colab) {
                $colaboradores_unicos[$id_colab]['idade'] = $dados_colab['idade'];
                $colaboradores_unicos[$id_colab]['remuneracao'] = $dados_colab['remuneracao'];
                $colaboradores_unicos[$id_colab]['tempo_empresa'] = $dados_colab['tempo_empresa'];
                $colaboradores_unicos[$id_colab]['cargo'] = $dados_colab['cargo'];
            }
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
            <!-- Botões de seleção de dashboard -->
            <div class="dashboard-selector" style="display: flex; gap: 20px; justify-content: center; margin: 40px 0 30px 0;">
                <button type="button" id="btn-empresa" style="padding: 15px 30px; font-size: 1.1em; border-radius: 8px; border: 1px solid #36a2eb; background: #36a2eb; color: #fff; cursor: pointer;">
                    Dashboard Empresa
                </button>
                <button type="button" id="btn-equipa" style="padding: 15px 30px; font-size: 1.1em; border-radius: 8px; border: 1px solid #4bc0c0; background: #4bc0c0; color: #fff; cursor: pointer;">
                    Dashboard Equipa
                </button>
                <button type="button" id="btn-colaborador" style="padding: 15px 30px; font-size: 1.1em; border-radius: 8px; border: 1px solid #ff6384; background: #ff6384; color: #fff; cursor: pointer;">
                    Dashboard Colaborador
                </button>
            </div>
            <!-- Fim dos botões de seleção -->

            <!-- Bloco de seleção para Empresa -->
            <div id="empresa-options" style="display:none; max-width: 500px; margin: 0 auto 30px auto; border:1px solid #36a2eb; border-radius:8px; padding:24px;">
                <h3>Selecionar KPIs</h3>
                <div>
                    <label><input type="checkbox" checked> Nº Total de Colaboradores</label><br>
                    <label><input type="checkbox" checked> Idade Média</label><br>
                    <label><input type="checkbox" checked> Remuneração Média</label><br>
                    <label><input type="checkbox" checked> Tempo Médio na Empresa</label><br>
                    <label><input type="checkbox" checked> Taxa de Retenção Global</label>
                </div>
                <h3 style="margin-top:18px;">Selecionar Gráficos</h3>
                <div>
                    <label><input type="checkbox" checked> Distribuição por Género</label><br>
                    <label><input type="checkbox" checked> Distribuição Geográfica</label><br>
                    <label><input type="checkbox" checked> Distribuição por Nível Hierárquico</label><br>
                    <label><input type="checkbox" checked> Idade Média por Equipa</label><br>
                    <label><input type="checkbox" checked> Remuneração Média por Equipa</label>
                </div>
                <!-- Botão Gerar Dashboard -->
                <div style="text-align:center; margin-top:28px;">
                    <button type="button" style="padding:12px 32px; font-size:1em; border-radius:6px; background:#36a2eb; color:#fff; border:none; cursor:pointer;">
                        Gerar Dashboard
                    </button>
                </div>
            </div>

            <!-- Bloco de seleção para Equipa -->
            <div id="equipa-options" style="display:none; max-width: 500px; margin: 0 auto 30px auto; border:1px solid #4bc0c0; border-radius:8px; padding:24px;">
                <h3>Selecionar Equipa</h3>
                <select id="select-equipa" style="width:100%;padding:8px;font-size:1em;margin-bottom:18px;">
                    <option value="">-- Escolha uma equipa --</option>
                    <?php foreach ($equipas_labels as $nome_equipa): ?>
                        <option value="<?= htmlspecialchars($nome_equipa) ?>"><?= htmlspecialchars($nome_equipa) ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="equipa-checklists" style="display:none;">
                    <h3>Selecionar KPIs</h3>
                    <div>
                        <label><input type="checkbox" checked> Nº de Colaboradores</label><br>
                        <label><input type="checkbox" checked> Idade Média</label><br>
                        <label><input type="checkbox" checked> Remuneração Média</label><br>
                        <label><input type="checkbox" checked> Tempo Médio na Empresa</label><br>
                        <label><input type="checkbox" checked> Taxa de Retenção</label>
                    </div>
                    <h3 style="margin-top:18px;">Selecionar Gráficos</h3>
                    <div>
                        <label><input type="checkbox" checked> Distribuição por Género</label><br>
                        <label><input type="checkbox" checked> Distribuição Geográfica</label><br>
                        <label><input type="checkbox" checked> Distribuição por Nível Hierárquico</label>
                    </div>
                    <!-- Botão Gerar Dashboard -->
                    <div style="text-align:center; margin-top:28px;">
                        <button type="button" style="padding:12px 32px; font-size:1em; border-radius:6px; background:#4bc0c0; color:#fff; border:none; cursor:pointer;">
                            Gerar Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bloco de seleção para Colaborador -->
            <div id="colaborador-options" style="display:none; max-width: 500px; margin: 0 auto 30px auto; border:1px solid #ff6384; border-radius:8px; padding:24px;">
                <h3>Selecionar Colaborador</h3>
                <select id="select-colaborador" style="width:100%;padding:8px;font-size:1em;margin-bottom:18px;">
                    <option value="">-- Escolha um colaborador --</option>
                    <?php foreach ($colaboradores_unicos as $colab): ?>
                        <option value="<?= htmlspecialchars($colab['colaborador_id']) ?>">
                            <?= htmlspecialchars($colab['colaborador_nome']) ?> (<?= htmlspecialchars($colab['equipa_nome']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="colaborador-checklists" style="display:none;">
                    <h3>Selecionar KPIs</h3>
                    <div>
                        <label><input type="checkbox" checked> Idade</label><br>
                        <label><input type="checkbox" checked> Remuneração</label><br>
                        <label><input type="checkbox" checked> Tempo na Empresa</label><br>
                        <label><input type="checkbox" checked> Cargo</label>
                    </div>
                    <h3 style="margin-top:18px;">Selecionar Gráficos</h3>
                    <div>
                        <label><input type="checkbox" checked> Evolução Remuneração</label><br>
                        <label><input type="checkbox" checked> Formação Realizada</label>
                    </div>
                    <h3 style="margin-top:18px;">Opções Extra</h3>
                    <div>
                        <label><input type="checkbox" checked> Comparação com perfil médio</label>
                    </div>
                    <!-- Botão Gerar Dashboard -->
                    <div style="text-align:center; margin-top:28px;">
                        <button type="button" style="padding:12px 32px; font-size:1em; border-radius:6px; background:#ff6384; color:#fff; border:none; cursor:pointer;">
                            Gerar Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resultado da dashboard personalizada -->
            <div id="dashboard-result" style="max-width: 1200px; margin: 40px auto 0 auto;"></div>
            <div id="export-pdf-colaborador-container" style="display:none; text-align:right; margin: 16px 0;">
                <button id="btn-export-pdf-colaborador" style="padding:10px 24px; border-radius:6px; background:#ff6384; color:#fff; border:none; cursor:pointer;">
                    Exportar KPIs e Comparação em PDF
                </button>
            </div>
        </main>
        <!-- Adiciona html2pdf.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
        // Alternar blocos de seleção conforme o botão
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btn-empresa').addEventListener('click', function() {
                document.getElementById('empresa-options').style.display = 'block';
                document.getElementById('equipa-options').style.display = 'none';
                document.getElementById('colaborador-options').style.display = 'none';
                document.getElementById('dashboard-result').innerHTML = '';
            });
            document.getElementById('btn-equipa').addEventListener('click', function() {
                document.getElementById('empresa-options').style.display = 'none';
                document.getElementById('equipa-options').style.display = 'block';
                document.getElementById('colaborador-options').style.display = 'none';
                document.getElementById('equipa-checklists').style.display = 'none';
                document.getElementById('select-equipa').value = '';
                document.getElementById('dashboard-result').innerHTML = '';
            });
            document.getElementById('btn-colaborador').addEventListener('click', function() {
                document.getElementById('empresa-options').style.display = 'none';
                document.getElementById('equipa-options').style.display = 'none';
                document.getElementById('colaborador-options').style.display = 'block';
                document.getElementById('colaborador-checklists').style.display = 'none';
                document.getElementById('select-colaborador').value = '';
                document.getElementById('dashboard-result').innerHTML = '';
            });
            // Mostrar checklists após seleção de equipa
            document.getElementById('select-equipa').addEventListener('change', function() {
                document.getElementById('equipa-checklists').style.display = this.value ? 'block' : 'none';
                document.getElementById('dashboard-result').innerHTML = '';
            });
            // Mostrar checklists após seleção de colaborador
            document.getElementById('select-colaborador').addEventListener('change', function() {
                document.getElementById('colaborador-checklists').style.display = this.value ? 'block' : 'none';
                document.getElementById('dashboard-result').innerHTML = '';
            });

            // Eventos dos botões Gerar Dashboard
            document.querySelector('#empresa-options button').onclick = function() {
                gerarDashboard('empresa');
            };
            document.querySelector('#equipa-options button').onclick = function() {
                gerarDashboard('equipa');
            };
            document.querySelector('#colaborador-options button').onclick = function() {
                gerarDashboard('colaborador');
            };
        
        });

        // Função para gerar KPIs (igual ao dashboard_rh.php)
        function gerarKPI(nome, valor, sufix = '', decimals = 0) {
            return `<div class="kpi-card" style="background:rgba(92,176,255,0.08);display:inline-block;min-width:180px;margin:10px 20px 10px 0;padding:18px 24px;border-radius:8px;text-align:center;">
                <div style="font-size:2em;font-weight:bold;color:#222;">${valor}${sufix}</div>
                <div style="font-size:1em;color:#666;margin-top:6px;">${nome}</div>
            </div>`;
        }

        // Função para gerar o container do gráfico (igual ao dashboard_rh.php)
        function gerarGrafico(titulo, id) {
            return `<div class="chart-card" style="display:inline-block;vertical-align:top;margin:18px 28px 18px 0;">
                <div class="chart-card-title" style="font-weight:bold;margin-bottom:8px;">${titulo}</div>
                <div id="${id}" class="chart-area" style="width:340px;height:220px;background:#fff;border:1px solid #eee;border-radius:8px;"></div>
            </div>`;
        }

        // Dados PHP para JS (apenas exemplos, adapte conforme necessário)
        const dashboardData = {
            empresa: {
                'Nº Total de Colaboradores': <?= json_encode(array_sum($equipas_membros)) ?>,
                'Idade Média': <?= json_encode(round(array_sum($equipas_idade_media) / (count($equipas_idade_media) ?: 1), 1)) ?>,
                'Remuneração Média': <?= json_encode(round(array_sum($equipas_remuneracao_media) / (count($equipas_remuneracao_media) ?: 1), 2)) ?>,
                'Tempo Médio na Empresa': <?= json_encode(round(array_sum($tempo_medio_empresa) / (count($tempo_medio_empresa) ?: 1), 1)) ?>,
                'Taxa de Retenção Global': <?= json_encode(round($retencao_global, 1)) ?>,
            },
            equipa: {
                <?php foreach ($equipas_labels as $i => $nome_equipa): ?>
                <?= json_encode($nome_equipa) ?>: {
                    'Nº de Colaboradores': <?= json_encode($equipas_membros[$i]) ?>,
                    'Idade Média': <?= json_encode($equipas_idade_media[$i]) ?>,
                    'Remuneração Média': <?= json_encode($equipas_remuneracao_media[$i]) ?>,
                    'Tempo Médio na Empresa': <?= json_encode($tempo_medio_empresa[$i]) ?>,
                    'Taxa de Retenção': <?= json_encode($retencao_por_equipa[$i]) ?>
                },
                <?php endforeach; ?>
            },
            colaborador: {
                <?php foreach ($colaboradores_unicos as $colab): ?>
                <?= json_encode($colab['colaborador_id']) ?>: {
                    'Idade': <?= json_encode($colab['idade'] ?? '') ?>,
                    'Remuneração': <?= json_encode($colab['remuneracao'] ?? '') ?>,
                    'Tempo na Empresa': <?= json_encode($colab['tempo_empresa'] ?? '') ?>,
                    'Cargo': <?= json_encode($colab['cargo'] ?? '') ?>
                },
                <?php endforeach; ?>
            }
        };

        // Gráficos: nomes e IDs (ajuste conforme os gráficos reais)
        const graficosEmpresa = [
            {nome: 'Distribuição por Género', id: 'grafico-genero'},
            {nome: 'Distribuição Geográfica', id: 'grafico-geografica'},
            {nome: 'Distribuição por Nível Hierárquico', id: 'grafico-nivel'},
            {nome: 'Idade Média por Equipa', id: 'grafico-idade-equipa'},
            {nome: 'Remuneração Média por Equipa', id: 'grafico-remuneracao-equipa'}
        ];
        const graficosEquipa = [
            {nome: 'Distribuição por Género', id: 'grafico-genero-equipa'},
            {nome: 'Distribuição Geográfica', id: 'grafico-geografica-equipa'},
            {nome: 'Distribuição por Nível Hierárquico', id: 'grafico-nivel-equipa'}
        ];
        const graficosColaborador = [
            {nome: 'Evolução Remuneração', id: 'grafico-evolucao-remuneracao'},
            {nome: 'Formação Realizada', id: 'grafico-formacao'}
        ];

        // Funções CanvasJS (copiadas do dashboard_rh.php)
        function renderColaboradoresPorEquipa(id, labels, data) {
            var chart = new CanvasJS.Chart(id, {
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
                    dataPoints: labels.map((l, i) => ({ label: l, y: data[i], color: "#36a2eb", indexLabel: String(data[i]) }))
                }]
            });
            chart.render();
        }

        function renderGraficoIdadeMedia(id, labels, idades, colaboradores) {
            // Gráfico de barras: cada equipa com a sua idade média
            let dataPoints = [];
            labels.forEach((equipa, i) => {
                let ages = idades[equipa] || [];
                let media = 0;
                if (ages.length > 0) {
                    let soma = ages.reduce((a, b) => Number(a) + Number(b), 0);
                    media = soma / ages.length;
                }
                dataPoints.push({
                    label: equipa,
                    y: Math.round(media * 10) / 10,
                    color: "#764ba2",
                    indexLabel: ages.length > 0 ? (Math.round(media * 10) / 10).toString() : "-"
                });
            });

            var chart = new CanvasJS.Chart(id, {
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
                axisY: { title: "Idade Média", minimum: 0, labelFontColor: "#19365f", gridColor: "#ecebfa" },
                legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                toolTip: { enabled: false },
                data: [{
                    type: "column",
                    showInLegend: true,
                    legendText: "Equipa",
                    dataPoints: dataPoints
                }]
            });
            chart.render();
        }

        function renderGraficoTempoMedio(id, labels, data) {
            var chartTempo = new CanvasJS.Chart(id, {
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
                    dataPoints: labels.map((l, i) => ({ label: l, y: data[i], color: "#ff9f40", indexLabel: String(data[i]) }))
                }]
            });
            chartTempo.render();
        }

        function renderGraficoNivelHierarquico(id, labels, data, pieColors) {
            var chartNivel = new CanvasJS.Chart(id, {
                animationEnabled: true,
                backgroundColor: "transparent",
                theme: "light1",
                legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                toolTip: { enabled: false },
                data: [{ type: "doughnut", indexLabel: "{label}: {y}", showInLegend: true, legendText: "{label}", dataPoints: labels.map((l, i) => ({ label: l, y: data[i], color: pieColors[i % pieColors.length] })), indexLabelLineThickness: 0 }]
            });
            chartNivel.render();
        }

        function renderGraficoGeografia(id, labels, data, pieColors) {
            let totalGeo = data.reduce((a, b) => a + b, 0);
            let dataPointsGeo = labels.map((label, i) => ({
                y: totalGeo > 0 ? Math.round((data[i] / totalGeo) * 1000) / 10 : 0,
                label: label,
                color: pieColors[i % pieColors.length]
            }));
            var chartGeo = new CanvasJS.Chart(id, {
                animationEnabled: true,
                backgroundColor: "transparent",
                theme: "light1",
                legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                data: [{ type: "pie", indexLabel: "{label}: {y}%", showInLegend: true, legendText: "{label}", dataPoints: dataPointsGeo, indexLabelLineThickness: 0 }]
            });
            chartGeo.render();
        }

        function renderGraficoGenero(id, masc, fem, outro) {
            var chartGenero = new CanvasJS.Chart(id, {
                animationEnabled: true,
                backgroundColor: "transparent",
                theme: "light1",
                legend: { verticalAlign: "bottom", fontSize: 13, fontColor: "#19365f" },
                data: [{
                    type: "pie",
                    indexLabel: "{label}: {y}%",
                    showInLegend: true,
                    legendText: "{label}",
                    dataPoints: [
                        { y: masc, label: "Masculino", color: "#36a2eb" },
                        { y: fem, label: "Feminino", color: "#ff6384" },
                        { y: outro, label: "Outro", color: "#b2dfdb" }
                    ],
                    indexLabelLineThickness: 0
                }]
            });
            chartGenero.render();
        }

        // Função para obter os KPIs e gráficos selecionados
        function getSelecionados(containerId) {
            const kpis = [];
            const graficos = [];
            document.querySelectorAll(`#${containerId} input[type=checkbox]`).forEach(cb => {
                if (cb.checked) {
                    const label = cb.parentNode.textContent.trim();
                    // Heurística: se está antes do "Selecionar Gráficos", é KPI, senão é gráfico
                    if (cb.closest('div').previousElementSibling && cb.closest('div').previousElementSibling.tagName === 'H3' && cb.closest('div').previousElementSibling.textContent.includes('KPIs')) {
                        kpis.push(label);
                    } else if (cb.closest('div').previousElementSibling && cb.closest('div').previousElementSibling.tagName === 'H3' && cb.closest('div').previousElementSibling.textContent.includes('Gráficos')) {
                        graficos.push(label);
                    } else if (containerId === 'colaborador-checklists' && label === 'Comparação com perfil médio') {
                        graficos.push(label);
                    }
                }
            });
            return { kpis, graficos };
        }

        // Função para gerar a dashboard personalizada
        function gerarDashboard(tipo) {
            let kpis = [];
            let graficos = [];
            let dashboardHTML = '';

            if (tipo === 'empresa') {
                const selecionados = getSelecionados('empresa-options');
                kpis = selecionados.kpis;
                graficos = selecionados.graficos;
            } else if (tipo === 'equipa') {
                const selecionados = getSelecionados('equipa-checklists');
                kpis = selecionados.kpis;
                graficos = selecionados.graficos;
            } else if (tipo === 'colaborador') {
                const selecionados = getSelecionados('colaborador-checklists');
                kpis = selecionados.kpis;
                graficos = selecionados.graficos;
            }

            // KPIs
            dashboardHTML += '<div style="margin-bottom:30px; display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px;">';
            if (tipo === 'colaborador') {
                const colaborador = document.getElementById('select-colaborador').value;
                const colabData = dashboardData.colaborador[colaborador] || {};
                // Mostra sempre os KPIs principais do colaborador, independentemente dos checkboxes
                dashboardHTML += gerarKPI('Idade', colabData['Idade'] ?? '-', ' anos', 0);
                dashboardHTML += gerarKPI('Remuneração', colabData['Remuneração'] ?? '-', ' €', 2);
                dashboardHTML += gerarKPI('Tempo na Empresa', colabData['Tempo na Empresa'] ?? '-', ' anos', 1);
                dashboardHTML += gerarKPI('Cargo', colabData['Cargo'] ?? '-', '', 0);
            } else {
                kpis.forEach(kpi => {
                    let valor = '-';
                    if (tipo === 'empresa' && dashboardData.empresa[kpi] !== undefined) valor = dashboardData.empresa[kpi];
                    if (tipo === 'equipa') {
                        const equipa = document.getElementById('select-equipa').value;
                        if (dashboardData.equipa[equipa] && dashboardData.equipa[equipa][kpi] !== undefined) valor = dashboardData.equipa[equipa][kpi];
                    }
                    dashboardHTML += gerarKPI(kpi, valor, '', 1);
                });
            }
            dashboardHTML += '</div>';

            // Gráficos
            dashboardHTML += '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 30px;">';
            if (tipo === 'empresa') {
                graficosEmpresa.forEach(graf => {
                    if (graficos.includes(graf.nome)) {
                        dashboardHTML += gerarGrafico(graf.nome, graf.id);
                    }
                });
            }
            if (tipo === 'equipa') {
                graficosEquipa.forEach(graf => {
                    if (graficos.includes(graf.nome)) {
                        dashboardHTML += gerarGrafico(graf.nome, graf.id);
                    }
                });
            }
            if (tipo === 'colaborador') {
                const colaborador = document.getElementById('select-colaborador').value;
                graficosColaborador.forEach(graf => {
                    if (graficos.includes(graf.nome)) {
                        dashboardHTML += gerarGrafico(graf.nome, graf.id);
                    }
                });

                // Renderização dos gráficos do colaborador (exemplo simples)
                setTimeout(() => {
                    if (graficos.includes('Evolução Remuneração') && document.getElementById('grafico-evolucao-remuneracao')) {
                        // Exemplo: gráfico de linha com valores fictícios
                        var chart = new CanvasJS.Chart('grafico-evolucao-remuneracao', {
                            animationEnabled: true,
                            backgroundColor: "transparent",
                            theme: "light1",
                            axisX: { labelFontSize: 13, labelFontColor: "#19365f" },
                            axisY: { title: "Remuneração (€)", labelFontColor: "#19365f", gridColor: "#ecebfa" },
                            data: [{
                                type: "line",
                                color: "#36a2eb",
                                markerSize: 8,
                                dataPoints: [
                                    // Substitua por dados reais se existirem
                                    { label: "2021", y: 1000 },
                                    { label: "2022", y: 1200 },
                                    { label: "2023", y: 1300 }
                                ]
                            }]
                        });
                        chart.render();
                    }
                    if (graficos.includes('Formação Realizada') && document.getElementById('grafico-formacao')) {
                        // Exemplo: gráfico de barras com valores fictícios
                        var chart = new CanvasJS.Chart('grafico-formacao', {
                            animationEnabled: true,
                            backgroundColor: "transparent",
                            theme: "light1",
                            axisX: { labelFontSize: 13, labelFontColor: "#19365f" },
                            axisY: { title: "Horas", labelFontColor: "#19365f", gridColor: "#ecebfa" },
                            data: [{
                                type: "column",
                                color: "#4bc0c0",
                                dataPoints: [
                                    // Substitua por dados reais se existirem
                                    { label: "2022", y: 12 },
                                    { label: "2023", y: 20 }
                                ]
                            }]
                        });
                        chart.render();
                    }
                }, 100);

                // Comparação com perfil médio
                if (graficos.includes('Comparação com perfil médio')) {
                    // Dados do colaborador
                    const colabData = dashboardData.colaborador[colaborador] || {};
                    // Perfil médio
                    const media_salario = <?= isset($equipas_remuneracao_media) && count($equipas_remuneracao_media) && array_sum($equipas_remuneracao_media) > 0
                        ? json_encode(round(array_sum($equipas_remuneracao_media)/count(array_filter($equipas_remuneracao_media)),2))
                        : 'null' ?>;
                    const media_idade = <?= count($equipas_idade_media) && array_sum($equipas_idade_media) > 0
                        ? json_encode(round(array_sum($equipas_idade_media)/count(array_filter($equipas_idade_media)),1))
                        : 'null' ?>;
                    const media_tempo = <?= count($tempo_medio_empresa) && array_sum($tempo_medio_empresa) > 0
                        ? json_encode(round(array_sum($tempo_medio_empresa)/count(array_filter($tempo_medio_empresa)),1))
                        : 'null' ?>;
                    const media_retencao = <?= $retencao_media_equipas > 0 ? json_encode(round($retencao_media_equipas, 1)) : 'null' ?>;

                    dashboardHTML += `
                    <div style="margin:30px 0;padding:18px 24px;background:#f8f8ff;border-radius:8px;">
                        <b>Comparação com perfil médio:</b>
                        <div style="display:flex;flex-wrap:wrap;gap:32px;justify-content:space-between;margin-top:18px;">
                            <div style="flex:1 1 180px;min-width:160px;">
                                <div style="font-size:13px;color:#888;margin-bottom:4px;">Remuneração (€)</div>
                                <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                                    ${colabData['Remuneração'] ?? '-'} <span style="color:#aaa;font-size:0.9em;">vs</span> ${media_salario !== null ? media_salario + ' €' : '-'}
                                </div>
                            </div>
                            <div style="flex:1 1 180px;min-width:160px;">
                                <div style="font-size:13px;color:#888;margin-bottom:4px;">Idade</div>
                                <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                                    ${colabData['Idade'] ?? '-'} <span style="color:#aaa;font-size:0.9em;">vs</span> ${media_idade !== null ? media_idade + ' anos' : '-'}
                                </div>
                            </div>
                            <div style="flex:1 1 180px;min-width:160px;">
                                <div style="font-size:13px;color:#888;margin-bottom:4px;">Tempo na Empresa</div>
                                <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                                    ${colabData['Tempo na Empresa'] ?? '-'} <span style="color:#aaa;font-size:0.9em;">vs</span> ${media_tempo !== null ? media_tempo + ' anos' : '-'}
                                </div>
                            </div>
                            <div style="flex:1 1 180px;min-width:160px;">
                                <div style="font-size:13px;color:#888;margin-bottom:4px;">Taxa de Retenção</div>
                                <div style="font-size:1.3em;color:#19365f;font-weight:bold;">
                                    ${colabData['Taxa de Retenção'] !== undefined ? colabData['Taxa de Retenção'] + '%' : '-'}
                                    <span style="color:#aaa;font-size:0.9em;">vs</span> ${media_retencao !== null ? media_retencao + '%' : '-'}
                                </div>
                            </div>
                        </div>
                    </div>`;
                }
            }

            // Atualizar resultado da dashboard
            document.getElementById('dashboard-result').innerHTML = dashboardHTML;

            // Renderizar gráficos após um pequeno atraso para garantir que o HTML está atualizado
            setTimeout(() => {
                if (tipo === 'empresa') {
                    if (graficos.includes('Distribuição por Género')) renderGraficoGenero('grafico-genero', <?= json_encode($total_masc_global) ?>, <?= json_encode($total_fem_global) ?>, <?= json_encode($total_outro_global) ?>);
                    if (graficos.includes('Distribuição Geográfica')) renderGraficoGeografia('grafico-geografica', <?= json_encode($geo_labels) ?>, <?= json_encode($geo_data) ?>, <?= json_encode($pie_colors) ?>);
                    if (graficos.includes('Distribuição por Nível Hierárquico')) renderGraficoNivelHierarquico('grafico-nivel', <?= json_encode($nivel_labels) ?>, <?= json_encode($nivel_data) ?>, <?= json_encode($pie_colors) ?>);
                    if (graficos.includes('Idade Média por Equipa')) renderGraficoIdadeMedia('grafico-idade-equipa', <?= json_encode($equipas_labels) ?>, <?= json_encode($equipas_idades) ?>, <?= json_encode($colaboradores_unicos) ?>);
                    if (graficos.includes('Remuneração Média por Equipa')) renderColaboradoresPorEquipa('grafico-remuneracao-equipa', <?= json_encode($equipas_labels) ?>, <?= json_encode($equipas_remuneracao_media) ?>);
                }
                if (tipo === 'equipa') {
                    const equipaSelecionada = document.getElementById('select-equipa').value;
                    if (!equipaSelecionada) return;

                    // 1. Distribuição por Género da equipa selecionada
                    if (graficos.includes('Distribuição por Género')) {
                        // Calcular percentuais só para a equipa selecionada
                        let masc = 0, fem = 0, outro = 0, total = 0;
                        let ids_processados_equipa = {};
                        for (const colab of <?= json_encode($colabs) ?>) {
                            if (colab.equipa_nome === equipaSelecionada && colab.colaborador_id && !ids_processados_equipa[colab.colaborador_id]) {
                                ids_processados_equipa[colab.colaborador_id] = true;
                                const genero_norm = (colab.sexo || '').toLowerCase().trim();
                                if (genero_norm === 'm' || genero_norm === 'masculino') masc++;
                                else if (genero_norm === 'f' || genero_norm === 'feminino') fem++;
                                else outro++;
                                total++;
                            }
                        }
                        let mascP = total > 0 ? Math.round(masc / total * 1000) / 10 : 0;
                        let femP = total > 0 ? Math.round(fem / total * 1000) / 10 : 0;
                        let outroP = total > 0 ? Math.round(outro / total * 1000) / 10 : 0;
                        renderGraficoGenero('grafico-genero-equipa', mascP, femP, outroP);
                    }

                    // 2. Distribuição Geográfica da equipa selecionada
                    if (graficos.includes('Distribuição Geográfica')) {
                        // Corrigido para usar localidades por equipa
                        const localidadesPorEquipa = <?= json_encode($equipas_localidades) ?>;
                        const localidades = localidadesPorEquipa[equipaSelecionada] || [];
                        // Conta quantas vezes cada localidade aparece
                        const contagem = {};
                        localidades.forEach(loc => {
                            if (loc) contagem[loc] = (contagem[loc] || 0) + 1;
                        });
                        const geo_labels_equipa = Object.keys(contagem);
                        const geo_data_equipa = geo_labels_equipa.map(l => contagem[l]);
                        renderGraficoGeografia('grafico-geografica-equipa', geo_labels_equipa, geo_data_equipa, <?= json_encode($pie_colors) ?>);
                    }

                    // 3. Distribuição por Nível Hierárquico da equipa selecionada
                    if (graficos.includes('Distribuição por Nível Hierárquico')) {
                        // Usa o array PHP $colabs_nivel (colaboradores por equipa e nivel_hierarquico)
                        const colabsNivel = <?= json_encode($rhBLL->getColaboradoresNivelHierarquicoPorEquipa()) ?>;
                        // Mapeamento de perfil_id para nível hierárquico
                        const nivelMap = {
                            1: 'Colaborador',
                            2: 'Coordenador',
                            3: 'RH'
                        };
                        // Para cada colaborador da equipa, determina o nível hierárquico
                        const niveis = {};
                        colabsNivel.forEach(row => {
                            if (row.equipa_nome === equipaSelecionada) {
                                let nivel = row.nivel_hierarquico;
                                // Se não existir, tenta deduzir pelo perfil_id (se disponível)
                                // (Se já está preenchido na base de dados, não precisa deduzir)
                                if (!nivel || nivel === '') nivel = 'Colaborador';
                                niveis[nivel] = (niveis[nivel] || 0) + 1;
                            }
                        });
                        const nivel_labels_equipa = Object.keys(niveis);
                        const nivel_data_equipa = nivel_labels_equipa.map(n => niveis[n]);
                        renderGraficoNivelHierarquico('grafico-nivel-equipa', nivel_labels_equipa, nivel_data_equipa, <?= json_encode($pie_colors) ?>);
                    }
                }
                if (tipo === 'colaborador') {
                    // Gráficos específicos do colaborador já são renderizados na função gerarDashboard
                }
            }, 100);
        }

        // Exportação PDF para colaborador (apenas KPIs e comparação)
        document.addEventListener('DOMContentLoaded', function() {
            // ...existing code...
            document.getElementById('btn-export-pdf-colaborador').onclick = function() {
                let conteudo = '';
                if (window._kpis_colab_pdf) conteudo += window._kpis_colab_pdf + '\n';
                if (window._comparacao_colab_pdf) conteudo += '\n' + window._comparacao_colab_pdf;
                if (!conteudo) {
                    alert('Nada para exportar.');
                    return;
                }
                // Cria um elemento temporário para exportação
                const tempDiv = document.createElement('div');
                tempDiv.style.whiteSpace = 'pre-wrap';
                tempDiv.style.fontFamily = 'monospace';
                tempDiv.textContent = conteudo;
                document.body.appendChild(tempDiv);

                html2pdf().set({
                    margin: 15,
                    filename: 'colaborador_kpis_comparacao.pdf',
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                }).from(tempDiv).save().then(() => {
                    document.body.removeChild(tempDiv);
                });
            };
        });
        </script>
    </body>
    </html>