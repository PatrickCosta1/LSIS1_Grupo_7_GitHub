<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_notificacoes.php';
$notBLL = new NotificacoesManager();
$notificacoes = $notBLL->getNotificacoesByUserId($_SESSION['user_id']);

// Marcar notificação como lida
if (isset($_GET['marcar_lida'])) {
    $notificacaoId = $_GET['marcar_lida'];
    $notBLL->marcarComoLida($notificacaoId);
    header('Location: notificacoes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php if ($_SESSION['profile'] === 'coordenador'): ?>
                <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
                <a href="../Coordenador/equipa.php">Minha Equipa</a>
                <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'colaborador'): ?>
                <a href="../Colaborador/dashboard_colaborador.php">Dashboard</a>
                <a href="../Colaborador/ficha_colaborador.php">Minha Ficha</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'rh'): ?>
                <a href="../RH/dashboard_rh.php">Dashboard</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../RH/exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Notificações</h1>
        <ul class="notificacoes-lista">
            <?php foreach ($notificacoes as $n): ?>
                <li>
                    <strong><?php echo htmlspecialchars($n['tipo']); ?>:</strong>
                    <?php echo htmlspecialchars($n['mensagem']); ?>
                    <span class="data"><?php echo htmlspecialchars($n['data_envio']); ?></span>
                    <?php if (!$n['lida']): ?>
                        <a href="notificacoes.php?marcar_lida=<?php echo $n['id']; ?>" class="btn">Marcar como lida</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
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