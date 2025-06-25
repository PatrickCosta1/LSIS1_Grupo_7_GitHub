<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'convidado') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Convidado/BLL_dashboard_convidado.php';
$convidadoBLL = new ConvidadoDashboardManager();
$nome = htmlspecialchars($convidadoBLL->getConvidadoName($_SESSION['user_id']));
$menu = [
    'Preencher Dados' => 'onboarding_convidado.php',
    'Sair' => '../Comuns/logout.php'
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Onboarding - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
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
    <main>
        <h1>Bem-vindo(a), <?php echo $nome; ?>!</h1>
        <p>Por favor, preencha os seus dados para iniciar o processo de contratação.</p>
        <a href="onboarding_convidado.php" class="btn">Preencher Dados</a>
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