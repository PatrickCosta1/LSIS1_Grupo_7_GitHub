<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Comuns/BLL_notificacoes.php';
$notifBLL = new NotificacoesManager();

$userId = $_SESSION['user_id'];
$perfil = $_SESSION['profile'] ?? '';

// Para RH, buscar pedidos pendentes
$pedidosFeriasPendentes = [];
$pedidosComprovantivosPendentes = [];
$pedidosAlteracaoFichaPendentes = [];
if ($perfil === 'rh') {
    require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
    $fichaManager = new ColaboradorFichaManager();
    $pedidosFeriasPendentes = $fichaManager->listarPedidosFeriasPendentes();
    $pedidosComprovantivosPendentes = $fichaManager->listarPedidosComprovantivosPendentes();
    $pedidosAlteracaoFichaPendentes = $fichaManager->listarPedidosPendentes();
    
    // Processar aprovação/recusa de férias
    if (isset($_POST['aprovar_ferias'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->atualizarEstadoPedidoFerias($pedidoId, 'aceite');
        $pedido = $fichaManager->getPedidoFeriasById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu pedido de férias de {$pedido['data_inicio']} até {$pedido['data_fim']} foi aprovado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
    
    if (isset($_POST['recusar_ferias'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->atualizarEstadoPedidoFerias($pedidoId, 'recusado');
        $pedido = $fichaManager->getPedidoFeriasById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu pedido de férias de {$pedido['data_inicio']} até {$pedido['data_fim']} foi recusado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
    
    // Processar aprovação/recusa de comprovativos
    if (isset($_POST['aprovar_comprovativo'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->aprovarPedidoComprovativo($pedidoId);
        $pedido = $fichaManager->getPedidoComprovantivoById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $tipoLimpo = str_replace('comprovativo_', '', $pedido['tipo_comprovativo']);
                $tipoLimpo = ucfirst(str_replace('_', ' ', $tipoLimpo));
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu comprovativo '{$tipoLimpo}' foi aprovado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
    
    if (isset($_POST['recusar_comprovativo'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->recusarPedidoComprovativo($pedidoId);
        $pedido = $fichaManager->getPedidoComprovantivoById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $tipoLimpo = str_replace('comprovativo_', '', $pedido['tipo_comprovativo']);
                $tipoLimpo = ucfirst(str_replace('_', ' ', $tipoLimpo));
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu comprovativo '{$tipoLimpo}' foi recusado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
    
    // Processar aprovação/recusa de alterações de ficha
    if (isset($_POST['aprovar_alteracao'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->aprovarPedido($pedidoId);
        $pedido = $fichaManager->getPedidoById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu pedido de alteração do campo '{$pedido['campo']}' foi aprovado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
    
    if (isset($_POST['recusar_alteracao'])) {
        $pedidoId = $_POST['pedido_id'];
        $fichaManager->recusarPedido($pedidoId);
        $pedido = $fichaManager->getPedidoById($pedidoId);
        if ($pedido) {
            require_once '../../DAL/Database.php';
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$pedido['colaborador_id']]);
            $utilizador_id = $stmt->fetchColumn();
            if ($utilizador_id) {
                $notifBLL->enviarNotificacao(null, $utilizador_id, 
                    "O seu pedido de alteração do campo '{$pedido['campo']}' foi recusado pelo RH.");
            }
        }
        header('Location: notificacoes.php');
        exit();
    }
}

// Marcar notificação como lida
if (isset($_POST['marcar_lida']) && isset($_POST['notif_id'])) {
    $notifBLL->marcarComoLida($_POST['notif_id'], $userId);
    header('Location: notificacoes.php');
    exit();
}

// Marcar todas como lidas
if (isset($_POST['marcar_todas_lidas'])) {
    $notifBLL->marcarTodasComoLidas($userId);
    header('Location: notificacoes.php');
    exit();
}

// Remover notificação
if (isset($_POST['remover_notif']) && isset($_POST['notif_id'])) {
    require_once '../../DAL/Comuns/DAL_notificacoes.php';
    $dal = new DAL_Notificacoes();
    $dal->removerNotificacao($_POST['notif_id'], $userId);
    header('Location: notificacoes.php');
    exit();
}

$notificacoes = $notifBLL->getNotificacoesPorUtilizador($userId);
$naoLidas = $notifBLL->contarNaoLidas($userId);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Comuns/notificacoes.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
    <style>
        .notificacoes-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .notificacao-item {
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #0360e9;
            background: #f8f9fa;
            border-radius: 6px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .notificacao-item.nao-lida {
            background: #e7f3ff;
            border-left-color: #ff8c00;
        }
        .notificacao-conteudo {
            flex: 1;
        }
        .notificacao-mensagem {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        .notificacao-data {
            font-size: 12px;
            color: #666;
        }
        .notificacao-acoes {
            display: flex;
            gap: 10px;
        }
        .btn-acao {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-marcar {
            background: #28a745;
            color: white;
        }
        .btn-remover {
            background: #dc3545;
            color: white;
        }
        .btn-todas {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
        .estatisticas {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
        }
        
        .page-layout {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            gap: 20px;
        }
        
        .menu-lateral-rh {
            width: 320px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .menu-lateral-rh h3 {
            color: #0360e9;
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #0360e9;
            padding-bottom: 8px;
        }
        
        .menu-secao {
            margin-bottom: 25px;
        }
        
        .menu-secao h4 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .pedido-item {
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .pedido-item.ferias {
            border-left: 4px solid #ff8c00;
        }
        
        .pedido-item.comprovativo {
            border-left: 4px solid #28a745;
        }
        
        .pedido-item.alteracao {
            border-left: 4px solid #6f42c1;
        }
        
        .pedido-colaborador {
            font-weight: 600;
            color: #0360e9;
            margin-bottom: 5px;
        }
        
        .pedido-detalhes {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        
        .pedido-acoes {
            display: flex;
            gap: 8px;
        }
        
        .btn-mini {
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .btn-aprovar {
            background: #28a745;
            color: white;
        }
        
        .btn-recusar {
            background: #dc3545;
            color: white;
        }
        
        .notificacoes-principal {
            flex: 1;
        }
        
        .contador-pendentes {
            background: #ff8c00;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 8px;
        }
        
        @media (max-width: 768px) {
            .page-layout {
                flex-direction: column;
            }
            
            .menu-lateral-rh {
                width: 100%;
                order: 2;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="<?php 
            if ($perfil === 'colaborador') echo '../Colaborador/pagina_inicial_colaborador.php';
            elseif ($perfil === 'coordenador') echo '../Coordenador/pagina_inicial_coordenador.php';
            elseif ($perfil === 'rh') echo '../RH/pagina_inicial_RH.php';
            elseif ($perfil === 'admin') echo '../Admin/pagina_inicial_admin.php';
            else echo '../Comuns/login.php';
        ?>">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>
        <nav>
            <?php if ($perfil === 'colaborador'): ?>
                    <a href="../Colaborador/ficha_colaborador.php">A Minha Ficha</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <div class="dropdown-perfil">
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                        <span class="seta-baixo">▾</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                        <a href="../Colaborador/beneficios.php">Benefícios</a>
                        <a href="../Colaborador/ferias.php">Férias</a>
                        <a href="../Colaborador/formacoes.php">Formações</a>
                        <a href="../Colaborador/recibos.php">Recibos</a>
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($perfil === 'coordenador'): ?>
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
                        <span class="seta-baixo">▾</span>
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
                        <span class="seta-baixo">▾</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                        <a href="../Colaborador/beneficios.php">Benefícios</a>
                        <a href="../Colaborador/ferias.php">Férias</a>
                        <a href="../Colaborador/formacoes.php">Formações</a>
                        <a href="../Colaborador/recibos.php">Recibos</a>
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($perfil === 'rh'): ?>
                 <div class="dropdown-equipas">
                <a href="../RH/equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/relatorios.php">Relatórios</a>
                    <a href="../RH/dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="../RH/colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="../RH/gerir_formacoes.php">Gerir Formações</a>
                    <a href="../RH/gerir_recibos.php">Submeter Recibos</a>
                    <a href="../RH/campos_personalizados.php">Campos Personalizados</a>
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
            <?php elseif ($perfil === 'admin'): ?>
            <a href="../Admin/utilizadores.php">Utilizadores</a>
            <a href="../Admin/permissoes.php">Permissões</a>
            <a href="../Admin/alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if ($perfil === 'rh'): ?>
        <div class="page-layout">
            <!-- Menu Lateral para RH -->
            <div class="menu-lateral-rh">
                <h3>⚡ Ações Rápidas</h3>
                
                <div class="menu-secao">
                    <h4>
                        🏖️ Pedidos de Férias
                        <?php if (count($pedidosFeriasPendentes) > 0): ?>
                            <span class="contador-pendentes"><?= count($pedidosFeriasPendentes) ?></span>
                        <?php endif; ?>
                    </h4>
                    
                    <?php if (empty($pedidosFeriasPendentes)): ?>
                        <p style="color: #666; font-size: 0.9rem;">Nenhum pedido pendente</p>
                    <?php else: ?>
                        <?php foreach ($pedidosFeriasPendentes as $pedido): ?>
                            <div class="pedido-item ferias">
                                <div class="pedido-colaborador"><?= htmlspecialchars($pedido['colaborador_nome']) ?></div>
                                <div class="pedido-detalhes">
                                    <?= date('d/m/Y', strtotime($pedido['data_inicio'])) ?> até 
                                    <?= date('d/m/Y', strtotime($pedido['data_fim'])) ?>
                                </div>
                                <div class="pedido-acoes">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="aprovar_ferias" class="btn-mini btn-aprovar">✓ Aprovar</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="recusar_ferias" class="btn-mini btn-recusar">✗ Recusar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="menu-secao">
                    <h4>
                        📎 Comprovativos
                        <?php if (count($pedidosComprovantivosPendentes) > 0): ?>
                            <span class="contador-pendentes"><?= count($pedidosComprovantivosPendentes) ?></span>
                        <?php endif; ?>
                    </h4>
                    
                    <?php if (empty($pedidosComprovantivosPendentes)): ?>
                        <p style="color: #666; font-size: 0.9rem;">Nenhum comprovativo pendente</p>
                    <?php else: ?>
                        <?php foreach ($pedidosComprovantivosPendentes as $pedido): ?>
                            <div class="pedido-item comprovativo">
                                <div class="pedido-colaborador"><?= htmlspecialchars($pedido['colaborador_nome']) ?></div>
                                <div class="pedido-detalhes">
                                    <?php 
                                    $tipo = str_replace('comprovativo_', '', $pedido['tipo_comprovativo']);
                                    $tipo = ucfirst(str_replace('_', ' ', $tipo));
                                    echo $tipo;
                                    ?>
                                    <br>
                                    <a href="../../Uploads/comprovativos/<?= htmlspecialchars($pedido['comprovativo_novo']) ?>" 
                                       target="_blank" style="color: #0360e9; font-size: 0.8rem;">📄 Ver ficheiro</a>
                                </div>
                                <div class="pedido-acoes">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="aprovar_comprovativo" class="btn-mini btn-aprovar">✓ Aprovar</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="recusar_comprovativo" class="btn-mini btn-recusar">✗ Recusar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="menu-secao">
                    <h4>
                        ✏️ Alterações Ficha
                        <?php if (count($pedidosAlteracaoFichaPendentes) > 0): ?>
                            <span class="contador-pendentes"><?= count($pedidosAlteracaoFichaPendentes) ?></span>
                        <?php endif; ?>
                    </h4>
                    
                    <?php if (empty($pedidosAlteracaoFichaPendentes)): ?>
                        <p style="color: #666; font-size: 0.9rem;">Nenhuma alteração pendente</p>
                    <?php else: ?>
                        <?php foreach ($pedidosAlteracaoFichaPendentes as $pedido): ?>
                            <div class="pedido-item alteracao">
                                <div class="pedido-colaborador"><?= htmlspecialchars($pedido['colaborador_nome']) ?></div>
                                <div class="pedido-detalhes">
                                    <strong><?= htmlspecialchars($pedido['campo']) ?></strong><br>
                                    De: <?= htmlspecialchars($pedido['valor_antigo'] ?: '-') ?><br>
                                    Para: <?= htmlspecialchars($pedido['valor_novo']) ?>
                                </div>
                                <div class="pedido-acoes">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="aprovar_alteracao" class="btn-mini btn-aprovar">✓ Aprovar</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="recusar_alteracao" class="btn-mini btn-recusar">✗ Recusar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Notificações Principal -->
            <div class="notificacoes-principal">
        <?php else: ?>
        <div class="notificacoes-container">
        <?php endif; ?>
        
            <h1>Notificações</h1>
            
            <div class="estatisticas">
                <strong>📊 Estatísticas:</strong> 
                <?= count($notificacoes) ?> notificações total, 
                <?= $naoLidas ?> não lidas
                
                <?php if ($naoLidas > 0): ?>
                    <form method="post" style="display: inline; margin-left: 15px;">
                        <button type="submit" name="marcar_todas_lidas" class="btn-todas" style="background: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                            ✓ Marcar todas como lidas
                        </button>
                    </form>
                    
                    <button type="button" onclick="abrirModalNaoLidas()" style="background: #ff8c00; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-left: 10px;">
                        🔔 Ver só não lidas (<?= $naoLidas ?>)
                    </button>
                <?php endif; ?>
            </div>

            <?php if (empty($notificacoes)): ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    📭 Não tem notificações
                </div>
            <?php else: ?>
                <?php foreach ($notificacoes as $notif): ?>
                    <div class="notificacao-item <?= $notif['lida'] ? '' : 'nao-lida' ?>">
                        <div class="notificacao-conteudo">
                            <div class="notificacao-mensagem">
                                <?= htmlspecialchars($notif['mensagem']) ?>
                            </div>
                            <div class="notificacao-data">
                                📅 <?= date('d/m/Y H:i', strtotime($notif['data_envio'])) ?>
                                <?php if (!$notif['lida']): ?>
                                    <span style="color: #ff8c00; font-weight: bold;"> • NOVA</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="notificacao-acoes">
                            <?php if (!$notif['lida']): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                                    <button type="submit" name="marcar_lida" class="btn-acao btn-marcar">
                                        ✓ Marcar lida
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                                <button type="submit" name="remover_notif" class="btn-acao btn-remover" 
                                        onclick="return confirm('Remover esta notificação?')">
                                    🗑️ Remover
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        <?php if ($perfil === 'rh'): ?>
            </div>
        </div>
        <?php else: ?>
        </div>
        <?php endif; ?>

        <!-- Modal para mostrar apenas notificações não lidas -->
        <div id="modalNaoLidas" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="max-width: 600px; max-height: 80vh; overflow-y: auto;">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ddd;">
                    <h2 style="margin: 0; color: #ff8c00;">🔔 Notificações Não Lidas (<?= $naoLidas ?>)</h2>
                    <button onclick="fecharModalNaoLidas()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <?php 
                    $naoLidasList = array_filter($notificacoes, function($n) { return !$n['lida']; });
                    if (empty($naoLidasList)): 
                    ?>
                        <p style="text-align: center; color: #666;">Não tem notificações não lidas</p>
                    <?php else: ?>
                        <?php foreach ($naoLidasList as $notif): ?>
                            <div class="notificacao-item nao-lida" style="margin-bottom: 15px;">
                                <div class="notificacao-conteudo">
                                    <div class="notificacao-mensagem">
                                        <?= htmlspecialchars($notif['mensagem']) ?>
                                    </div>
                                    <div class="notificacao-data">
                                        📅 <?= date('d/m/Y H:i', strtotime($notif['data_envio'])) ?>
                                        <span style="color: #ff8c00; font-weight: bold;"> • NOVA</span>
                                    </div>
                                </div>
                                
                                <div class="notificacao-acoes" style="margin-top: 10px;">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                                        <button type="submit" name="marcar_lida" class="btn-acao btn-marcar" style="font-size: 11px;">
                                            ✓ Marcar lida
                                        </button>
                                    </form>
                                    
                                    <form method="post" style="display: inline; margin-left: 5px;">
                                        <input type="hidden" name="notif_id" value="<?= $notif['id'] ?>">
                                        <button type="submit" name="remover_notif" class="btn-acao btn-remover" style="font-size: 11px;"
                                                onclick="return confirm('Remover esta notificação?')">
                                            🗑️ Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            function abrirModalNaoLidas() {
                document.getElementById('modalNaoLidas').style.display = 'flex';
            }
            
            function fecharModalNaoLidas() {
                document.getElementById('modalNaoLidas').style.display = 'none';
            }
            
            // Fechar modal ao clicar fora dele
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('modalNaoLidas');
                if (e.target === modal) {
                    fecharModalNaoLidas();
                }
            });
            
            // Fechar modal com tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalNaoLidas();
                }
            });
        </script>
    </main>
    <?php if ($perfil === 'colaborador'): ?>
    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg,rgb(255, 203, 120) 0%,rgb(251, 155, 0) 100%);
          color:rgb(255, 255, 255);
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
    <?php endif; ?>
</body>
</html>