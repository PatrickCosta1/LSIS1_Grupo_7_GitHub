<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_dashboard_rh.php';
$rhBLL = new RHDashboardManager();
$nome = htmlspecialchars($rhBLL->getRHName($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RH - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/dashboard_rh.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
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
    <main>
        <h1>Olá, <?php echo $nome; ?></h1>
        <section class="dashboard-cards">
            <div class="card">
                <h2>Gestão de Colaboradores</h2>
                <p>Adicione, edite ou remova colaboradores.</p>
                <a href="colaboradores_gerir.php" class="btn">Gerir</a>
            </div>
            <div class="card">
                <h2>Gestão de Equipas</h2>
                <p>Organize e acompanhe as equipas.</p>
                <a href="equipas.php" class="btn">Gerir Equipas</a>
            </div>
            <div class="card">
                <h2>Relatórios</h2>
                <p>Indicadores globais e por equipa.</p>
                <a href="relatorios.php" class="btn">Ver Relatórios</a>
            </div>
            <div class="card">
                <h2>Exportação</h2>
                <p>Exporte dados para Excel.</p>
                <a href="exportar.php" class="btn">Exportar</a>
            </div>
        </section>
    </main>

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
</body>
</html>