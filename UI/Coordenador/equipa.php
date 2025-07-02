<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();

$equipaId = $_GET['id'] ?? null;
if (!$equipaId) {
    header('Location: dashboard_coordenador.php');
    exit();
}
$colaboradores = $coordBLL->getColaboradoresByEquipa($equipaId);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Coordenador/equipa.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_coordenador.php';">
        <nav>
            <?php
                // Corrigir link da equipa para incluir o id da equipa do coordenador
                $equipaLink = "equipa.php";
                if (!empty($equipas) && isset($equipas[0]['id'])) {
                    $equipaLink = "equipa.php?id=" . urlencode($equipas[0]['id']);
                }
            ?>
            <div class="dropdown-equipa">
                <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                    Equipa
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="dashboard_coordenador.php">Dashboard</a>
                    <a href="relatorios_equipa.php">Relatórios Equipa</a>
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
                    <a href="beneficios.php">Benefícios</a>
                    <a href="ferias.php">Férias</a>
                    <a href="formacoes.php">Formações</a>
                    <a href="recibos.php">Recibos</a>
                    <!-- Adiciona mais opções se quiseres -->
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Membros da Equipa</h1>
        <?php if (!empty($colaboradores)): ?>
        <div class="tabela-colaboradores-wrapper">
            <table class="tabela-colaboradores">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Última Atividade</th>
                        <th>Ficha</th>
                        <th>Contactar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colaboradores as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['nome']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['cargo']); ?></td>
                        <td><!-- Última Atividade --></td>
                        <td>
                            <a class="btn-azul-ficha" href="../Colaborador/ficha_colaborador.php?id=<?php echo urlencode($c['id']); ?>" title="Ver Ficha">
                                Ver Ficha
                            </a>
                        </td>
                        <td>
                            <button class="btn-contactar" data-nome="<?php echo htmlspecialchars($c['nome']); ?>" data-id="<?php echo $c['id']; ?>" title="Enviar mensagem">
                                <img src="https://cdn-icons-png.flaticon.com/512/3682/3682321.png" alt="Mensagem" style="width:22px;height:22px;display:block;margin:0 auto;">
                            </button>              
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div style="text-align:center; margin: 40px 0; color: #23408e; font-size:1.1rem;">
                Não existem colaboradores nesta equipa.
            </div>
        <?php endif; ?>

        <!-- Modal de Mensagem Moderno -->
        <div id="modalMensagem" class="modal-mensagem" style="display:none;">
            <div class="modal-content">
                <span class="close-modal" onclick="fecharModalMensagem()">&times;</span>
                <h2>
                    <svg style="vertical-align:middle;margin-right:8px;" width="28" height="28" fill="#299cf3" viewBox="0 0 24 24">
                        <path d="M2 21l21-9-21-9v7l15 2-15 2z"/>
                    </svg>
                    Nova Mensagem
                </h2>
                <form id="formMensagem" method="post" action="../Comuns/enviar_mensagem.php" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-row">
                        <label for="modalNome" class="modal-label">Destinatário:</label>
                        <input type="text" id="modalNome" class="modal-input" readonly style="background:#f5f7fa;">
                        <input type="hidden" name="destinatario_id" id="modalDestinatarioId">
                    </div>
                    <div class="modal-row">
                        <label for="modalAssunto" class="modal-label">Assunto:</label>
                        <input type="text" name="assunto" id="modalAssunto" class="modal-input" maxlength="100" required placeholder="Assunto da mensagem">
                    </div>
                    <div class="modal-row">
                        <label for="modalMensagemTexto" class="modal-label">Mensagem:</label>
                        <textarea name="mensagem" id="modalMensagemTexto" class="modal-textarea" required placeholder="Escreva a sua mensagem..." rows="5"></textarea>
                    </div>
                    <div class="modal-row">
                        <label for="modalAnexo" class="modal-label">Anexar ficheiro:</label>
                        <input type="file" name="anexo" id="modalAnexo" class="modal-input-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt">
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn-azul-ficha">
                            <svg width="18" height="18" fill="#fff" style="vertical-align:middle;margin-right:6px;" viewBox="0 0 24 24">
                                <path d="M2 21l21-9-21-9v7l15 2-15 2z"/>
                            </svg>
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

<script>
document.querySelectorAll('.btn-contactar').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalNome').value = this.dataset.nome;
        document.getElementById('modalDestinatarioId').value = this.dataset.id;
        document.getElementById('modalMensagem').style.display = 'flex';
        document.getElementById('modalAssunto').focus();
    });
});
function fecharModalMensagem() {
    document.getElementById('modalMensagem').style.display = 'none';
    document.getElementById('formMensagem').reset();
}
window.onclick = function(event) {
    const modal = document.getElementById('modalMensagem');
    if (event.target === modal) fecharModalMensagem();
};
</script>

</html>