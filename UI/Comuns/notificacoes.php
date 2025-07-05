<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Comuns/BLL_notificacoes.php';
$notBLL = new NotificacoesManager();
$notificacoes = $notBLL->getNotificacoesByUserId($_SESSION['user_id']);

require_once '../../BLL/Comuns/BLL_mensagens.php';
$mensagemBLL = new MensagensManager();
$mensagensRecebidas = $mensagemBLL->getMensagensParaUtilizador($_SESSION['user_id']);

// Definir $naoLidas ANTES de processar o POST
$naoLidas = array_filter($notificacoes, function($not) { return !$not['lida']; });

// Eliminar notificação
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $notBLL->eliminarNotificacao($_GET['eliminar']);
    header('Location: notificacoes.php');
    exit();
}

// Marcar notificação como lida
if (isset($_GET['marcar_lida'])) {
    $notificacaoId = $_GET['marcar_lida'];
    $notBLL->marcarComoLida($notificacaoId);
    header('Location: notificacoes.php');
    exit();
}

// Marcar todas as notificações como lidas
if (isset($_POST['marcar_todas_lidas'])) {
    foreach ($naoLidas as $not) {
        $notBLL->marcarComoLida($not['id']);
    }
    header('Location: notificacoes.php');
    exit();
}

// RH: Aprovação de pedidos de alteração e férias
$aprovacao_msg = '';
if ($_SESSION['profile'] === 'rh') {
    require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
    $colabBLL = new ColaboradorFichaManager();
    $notificacoesManager = new NotificacoesManager();

    // Aprovar pedido de comprovativo
    if (isset($_POST['aprovar_comprovativo'])) {
        if ($colabBLL->aprovarPedidoComprovativo($_POST['pedido_comprovativo_id'])) {
            $pedido = $colabBLL->getPedidoComprovantivoById($_POST['pedido_comprovativo_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorComprovativo(
                    $pedido['colaborador_id'],
                    'aprovado',
                    $pedido['tipo_comprovativo']
                );
            }
            $aprovacao_msg = "Comprovativo aprovado com sucesso.";
        }
    }

    // Recusar pedido de comprovativo
    if (isset($_POST['recusar_comprovativo'])) {
        if ($colabBLL->recusarPedidoComprovativo($_POST['pedido_comprovativo_id'])) {
            $pedido = $colabBLL->getPedidoComprovantivoById($_POST['pedido_comprovativo_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorComprovativo(
                    $pedido['colaborador_id'],
                    'recusado',
                    $pedido['tipo_comprovativo']
                );
            }
            $aprovacao_msg = "Comprovativo recusado.";
        }
    }

    // Aprovar pedido de alteração
    if (isset($_POST['aprovar_pedido'])) {
        if ($colabBLL->aprovarPedido($_POST['pedido_id'])) {
            // Buscar dados do pedido para o email
            $pedido = $colabBLL->getPedidoById($_POST['pedido_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorPedidoAlteracao(
                    $pedido['colaborador_id'],
                    'aprovado',
                    $pedido['campo'],
                    $pedido['valor_antigo'],
                    $pedido['valor_novo']
                );
            }
            $aprovacao_msg = "Alteração aprovada e aplicada.";
        } else {
            $aprovacao_msg = "Erro ao aprovar pedido.";
        }
    }
    // Recusar pedido de alteração
    if (isset($_POST['recusar_pedido'])) {
        if ($colabBLL->recusarPedido($_POST['pedido_id'])) {
            // Buscar dados do pedido para o email
            $pedido = $colabBLL->getPedidoById($_POST['pedido_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorPedidoAlteracao(
                    $pedido['colaborador_id'],
                    'recusado',
                    $pedido['campo'],
                    $pedido['valor_antigo'],
                    $pedido['valor_novo']
                );
            }
            $aprovacao_msg = "Alteração recusada.";
        } else {
            $aprovacao_msg = "Erro ao recusar pedido.";
        }
    }
    // Aprovar pedido de férias
    if (isset($_POST['aprovar_pedido_ferias'])) {
        if ($colabBLL->aprovarPedidoFerias($_POST['pedido_ferias_id'])) {
            $pedido = $colabBLL->getPedidoFeriasById($_POST['pedido_ferias_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorPedidoFerias(
                    $pedido['colaborador_id'],
                    'aceite',
                    $pedido['data_inicio'],
                    $pedido['data_fim']
                );
            }
            $aprovacao_msg = "Pedido de férias aprovado.";
        } else {
            $aprovacao_msg = "Erro ao aprovar pedido de férias.";
        }
    }
    // Recusar pedido de férias
    if (isset($_POST['recusar_pedido_ferias'])) {
        if ($colabBLL->recusarPedidoFerias($_POST['pedido_ferias_id'])) {
            $pedido = $colabBLL->getPedidoFeriasById($_POST['pedido_ferias_id']);
            if ($pedido) {
                $notificacoesManager->notificarColaboradorPedidoFerias(
                    $pedido['colaborador_id'],
                    'recusado',
                    $pedido['data_inicio'],
                    $pedido['data_fim']
                );
            }
            $aprovacao_msg = "Pedido de férias recusado.";
        } else {
            $aprovacao_msg = "Erro ao recusar pedido de férias.";
        }
    }
    // Buscar pedidos pendentes
    $pedidosPendentes = $colabBLL->listarPedidosPendentes();
    $pedidosFeriasPendentes = $colabBLL->listarPedidosFeriasPendentes();
    $pedidosComprovantivosPendentes = $colabBLL->listarPedidosComprovantivosPendentes();
}

