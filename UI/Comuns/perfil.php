<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Comuns/BLL_perfil.php';
$userBLL = new PerfilManager();
$user = $userBLL->getUserById($_SESSION['user_id']);

$success_message = '';
$error_message = '';

// Menu dinâmico por perfil (sempre pelo $_SESSION['profile'])
$menu = [];
switch ($_SESSION['profile']) {
    case 'admin':
        $menu = [
            'Dashboard' => '../Admin/dashboard_admin.php',
            'Utilizadores' => '../Admin/utilizadores.php',
            'Permissões' => '../Admin/permissoes.php',
            'Campos Personalizados' => '../Admin/campos_personalizados.php',
            'Alertas' => '../Admin/alertas.php',
            'Colaboradores' => '../RH/colaboradores_gerir.php',
            'Equipas' => '../RH/equipas.php',
            'Relatórios' => '../RH/relatorios.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'rh':
        $menu = [
            'Dashboard' => '../RH/dashboard_rh.php',
            'Colaboradores' => '../RH/colaboradores_gerir.php',
            'Equipas' => '../RH/equipas.php',
            'Relatórios' => '../RH/relatorios.php',
            'Exportar' => '../RH/exportar.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'coordenador':
        $menu = [
            'Dashboard' => '../Coordenador/dashboard_coordenador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Minha Equipa' => '../Coordenador/equipa.php',
            'Relatórios Equipa' => '../Coordenador/relatorios_equipa.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'colaborador':
        $menu = [
            'Dashboard' => '../Colaborador/dashboard_colaborador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Notificações' => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'convidado':
        $menu = [
            'Preencher Dados' => '../Convidado/onboarding_convidado.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
}

// Adicionar sino de notificações para colaborador
require_once '../../BLL/Admin/BLL_alertas.php';
require_once '../../DAL/Admin/DAL_utilizadores.php';
$alertasBLL = new AdminAlertasManager();
$dalUtil = new DAL_UtilizadoresAdmin();
$user_alerta = $dalUtil->getUtilizadorById($_SESSION['user_id']);
$perfil_id_alerta = $user_alerta['perfil_id'];
$user_id_alerta = $_SESSION['user_id'];
$alertas = $alertasBLL->getAlertasParaUtilizador($perfil_id_alerta);
$tem_nao_lidas = false;
foreach ($alertas as $a) {
    if (!$alertasBLL->isAlertaLido($a['id'], $user_id_alerta)) {
        $tem_nao_lidas = true;
        break;
    }
}
$icone_sino = '<span style="position:relative;display:inline-block;">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4a468a" viewBox="0 0 24 24" style="vertical-align:middle;">
        <path d="M12 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 5 14h14a1 1 0 0 0 .707-1.707L19 11.586V8a6 6 0 0 0-6-6zm0 20a2.978 2.978 0 0 0 2.816-2H9.184A2.978 2.978 0 0 0 12 22z"/>
    </svg>';
if ($tem_nao_lidas) {
    $icone_sino .= '<span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#e53e3e;border-radius:50%;border:2px solid #fff;"></span>';
}
$icone_sino .= '</span>';
// Ajustar menu para coordenador
if ($_SESSION['profile'] === 'coordenador') {
    $menu = [
        'Dashboard' => '../Coordenador/dashboard_coordenador.php',
        'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
        'Minha Equipa' => '../Coordenador/equipa.php',
        $icone_sino => '../Comuns/notificacoes.php',
        'Perfil' => '../Comuns/perfil.php',
        'Sair' => '../Comuns/logout.php'
    ];
} elseif ($_SESSION['profile'] === 'colaborador') {
    $menu = [
        'Dashboard' => '../Colaborador/dashboard_colaborador.php',
        'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
        $icone_sino => '../Comuns/notificacoes.php',
        'Perfil' => '../Comuns/perfil.php',
        'Sair' => '../Comuns/logout.php'
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <?php if (!in_array($_SESSION['profile'], ['admin', 'rh'])): ?>
        <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
    <?php endif; ?>
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
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="perfil.php">Perfil</a>
                <a href="logout.php">Sair</a>
            <?php elseif ($_SESSION['profile'] === 'rh'): ?>
                <a href="../RH/dashboard_rh.php">Dashboard</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../RH/exportar.php">Exportar</a>
                <a href="perfil.php">Perfil</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <?php foreach ($menu as $label => $url): ?>
                    <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Meu Perfil</h1>
        <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
        <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
        <form class="ficha-form ficha-form-moderna" method="POST">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Nova Palavra-passe:</label>
                    <input type="password" name="nova_password">
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Guardar Alterações</button>
            </div>
        </form>
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