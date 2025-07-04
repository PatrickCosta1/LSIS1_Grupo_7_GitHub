<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_relatorios.php';
$relatoriosBLL = new RHRelatoriosManager();
$indicadores = $relatoriosBLL->getIndicadoresGlobais();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/relatorios.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
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
        <h1 class="relatorios-titulo">Relatórios e Dashboards</h1>
        <section>
            <div class="indicadores-titulo">Indicadores Gerais</div>
            <table class="indicadores-table">                   
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total de colaboradores</td>
                                <td><?php echo htmlspecialchars($indicadores['total_colaboradores']); ?></td>
                            </tr>
                            <tr>
                                <td>Colaboradores ativos</td>
                                <td><?php echo htmlspecialchars($indicadores['ativos']); ?></td>
                            </tr>
                            <tr>
                                <td>Colaboradores inativos</td>
                                <td><?php echo htmlspecialchars($indicadores['inativos']); ?></td>
                            </tr>
                            <tr>
                                <td>Total de equipas</td>
                                <td><?php echo htmlspecialchars($indicadores['total_equipas']); ?></td>
                            </tr>
                        </tbody>
                    </table>
        </section>
        <section class="dashboard-cards">
            <div class="card">
                <h2>Aniversários por Equipa</h2>
                <a href="./relatorio_aniversarios.php" class="btn" style="display:inline-block;">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Alterações Contratuais</h2>
                <a href="./relatorio_alteracoes.php" class="btn" style="display:inline-block;">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Vouchers Atribuídos</h2>
                <a href="./relatorio_vouchers.php" class="btn" style="display:inline-block;">Ver Relatório</a>
            </div>
            <div class="card">
                <h2>Dashboards de Equipa</h2>
                <a href="./dashboard_rh.php" class="btn" style="display:inline-block;">Ver Dashboard</a>
            </div>
        </section>
    </main>
jscjbsj

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