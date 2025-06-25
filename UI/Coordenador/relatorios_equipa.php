<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
$equipaId = $_GET['id'] ?? ($equipas[0]['id'] ?? null);
$equipa = null;
if ($equipaId) {
    foreach ($equipas as $e) {
        if ($e['id'] == $equipaId) {
            $equipa = $e;
            break;
        }
    }
}
$dadosEquipa = $equipa ? $coordBLL->getIndicadoresEquipa($equipa['id']) : null;
$idades = $equipa ? $coordBLL->getIdadesPorEquipa($equipa['id']) : [];
$aniversariantes = $equipa ? $coordBLL->getAniversariantesEquipaMes($equipa['id']) : [];
// Gênero
$generos = [];
if ($equipa) {
    $generos = $coordBLL->getGenerosPorEquipa($equipa['id']); // Implemente na DAL/BLL: retorna ['Masculino'=>x, 'Feminino'=>y, ...]
}
// Tipo de Email
$emailsTipo = $equipa ? $coordBLL->getEmailsTipoPorEquipa($equipa['id']) : [];
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
    <title>Relatórios Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f7f8fa; font-family: 'Segoe UI', Arial, sans-serif; }
        .dashboard-container { max-width: 1100px; margin: 0 auto; padding: 32px 16px; }
        .kpi-cards { display: flex; gap: 24px; margin-bottom: 32px; flex-wrap: wrap; }
        .kpi-card {
            flex: 1 1 200px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(102,126,234,0.08);
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 180px;
            min-height: 110px;
            position: relative;
        }
        .kpi-card h3 { margin: 0 0 8px 0; font-size: 1.1rem; color: #764ba2; font-weight: 600; }
        .kpi-card .kpi-value { font-size: 2.2rem; color: #3a366b; font-weight: 700; }
        .kpi-card .kpi-desc { font-size: 0.98rem; color: #888; margin-top: 6px; }
        .charts-row { display: flex; gap: 32px; flex-wrap: wrap; }
        .chart-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(102,126,234,0.08);
            padding: 24px 18px 18px 18px;
            flex: 1 1 350px;
            min-width: 320px;
            margin-bottom: 32px;
        }
        .chart-card h4 { margin: 0 0 12px 0; font-size: 1.08rem; color: #4a468a; font-weight: 600; }
        .chart-desc { font-size: 0.97rem; color: #888; margin-bottom: 10px; }
        @media (max-width: 900px) {
            .kpi-cards, .charts-row { flex-direction: column; gap: 16px; }
            .chart-card { min-width: 0; }
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
        <h1 style="color:#3a366b;font-size:2.1rem;margin-bottom:18px;">Relatórios da Equipa</h1>
        <?php if ($equipa && $dadosEquipa): ?>
            <div class="kpi-cards">
                <div class="kpi-card">
                    <h3>Ativos</h3>
                    <span class="kpi-value"><?php echo $dadosEquipa['ativos']; ?></span>
                    <span class="kpi-desc">Colaboradores ativos na equipa</span>
                </div>
                <div class="kpi-card">
                    <h3>Inativos</h3>
                    <span class="kpi-value"><?php echo $dadosEquipa['inativos']; ?></span>
                    <span class="kpi-desc">Colaboradores inativos na equipa</span>
                </div>
                <div class="kpi-card">
                    <h3>Funções</h3>
                    <span class="kpi-value"><?php echo count($dadosEquipa['funcoes']); ?></span>
                    <span class="kpi-desc">Funções diferentes na equipa</span>
                </div>
                <div class="kpi-card">
                    <h3>Média de Idade</h3>
                    <span class="kpi-value">
                        <?php echo count($idades) ? round(array_sum($idades)/count($idades),1) : '-'; ?>
                    </span>
                    <span class="kpi-desc">Idade média dos membros</span>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Distribuição de Ativos vs Inativos</h4>
                    <div class="chart-desc">Veja a proporção de colaboradores ativos e inativos na sua equipa.</div>
                    <canvas id="graficoAtivosEquipa"></canvas>
                </div>
                <div class="chart-card">
                    <h4>Distribuição por Função</h4>
                    <div class="chart-desc">Visualize a quantidade de colaboradores por função/cargo.</div>
                    <canvas id="graficoFuncoesEquipa"></canvas>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Distribuição de Idades</h4>
                    <div class="chart-desc">Entenda a diversidade etária da equipa.</div>
                    <canvas id="graficoIdades"></canvas>
                </div>
                <div class="chart-card">
                    <h4>Aniversariantes do Mês</h4>
                    <div class="chart-desc">Colaboradores que fazem aniversário este mês.</div>
                    <canvas id="graficoAniversariantes"></canvas>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Distribuição de Gênero</h4>
                    <div class="chart-desc">Veja a proporção de gêneros na equipa.</div>
                    <canvas id="graficoGenero"></canvas>
                </div>
            </div>
            <div class="charts-row">
                <div class="chart-card">
                    <h4>Tipo de Email dos Colaboradores</h4>
                    <div class="chart-desc">Veja quantos usam email institucional vs pessoal.</div>
                    <canvas id="graficoEmailTipo"></canvas>
                </div>
            </div>
            <script>
            // Ativos/Inativos
            new Chart(document.getElementById('graficoAtivosEquipa'), {
                type: 'doughnut',
                data: {
                    labels: ['Ativos', 'Inativos'],
                    datasets: [{
                        data: [
                            <?php echo $dadosEquipa['ativos']; ?>,
                            <?php echo $dadosEquipa['inativos']; ?>
                        ],
                        backgroundColor: ['#667eea', '#e53e3e']
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed}` } }
                    }
                }
            });

            // Funções
            new Chart(document.getElementById('graficoFuncoesEquipa'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($dadosEquipa['funcoes'])); ?>,
                    datasets: [{
                        label: 'Colaboradores',
                        data: <?php echo json_encode(array_values($dadosEquipa['funcoes'])); ?>,
                        backgroundColor: '#764ba2'
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => `Colaboradores: ${ctx.parsed.y}` } }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
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

            // Aniversariantes do mês
            const aniversariantes = <?php echo json_encode($aniversariantes); ?>;
            new Chart(document.getElementById('graficoAniversariantes'), {
                type: 'bar',
                data: {
                    labels: aniversariantes.length ? aniversariantes : ['Nenhum'],
                    datasets: [{
                        label: 'Aniversariantes',
                        data: aniversariantes.length ? aniversariantes.map(()=>1) : [0],
                        backgroundColor: '#f6ad55'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, stepSize: 1, max: 1 } }
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

            // Tipo de Email
            const emailTipoLabels = <?php echo json_encode(array_keys($emailsTipo)); ?>;
            const emailTipoData = <?php echo json_encode(array_values($emailsTipo)); ?>;
            new Chart(document.getElementById('graficoEmailTipo'), {
                type: 'pie',
                data: {
                    labels: emailTipoLabels,
                    datasets: [{
                        data: emailTipoData,
                        backgroundColor: ['#667eea', '#ecc94b']
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } }
                }
            });
            </script>
        <?php else: ?>
            <p style="color:#e53e3e;">Selecione uma equipa para ver os relatórios.</p>
        <?php endif; ?>
    </div>
</body>
</html>