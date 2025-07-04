<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/RH/BLL_gerir_beneficios.php';
$beneficiosBLL = new RHBeneficiosGerirManager();

$success = '';
$error = '';

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar'])) {
        $dados = [
            'titulo' => $_POST['titulo'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'ordem' => intval($_POST['ordem'] ?? 0)
        ];
        if ($beneficiosBLL->adicionarBeneficio($dados)) {
            $success = "Benefício adicionado!";
        } else {
            $error = "Erro ao adicionar benefício.";
        }
    } elseif (isset($_POST['editar']) && isset($_POST['beneficio_id'])) {
        $id = intval($_POST['beneficio_id']);
        $dados = [
            'titulo' => $_POST['titulo'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'ordem' => intval($_POST['ordem'] ?? 0)
        ];
        if ($beneficiosBLL->editarBeneficio($id, $dados)) {
            $success = "Benefício atualizado!";
        } else {
            $error = "Erro ao atualizar benefício.";
        }
    } elseif (isset($_POST['remover']) && isset($_POST['beneficio_id'])) {
        $id = intval($_POST['beneficio_id']);
        if ($beneficiosBLL->removerBeneficio($id)) {
            $success = "Benefício removido.";
        } else {
            $error = "Erro ao remover benefício.";
        }
    } elseif (isset($_POST['ordenar']) && isset($_POST['ordens'])) {
        // Recebe array de id => ordem
        $ordens = $_POST['ordens'];
        if ($beneficiosBLL->atualizarOrdem($ordens)) {
            $success = "Ordem dos benefícios atualizada!";
        } else {
            $error = "Erro ao atualizar ordem.";
        }
    }
}