// --- NOVO: Onboarding pendente para RH ---
$onboardingsPendentes = [];
if ($_SESSION['profile'] === 'rh') {
    require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
    $colabBLL = new RHColaboradoresManager();
    $onboardingsPendentes = $colabBLL->listarOnboardingsPendentes();
}

// Aprovação/rejeição de onboarding
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['onboarding_token'])) {
    require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
    $colabBLL = new RHColaboradoresManager();
    $token = $_POST['onboarding_token'];
    if (isset($_POST['aprovar_onboarding'])) {
        $novoColabId = $colabBLL->aprovarOnboarding($token);
        if ($novoColabId) {
            // GUARDA O ID NA SESSÃO E NO GET PARA O REDIRECT
            $_SESSION['onboarding_novo_colab_id'] = $novoColabId;
            header('Location: notificacoes.php?onboarding_popup=1&colab_id=' . urlencode($novoColabId));
            exit();
        }
        header('Location: notificacoes.php?onboarding_popup=1');
        exit();
    }
    if (isset($_POST['recusar_onboarding'])) {
        $colabBLL->recusarOnboarding($token);
        header('Location: notificacoes.php?onboarding_recusado=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Comuns/notificacoes.css">
    <style>
    /* Popup de sucesso onboarding aprovado */
    .popup-sucesso-bg {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(20,40,80,0.18);
        z-index: 4000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInBg 0.3s;
    }
    .popup-sucesso-content {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(3,96,233,0.13), 0 1.5px 8px rgba(0,0,0,0.08);
        padding: 34px 32px 28px 32px;
        min-width: 320px;
        max-width: 90vw;
        text-align: center;
        border: 2px solid #e6eaf7;
        animation: fadeInUp 0.4s;
        position: relative;
    }
    .popup-sucesso-content .close {
        position: absolute;
        top: 12px;
        right: 18px;
        background: none;
        border: none;
        font-size: 1.7rem;
        color: #19365f;
        cursor: pointer;
        font-weight: bold;
        transition: color 0.2s;
        z-index: 10;
        line-height: 1;
    }
    .popup-sucesso-content .close:hover {
        color: #299cf3;
    }
    .popup-sucesso-content .icon {
        font-size: 2.8rem;
        color: #3ed829;
        margin-bottom: 10px;
        display: block;
    }
    .popup-sucesso-content h3 {
        color: #0360e9;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 10px;
        margin-top: 0;
    }
    .popup-sucesso-content p {
        color: #23408e;
        font-size: 1.05rem;
        margin-bottom: 0;
    }
    .popup-sucesso-content .btn-ficha {
        margin-top: 18px;
        background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 10px 28px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(3,96,233,0.08);
        text-decoration: none;
        display: inline-block;
    }
    .popup-sucesso-content .btn-ficha:hover {
        background: linear-gradient(135deg, #1c3c69 0%, #0360e9 100%);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px);}
        to { opacity: 1; transform: translateY(0);}
    }
    @keyframes fadeInBg {
        from { opacity: 0;}
        to { opacity: 1;}
    }
    </style>
</head>
<body>
    <header>
        <?php
            // Define o destino do logo conforme o perfil
            $logoLink = "#";
            if ($_SESSION['profile'] === 'colaborador') {
                $logoLink = "../Colaborador/pagina_inicial_colaborador.php";
            } elseif ($_SESSION['profile'] === 'coordenador') {
                $logoLink = "../Coordenador/pagina_inicial_coordenador.php";
            } elseif ($_SESSION['profile'] === 'admin') {
                $logoLink = "../Admin/dashboard_admin.php";
            } elseif ($_SESSION['profile'] === 'rh') {
                $logoLink = "../RH/pagina_inicial_RH.php";
            } else {
                $logoLink = "../Convidado/onboarding_convidado.php";
            }
        ?>
        <a href="<?php echo $logoLink; ?>">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;">
        </a>
        <nav>
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

            <?php if ($_SESSION['profile'] === 'coordenador'): ?>
                
                <?php
                    // Corrigir link da equipa para incluir o id da equipa do coordenador
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
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                        <a href="../Colaborador/beneficios.php">Benefícios</a>
                        <a href="../Colaborador/ferias.php">Férias</a>
                        <a href="../Colaborador/formacoes.php">Formações</a>
                        <a href="../Colaborador/recibos.php">Recibos</a>
                        <!-- Adiciona mais opções se quiseres -->
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
        
            <?php elseif ($_SESSION['profile'] === 'colaborador'): ?>
                <a href="../Colaborador/ficha_colaborador.php">A Minha Ficha</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <div class="dropdown-perfil">
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                        <span class="seta-baixo">&#9662;</span>
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
            <?php elseif ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <div class="dropdown-perfil">
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                        <span class="seta-baixo">&#9662;</span>
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
                    <?php elseif ($_SESSION['profile'] === 'rh'): ?>
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
            <?php else: ?>
                <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <!-- Modal de Onboarding (logo após o nav/header) -->
    <div id="modalOnboarding" class="modal-onboarding-bg" style="display:none;">
        <div class="modal-onboarding-content compacto">
            <button class="close" onclick="fecharModalOnboarding()" title="Fechar">&times;</button>
            <div class="modal-onboarding-header">
                <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="modal-onboarding-logo">
                <h2>Ficha de Onboarding do Candidato</h2>
            </div>
            <div id="onboarding-dados" class="onboarding-dados-detalhes">
                <!-- Conteúdo preenchido via JS -->
            </div>
            <form method="POST" id="formAprovarOnboarding" class="modal-onboarding-actions">
                <input type="hidden" name="onboarding_token" id="onboarding_token">
                <button type="submit" name="aprovar_onboarding" class="btn btn-aprovar" title="Aceitar este onboarding">
                    <span>✔ Aceitar</span>
                </button>
                <button type="submit" name="recusar_onboarding" class="btn btn-recusar" title="Recusar este onboarding">
                    <span>✖ Recusar</span>
                </button>
            </form>
        </div>
    </div>
    <main>
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        <h1>Notificações</h1>
        
        <?php if ($_SESSION['profile'] === 'rh'): ?>
            <!-- Menu lateral para RH -->
            <div class="menu-lateral-notificacoes">
                <button type="button" class="menu-notif-link active" data-scroll="#notificacoes-sistema">
                    <span class="menu-icon">🔔</span>
                    Notificações Sistema
                </button>
                <button type="button" class="menu-notif-link" data-scroll="#pedidos-alteracao">
                    <span class="menu-icon">📝</span>
                    Pedidos Alteração
                </button>
                <button type="button" class="menu-notif-link" data-scroll="#pedidos-ferias">
                    <span class="menu-icon">🏖️</span>
                    Pedidos Férias
                </button>
                <button type="button" class="menu-notif-link" data-scroll="#pedidos-comprovativos">
                    <span class="menu-icon">📄</span>
                    Comprovativos
                </button>
            </div>
        <?php endif; ?>

        <div class="notificacoes-container">
        
        <!-- Adicionar contador de não lidas no topo -->
        <?php 
        $totalNaoLidas = count($naoLidas);
        ?>
        
        <?php if ($totalNaoLidas > 0): ?>
            <div id="banner-nao-lidas" class="banner-nao-lidas" onclick="abrirModalNaoLidas()" style="display:flex;justify-content:center;">
                <button type="button" class="btn-nao-lidas">
                    📢 Tem <?php echo $totalNaoLidas; ?> notificação<?php echo $totalNaoLidas > 1 ? 'ões' : ''; ?> por ler - Clique para ver
                </button>
            </div>
        <?php endif; ?>

        <div id="notificacoes-sistema">
            <?php if (!empty($notificacoes)): ?>
                <h2 class="notificacoes-section-title">Notificações do Sistema</h2>
                <ul class="notificacoes-lista">
                    <?php foreach ($notificacoes as $not): ?>
                        <li class="notificacao<?php if (!$not['lida']) echo ' unread'; ?>">
                            <span class="titulo">
                                <?php echo htmlspecialchars($not['mensagem']); ?>
                            </span>
                            <span class="data"><?php echo date('d/m/Y H:i', strtotime($not['data_envio'])); ?></span>
                            <div class="acao">
                                <?php if (!$not['lida']): ?>
                                    <form method="get" class="acao-form">
                                        <input type="hidden" name="marcar_lida" value="<?php echo $not['id']; ?>">
                                        <button type="submit" class="btn">Marcar como lida</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($not['lida']): ?>
                                    <form method="get" class="acao-form">
                                        <input type="hidden" name="eliminar" value="<?php echo $not['id']; ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Eliminar esta notificação?')">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="notificacoes-vazio">
                    Não existem notificações.
                </div>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION['profile'] === 'rh'): ?>
            <div id="pedidos-alteracao">
                <h2 class="notificacoes-section-title">Pedidos de Alteração de Ficha</h2>
                <?php if ($aprovacao_msg): ?>
                    <div class="success-message"><?php echo htmlspecialchars($aprovacao_msg); ?></div>
                <?php endif; ?>
                <?php if (!empty($pedidosPendentes)): ?>
                    <ul class="notificacoes-lista">
                    <?php foreach ($pedidosPendentes as $p): ?>
                        <li class="notificacao unread">
                            <span class="titulo">
                                <strong><?php echo htmlspecialchars($p['colaborador_nome']); ?></strong> pediu alteração:
                                <strong><?php echo htmlspecialchars($p['campo']); ?></strong>
                                <br>
                                <span class="valor-info">De:</span> <?php echo htmlspecialchars($p['valor_antigo']); ?>
                                <span class="valor-info">Para:</span> <strong><?php echo htmlspecialchars($p['valor_novo']); ?></strong>
                            </span>
                            <span class="data"><?php echo date('d/m/Y H:i', strtotime($p['data_pedido'])); ?></span>
                            <form method="post" class="acao-form">
                                <input type="hidden" name="pedido_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" name="aprovar_pedido" class="btn btn-sm">Aprovar</button>
                                <button type="submit" name="recusar_pedido" class="btn btn-danger btn-sm">Recusar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="notificacoes-vazio">
                        Não existem pedidos de alteração pendentes.
                    </div>
                <?php endif; ?>
            </div>

            <div id="pedidos-ferias">
                <h2 class="notificacoes-section-title">Pedidos de Férias Pendentes</h2>
                <?php if (!empty($pedidosFeriasPendentes)): ?>
                    <ul class="notificacoes-lista">
                    <?php foreach ($pedidosFeriasPendentes as $pf): ?>
                        <li class="notificacao unread">
                            <span class="titulo">
                                <strong><?php echo htmlspecialchars($pf['colaborador_nome']); ?></strong> pediu férias:
                                <br>
                                <span class="valor-info">De:</span> <?php echo htmlspecialchars($pf['data_inicio']); ?>
                                <span class="valor-info">Até:</span> <strong><?php echo htmlspecialchars($pf['data_fim']); ?></strong>
                            </span>
                            <span class="data"><?php echo date('d/m/Y H:i', strtotime($pf['data_pedido'])); ?></span>
                            <form method="post" class="acao-form">
                                <input type="hidden" name="pedido_ferias_id" value="<?php echo $pf['id']; ?>">
                                <button type="submit" name="aprovar_pedido_ferias" class="btn btn-sm">Aprovar</button>
                                <button type="submit" name="recusar_pedido_ferias" class="btn btn-danger btn-sm">Recusar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="notificacoes-vazio">
                        Não existem pedidos de férias pendentes.
                    </div>
                <?php endif; ?>
            </div>

            <div id="pedidos-comprovativos">
                <h2 class="notificacoes-section-title">Pedidos de Comprovativos Pendentes</h2>
                <?php if (!empty($pedidosComprovantivosPendentes)): ?>
                    <ul class="notificacoes-lista">
                    <?php foreach ($pedidosComprovantivosPendentes as $pc): ?>
                        <li class="notificacao unread">
                            <span class="titulo">
                                <strong><?php echo htmlspecialchars($pc['colaborador_nome']); ?></strong> enviou novo comprovativo:
                                <br>
                                <span class="valor-info">Tipo:</span> <strong><?php echo ucfirst(str_replace('_', ' ', $pc['tipo_comprovativo'])); ?></strong>
                            </span>
                            <div class="comprovativo-comparacao">
                                <div class="comprovativo-grid">
                                    <div>
                                        <strong class="comprovativo-anterior">Anterior:</strong>
                                        <?php if ($pc['comprovativo_antigo']): ?>
                                            <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($pc['comprovativo_antigo']); ?>" 
                                               target="_blank" 
                                               class="comprovativo-link anterior">
                                                📄 Ver ficheiro anterior
                                            </a>
                                        <?php else: ?>
                                            <span class="comprovativo-sem-arquivo">Sem comprovativo anterior</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong class="comprovativo-novo">Novo:</strong>
                                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($pc['comprovativo_novo']); ?>" 
                                           target="_blank" 
                                           class="comprovativo-link novo">
                                            📄 Ver novo ficheiro
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <span class="data"><?php echo date('d/m/Y H:i', strtotime($pc['data_pedido'])); ?></span>
                            <form method="post" class="acao-form">
                                <input type="hidden" name="pedido_comprovativo_id" value="<?php echo $pc['id']; ?>">
                                <button type="submit" name="aprovar_comprovativo" class="btn btn-sm">Aprovar</button>
                                <button type="submit" name="recusar_comprovativo" class="btn btn-danger btn-sm">Recusar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="notificacoes-vazio">
                        Não existem pedidos de comprovativos pendentes.
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($onboardingsPendentes)): ?>
                <h2 class="notificacoes-section-title">Onboardings Pendentes</h2>
                <div class="onboarding-lista">
                    <?php foreach ($onboardingsPendentes as $ob): ?>
                        <div class="onboarding-card">
                            <span>
                                <b><?= htmlspecialchars($ob['nome']) ?></b>
                                <span class="onboarding-email">(<?= htmlspecialchars($ob['email_pessoal']) ?>)</span>
                            </span>
                            <button class="btn-ver-onboarding" data-token="<?= htmlspecialchars($ob['token']) ?>">Ver Dados Onboarding</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($mensagensRecebidas)): ?>
            <h2 class="notificacoes-section-title">Mensagens Recebidas</h2>
            <ul class="notificacoes-lista">
                <?php foreach ($mensagensRecebidas as $msg): ?>
                    <li class="notificacao<?php if (!$msg['lida']) echo ' unread'; ?>">
                        <span class="titulo"><?php echo htmlspecialchars($msg['assunto']); ?></span>
                        <span class="mensagem"><?php echo nl2br(htmlspecialchars($msg['mensagem'])); ?></span>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($msg['data_envio'])); ?></span>
                        <div class="detalhes">
                            <strong>Enviada por:</strong> <?php echo htmlspecialchars($msg['remetente_nome']); ?><br>
                            <strong>Mensagem:</strong> <?php echo nl2br(htmlspecialchars($msg['mensagem'])); ?><br>
                            <?php if ($msg['anexo']): ?>
                                <strong>Anexo:</strong> <a href="../Uploads/Mensagens/<?php echo htmlspecialchars($msg['anexo']); ?>" target="_blank">Ver ficheiro</a><br>
                            <?php endif; ?>
                            <strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($msg['data_envio'])); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        </div>
    </main>

    <!-- Modal de Notificações Não Lidas -->
    <div id="modalNaoLidas" class="modal-nao-lidas">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    🔔 Notificações por Ler
                </h3>
                <button class="modal-close" onclick="fecharModalNaoLidas()">&times;</button>
            </div>
            <div class="modal-body">
                <?php if (!empty($naoLidas)): ?>
                    <?php foreach ($naoLidas as $not): ?>
                        <div class="notificacao-modal" onclick="marcarComoLidaEFechar(<?php echo $not['id']; ?>)">
                            <div class="indicador-novo"></div>
                            <div class="titulo-modal">
                                <?php echo htmlspecialchars($not['mensagem']); ?>
                            </div>
                            <div class="data-modal">
                                <?php echo date('d/m/Y H:i', strtotime($not['data_envio'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notificacoes-vazio-modal">
                        ✅ Não tem notificações por ler!
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($naoLidas)): ?>
                <div class="modal-footer">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="marcar_todas_lidas" class="btn-marcar-todas" onclick="return confirm('Marcar todas as notificações como lidas?')">
                            Marcar Todas como Lidas
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Menu lateral RH - navegação
    document.addEventListener('DOMContentLoaded', function() {
        const menuLinks = document.querySelectorAll('.menu-notif-link');
        
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active de todos
                menuLinks.forEach(l => l.classList.remove('active'));
                // Adiciona active ao clicado
                this.classList.add('active');
                
                // Navega para a secção
                const targetId = this.getAttribute('data-scroll');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const headerHeight = 160; // Altura aproximada do header
                    const targetPosition = targetElement.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Auto-highlight baseado no scroll
        window.addEventListener('scroll', function() {
            const sections = ['#notificacoes-sistema', '#pedidos-alteracao', '#pedidos-ferias', '#pedidos-comprovativos'];
            const scrollPos = window.scrollY + 200; // Offset para melhor detecção
            
            let activeSection = 0;
            sections.forEach((sectionId, index) => {
                const section = document.querySelector(sectionId);
                if (section && section.offsetTop <= scrollPos) {
                    activeSection = index;
                }
            });
            
            menuLinks.forEach((link, index) => {
                link.classList.toggle('active', index === activeSection);
            });
        });
    });

    // Modal de notificações não lidas
    function abrirModalNaoLidas() {
        document.getElementById('modalNaoLidas').style.display = 'flex';
    }

    function fecharModalNaoLidas() {
        document.getElementById('modalNaoLidas').style.display = 'none';
    }

    function marcarComoLidaEFechar(notificacaoId) {
        // Criar form invisível para marcar como lida
        const form = document.createElement('form');
        form.method = 'get';
        form.style.display = 'none';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'marcar_lida';
        input.value = notificacaoId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        const modal = document.getElementById('modalNaoLidas');
        if (event.target === modal) {
            fecharModalNaoLidas();
        }
    }

    document.querySelectorAll('.notificacao').forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Evita expandir ao clicar no botão
            if(e.target.classList.contains('btn')) return;
            document.querySelectorAll('.notificacao.expandida').forEach(function(n) {
                if(n !== item) n.classList.remove('expandida');
            });
            item.classList.toggle('expandida');
            // Se estava por ler, remove a classe 'unread' ao expandir
            if(item.classList.contains('expandida') && item.classList.contains('unread')) {
                item.classList.remove('unread');
                // Aqui podes fazer um fetch/ajax para marcar como lida na base de dados se quiseres
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Abrir modal e buscar dados via AJAX
        document.querySelectorAll('.btn-ver-onboarding').forEach(btn => {
            btn.addEventListener('click', function() {
                const token = this.getAttribute('data-token');
                fetch('../../BLL/RH/ajax_onboarding_dados.php?token=' + encodeURIComponent(token))
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.dados) {
                            let html = '<ul class="onboarding-campos-lista">';
                            const ordem = [
                                'nome','apelido','data_nascimento','morada','localidade','codigo_postal','telemovel','sexo','estado_civil','habilitacoes','curso','nif','niss','iban','nome_contacto_emergencia','grau_relacionamento','contacto_emergencia'
                            ];
                            const labels = {
                                nome: "Primeiro Nome",
                                apelido: "Apelido",
                                data_nascimento: "Data de Nascimento",
                                morada: "Morada",
                                localidade: "Localidade",
                                codigo_postal: "Código Postal",
                                telemovel: "Telemóvel",
                                sexo: "Sexo",
                                estado_civil: "Estado Civil",
                                habilitacoes: "Habilitações Literárias",
                                curso: "Curso",
                                nif: "NIF",
                                niss: "NISS",
                                iban: "IBAN",
                                nome_contacto_emergencia: "Nome Contacto Emergência",
                                grau_relacionamento: "Grau de Parentesco",
                                contacto_emergencia: "Nº Contacto Emergência"
                            };
                            ordem.forEach(k => {
                                if (data.dados[k] !== undefined) {
                                    html += `<li>
                                        <span class="campo-label">${labels[k] ?? k}:</span>
                                        <span class="campo-valor">${data.dados[k]}</span>
                                    </li>`;
                                }
                            });
                            html += '</ul>';
                            document.getElementById('onboarding-dados').innerHTML = html;
                            document.getElementById('onboarding_token').value = token;
                            document.getElementById('modalOnboarding').style.display = 'flex';
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.getElementById('onboarding-dados').innerHTML = '<div style="color:red;">Erro ao carregar dados.</div>';
                        }
                    });
            });
        });
    });

    function fecharModalOnboarding() {
        document.getElementById('modalOnboarding').style.display = 'none';
        document.body.style.overflow = '';
    }
    window.addEventListener('keydown', function(e) {
        if (e.key === "Escape") fecharModalOnboarding();
    });
    </script>

    <?php if (isset($_GET['onboarding_popup'])): 
        // Preferir o id do GET, se existir, senão usar o da sessão
        $novoColabId = $_GET['colab_id'] ?? ($_SESSION['onboarding_novo_colab_id'] ?? null);
        unset($_SESSION['onboarding_novo_colab_id']);
    ?>
    <div id="popupSucessoOnboarding" class="popup-sucesso-bg">
        <div class="popup-sucesso-content">
            <button class="close" onclick="document.getElementById('popupSucessoOnboarding').style.display='none'">&times;</button>
            <span class="icon">🎉</span>
            <h3>Novo colaborador adicionado com sucesso!</h3>
            <p>
                O colaborador foi integrado.<br>
                <b>Aceda à ficha do colaborador para completar ou atualizar os dados finais.</b>
            </p>
        </div>
    </div>
    <script>
        // Fechar popup ao pressionar ESC ou clicar fora
        window.addEventListener('keydown', function(e) {
            if (e.key === "Escape") document.getElementById('popupSucessoOnboarding').style.display = 'none';
        });
        document.getElementById('popupSucessoOnboarding').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    </script>
    <?php endif; ?>
</body>
</html>