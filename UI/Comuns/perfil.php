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

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/perfil.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
<header>
    <?php
        // Define o destino do logo conforme o perfil
        $logoLink = "#";
        if ($_SESSION['profile'] === 'colaborador') {
            $logoLink = "../Colaborador/pagina_inicial_colaborador.php";
        } elseif ($_SESSION['profile'] === 'coordenador') {
            $logoLink = "../Coordenador/dashboard_coordenador.php";
        } elseif ($_SESSION['profile'] === 'admin') {
            $logoLink = "../Admin/dashboard_admin.php";
        } elseif ($_SESSION['profile'] === 'rh') {
            $logoLink = "../RH/dashboard_rh.php";
        } else {
            $logoLink = "../Convidado/onboarding_convidado.php";
        }
    ?>
    <a href="<?php echo $logoLink; ?>">
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;">
    </a>
    <nav>
        <?php if ($_SESSION['profile'] === 'coordenador'): ?>
            <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
            <a href="../Colaborador/ficha_colaborador.php">A Minha Ficha</a>
            <a href="../Coordenador/equipa.php">A Minha Equipa</a>
            <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        <?php elseif ($_SESSION['profile'] === 'colaborador'): ?>
            <a href="../Colaborador/ficha_colaborador.php">A Minha Ficha</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        <?php elseif ($_SESSION['profile'] === 'admin'): ?>
            <a href="../Admin/dashboard_admin.php">Dashboard</a>
            <a href="../Admin/utilizadores.php">Utilizadores</a>
            <a href="../Admin/permissoes.php">Permissões</a>
            <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
            <a href="../Admin/alertas.php">Alertas</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        <?php elseif ($_SESSION['profile'] === 'rh'): ?>
            <a href="../RH/dashboard_rh.php">Dashboard</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../RH/exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        <?php else: ?>
            <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
            <a href="../Comuns/logout.php">Sair</a>
        <?php endif; ?>
    </nav>
</header>
    <main>
        <h1>O Meu Perfil</h1>
        <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
        <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
        <div class="perfil-edit-container">
        <form class="perfil-edit-form" method="POST">
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
            <div class="perfil-edit-actions">
                <button type="submit" class="btn">Guardar Alterações</button>
            </div>
        </form>
        </div>
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