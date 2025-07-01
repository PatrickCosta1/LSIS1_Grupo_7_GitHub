<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Colaborador/BLL_recibos_vencimento.php';
$recibosManager = new RecibosVencimentoManager();
$recibos = $recibosManager->getRecibosPorColaborador($_SESSION['user_id']);

// Nome do colaborador (opcional)
$nome = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Colaborador';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibos de Vencimento - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/recibos_vencimento.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';">
        <nav>
            <a href="#">A Minha Ficha</a>
            <a href="#">Notificações</a>
            <div class="dropdown-perfil">
                <a href="#" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="#">Ficha Colaborador</a>
                    <a href="#">Benefícios</a>
                    <a href="#">Férias</a>
                    <a href="#">Formações</a>
                    <a href="#">Recibos</a>
                </div>
            </div>
            <a href="#">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Recibos de Vencimento</h1>
        <p class="descricao-inicial">
            Aqui pode consultar e descarregar todos os seus recibos de vencimento submetidos pelo RH.
        </p>
        <div class="recibos-lista">
            <div class="recibo-card">
                <div class="recibo-info">
                    <div class="recibo-nome">Recibo 1</div>
                    <div class="recibo-data">Submetido em: 01/07/2025 15:30</div>
                </div>
            </div>
            <div class="recibo-card">
                <div class="recibo-info">
                    <div class="recibo-nome">Recibo 2</div>
                    <div class="recibo-data">Submetido em: 01/06/2025 15:30</div>
                </div>
            </div>
            <div class="recibo-card">
                <div class="recibo-info">
                    <div class="recibo-nome">Recibo 3</div>
                    <div class="recibo-data">Submetido em: 01/05/2025 15:30</div>
                </div>
            </div>
            
        </div>
    </main>
</div>
</body>
</html>