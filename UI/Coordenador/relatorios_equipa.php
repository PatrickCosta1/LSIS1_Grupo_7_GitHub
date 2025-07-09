<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
$coordBLL = new CoordenadorDashboardManager();
$equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
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
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_coordenador.php';">
        <nav>
            <div class="dropdown-equipa">
                <a href="equipa.php<?php echo (!empty($equipas) && isset($equipas[0]['id'])) ? '?id=' . urlencode($equipas[0]['id']) : ''; ?>" class="equipa-link">
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
                    <a href="../Colaborador/beneficios.php">Benefícios</a>
                    <a href="../Colaborador/ferias.php">Férias</a>
                    <a href="../Colaborador/formacoes.php">Formações</a>
                    <a href="../Colaborador/recibos.php">Recibos</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
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
    function loadEquipas() {
        fetch('relatorios_equipa_ajax.php?action=equipas')
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
        fetch('relatorios_equipa_ajax.php?action=aniversarios&eid=' + equipaId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('aniversarios_result').innerHTML = html;
            });
    }
    function loadAlteracoes() {
        fetch('relatorios_equipa_ajax.php?action=alteracoes')
            .then(response => response.text())
            .then(html => {
                document.getElementById('alteracoes_result').innerHTML = html;
            });
    }
    function loadVouchers() {
        fetch('relatorios_equipa_ajax.php?action=vouchers')
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
        window.open('relatorios_equipa_pdf.php' + param, '_blank');
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
    <script src="../../assets/chatbot.js"></script>
</body>
</html>