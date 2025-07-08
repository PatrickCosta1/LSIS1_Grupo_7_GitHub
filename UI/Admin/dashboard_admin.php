<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    
    <main>
        <h1>Dashboard Administrativo</h1>
        <div class="dashboard-overview">
            <div class="metric-card">
                <h3>Sistema</h3>
                <p>Monitorização do Portal Tlantic</p>
            </div>
        </div>
    </main>
</body>
</html>