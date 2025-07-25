<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Colaborador/BLL_dashboard_colaborador.php';
$colabBLL = new ColaboradorDashboardManager();
$nome = htmlspecialchars($colabBLL->getColaboradorName($_SESSION['user_id']));
$dados = $colabBLL->getDashboardData($_SESSION['user_id']);
$nmr_notificacoes = isset($dados['notificacoes_novas']) ? (int)$dados['notificacoes_novas'] : 0;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial Colaborador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/pagina_inicial_colaborador.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';">
        <nav>
            <a href="ficha_colaborador.php">A Minha Ficha</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
    <h1>Bem-vindo, <?php echo $nome; ?>!</h1>
    <p class="descricao-inicial">
        Atualiza os teus <strong>dados</strong>, recebe <strong>notificações</strong>, consulta informações importantes e gere a tua ligação com a empresa de forma <strong>simples e segura</strong>.
    </p>
    <div class="botoes-atalho">
        <a href="#" class="botao-atalho roxo">Recibos</a>
        <a href="ficha_colaborador.php#" class="botao-atalho laranja">Ficha Colaborador</a>
        <a href="#" class="botao-atalho verde">Férias</a>
    </div>
    <div class="dash-carousel-container">
        <span class="dash-topic active">Marcação de Férias</span>
        <span class="dash-topic">Consulta de Benefícios</span>
        <span class="dash-topic">Inscrição em Formações</span>
        <span class="dash-topic">Atualização de Dados</span>
        <span class="dash-topic">Consulta de Dados</span>
        <span class="dash-topic">Recibos de Vencimento</span>
        <img id="dash-img" class="dash-img" src="../../assets/5.png" alt="" />
    </div>
</main>
</div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topics = document.querySelectorAll('.dash-topic');
    const img = document.getElementById('dash-img');
    // Ordem das imagens deve corresponder à ordem dos tópicos!
    const imgs = [
        '../../assets/5.png', // Marcação de Férias
        '../../assets/4.png', // Consulta de Benefícios
        '../../assets/6.png', // Inscrição em Formações
        '../../assets/3.png', // Atualização de Dados
        '../../assets/1.png', // Consulta de Dados
        '../../assets/2.png'  // Recibos de Vencimento
    ];
    let idx = 0;
    setInterval(() => {
        topics[idx].classList.remove('active');
        img.classList.add('fade');
        idx = (idx + 1) % topics.length;
        topics[idx].classList.add('active');
        setTimeout(() => {
            img.src = imgs[idx];
            img.classList.remove('fade');
        }, 350); // metade do tempo da transição para suavizar
    }, 3000);
});
</script>


</body>
</html>