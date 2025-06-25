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
$equipa = $equipas && count($equipas) > 0 ? $equipas[0] : null;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Coordenador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
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
        <h1>Olá, <?php echo $nome; ?></h1>
        <?php if ($equipa): ?>
            <section class="dashboard-cards">
                <div class="card">
                    <h2>Minha Equipa</h2>
                    <p><?php echo htmlspecialchars($equipa['nome']); ?></p>
                    <a href="equipa.php?id=<?php echo $equipa['id']; ?>" class="btn">Ver Equipa</a>
                </div>
                <div class="card">
                    <h2>Perfil</h2>
                    <a href="../Comuns/perfil.php" class="btn">Ver Perfil</a>
                </div>
            </section>
        <?php else: ?>
            <p>Não gere nenhuma equipa.</p>
        <?php endif; ?>
    </main>
</body>
</html>