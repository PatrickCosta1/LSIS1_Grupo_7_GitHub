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
                    <a href="gerir_recibos.php">Submeter Recibos</a>
                    <a href="campos_personalizados.php">Campos Personalizados</a>
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