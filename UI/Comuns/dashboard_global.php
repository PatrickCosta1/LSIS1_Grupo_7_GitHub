<?php
// ...existing code to check session/profile and load data for each profile...
// Exemplo: $kpis, $graficos, $menu são arrays dinâmicos conforme o perfil
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Portal Gestão Colaboradores</title>
    <link rel="stylesheet" href="../../assets/global.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f7f8fa; font-family: 'Segoe UI', Arial, sans-serif; color: #2d3748; }
        .container { max-width: 1400px; margin: 0 auto; padding: 36px 16px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
        .header h1 { font-size: 2.5rem; color: #764ba2; font-weight: 700; margin: 0; }
        .kpi-cards { display: flex; gap: 28px; margin-bottom: 36px; flex-wrap: wrap; }
        .kpi-card {
            flex: 1 1 220px; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(102,126,234,0.10);
            padding: 32px 28px; display: flex; flex-direction: column; align-items: flex-start;
            min-width: 200px; min-height: 120px; position: relative; transition: transform 0.12s; cursor: pointer;
        }
        .kpi-card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 8px 32px rgba(102,126,234,0.15);}
        .kpi-card .kpi-icon { font-size: 2.2rem; margin-bottom: 10px; color: #667eea; opacity: 0.85; }
        .kpi-card h3 { margin: 0 0 8px 0; font-size: 1.13rem; color: #764ba2; font-weight: 600; }
        .kpi-card .kpi-value { font-size: 2.5rem; color: #2d3748; font-weight: 700; }
        .kpi-card .kpi-desc { font-size: 1.01rem; color: #888; margin-top: 7px; }
        .charts-row { display: flex; gap: 36px; flex-wrap: wrap; margin-bottom: 32px; }
        .chart-card {
            background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(102,126,234,0.10);
            padding: 28px 22px; flex: 1 1 400px; min-width: 340px; margin-bottom: 32px; display: flex; flex-direction: column;
        }
        .chart-card h4 { margin: 0 0 12px 0; font-size: 1.13rem; color: #667eea; font-weight: 600; }
        .chart-desc { font-size: 1.01rem; color: #888; margin-bottom: 10px; }
        @media (max-width: 1100px) {
            .container { padding: 12px 4px; }
            .kpi-cards, .charts-row { flex-direction: column; gap: 18px; }
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
    <div class="container">
        <div class="header">
            <h1>Dashboard Geral</h1>
            <button class="toggle-dark" id="toggle-dark" title="Alternar modo escuro/claro">🌙</button>
        </div>
        <div class="kpi-cards">
            <?php foreach ($kpis as $kpi): ?>
                <div class="kpi-card">
                    <span class="kpi-icon"><?php echo $kpi['icon']; ?></span>
                    <h3><?php echo $kpi['title']; ?></h3>
                    <span class="kpi-value"><?php echo $kpi['value']; ?></span>
                    <span class="kpi-desc"><?php echo $kpi['desc']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="charts-row">
            <?php foreach ($graficos as $grafico): ?>
                <div class="chart-card">
                    <h4><?php echo $grafico['title']; ?></h4>
                    <div class="chart-desc"><?php echo $grafico['desc']; ?></div>
                    <canvas id="<?php echo $grafico['id']; ?>"></canvas>
                </div>
            <?php endforeach; ?>
        </div>
        <script>
        // Dark mode toggle
        document.getElementById('toggle-dark').onclick = function() {
            document.body.classList.toggle('dark');
            this.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
        };
        // Exemplo de inicialização de gráficos (substitua pelos dados reais)
        <?php foreach ($graficos as $grafico): ?>
        new Chart(document.getElementById('<?php echo $grafico['id']; ?>'), <?php echo $grafico['js']; ?>);
        <?php endforeach; ?>
        </script>
    </div>
</body>
</html>
