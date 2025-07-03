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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Comuns/notificacoes.css">
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
                <a href="../RH/dashboard_rh.php">Dashboard</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../RH/exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                    </a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
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
        <div class="notificacoes-container">
        
        <!-- Adicionar contador de não lidas no topo -->
        <?php 
        $totalNaoLidas = count($naoLidas);
        ?>
        
        <?php if ($totalNaoLidas > 0): ?>
            <div id="banner-nao-lidas" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s;" onclick="abrirModalNaoLidas()">
                📢 Tem <?php echo $totalNaoLidas; ?> notificação<?php echo $totalNaoLidas > 1 ? 'ões' : ''; ?> por ler - Clique para ver
            </div>
        <?php endif; ?>

        <?php if (!empty($notificacoes)): ?>
            <h2 style="margin-top:16px;">Notificações do Sistema</h2>
            <ul class="notificacoes-lista">
                <?php foreach ($notificacoes as $not): ?>
                    <li class="notificacao<?php if (!$not['lida']) echo ' unread'; ?>">
                        <span class="titulo">
                            <?php echo htmlspecialchars($not['mensagem']); ?>
                        </span>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($not['data_envio'])); ?></span>
                        <div class="acao">
                            <?php if (!$not['lida']): ?>
                                <form method="get" style="display:inline;">
                                    <input type="hidden" name="marcar_lida" value="<?php echo $not['id']; ?>">
                                    <button type="submit" class="btn">Marcar como lida</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($not['lida']): ?>
                                <form method="get" style="display:inline;">
                                    <input type="hidden" name="eliminar" value="<?php echo $not['id']; ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Eliminar esta notificação?')">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div style="color:#888; text-align:center; margin:32px 0;">
                Não existem notificações.
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['profile'] === 'rh'): ?>
            <h2 style="margin-top:32px;">Pedidos de Alteração de Ficha</h2>
            <?php if ($aprovacao_msg): ?>
                <div class="success-message" style="margin-bottom:16px;"><?php echo htmlspecialchars($aprovacao_msg); ?></div>
            <?php endif; ?>
            <?php if (!empty($pedidosPendentes)): ?>
                <ul class="notificacoes-lista">
                <?php foreach ($pedidosPendentes as $p): ?>
                    <li class="notificacao unread">
                        <span class="titulo">
                            <strong><?php echo htmlspecialchars($p['colaborador_nome']); ?></strong> pediu alteração:
                            <strong><?php echo htmlspecialchars($p['campo']); ?></strong>
                            <br>
                            <span style="color:#888;">De:</span> <?php echo htmlspecialchars($p['valor_antigo']); ?>
                            <span style="color:#888;">Para:</span> <strong><?php echo htmlspecialchars($p['valor_novo']); ?></strong>
                        </span>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($p['data_pedido'])); ?></span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="aprovar_pedido" class="btn btn-sm">Aprovar</button>
                            <button type="submit" name="recusar_pedido" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div style="color:#888; text-align:center; margin:16px 0;">
                    Não existem pedidos de alteração pendentes.
                </div>
            <?php endif; ?>

            <h2 style="margin-top:32px;">Pedidos de Férias Pendentes</h2>
            <?php if (!empty($pedidosFeriasPendentes)): ?>
                <ul class="notificacoes-lista">
                <?php foreach ($pedidosFeriasPendentes as $pf): ?>
                    <li class="notificacao unread">
                        <span class="titulo">
                            <strong><?php echo htmlspecialchars($pf['colaborador_nome']); ?></strong> pediu férias:
                            <br>
                            <span style="color:#888;">De:</span> <?php echo htmlspecialchars($pf['data_inicio']); ?>
                            <span style="color:#888;">Até:</span> <strong><?php echo htmlspecialchars($pf['data_fim']); ?></strong>
                        </span>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($pf['data_pedido'])); ?></span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="pedido_ferias_id" value="<?php echo $pf['id']; ?>">
                            <button type="submit" name="aprovar_pedido_ferias" class="btn btn-sm">Aprovar</button>
                            <button type="submit" name="recusar_pedido_ferias" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div style="color:#888; text-align:center; margin:16px 0;">
                    Não existem pedidos de férias pendentes.
                </div>
            <?php endif; ?>

            <h2 style="margin-top:32px;">Pedidos de Comprovativos Pendentes</h2>
            <?php if (!empty($pedidosComprovantivosPendentes)): ?>
                <ul class="notificacoes-lista">
                <?php foreach ($pedidosComprovantivosPendentes as $pc): ?>
                    <li class="notificacao unread">
                        <span class="titulo">
                            <strong><?php echo htmlspecialchars($pc['colaborador_nome']); ?></strong> enviou novo comprovativo:
                            <br>
                            <span style="color:#888;">Tipo:</span> <strong><?php echo ucfirst(str_replace('_', ' ', $pc['tipo_comprovativo'])); ?></strong>
                        </span>
                        <div class="comprovativo-comparacao" style="margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <strong style="color: #dc3545;">Anterior:</strong>
                                    <?php if ($pc['comprovativo_antigo']): ?>
                                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($pc['comprovativo_antigo']); ?>" 
                                           target="_blank" 
                                           style="display: block; color: #dc3545; text-decoration: none; font-size: 0.9rem;">
                                            📄 Ver arquivo anterior
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #888; font-style: italic;">Sem comprovativo anterior</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong style="color: #28a745;">Novo:</strong>
                                    <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($pc['comprovativo_novo']); ?>" 
                                       target="_blank" 
                                       style="display: block; color: #28a745; text-decoration: none; font-size: 0.9rem;">
                                        📄 Ver novo arquivo
                                    </a>
                                </div>
                            </div>
                        </div>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($pc['data_pedido'])); ?></span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="pedido_comprovativo_id" value="<?php echo $pc['id']; ?>">
                            <button type="submit" name="aprovar_comprovativo" class="btn btn-sm">Aprovar</button>
                            <button type="submit" name="recusar_comprovativo" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div style="color:#888; text-align:center; margin:16px 0;">
                    Não existem pedidos de comprovativos pendentes.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($mensagensRecebidas)): ?>
            <h2 style="margin-top:32px;">Mensagens Recebidas</h2>
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
    </script>

</body>
</html>