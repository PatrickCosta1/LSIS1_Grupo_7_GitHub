<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Colaborador/BLL_dashboard_colaborador.php';
$colabBLL = new ColaboradorDashboardManager();
$nome = htmlspecialchars($colabBLL->getColaboradorName($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Colaborador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/dashboard_colaborador.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';">
        <nav>
            <a href="dashboard_colaborador.php">Dashboard</a>
            <a href="ficha_colaborador.php">Minha Ficha</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1 style="text-align:center; margin-top:80px;">A CONCLUIR</h1>
    </main>

    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
    <button id="open-chatbot" style="
            background: linear-gradient(135deg,rgb(233, 103, 3) 0%,rgb(243, 176, 41) 100%);
            color: white;
            border: none;
            border-radius: 80%;
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