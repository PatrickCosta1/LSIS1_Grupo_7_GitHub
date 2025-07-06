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
        <div class="notificacoes-container">
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
        </div>
    </main>

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
</body>
</html>