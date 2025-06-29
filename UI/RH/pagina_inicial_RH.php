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
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="dashboard_rh.php">Dashboard</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1 style="text-align:center; margin-top:40px;">Bem-vindo, <?php echo $nome; ?>!</h1>
        <div class="dashboard-cards" style="display:flex; flex-wrap:wrap; justify-content:center; gap:32px; margin-top:40px;">
            <a href="colaboradores_gerir.php" class="dashboard-card">
                <h2>Gestão de Colaboradores</h2>
                <p>Gerir, adicionar e editar colaboradores.</p>
            </a>
            <a href="equipas.php" class="dashboard-card">
                <h2>Gestão de Equipas</h2>
                <p>Criar e gerir equipas de trabalho.</p>
            </a>
            <a href="relatorios.php" class="dashboard-card">
                <h2>Relatórios</h2>
                <p>Consultar e exportar relatórios.</p>
            </a>
            <a href="exportar.php" class="dashboard-card">
                <h2>Exportação</h2>
                <p>Exportar dados para outros formatos.</p>
            </a>
        </div>
    </main>
</body>
</html>