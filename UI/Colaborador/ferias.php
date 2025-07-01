<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Férias - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/ferias.css">
</head>
<body>
<div class="azul-container">
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';">
        <nav>
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
        </nav>
    </header>
    <main>
        <h1>Pedido de Férias</h1>
        <p class="descricao-inicial">
            Selecione o intervalo de datas em que pretende usufruir das suas férias e submeta o pedido para aprovação.
        </p>
        <form class="ferias-form" id="feriasForm" method="post" autocomplete="off">
            <div class="ferias-campos">
                <label for="data_inicio">Data de início:</label>
                <input type="date" id="data_inicio" name="data_inicio" required>

                <label for="data_fim">Data de fim:</label>
                <input type="date" id="data_fim" name="data_fim" required>
            </div>
            <button type="submit" class="btn-pedir-ferias">Pedir Férias</button>
        </form>
        <div id="ferias-msg" class="ferias-msg" style="display:none;"></div>
    </main>
</div>
<script>
document.getElementById('feriasForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data_inicio = document.getElementById('data_inicio').value;
    const data_fim = document.getElementById('data_fim').value;
    const msg = document.getElementById('ferias-msg');
    if (data_inicio > data_fim) {
        msg.textContent = "A data de início não pode ser posterior à data de fim.";
        msg.style.display = "block";
        msg.style.background = "#ffe0e0";
        msg.style.color = "#b00";
        return;
    }
    fetch('pedir_ferias.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ data_inicio, data_fim })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msg.textContent = "Pedido de férias submetido com sucesso!";
            msg.style.display = "block";
            msg.style.background = "#e0ffe0";
            msg.style.color = "#23408e";
            document.getElementById('feriasForm').reset();
        } else {
            msg.textContent = data.error || "Ocorreu um erro ao submeter o pedido.";
            msg.style.display = "block";
            msg.style.background = "#ffe0e0";
            msg.style.color = "#b00";
        }
    });
});
</script>
</body>
</html>