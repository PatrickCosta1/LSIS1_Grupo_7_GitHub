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
        <?php
        // Menu dinâmico com sino para coordenador
        require_once '../../BLL/Admin/BLL_alertas.php';
        require_once '../../DAL/Admin/DAL_utilizadores.php';
        $alertasBLL = new AdminAlertasManager();
        $dalUtil = new DAL_UtilizadoresAdmin();
        $user = $dalUtil->getUtilizadorById($_SESSION['user_id']);
        $perfil_id = $user['perfil_id'];
        $user_id = $_SESSION['user_id'];
        $alertas = $alertasBLL->getAlertasParaUtilizador($perfil_id);
        $tem_nao_lidas = false;
        foreach ($alertas as $a) {
            if (!$alertasBLL->isAlertaLido($a['id'], $user_id)) {
                $tem_nao_lidas = true;
                break;
            }
        }
        $icone_sino = '<span style="position:relative;display:inline-block;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4a468a" viewBox="0 0 24 24" style="vertical-align:middle;">
                <path d="M12 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 5 14h14a1 1 0 0 0 .707-1.707L19 11.586V8a6 6 0 0 0-6-6zm0 20a2.978 2.978 0 0 0 2.816-2H9.184A2.978 2.978 0 0 0 12 22z"/>
            </svg>';
        if ($tem_nao_lidas) {
            $icone_sino .= '<span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#e53e3e;border-radius:50%;border:2px solid #fff;"></span>';
        }
        $icone_sino .= '</span>';
        $menu = [
            'Dashboard' => 'dashboard_coordenador.php',
            'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
            'Minha Equipa' => isset($equipa['id']) ? 'equipa.php?id=' . urlencode($equipa['id']) : 'equipa.php',
            $icone_sino => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        ?>
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