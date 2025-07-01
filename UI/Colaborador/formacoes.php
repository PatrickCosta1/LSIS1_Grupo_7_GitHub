<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Comuns/erro.php');
    exit;
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
                        <button class="btn-inscrever" type="button">Inscrever-me</button>
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
        <div class="inscricao-modal" id="inscricao-modal" style="display:none;">
            <div class="inscricao-content">
                <button class="fechar-inscricao" onclick="fecharInscricao()">×</button>
                <h2 id="inscricao-titulo"></h2>
                <p>Deseja confirmar a inscrição nesta formação?</p>
                <button class="btn-confirmar" onclick="confirmarInscricao()">Confirmar Inscrição</button>
            </div>
        </div>
    </main>
    <script>
    const formacoes = <?= json_encode($formacoes) ?>;
    const cards = document.querySelectorAll('.formacao-card');
    const modal = document.getElementById('calendario-modal');
    const titulo = document.getElementById('calendario-titulo');
    const sessoesDiv = document.getElementById('calendario-sessoes');

    // Ver calendário
    document.querySelectorAll('.btn-marcacao').forEach((btn, idx) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
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

    <script>
    let formacaoSelecionada = null;
    const btnsInscrever = document.querySelectorAll('.btn-inscrever');
    const inscricaoModal = document.getElementById('inscricao-modal');
    const inscricaoTitulo = document.getElementById('inscricao-titulo');

    btnsInscrever.forEach((btn, idx) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            formacaoSelecionada = idx;
            inscricaoTitulo.textContent = formacoes[idx].nome;
            inscricaoModal.style.display = 'flex';
        });
    });

    function fecharInscricao() {
        inscricaoModal.style.display = 'none';
    }

    function confirmarInscricao() {
        fetch('inscrever_formacao.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                formacao_nome: formacoes[formacaoSelecionada].nome
            })
        })
        .then(res => res.json())
        .then(data => {
            inscricaoModal.innerHTML = `
                <div class="inscricao-content">
                    <h2>Inscrição efetuada!</h2>
                    <p>Está inscrito em: <b>${formacoes[formacaoSelecionada].nome}</b></p>
                </div>
            `;
            setTimeout(() => { inscricaoModal.style.display = 'none'; location.reload(); }, 1800);
        });
    }
    </script>
</div>
</body>
</html>