<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_relatorios.php';
$relatoriosBLL = new RHRelatoriosManager();
$indicadores = $relatoriosBLL->getIndicadoresGlobais();
$equipas = $relatoriosBLL->getEquipasIndicadores();
$aniversarios = $relatoriosBLL->getAniversariosPorEquipa();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios & Dashboards RH - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/global.css">
    <link rel="stylesheet" href="../../assets/rh.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f7f8fa; font-family: 'Segoe UI', Arial, sans-serif; }
        .dashboard-container { max-width: 1200px; margin: 0 auto; padding: 32px 16px; }
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
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="dashboard-container">
        <h1 style="color:#3a366b;font-size:2.1rem;margin-bottom:18px;">Dashboard RH</h1>
        <div class="kpi-cards">
            <div class="kpi-card">
                <h3>Total de Colaboradores</h3>
                <span class="kpi-value"><?php echo htmlspecialchars($indicadores['total_colaboradores']); ?></span>
                <span class="kpi-desc">Inclui todos os colaboradores registados</span>
            </div>
            <div class="kpi-card">
                <h3>Ativos</h3>
                <span class="kpi-value"><?php echo htmlspecialchars($indicadores['ativos']-1); ?></span>
                <span class="kpi-desc">Colaboradores atualmente ativos</span>
            </div>
            <div class="kpi-card">
                <h3>Inativos</h3>
                <span class="kpi-value"><?php echo htmlspecialchars($indicadores['inativos']); ?></span>
                <span class="kpi-desc">Colaboradores desligados/inativos</span>
            </div>
            <div class="kpi-card">
                <h3>Total de Equipas</h3>
                <span class="kpi-value"><?php echo htmlspecialchars($indicadores['total_equipas']); ?></span>
                <span class="kpi-desc">Equipas registadas no sistema</span>
            </div>
        </div>
        <div class="charts-row">
            <div class="chart-card">
                <h4>Distribuição de Ativos vs Inativos</h4>
                <canvas id="graficoAtivos"></canvas>
            </div>
            <div class="chart-card">
                <h4>Colaboradores por Equipa</h4>
                <canvas id="graficoColabPorEquipa"></canvas>
            </div>
        </div>
        <div class="charts-row">
            <div class="chart-card">
                <h4>Aniversariantes por Equipa (este mês)</h4>
                <canvas id="graficoAniversarios"></canvas>
            </div>
            <div class="chart-card">
                <h4>Total de Equipas</h4>
                <canvas id="graficoEquipas"></canvas>
            </div>
        </div>
    </div>

    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          border: none;
          border-radius: 50%;
          width: 60px;
          height: 60px;
          box-shadow: 0 4px 16px rgba(0,0,0,0.15);
          font-size: 28px;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          ">
        ?
      </button>
      <iframe
        id="chatbot-iframe"
        src="https://www.chatbase.co/chatbot-iframe/SHUUk9C_zO-W-kHarKtWh"
        title="Ajuda Chatbot"
        width="350"
        height="500"
        style="display: none; position: absolute; bottom: 70px; right: 0; border: none; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
      </iframe>
    </div>
    <script src="../../assets/chatbot.js"></script>
    <script>
    // Gráfico de Ativos/Inativos
    new Chart(document.getElementById('graficoAtivos'), {
        type: 'doughnut',
        data: {
            labels: ['Ativos', 'Inativos'],
            datasets: [{
                data: [<?php echo $indicadores['ativos']-1; ?>, <?php echo $indicadores['inativos']; ?>],
                backgroundColor: ['#667eea', '#e53e3e']
            }],
            options: {
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed}` } }
                }
            }
        });

    // Gráfico de colaboradores por equipa
    const equipasLabels = <?php echo json_encode(array_column($equipas, 'nome')); ?>;
    const equipasData = <?php echo json_encode(array_map('intval', array_column($equipas, 'total_colaboradores'))); ?>;
    new Chart(document.getElementById('graficoColabPorEquipa'), {
        type: 'bar',
        data: {
            labels: equipasLabels,
            datasets: [{
                label: 'Colaboradores',
                data: equipasData,
                backgroundColor: '#764ba2'
            }],
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

    // Gráfico de equipas (total)
    new Chart(document.getElementById('graficoEquipas'), {
        type: 'bar',
        data: {
            labels: ['Equipas'],
            datasets: [{
                label: 'Total de Equipas',
                data: [<?php echo $indicadores['total_equipas']; ?>],
                backgroundColor: '#667eea'
            }],
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

    // Gráfico de aniversariantes por equipa
    const aniversariosLabels = <?php echo json_encode(array_column($aniversarios, 'nome')); ?>;
    const aniversariosData = <?php echo json_encode(array_map('intval', array_column($aniversarios, 'aniversariantes'))); ?>;
    new Chart(document.getElementById('graficoAniversarios'), {
        type: 'bar',
        data: {
            labels: aniversariosLabels,
            datasets: [{
                label: 'Aniversariantes',
                data: aniversariosData,
                backgroundColor: '#38a169'
            }],
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `Aniversariantes: ${ctx.parsed.y}` } }
                },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
</body>
</html>