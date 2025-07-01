<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

// Eliminar notificação
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    require_once '../../BLL/Comuns/BLL_notificacoes.php';
    $notBLL = new NotificacoesManager();
    $notBLL->eliminarNotificacao($_GET['eliminar']);
    header('Location: notificacoes.php');
    exit();
}

require_once '../../BLL/Comuns/BLL_notificacoes.php';
$notBLL = new NotificacoesManager();
$notificacoes = $notBLL->getNotificacoesByUserId($_SESSION['user_id']);

require_once '../../BLL/Comuns/BLL_mensagens.php';
$mensagemBLL = new MensagensManager();
$mensagensRecebidas = $mensagemBLL->getMensagensParaUtilizador($_SESSION['user_id']);

// Marcar notificação como lida
if (isset($_GET['marcar_lida'])) {
    $notificacaoId = $_GET['marcar_lida'];
    $notBLL->marcarComoLida($notificacaoId);
    header('Location: notificacoes.php');
    exit();
}

// RH: Aprovação de pedidos de alteração
$aprovacao_msg = '';
if ($_SESSION['profile'] === 'rh') {
    require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
    $colabBLL = new ColaboradorFichaManager();
    $notificacoesManager = new NotificacoesManager();

    // Aprovar pedido
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
    // Recusar pedido
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
    // Buscar pedidos pendentes
    $pedidosPendentes = $colabBLL->listarPedidosPendentes();
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
            <?php else: ?>
                <a href="../Convidado/onboarding_convidado.php">Preencher Dados</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Notificações</h1>
        <div class="notificacoes-container">

        <?php if (!empty($notificacoes)): ?>
            <h2 style="margin-top:32px;">Notificações do Sistema</h2>
            <ul class="notificacoes-lista">
                <?php foreach ($notificacoes as $not): ?>
                    <li class="notificacao<?php if (!$not['lida']) echo ' unread'; ?>">
                        <span class="titulo">
                            <?php echo htmlspecialchars($not['mensagem']); ?>
                        </span>
                        <span class="data"><?php echo date('d/m/Y H:i', strtotime($not['data_envio'])); ?></span>
                        <form method="get" style="display:inline;">
                            <input type="hidden" name="marcar_lida" value="<?php echo $not['id']; ?>">
                            <?php if (!$not['lida']): ?>
                                <button type="submit" class="btn btn-sm">Marcar como lida</button>
                            <?php endif; ?>
                        </form>
                        <?php if ($not['lida']): ?>
                            <form method="get" style="display:inline;">
                                <input type="hidden" name="eliminar" value="<?php echo $not['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminar esta notificação?')">Eliminar</button>
                            </form>
                        <?php endif; ?>
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

    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
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
    <script>
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