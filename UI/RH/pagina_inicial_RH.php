<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_dashboard_rh.php';
$rhBLL = new RHDashboardManager();
$nome = htmlspecialchars($rhBLL->getRHName($_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial RH - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/pagina_inicial.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <div class="dropdown-equipas">
                <a href="equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="relatorios.php">Relatórios</a>
                    <a href="dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="gerir_formacoes.php">Gerir Formações</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Perfil Colaborador</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
           
        </nav>
    </header>
    <main>
        <h1>Olá, <?php echo $nome; ?>!</h1>
        <p class="descricao-inicial">
            Gere os <strong>colaboradores</strong>, acompanhe <strong>relatórios</strong>, organize as <strong>equipas</strong> e exporte dados de forma <strong>eficiente e segura</strong> através do portal de Recursos Humanos.
        </p>

        <!-- Cards de funcionalidades RH -->
        <section class="funcionalidades-lista">
            <div class="funcionalidades-cards">
                <div class="funcionalidade-card" onclick="window.location.href='dashboard_rh.php'">
                    <span>Dashboard</span>
                </div>
                <div class="funcionalidade-card" onclick="window.location.href='colaboradores_gerir.php'">
                    <span>Gerir Colaboradores</span>
                </div>
                <div class="funcionalidade-card" onclick="window.location.href='equipas.php'">
                    <span>Gerir Equipas</span>
                </div>
                <div class="funcionalidade-card" onclick="window.location.href='relatorios.php'">
                    <span>Relatórios</span>
                </div>
                <div class="funcionalidade-card" onclick="window.location.href='exportar.php'">
                    <span>Exportar Dados</span>
                </div>
            </div>
        </section>

        
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
    const cards = document.querySelectorAll('.rh-card');
    let idx = 0;
    
    // Função para mostrar o próximo card
    function showNextCard() {
        cards[idx].classList.remove('active');
        idx = (idx + 1) % cards.length;
        cards[idx].classList.add('active');
    }
    
    // Auto-rotação a cada 4 segundos
    setInterval(showNextCard, 4000);
    
    // Click para navegar para a página
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const link = this.getAttribute('data-link');
            if (link) {
                window.location.href = link;
            }
        });
    });
});
</script>

</body>
</html>