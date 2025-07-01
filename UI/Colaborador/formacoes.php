<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    header('Location: ../Comuns/erro.php');
    exit();
}

// Exemplo de formações disponíveis (substitui por dados da BD no futuro)
$formacoes = [
    [
        'nome' => 'Excel Avançado',
        'data' => '2025-08-24',
        'horas' => 4,
        'admin' => 'Joana Silva',
        'certificacao' => 'Microsoft Office Specialist',
        'sessoes' => [
            ['data' => '2025-08-24', 'hora_inicio' => '18:00', 'hora_fim' => '20:00'],
            ['data' => '2025-08-25', 'hora_inicio' => '18:00', 'hora_fim' => '20:00'],
        ]
    ],
    [
        'nome' => 'Gestão de Equipas',
        'data' => '2025-08-28',
        'horas' => 3,
        'admin' => 'Carlos Pereira',
        'certificacao' => 'Certificado Interno',
        'sessoes' => [
            ['data' => '2025-08-28', 'hora_inicio' => '17:00', 'hora_fim' => '20:00'],
        ]
    ],
    [
        'nome' => 'Comunicação Eficaz',
        'data' => '2025-09-02',
        'horas' => 2,
        'admin' => 'Ana Costa',
        'certificacao' => 'Certificado Interno',
        'sessoes' => [
            ['data' => '2025-09-02', 'hora_inicio' => '18:00', 'hora_fim' => '20:00'],
        ]
    ],
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Marcação de Formações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/formacoes.css">
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
        <h1>Marcação de Formações</h1>
        <p class="descricao-inicial">
            Consulte as formações disponíveis e clique numa formação para ver o calendário das sessões.
        </p>
        <div class="formacoes-container">
            <?php foreach ($formacoes as $i => $f): ?>
                <div class="formacao-card" data-index="<?= $i ?>">
                    <div class="formacao-info">
                        <div class="formacao-nome"><?= htmlspecialchars($f['nome']) ?></div>
                        <div class="formacao-meta">
                            <span><strong>Data:</strong> <?= date('d/m/Y', strtotime($f['data'])) ?></span>
                            <span><strong>Duração:</strong> <?= $f['horas'] ?>h</span>
                        </div>
                        <div class="formacao-admin">
                            <strong>Administrador:</strong> <?= htmlspecialchars($f['admin']) ?>
                        </div>
                        <div class="formacao-cert">
                            <strong>Certificação:</strong> <?= htmlspecialchars($f['certificacao']) ?>
                        </div>
                    </div>
                    <div class="formacao-acoes">
                        <button class="btn-marcacao" type="button">Ver calendário</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="formacoes-calendario" id="calendario-modal" style="display:none;">
            <div class="calendario-content">
                <button class="fechar-calendario" onclick="fecharCalendario()">×</button>
                <h2 id="calendario-titulo"></h2>
                <div id="calendario-sessoes"></div>
            </div>
        </div>
    </main>
    <script>
    const formacoes = <?= json_encode($formacoes) ?>;
    const cards = document.querySelectorAll('.formacao-card');
    const modal = document.getElementById('calendario-modal');
    const titulo = document.getElementById('calendario-titulo');
    const sessoesDiv = document.getElementById('calendario-sessoes');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const idx = card.getAttribute('data-index');
            const formacao = formacoes[idx];
            titulo.textContent = formacao.nome + " - Sessões";
            sessoesDiv.innerHTML = '';
            formacao.sessoes.forEach(sessao => {
                const data = new Date(sessao.data);
                const dia = data.toLocaleDateString('pt-PT');
                const hora = `${sessao.hora_inicio} - ${sessao.hora_fim}`;
                sessoesDiv.innerHTML += `
                    <div class="sessao-item">
                        <span class="sessao-dia"><b>${dia}</b></span>
                        <span class="sessao-horas">${hora}</span>
                    </div>
                `;
            });
            modal.style.display = 'flex';
        });
    });
    function fecharCalendario() {
        modal.style.display = 'none';
    }
    </script>
</div>
</body>
</html>