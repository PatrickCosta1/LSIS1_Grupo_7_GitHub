<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$nome = htmlspecialchars($coordBLL->getCoordenadorName($_SESSION['user_id']));
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
$equipa = $equipas && count($equipas) > 0 ? $equipas[0] : null;
$equipaId = $equipa ? $equipa['id'] : null;

// Dados simulados para exemplo visual (substitua por métodos reais)
$dadosEquipa = $equipa ? $coordBLL->getIndicadoresEquipa($equipaId) : null;
$evolucaoColab = $equipa ? $coordBLL->getEvolucaoColaboradores($equipaId) : ['Jan'=>10,'Fev'=>12,'Mar'=>13,'Abr'=>14,'Mai'=>13,'Jun'=>15];
$faltas = $equipa ? $coordBLL->getFaltasPorColaborador($equipaId) : ['João'=>2,'Maria'=>0,'Carlos'=>1,'Ana'=>0,'Pedro'=>3];
$avaliacoes = $equipa ? $coordBLL->getAvaliacoesDesempenhoPorEquipa($equipaId) : ['Excelente'=>2,'Bom'=>5,'Regular'=>1,'Ruim'=>0];
$idades = $equipa ? $coordBLL->getIdadesPorEquipa($equipaId) : [28,32,29,41,36,27,30];
$funcoes = $equipa && isset($dadosEquipa['funcoes']) ? $dadosEquipa['funcoes'] : ['Analista'=>2,'Gestor'=>1,'Técnico'=>3];
$generos = $equipa ? $coordBLL->getGenerosPorEquipa($equipaId) : ['Masculino'=>4,'Feminino'=>3];

