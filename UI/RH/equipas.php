<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipas.php';
$equipasBLL = new RHEquipasManager();
$equipas = $equipasBLL->getAllEquipas();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Equipas - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/equipas.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
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
            <?php endif; ?>
        </nav>
    </header>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Do Colaborador</span>
    </div>
    <div class="equipas-container">
        <div class="equipas-header">
            <h1>Gestão de Equipas</h1>
        </div>
        <div class="equipas-cards-lista">
            <?php foreach ($equipas as $eq): ?>
                <button class="equipa-card-flip" onclick="window.location.href='equipa.php?id=<?php echo $eq['id']; ?>'" title="Ver equipa <?php echo htmlspecialchars($eq['nome']); ?>">
                    <div class="equipa-card-inner">
                        <div class="equipa-card-front">
                            <span class="equipa-nome"><?php echo htmlspecialchars($eq['nome']); ?></span>
                        </div>
                        <div class="equipa-card-back">
                            <div>
                                <span class="equipa-info-label">Responsável</span><br>
                                <span class="equipa-info-value"><?php echo htmlspecialchars($eq['responsavel']); ?></span>
                            </div>
                            <div>
                                <span class="equipa-info-label">Nº Colaboradores</span><br>
                                <span class="equipa-info-ncolab"><?php echo htmlspecialchars($eq['num_colaboradores']); ?></span>
                            </div>
                        </div>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="nova-equipa-btn">
            <a href="equipa_nova.php" class="btn">+ Nova Equipa</a>
        </div>
    </div>
</body>
</html>