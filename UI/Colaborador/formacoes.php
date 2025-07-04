<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !in_array($perfil, ['colaborador', 'coordenador', 'rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Colaborador/BLL_formacoes.php';
require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';

$colabBLL = new ColaboradorFichaManager();
$colab = $colabBLL->getColaboradorByUserId($_SESSION['user_id']);
$colaborador_id = $colab['id'] ?? null;

if (!$colaborador_id) {
    die('Erro: Não foi possível identificar o colaborador.');
}

$formacoesBLL = new FormacoesManager();

// Buscar formações ANTES de processar o POST
$formacoes = $formacoesBLL->listarFormacoesFuturas();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formacao_id'])) {
    $resultado = $formacoesBLL->inscrever($colaborador_id, intval($_POST['formacao_id']));
    
    if ($resultado) {
        // Buscar dados da formação para a notificação
        $formacao = null;
        foreach ($formacoes as $f) {
            if ($f['id'] == $_POST['formacao_id']) {
                $formacao = $f;
                break;
            }
        }
        
        if ($formacao) {
            // Enviar notificação automática de confirmação
            require_once '../../BLL/Comuns/BLL_notificacoes.php';
            $notBLL = new NotificacoesManager();
            
            $dataInicio = date('d/m/Y', strtotime($formacao['data_inicio']));
            $dataFim = date('d/m/Y', strtotime($formacao['data_fim']));
            
            $mensagem = "Inscrição confirmada na formação '{$formacao['nome']}'. " .
                       "Período: {$dataInicio} a {$dataFim}. " .
                       "A sua participação foi registada no sistema. " .
                       "Receberá informações adicionais sobre localização e materiais necessários via email corporativo.";
            
            $notBLL->enviarNotificacao(null, $_SESSION['user_id'], $mensagem);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Formações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/formacoes.css">
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
        <h1>Formações Disponíveis</h1>
        
        <div class="formacoes-container">
            <div class="formacoes-cards">
                <?php if (!empty($formacoes)): ?>
                    <?php foreach ($formacoes as $f): ?>
                    <div class="formacao-card">
                        <div class="formacao-info">
                            <div class="formacao-nome"><?= htmlspecialchars($f['nome']) ?></div>
                            <div class="formacao-descricao"><?= htmlspecialchars($f['descricao']) ?></div>
                        </div>
                        <div class="formacao-acoes">
                            <button class="btn-data" onclick="abrirModalData('<?= $f['id'] ?>', '<?= htmlspecialchars($f['nome']) ?>', '<?= htmlspecialchars($f['data_inicio']) ?>', '<?= htmlspecialchars($f['data_fim']) ?>', '<?= htmlspecialchars($f['horario_semanal'] ?? '') ?>')">
                                Ver Datas
                            </button>
                            <?php if ($formacoesBLL->jaInscrito($colaborador_id, $f['id'])): ?>
                                <span class="inscrito-label">Inscrito</span>
                            <?php else: ?>
                                <button class="btn-inscrever" onclick="abrirModalInscricao('<?= $f['id'] ?>', '<?= htmlspecialchars($f['nome']) ?>')">
                                    Inscrever-me
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; color: #888; margin: 40px 0;">
                        Não existem formações disponíveis no momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal de Datas -->
        <div id="modalDatas" class="formacao-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModalDatas()">&times;</span>
                <h2 id="modalDatasTitle">Datas da Formação</h2>
                <div class="datas-info">
                    <div class="data-item">
                        <strong>Data de Início:</strong>
                        <span id="modalDataInicio"></span>
                    </div>
                    <div class="data-item">
                        <strong>Data de Fim:</strong>
                        <span id="modalDataFim"></span>
                    </div>
                    <div class="data-item horario-semanal">
                        <strong>Horário Semanal:</strong>
                        <div id="modalHorarioSemanal"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Inscrição -->
        <div id="modalInscricao" class="formacao-modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModalInscricao()">&times;</span>
                <h2>Confirmar Inscrição</h2>
                <p>Deseja inscrever-se na formação <strong id="modalInscricaoNome"></strong>?</p>
                <div class="modal-actions">
                    <form method="post" id="formInscricao">
                        <input type="hidden" name="formacao_id" id="modalFormacaoId">
                        <button type="submit" class="btn-confirmar">Confirmar Inscrição</button>
                        <button type="button" class="btn-cancelar" onclick="fecharModalInscricao()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

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
    
<script>
// Modal de Datas
function abrirModalData(id, nome, dataInicio, dataFim, horarioSemanal) {
    document.getElementById('modalDatasTitle').textContent = nome;
    document.getElementById('modalDataInicio').textContent = new Date(dataInicio).toLocaleDateString('pt-PT');
    document.getElementById('modalDataFim').textContent = new Date(dataFim).toLocaleDateString('pt-PT');
    
    // Processar horário semanal
    const horarioContainer = document.getElementById('modalHorarioSemanal');
    if (horarioSemanal && horarioSemanal.trim() !== '') {
        try {
            // Assumindo que o horário vem em formato JSON ou texto estruturado
            const horarios = JSON.parse(horarioSemanal);
            let horarioHTML = '<div class="horario-grid">';
            
            // Corrigir: usar as chaves sem acentos que estão na base de dados
            const diasSemana = [
                { display: 'Segunda', key: 'segunda' },
                { display: 'Terça', key: 'terca' },
                { display: 'Quarta', key: 'quarta' },
                { display: 'Quinta', key: 'quinta' },
                { display: 'Sexta', key: 'sexta' },
                { display: 'Sábado', key: 'sabado' },
                { display: 'Domingo', key: 'domingo' }
            ];
            
            diasSemana.forEach(dia => {
                const horarioDia = horarios[dia.key] || 'Não definido';
                horarioHTML += `
                    <div class="horario-dia">
                        <span class="dia-nome">${dia.display}:</span>
                        <span class="dia-horario">${horarioDia}</span>
                    </div>
                `;
            });
            
            horarioHTML += '</div>';
            horarioContainer.innerHTML = horarioHTML;
        } catch (e) {
            // Se não for JSON, mostrar como texto simples
            horarioContainer.innerHTML = `<div class="horario-texto">${horarioSemanal}</div>`;
        }
    } else {
        horarioContainer.innerHTML = '<div class="horario-texto">Horário não definido</div>';
    }
    
    document.getElementById('modalDatas').style.display = 'flex';
}

function fecharModalDatas() {
    document.getElementById('modalDatas').style.display = 'none';
}

// Modal de Inscrição
function abrirModalInscricao(id, nome) {
    document.getElementById('modalInscricaoNome').textContent = nome;
    document.getElementById('modalFormacaoId').value = id;
    document.getElementById('modalInscricao').style.display = 'flex';
}

function fecharModalInscricao() {
    document.getElementById('modalInscricao').style.display = 'none';
}

// Fechar modais ao clicar fora
window.onclick = function(event) {
    const modalDatas = document.getElementById('modalDatas');
    const modalInscricao = document.getElementById('modalInscricao');
    
    if (event.target === modalDatas) {
        fecharModalDatas();
    }
    if (event.target === modalInscricao) {
        fecharModalInscricao();
    }
}
</script>
</body>
</html>