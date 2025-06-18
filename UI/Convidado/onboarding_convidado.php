<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'convidado') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Convidado/BLL_onboarding_convidado.php';
$convidadoBLL = new OnboardingConvidadoManager();
$colab = $convidadoBLL->getConvidadoByUserId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Onboarding - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="onboarding_convidado.php">Preencher Dados</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Preencha os seus dados</h1>
        <form class="ficha-form">
            <label>Nome: <input type="text" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>"></label><br><br>
            <label>Email: <input type="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>"></label><br><br>
            <label>Morada: <input type="text" value="<?php echo htmlspecialchars($colab['morada'] ?? ''); ?>"></label><br><br>
            <label>Contacto: <input type="text" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>"></label><br><br>
            <button type="submit" class="btn">Submeter</button>
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