$beneficios = $beneficiosBLL->listarBeneficios();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Benefícios - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/gerir_beneficios.css">
    <style>
        /* Menu de navegação interno */
        .menu-beneficios {
            display: flex;
            justify-content: center;
            gap: 28px;
            margin: 36px auto 32px auto;
            padding: 0;
            list-style: none;
        }
        .menu-beneficios li {
            display: inline-block;
        }
        .menu-beneficios a {
            display: inline-block;
            padding: 10px 28px;
            border-radius: 18px;
            background: linear-gradient(90deg, #299cf3 0%, #0360e9 100%);
            color: #fff;
            font-weight: 600;
            font-size: 1.05rem;
            text-decoration: none;
            transition: background 0.18s, color 0.18s, transform 0.13s;
            box-shadow: 0 2px 8px rgba(41,156,243,0.13);
            margin: 0 2px;
        }
        .menu-beneficios a.active,
        .menu-beneficios a:hover {
            background: linear-gradient(90deg, #0360e9 0%, #299cf3 100%);
            color: #e0eaff;
            transform: translateY(-2px) scale(1.04);
        }
        .beneficio-section {
            display: block !important;
            animation: fadeInSec 0.3s;
        }
        @keyframes fadeInSec {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        /* Drag handle visual */
        .beneficio-card.dragging {
            opacity: 0.5;
            border: 2px dashed #299cf3;
            background: #eaf6ff;
        }
        .beneficio-card.drag-over {
            border: 2px solid #ff9b37;
            background: #fffbe7;
        }
        .beneficio-card .drag-handle {
            cursor: grab;
            margin-right: 8px;
            color: #299cf3;
            font-size: 1.1em;
            user-select: none;
        }
        .beneficios-ordenar-preview {
            margin-top: 48px;
            background: transparent;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        .beneficios-ordenar-preview h2 {
            color: #19365f;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
        }
        .beneficios-ordenar-preview .preview-monitor {
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
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
        <h1>Gestão de Benefícios</h1>
        <?php if ($success): ?><div class="success-msg"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-msg"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <section class="beneficio-section">
            <section class="beneficios-ordenar-preview">
                <form method="post" id="formOrdenar" autocomplete="off">
                    <div class="beneficios-cards" id="sortable-cards">
                        <?php foreach ($beneficios as $b): ?>
                            <div class="beneficio-card-wrapper" data-id="<?= $b['id'] ?>">
                                <div class="ordem-label-card">
                                    Ordem: <span class="ordem-num"><?= (int)$b['ordem'] ?></span>
                                </div>
                                <div class="beneficio-card" draggable="true" data-id="<?= $b['id'] ?>">
                                    <span class="edit-icon" title="Editar" onclick="abrirModalEditar(<?= $b['id'] ?>, '<?= htmlspecialchars(addslashes($b['titulo'])) ?>', `<?= htmlspecialchars(addslashes($b['descricao'])) ?>`, event)">
                                        &#9998;
                                    </span>
                                    <span class="drag-handle" title="Arraste para ordenar">&#9776;</span>
                                    <span onclick="abrirModalPreview('modalPreview<?= $b['id'] ?>')"><?= htmlspecialchars($b['titulo']) ?></span>
                                    <span class="delete-icon" title="Eliminar" onclick="eliminarCardOrdenar(<?= $b['id'] ?>, event)">&times;</span>
                                    <input type="hidden" name="ordens[<?= $b['id'] ?>]" value="<?= $b['ordem'] ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Botão de adicionar card -->
                        <div class="beneficio-card-wrapper add-card-wrapper">
                            <div class="ordem-label-card" style="opacity:0;">Ordem</div>
                            <div class="beneficio-card add-card-btn" onclick="abrirModalNovoBeneficio()" tabindex="0" title="Adicionar novo benefício">
                                <span class="plus-icon">+</span>
                                <span class="add-label">Adicionar</span>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($beneficios as $b): ?>
                        <div id="modalPreview<?= $b['id'] ?>" class="beneficio-modal">
                            <div class="modal-content">
                                <span class="close" onclick="fecharModalPreview('modalPreview<?= $b['id'] ?>')">&times;</span>
                                <h2><?= htmlspecialchars($b['titulo']) ?></h2>
                                <p><?= nl2br(htmlspecialchars($b['descricao'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Modal de edição -->
                    <div id="modalEditarBeneficio" class="beneficio-modal">
                        <div class="modal-content" style="max-width:420px;">
                            <span class="close" onclick="fecharModalEditar()">&times;</span>
                            <h2>Editar Benefício</h2>
                            <form method="post" id="formEditarBeneficio">
                                <input type="hidden" name="beneficio_id" id="editBeneficioId">
                                <div style="margin-bottom:12px;">
                                    <label for="editTitulo" style="font-size:0.95em;color:#23408e;font-weight:600;">Título:</label>
                                    <input type="text" name="titulo" id="editTitulo" required style="width:100%;padding:7px 10px;margin-top:3px;border-radius:6px;border:1px solid #ccd;">
                                </div>
                                <div style="margin-bottom:16px;">
                                    <label for="editDescricao" style="font-size:0.95em;color:#23408e;font-weight:600;">Descrição:</label>
                                    <textarea name="descricao" id="editDescricao" required style="width:100%;padding:7px 10px;margin-top:3px;border-radius:6px;border:1px solid #ccd;min-height:60px;max-height:180px;"></textarea>
                                </div>
                                <button type="submit" name="editar" class="btn btn-editar" style="width:100%;">Guardar Alterações</button>
                            </form>
                        </div>
                    </div>
                    <!-- Modal de novo benefício -->
                    <div id="modalNovoBeneficio" class="beneficio-modal">
                        <div class="modal-content add-card-modal">
                            <span class="close" onclick="fecharModalNovoBeneficio()">&times;</span>
                            <h2>Novo Benefício</h2>
                            <form method="post" class="beneficio-form" style="margin-top:18px;">
                                <input type="text" name="titulo" placeholder="Título do benefício" required style="margin-bottom:10px;">
                                <textarea name="descricao" placeholder="Descrição" required style="margin-bottom:10px;min-height:60px;max-height:180px;"></textarea>
                                <input type="number" name="ordem" placeholder="Ordem (ex: 1, 2, 3...)" min="1" style="width:120px;margin-bottom:18px;">
                                <button type="submit" name="adicionar" class="btn btn-editar" style="width:100%;">Criar Benefício</button>
                            </form>
                        </div>
                    </div>
                    <button type="submit" name="ordenar" class="btn btn-ordenar" style="margin:32px auto 0 auto;display:block;">Guardar Alterações</button>
                    <?php if ($success && isset($_POST['ordenar'])): ?>
                        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error && isset($_POST['ordenar'])): ?>
                        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                </form>
            </section>
        </section>
    </main>
    <script>
    function mostrarSecao(sec) {
        document.querySelectorAll('.menu-beneficios a').forEach(a => a.classList.remove('active'));
        document.querySelector('.menu-beneficios a[href="#'+sec+'"]').classList.add('active');
        document.querySelectorAll('.beneficio-section').forEach(s => s.classList.remove('active'));
        document.getElementById('secao-' + sec).classList.add('active');
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    function abrirModalPreview(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }
    function fecharModalPreview(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    // Modal editar benefício
    function abrirModalEditar(id, titulo, descricao, event) {
        event.stopPropagation();
        document.getElementById('editBeneficioId').value = id;
        document.getElementById('editTitulo').value = titulo.replace(/\\'/g, "'");
        document.getElementById('editDescricao').value = descricao.replace(/\\'/g, "'").replace(/\\n/g, "\n");
        document.getElementById('modalEditarBeneficio').style.display = 'flex';
    }
    function fecharModalEditar() {
        document.getElementById('modalEditarBeneficio').style.display = 'none';
    }
    // Modal novo benefício
    function abrirModalNovoBeneficio() {
        document.getElementById('modalNovoBeneficio').style.display = 'flex';
    }
    function fecharModalNovoBeneficio() {
        document.getElementById('modalNovoBeneficio').style.display = 'none';
    }
    // Eliminar card (envia submit para remover)
    function eliminarCardOrdenar(id, event) {
        event.stopPropagation();
        if (confirm('Remover este benefício?')) {
            // Cria e submete um form temporário
            var form = document.createElement('form');
            form.method = 'post';
            form.style.display = 'none';
            var inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'beneficio_id';
            inputId.value = id;
            var inputRemover = document.createElement('input');
            inputRemover.type = 'hidden';
            inputRemover.name = 'remover';
            inputRemover.value = '1';
            form.appendChild(inputId);
            form.appendChild(inputRemover);
            document.body.appendChild(form);
            form.submit();
        }
    }
    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        <?php foreach ($beneficios as $b): ?>
        if (event.target === document.getElementById('modalPreview<?= $b['id'] ?>')) {
            fecharModalPreview('modalPreview<?= $b['id'] ?>');
        }
        <?php endforeach; ?>
        if (event.target === document.getElementById('modalEditarBeneficio')) {
            fecharModalEditar();
        }
        if (event.target === document.getElementById('modalNovoBeneficio')) {
            fecharModalNovoBeneficio();
        }
    }

    // Drag & Drop para ordenar cards (ordem em grid: esquerda para direita, topo para baixo)
    let dragSrcEl = null;
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('sortable-cards');
        function atualizarOrdemInputs() {
            // Só considera os wrappers que têm input hidden (ignora o botão de adicionar)
            let wrappers = Array.from(container.children).filter(c => c.querySelector('input[type="hidden"]'));
            wrappers.forEach((wrapper, idx) => {
                const input = wrapper.querySelector('input[type="hidden"]');
                const ordemLabel = wrapper.querySelector('.ordem-num');
                if (input) input.value = idx + 1;
                if (ordemLabel) ordemLabel.textContent = idx + 1;
            });
        }
        function enableDragAndDrop() {
            container.querySelectorAll('.beneficio-card').forEach(card => {
                // Só ativa drag para cards normais (não o botão de adicionar)
                if (!card.querySelector('input[type="hidden"]')) return;
                card.setAttribute('draggable', 'true');
                card.addEventListener('dragstart', function(e) {
                    dragSrcEl = this.parentNode;
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                });
                card.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                    container.querySelectorAll('.beneficio-card').forEach(c => c.classList.remove('drag-over'));
                });
            });
            container.querySelectorAll('.beneficio-card-wrapper').forEach(wrapper => {
                // Não permite drop no botão de adicionar
                if (wrapper.classList.contains('add-card-wrapper')) return;
                wrapper.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                });
                wrapper.addEventListener('dragenter', function(e) {
                    if (dragSrcEl && this !== dragSrcEl) this.classList.add('drag-over');
                });
                wrapper.addEventListener('dragleave', function(e) {
                    this.classList.remove('drag-over');
                });
                wrapper.addEventListener('drop', function(e) {
                    e.stopPropagation();
                    if (dragSrcEl && dragSrcEl !== this) {
                        const parent = container;
                        const wrappersArr = Array.from(parent.children).filter(w => !w.classList.contains('add-card-wrapper'));
                        const srcIdx = wrappersArr.indexOf(dragSrcEl);
                        const tgtIdx = wrappersArr.indexOf(this);
                        if (srcIdx < tgtIdx) {
                            parent.insertBefore(dragSrcEl, this.nextSibling);
                        } else {
                            parent.insertBefore(dragSrcEl, this);
                        }
                        atualizarOrdemInputs();
                    }
                    this.classList.remove('drag-over');
                    return false;
                });
            });
        }
        enableDragAndDrop();
        atualizarOrdemInputs();

        // Corrige: garantir que todos os inputs estão habilitados antes do submit
        document.getElementById('formOrdenar').addEventListener('submit', function() {
            // Atualiza ordem antes de enviar (caso o user não tenha largado o drag)
            atualizarOrdemInputs();
            // Garante que todos os inputs estão habilitados
            let inputs = this.querySelectorAll('input[type="hidden"][name^="ordens"]');
            inputs.forEach(input => input.disabled = false);
        });
    });
    </script>
</body>
</html>
