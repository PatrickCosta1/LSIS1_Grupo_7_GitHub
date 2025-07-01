<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$nome = htmlspecialchars($coordBLL->getCoordenadorName($_SESSION['user_id']));
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/pagina_inicial_coordenador.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_coordenador.php';">
        <nav>
            <?php
                // Corrigir link da equipa para incluir o id da equipa do coordenador
                $equipaLink = "equipa.php";
                if (!empty($equipas) && isset($equipas[0]['id'])) {
                    $equipaLink = "equipa.php?id=" . urlencode($equipas[0]['id']);
                }
            ?>
            <div class="dropdown-equipa">
                <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                    Equipa
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="dashboard_coordenador.php">Dashboard</a>
                    <a href="relatorios_equipa.php">Relatórios Equipa</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                    <!-- Adiciona mais opções se quiseres -->
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Bem-vindo, <?php echo $nome; ?>!</h1>
        <p class="descricao-inicial">
            Gere a tua Equipa através da consulta de relatórios e dashboards, acede à tua Ficha de Colaborador, recebe notificações importantes e muito mais!
        </p>
        <div class="botoes-atalho">
            <a href="<?php echo $equipaLink; ?>" class="botao-atalho roxo">A Minha Equipa</a>
            <a href="relatorios_equipa.php" class="botao-atalho laranja">Relatórios</a>
            <a href="../Colaborador/ficha_colaborador.php" class="botao-atalho verde">A Minha Ficha</a>
        </div>
        <div class="dash-carousel-container">
            <span class="dash-topic active">Gestão de Equipa</span>
            <span class="dash-topic">Relatórios de Desempenho</span>
            <span class="dash-topic">Notificações Importantes</span>
            <span class="dash-topic">Consulta de Dados</span>
            <img id="dash-img" class="dash-img" src="../../assets/5.png" alt="" />
        </div>
    </main>
</div>
<div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
  <button id="open-chatbot" style="
        background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const topics = document.querySelectorAll('.dash-topic');
    const img = document.getElementById('dash-img');
    const imgs = [
        '../../assets/5.png', // Gestão de Equipa
        '../../assets/4.png', // Relatórios de Desempenho
        '../../assets/6.png', // Notificações Importantes
        '../../assets/1.png'  // Consulta de Dados
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
        }, 350);
    }, 3000);
});
</script>
</body>
</html>