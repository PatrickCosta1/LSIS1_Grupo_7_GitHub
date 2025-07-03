<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !in_array($perfil, ['colaborador', 'coordenador', 'rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Colaborador/BLL_recibos_vencimento.php';
$recibosManager = new RecibosVencimentoManager();
$recibos = $recibosManager->getRecibosPorColaborador($_SESSION['user_id']);

// Nome do colaborador (opcional)
$nome = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Colaborador';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibos de Vencimento - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/recibos_vencimento.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header"
            <?php if ($perfil === 'colaborador'): ?>
                style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';"
            <?php elseif ($perfil === 'coordenador'): ?>
                style="cursor:pointer;" onclick="window.location.href='../Coordenador/pagina_inicial_coordenador.php';"
            <?php endif; ?>
        >
        <nav>
            <?php if ($perfil === 'coordenador'): ?>
                <?php
                    require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
                    $coordBLL = new CoordenadorDashboardManager();
                    $equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
                    $equipaLink = "../Coordenador/equipa.php";
                    if (!empty($equipas) && isset($equipas[0]['id'])) {
                        $equipaLink = "../Coordenador/equipa.php?id=" . urlencode($equipas[0]['id']);
                    }
                ?>
                <div class="dropdown-equipa">
                    <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                        Equipa
                        <span class="seta-baixo">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                        <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
                    </div>
                </div>
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
            <?php else: ?>
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
            <?php endif; ?>
        </nav>
    </header>
    <div class="azul-container">
        <main>
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        <h1>Recibos de Vencimento</h1>
        <p class="descricao-inicial">
            Aqui pode consultar e descarregar todos os seus recibos de vencimento submetidos pelo RH.
        </p>

        <div class="recibos-lista">
            <?php if (!empty($recibos)): ?>
                <?php foreach ($recibos as $recibo): ?>
                <div class="recibo-card">
                    <div class="recibo-info">
                        <div class="recibo-nome"><?= htmlspecialchars($recibo['nome_ficheiro']) ?></div>
                        <div class="recibo-data"><?= date('F Y', strtotime($recibo['mes_ano'])) ?></div>
                    </div>
                    <div class="recibo-acoes">
                        <a href="../../Uploads/Recibos/<?= htmlspecialchars($recibo['caminho_ficheiro']) ?>" 
                           target="_blank" class="btn-download">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                            </svg>
                            Descarregar
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="recibos-vazio">
                    Não existem recibos de vencimento disponíveis.
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>