// Menu
$menu = [
    'Dashboard' => 'dashboard_coordenador.php',
    'Minha Equipa' => 'equipa.php?id=' . ($equipaId ?? ''),
    'Relatórios Equipa' => 'relatorios_equipa.php?id=' . ($equipaId ?? ''),
    'Perfil' => '../Comuns/perfil.php',
    'Sair' => '../Comuns/logout.php'
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --accent: #38a169;
            --danger: #e53e3e;
            --bg: #f7f8fa;
            --card-bg: #fff;
            --text: #2d3748;
            --muted: #888;
            --shadow: 0 4px 24px rgba(102,126,234,0.10);
        }
        body {
            background: var(--bg);
            font-family: 'Segoe UI', Arial, sans-serif;
            color: var(--text);
            margin: 0;
        }
        .dashboard-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 36px 16px 32px 16px;
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        .dashboard-header h1 {
            font-size: 2.3rem;
            color: var(--secondary);
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .kpi-cards {
            display: flex;
            gap: 28px;
            margin-bottom: 36px;
            flex-wrap: wrap;
        }
        .kpi-card {
            flex: 1 1 220px;
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 32px 28px 28px 28px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 200px;
            min-height: 120px;
            position: relative;
            transition: transform 0.12s;
            cursor: pointer;
            overflow: hidden;
        }
        .kpi-card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 8px 32px rgba(102,126,234,0.15);}
        .kpi-card .kpi-icon {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: var(--primary);
            opacity: 0.85;
        }
        .kpi-card h3 { margin: 0 0 8px 0; font-size: 1.13rem; color: var(--secondary); font-weight: 600; }
        .kpi-card .kpi-value { font-size: 2.5rem; color: var(--text); font-weight: 700; }
        .kpi-card .kpi-desc { font-size: 1.01rem; color: var(--muted); margin-top: 7px; }
        .charts-row {
            display: flex;
            gap: 36px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }
        .chart-card {
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 28px 22px 22px 22px;
            flex: 1 1 400px;
            min-width: 340px;
            margin-bottom: 32px;
            display: flex;
            flex-direction: column;
        }
        .chart-card h4 { margin: 0 0 12px 0; font-size: 1.13rem; color: var(--primary); font-weight: 600; }
        .chart-desc { font-size: 1.01rem; color: var(--muted); margin-bottom: 10px; }
        @media (max-width: 1100px) {
            .dashboard-container { padding: 12px 4px; }
            .kpi-cards, .charts-row { flex-direction: column; gap: 18px; }
            .chart-card { min-width: 0; }
        }
        /* Dark mode toggle */
        .toggle-dark {
            background: var(--card-bg);
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            box-shadow: var(--shadow);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            transition: background 0.2s;
        }
        .toggle-dark:hover { background: #ecebfa; }
        body.dark {
            --bg: #181a1b;
            --card-bg: #23272e;
            --text: #f7f8fa;
            --muted: #b0b0b0;
            --shadow: 0 4px 24px rgba(0,0,0,0.22);
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php foreach ($menu as $label => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Olá, <?php echo $nome; ?></h1>
            <button class="toggle-dark" id="toggle-dark" title="Alternar modo escuro/claro">🌙</button>
        </div>
        <?php if ($equipa && $dadosEquipa): ?>
            <div class="kpi-cards">
                <div class="kpi-card">
                    <span class="kpi-icon">👥</span>
                    <h3>Colaboradores Ativos</h3>
                    <span class="kpi-value"><?php echo $dadosEquipa['ativos']; ?></span>
                    <span class="kpi-desc">Atualmente na equipa</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-icon">📈</span>
                    <h3>Desempenho Excelente</h3>
                    <span class="kpi-value"><?php echo $avaliacoes['Excelente'] ?? 0; ?></span>
                    <span class="kpi-desc">Avaliados como excelentes</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-icon">⏳</span>
                    <h3>Média de Idade</h3>
                    <span class="kpi-value"><?php echo count($idades) ? round(array_sum($idades)/count($idades),1) : '-'; ?></span>
                    <span class="kpi-desc">Idade média dos membros</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-icon">🗂️</span>
                    <h3>Funções Diferentes</h3>
                    <span class="kpi-value"><?php echo count($funcoes); ?></span>
                    <span class="kpi-desc">Diversidade de cargos</span>
                </div>
                <div class="kpi-card">
                    <span class="kpi-icon">♀️♂️</span>
                    <h3>Gênero predominante</h3>
                    <span class="kpi-value">
                        <?php
                        $maxGen = '-';
                        if ($generos && max($generos) > 0) {
                            $maxGen = array_search(max($generos), $generos);
                        }
                        echo $maxGen;
                        ?>
                    </span>
                    <span class="kpi-desc">Entre os membros da equipa</span>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Evolução de Colaboradores Ativos</h4>
                    <div class="chart-desc">Variação do número de ativos nos últimos meses.</div>
                    <canvas id="graficoEvolucaoColab"></canvas>
                </div>
                <div class="chart-card">
                    <h4>Faltas por Colaborador</h4>
                    <div class="chart-desc">Veja quem mais falta na equipa.</div>
                    <canvas id="graficoFaltas"></canvas>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Avaliações de Desempenho</h4>
                    <div class="chart-desc">Distribuição das avaliações mais recentes.</div>
                    <canvas id="graficoAvaliacoes"></canvas>
                </div>
                <div class="chart-card">
                    <h4>Funções na Equipa</h4>
                    <div class="chart-desc">Diversidade de cargos/funções.</div>
                    <canvas id="graficoFuncoes"></canvas>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Distribuição de Idades</h4>
                    <div class="chart-desc">Faixa etária dos membros da equipa.</div>
                    <canvas id="graficoIdades"></canvas>
                </div>
                <div class="chart-card">
                    <h4>Gênero</h4>
                    <div class="chart-desc">Proporção de gêneros na equipa.</div>
                    <canvas id="graficoGenero"></canvas>
                </div>
            </div>
            <script>
            // Dark mode toggle
            document.getElementById('toggle-dark').onclick = function() {
                document.body.classList.toggle('dark');
                this.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
            };

            // Evolução de colaboradores ativos
            const evolucaoLabels = <?php echo json_encode(array_keys($evolucaoColab)); ?>;
            const evolucaoData = <?php echo json_encode(array_values($evolucaoColab)); ?>;
            new Chart(document.getElementById('graficoEvolucaoColab'), {
                type: 'line',
                data: {
                    labels: evolucaoLabels,
                    datasets: [{
                        label: 'Ativos',
                        data: evolucaoData,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102,126,234,0.15)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });

            // Faltas por colaborador
            const faltasLabels = <?php echo json_encode(array_keys($faltas)); ?>;
            const faltasData = <?php echo json_encode(array_values($faltas)); ?>;
            new Chart(document.getElementById('graficoFaltas'), {
                type: 'bar',
                data: {
                    labels: faltasLabels,
                    datasets: [{
                        label: 'Faltas',
                        data: faltasData,
                        backgroundColor: '#e53e3e'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, stepSize: 1 } }
                }
            });

            // Avaliações de desempenho
            const avalLabels = <?php echo json_encode(array_keys($avaliacoes)); ?>;
            const avalData = <?php echo json_encode(array_values($avaliacoes)); ?>;
            new Chart(document.getElementById('graficoAvaliacoes'), {
                type: 'pie',
                data: {
                    labels: avalLabels,
                    datasets: [{
                        data: avalData,
                        backgroundColor: ['#38a169', '#ecc94b', '#e53e3e', '#667eea']
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // Funções
            const funcoesLabels = <?php echo json_encode(array_keys($funcoes)); ?>;
            const funcoesData = <?php echo json_encode(array_values($funcoes)); ?>;
            new Chart(document.getElementById('graficoFuncoes'), {
                type: 'bar',
                data: {
                    labels: funcoesLabels,
                    datasets: [{
                        label: 'Colaboradores',
                        data: funcoesData,
                        backgroundColor: '#764ba2'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, stepSize: 1 } }
                }
            });

            // Idades
            const idades = <?php echo json_encode(array_map('intval', $idades)); ?>;
            const idadesLabels = [...new Set(idades)].sort((a,b)=>a-b);
            const idadesData = idadesLabels.map(i => idades.filter(x => x===i).length);
            new Chart(document.getElementById('graficoIdades'), {
                type: 'bar',
                data: {
                    labels: idadesLabels,
                    datasets: [{
                        label: 'Colaboradores',
                        data: idadesData,
                        backgroundColor: '#38a169'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, stepSize: 1 } }
                }
            });

            // Gênero
            const generoLabels = <?php echo json_encode(array_keys($generos)); ?>;
            const generoData = <?php echo json_encode(array_values($generos)); ?>;
            new Chart(document.getElementById('graficoGenero'), {
                type: 'pie',
                data: {
                    labels: generoLabels,
                    datasets: [{
                        data: generoData,
                        backgroundColor: ['#667eea', '#f687b3', '#ecc94b', '#38a169']
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } }
                }
            });
            </script>
        <?php else: ?>
            <p style="color:#e53e3e;">Selecione uma equipa para ver o resumo e os gráficos.</p>
        <?php endif; ?>
    </div>
</body>
</html>