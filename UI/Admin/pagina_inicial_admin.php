<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_dashboard_admin.php';
$adminBLL = new AdminDashboardManager();
$nome = htmlspecialchars($adminBLL->getAdminName($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <link rel="stylesheet" href="../../assets/CSS/Admin/dashboard.css">
</head>
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Bem-vindo, <?php echo $nome; ?></h1>
        <section class="dashboard-cards">
            <div class="card">
                <h2>Gestão de Utilizadores</h2>
                <p>Crie, edite e remova utilizadores e perfis.</p>
                <a href="utilizadores.php" class="btn">Gerir Utilizadores</a>
            </div>
            <div class="card">
                <h2>Permissões</h2>
                <p>Defina e ajuste permissões dos perfis.</p>
                <a href="permissoes.php" class="btn">Gerir Permissões</a>
            </div>
            <div class="card">
                <h2>Campos Personalizados</h2>
                <p>Adicione ou remova campos da ficha.</p>
                <a href="campos_personalizados.php" class="btn">Gerir Campos</a>
            </div>
            <div class="card">
                <h2>Alertas</h2>
                <p>Configure alertas e notificações.</p>
                <a href="alertas.php" class="btn">Gerir Alertas</a>
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