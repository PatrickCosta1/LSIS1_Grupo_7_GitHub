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
    <title>Benefícios do Colaborador - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/beneficios.css">
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
        <h1>Benefícios do Colaborador Tlantic</h1>
        <p class="descricao-inicial">
            A Tlantic valoriza os seus colaboradores e oferece um conjunto de benefícios que promovem o bem-estar, desenvolvimento e equilíbrio entre a vida profissional e pessoal.
        </p>
        <section class="beneficios-lista">
            <ul>
                <li><strong>Seguro de Saúde:</strong> Cobertura médica abrangente para colaboradores e familiares diretos.</li>
                <li><strong>Plano de Formação Contínua:</strong> Acesso a cursos, workshops e certificações profissionais.</li>
                <li><strong>Horário Flexível:</strong> Possibilidade de ajustar o horário de trabalho para melhor conciliação pessoal.</li>
                <li><strong>Dia de Aniversário Livre:</strong> Folga no dia de aniversário do colaborador.</li>
                <li><strong>Protocolos com Ginásios:</strong> Descontos em ginásios e academias parceiras.</li>
                <li><strong>Cartão Refeição:</strong> Subsídio de alimentação em cartão para maior comodidade.</li>
                <li><strong>Programa de Reconhecimento:</strong> Prémios e distinções para colaboradores de excelência.</li>
                <li><strong>Teletrabalho Parcial:</strong> Opção de trabalho remoto em determinados dias da semana.</li>
                <li><strong>Apoio à Família:</strong> Licenças parentais alargadas e apoio em situações familiares especiais.</li>
                <li><strong>Eventos Corporativos:</strong> Participação em eventos, team buildings e iniciativas internas.</li>
            </ul>
        </section>
    </main>
</div>
</body>
</html>