<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_relatorios.php';
$relatoriosBLL = new RHRelatoriosManager();
$indicadores = $relatoriosBLL->getIndicadoresGlobais();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/relatorios.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="equipas.php">Equipas</a>
                <a href="relatorios.php">Relatórios</a>
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
    <main>
        <h1 class="relatorios-titulo">Relatórios</h1>
        <!-- Mover os cards para cima da tabela -->
        <section class="dashboard-cards">
            <div class="card">
                <h2>Aniversários por Equipa</h2>
                <button class="btn" onclick="openModal('modalAniversarios')">Ver Relatório</button>
            </div>
            <div class="card">
                <h2>Alterações Contratuais</h2>
                <button class="btn" onclick="openModal('modalAlteracoes')">Ver Relatório</button>
            </div>
            <div class="card">
                <h2>Vouchers Atribuídos</h2>
                <button class="btn" onclick="openModal('modalVouchers')">Ver Relatório</button>
            </div>
        </section>
        <section>
            <div class="indicadores-titulo">Indicadores Gerais</div>
            <table class="indicadores-table">                   
                <thead>
                    <tr>
                        <th>Indicador</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total de Utilizadores</td>
                        <td><?php echo htmlspecialchars($indicadores['total_colaboradores']); ?></td>
                    </tr>
                    <tr>
                        <td>Utilizadores ativos</td>
                        <td><?php echo htmlspecialchars($indicadores['ativos']); ?></td>
                    </tr>
                    <tr>
                        <td>Utilizadores inativos</td>
                        <td><?php echo htmlspecialchars($indicadores['inativos']); ?></td>
                    </tr>
                    <tr>
                        <td>Total de equipas</td>
                        <td><?php echo htmlspecialchars($indicadores['total_equipas']); ?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modais dos Relatórios -->
    <div id="modalAniversarios" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalAniversarios')">&times;</span>
            <h2>Relatório de Aniversários por Equipa</h2>
            <label for="equipa_id_modal">Escolha a equipa:</label>
            <select id="equipa_id_modal"></select>
            <div id="aniversarios_result" style="margin-top:16px;"></div>
            <button class="btn" style="margin-top:18px;" onclick="exportPDF('aniversarios')">Exportar PDF</button>
        </div>
    </div>
    <div id="modalAlteracoes" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalAlteracoes')">&times;</span>
            <h2>Relatório de Alterações Contratuais</h2>
            <div id="alteracoes_result" style="margin-top:16px;"></div>
            <button class="btn" style="margin-top:18px;" onclick="exportPDF('alteracoes')">Exportar PDF</button>
        </div>
    </div>
    <div id="modalVouchers" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalVouchers')">&times;</span>
            <h2>Relatório de Vouchers Atribuídos</h2>
            <div id="vouchers_result" style="margin-top:16px;"></div>
            <button class="btn" style="margin-top:18px;" onclick="exportPDF('vouchers')">Exportar PDF</button>
        </div>
    </div>

    <script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
        if(id === 'modalAniversarios') {
            loadEquipas();
        }
        if(id === 'modalAlteracoes') {
            loadAlteracoes();
        }
        if(id === 'modalVouchers') {
            loadVouchers();
        }
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    window.onclick = function(event) {
        ['modalAniversarios','modalAlteracoes','modalVouchers'].forEach(function(id) {
            var modal = document.getElementById(id);
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }

    // AJAX para carregar equipas e aniversários
    function loadEquipas() {
        fetch('relatorios_ajax.php?action=equipas')
            .then(response => response.json())
            .then(data => {
                let select = document.getElementById('equipa_id_modal');
                select.innerHTML = '';
                data.forEach(function(equipa, idx) {
                    let opt = document.createElement('option');
                    opt.value = equipa.id;
                    opt.textContent = equipa.nome;
                    select.appendChild(opt);
                });
                if(data.length > 0) {
                    loadAniversarios(data[0].id);
                } else {
                    document.getElementById('aniversarios_result').innerHTML = 'Nenhuma equipa encontrada.';
                }
            });
        document.getElementById('equipa_id_modal').onchange = function() {
            loadAniversarios(this.value);
        };
    }
    function loadAniversarios(equipaId) {
        fetch('relatorios_ajax.php?action=aniversarios&eid=' + equipaId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('aniversarios_result').innerHTML = html;
            });
    }
    function loadAlteracoes() {
        fetch('relatorios_ajax.php?action=alteracoes')
            .then(response => response.text())
            .then(html => {
                document.getElementById('alteracoes_result').innerHTML = html;
            });
    }
    function loadVouchers() {
        fetch('relatorios_ajax.php?action=vouchers')
            .then(response => response.text())
            .then(html => {
                document.getElementById('vouchers_result').innerHTML = html;
            });
    }
    function exportPDF(tipo) {
        let param = '';
        if (tipo === 'aniversarios') {
            const equipaId = document.getElementById('equipa_id_modal').value;
            param = '?tipo=aniversarios&eid=' + encodeURIComponent(equipaId);
        } else {
            param = '?tipo=' + tipo;
        }
        window.open('relatorios_pdf.php' + param, '_blank');
    }
    </script>
    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
        position: relative;
    }
    .close {
        color: #aaa;
        position: absolute;
        top: 10px; right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    </style>
    <!-- Removido o chatbot -->
    <script src="../../assets/chatbot.js"></script>
</body>
</html>