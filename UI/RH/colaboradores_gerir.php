<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
require_once '../../BLL/Comuns/BLL_notificacoes.php'; // <--- Adicione esta linha

$colabBLL = new RHColaboradoresManager();
$notBLL = new NotificacoesManager();

$import_success = '';
$import_error = '';

// --- Importar colaborador existente via CSV ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['importar_colaborador'])) {
    $email_envio = trim($_POST['email_import'] ?? ''); // Email para envio das credenciais
    $email_utilizador = trim($_POST['email_empresa_import'] ?? ''); // Email da empresa (login)
    $perfil_id = intval($_POST['perfil_id_import'] ?? 2);
    $csv_ok = isset($_FILES['csv_import']) && $_FILES['csv_import']['error'] === UPLOAD_ERR_OK;

    if (!$email_envio || !filter_var($email_envio, FILTER_VALIDATE_EMAIL)) {
        $import_error = "Email para envio inválido.";
    } elseif (!$email_utilizador || !filter_var($email_utilizador, FILTER_VALIDATE_EMAIL)) {
        $import_error = "Email da empresa inválido.";
    } elseif (!in_array($perfil_id, [2,3,4])) {
        $import_error = "Cargo inválido.";
    } elseif (!$csv_ok) {
        $import_error = "Ficheiro CSV inválido.";
    } else {
        // Processar CSV
        $csvFile = $_FILES['csv_import']['tmp_name'];
        $handle = fopen($csvFile, 'r');
        if ($handle) {
            $header = fgetcsv($handle);
            $values = fgetcsv($handle);
            fclose($handle);
            if ($header && $values && count($header) === count($values)) {
                $dados = array_combine($header, $values);

                // Gerar username único e password
                $base_username = strtolower(preg_replace('/[^a-z0-9]/', '', explode('@', $email_utilizador)[0]));
                $username = $base_username;
                require_once '../../DAL/Database.php';
                $pdo = Database::getConnection();
                $try = 0;
                while (true) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE username = ?");
                    $stmt->execute([$username]);
                    if ($stmt->fetchColumn() == 0) break;
                    $try++;
                    $username = $base_username . $try;
                }
                $password = bin2hex(random_bytes(5)); // 10 chars

                // Criar utilizador com o perfil/cargo selecionado no modal
                $userId = $colabBLL->criarUtilizadorComPerfil($username, $perfil_id, $password, $email_utilizador);
                $maxTries = 30;
                $tries = 0;
                while ((!$userId || $userId === false) && $tries < $maxTries) {
                    $tries++;
                    $username = $base_username . ($try + $tries);
                    $userId = $colabBLL->criarUtilizadorComPerfil($username, $perfil_id, $password, $email_utilizador);
                }
                if (!$userId || $userId === false) {
                    $import_error = "Erro ao criar utilizador: username já existe ou conflito de dados. Tente outro email.";
                } else {
                    require_once '../../DAL/Colaborador/DAL_ficha_colaborador.php';
                    $dalFicha = new \DAL_FichaColaborador();

                    // Lista de campos pessoais aceitos (NÃO inclui cargos, emails institucionais, senhas, ids, perfis, etc)
                    $validFields = [
                        'utilizador_id',
                        'nome',
                        'apelido',
                        'nome_abreviado',
                        'num_mecanografico',
                        'data_nascimento',
                        'telemovel',
                        'sexo',
                        'habilitacoes',
                        'curso',
                        'matricula_viatura',
                        'morada',
                        'localidade',
                        'codigo_postal',
                        'cc',
                        'nif',
                        'niss',
                        'iban',
                        'situacao_irs',
                        'dependentes',
                        'irs_jovem',
                        'primeiro_ano_descontos',
                        'cartao_continente',
                        'voucher_nos',
                        'nome_contacto_emergencia',
                        'grau_relacionamento',
                        'contacto_emergencia',
                        'data_inicio_contrato',
                        'data_fim_contrato',
                        'remuneracao',
                        'tipo_contrato',
                        'regime_horario',
                        'estado_civil',
                        'morada_fiscal'
                    ];

                    // Sempre sobrescrever/definir corretamente:
                    $dadosColab = [];
                    $dadosColab['utilizador_id'] = $userId;
                    $dadosColab['email'] = $email_envio; // Email pessoal para contacto

                    // Preencher apenas os campos pessoais do CSV, ignorando todos os campos sensíveis
                    foreach ($validFields as $field) {
                        if ($field === 'utilizador_id' || $field === 'email') continue; // já definidos acima
                        if (isset($dados[$field])) {
                            $dadosColab[$field] = $dados[$field];
                        }
                    }

                    // Montar arrays para o insert
                    $insertFields = array_keys($dadosColab);
                    $insertValues = array_values($dadosColab);

                    // Inserir colaborador novo
                    $dalFicha->insertColaboradorFromImport($insertFields, $insertValues);

                    // Enviar email com credenciais para o email_envio
                    $notBLL->enviarEmailSimples($email_envio, "Acesso ao Portal Tlantic", 
                        "Bem-vindo ao Portal Tlantic.<br>O seu username é <b>$username</b> e a sua palavra-passe é <b>$password</b>.<br>Aceda ao portal para completar a sua ficha.");
                    $import_success = "Colaborador importado com sucesso e email enviado.";
                }
            } else {
                $import_error = "Ficheiro CSV inválido ou mal formatado.";
            }
        } else {
            $import_error = "Erro ao ler o ficheiro CSV.";
        }
    }
}
// --- Remover colaborador ---
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    $colabBLL->removerColaboradorComUtilizador((int)$_GET['remover']);
    header('Location: colaboradores_gerir.php?removido=1');
    exit();
}

$colaboradores = $colabBLL->getAllColaboradores($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Colaboradores - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/colaboradores_gerir.css">
    <style>
    /* Modal centralizado para importar colaborador */
    .importar-modal-bg {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0; top: 0; right: 0; bottom: 0;
        background: rgba(25,54,95,0.32);
        justify-content: center;
        align-items: center;
        animation: fadeInBg 0.25s;
    }
    .importar-modal-bg.active,
    .importar-modal-bg[style*="display: flex"] {
        display: flex !important;
    }
    .importar-modal {
        background: linear-gradient(135deg, #fafdff 0%, #eaf6ff 100%);
        border-radius: 22px;
        max-width: 420px;
        width: 92vw;
        padding: 38px 32px 28px 32px;
        box-shadow: 0 12px 48px rgba(3,96,233,0.20), 0 2px 12px rgba(0,0,0,0.08);
        text-align: center;
        position: relative;
        animation: modalPopIn 0.33s cubic-bezier(.23,1.12,.62,1.01);
        border: 2.5px solid #299cf3;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .importar-modal-close {
        position: absolute;
        top: 18px;
        right: 24px;
        background: none;
        border: none;
        font-size: 2.1rem;
        color: #299cf3;
        cursor: pointer;
        transition: color 0.2s, transform 0.18s;
        font-weight: bold;
        z-index: 2;
        line-height: 1;
        opacity: 0.85;
    }
    .importar-modal-close:hover {
        color: #e53e3e;
        transform: scale(1.18) rotate(8deg);
    }
    .importar-modal h2 {
        color: #0360e9;
        font-size: 1.35rem;
        margin-bottom: 18px;
        font-weight: 800;
        letter-spacing: 0.01em;
        text-shadow: 0 2px 8px rgba(3,96,233,0.07);
    }
    .importar-modal .info {
        color: #23408e;
        font-size: 1.08rem;
        margin-bottom: 18px;
        line-height: 1.6;
        font-weight: 500;
    }
    .importar-modal form label {
        display: block;
        margin-bottom: 8px;
        color: #1c3c69;
        font-weight: 500;
        font-size: 1rem;
        text-align: left;
    }
    .importar-modal input[type="email"],
    .importar-modal input[type="file"],
    .importar-modal select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #c3cfe2;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 12px;
        background: #f5f7fa;
        color: #1c3c69;
        transition: border 0.2s;
    }
    .importar-btn {
        background: linear-gradient(135deg, #ff8c00 0%, #ffd580 100%);
        color: #23408e;
        font-weight: 700;
        border-radius: 7px;
        box-shadow: 0 2px 8px #ff8c001a;
        padding: 12px 28px;
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        margin-top: 10px;
        outline: none;
        border: none;
        cursor: pointer;
    }
    .importar-btn:hover {
        background: linear-gradient(135deg, #ffd580 0%, #ff8c00 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.03);
    }
    </style>
    <script>
    function confirmarRemocao(nome, id) {
        if (confirm('Tem a certeza que deseja remover o colaborador "' + nome + '"? Esta ação é irreversível.')) {
            window.location.href = 'colaboradores_gerir.php?remover=' + id;
        }
    }
    function abrirModalImportar() {
        document.getElementById('modal-importar-bg').style.display = 'flex';
    }
    function fecharModalImportar() {
        document.getElementById('modal-importar-bg').style.display = 'none';
    }
    function mostrarInfoCSV() {
        document.getElementById('csv-info-modal-bg').style.display = 'flex';
    }
    function fecharInfoCSV() {
        document.getElementById('csv-info-modal-bg').style.display = 'none';
    }
    // Fechar modal ao clicar fora
    document.addEventListener('mousedown', function(event) {
        const modalBg = document.getElementById('modal-importar-bg');
        const infoBg = document.getElementById('csv-info-modal-bg');
        if (event.target === modalBg) fecharModalImportar();
        if (event.target === infoBg) fecharInfoCSV();
    });
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalImportar();
            fecharInfoCSV();
        }
    });
    </script>
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
        <h1>Gestão de Colaboradores</h1>
        <div class="rh-actions-bar">
            <a href="colaborador_novo.php" class="rh-action-btn add-colab-btn">Adicionar Novo Colaborador</a>
            <button class="rh-action-btn importar" onclick="abrirModalImportar()">Importar Colaborador Existente</button>
        </div>
        <!-- Modal Importar Colaborador (centralizado) -->
        <div class="importar-modal-bg" id="modal-importar-bg">
            <div class="importar-modal">
                <span class="importar-modal-close" onclick="fecharModalImportar()">&times;</span>
                <h2>Importar Colaborador Existente</h2>
                <div class="info">
                    Preencha os campos e selecione o ficheiro CSV do colaborador.<br>
                </div>
                <?php if (!empty($import_error)): ?>
                    <div class="error-message"><?= htmlspecialchars($import_error) ?></div>
                <?php elseif (!empty($import_success)): ?>
                    <div class="success-message"><?= htmlspecialchars($import_success) ?></div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                    <label>Email para envio das credenciais:</label>
                    <input type="email" name="email_import" required placeholder="Email pessoal do colaborador">
                    <label>Email da empresa (login):</label>
                    <input type="email" name="email_empresa_import" required placeholder="Email institucional">
                    <label>Cargo/Perfil:</label>
                    <select name="perfil_id_import" required>
                        <option value="2">Colaborador</option>
                        <option value="3">Coordenador</option>
                        <option value="4">RH</option>
                    </select>
                    <label>Ficheiro CSV:</label>
                    <input type="file" name="csv_import" accept=".csv" required>
                    <button type="submit" class="importar-btn" name="importar_colaborador">Importar</button>
                </form>
            </div>
        </div>
        <!-- Popup de informações CSV -->
        <div class="csv-info-modal-bg" id="csv-info-modal-bg" style="display:none;">
            <div class="csv-info-modal">
                <span class="csv-info-modal-close" onclick="fecharInfoCSV()">&times;</span>
                <h3>Como preparar o ficheiro CSV?</h3>
                <div class="csv-info-content">
                    <p>O ficheiro CSV deve conter uma linha de cabeçalho com os nomes dos campos e uma linha com os dados do colaborador.<br>
                    <b>Campos aceitos:</b> nome, apelido, nome_abreviado, num_mecanografico, data_nascimento, telemovel, sexo, habilitacoes, curso, matricula_viatura, morada, localidade, codigo_postal, cc, nif, niss, iban, situacao_irs, dependentes, irs_jovem, primeiro_ano_descontos, cartao_continente, voucher_nos, nome_contacto_emergencia, grau_relacionamento, contacto_emergencia, data_inicio_contrato, data_fim_contrato, remuneracao, tipo_contrato, regime_horario, estado_civil, morada_fiscal</p>
                    <p><b>Exemplo de cabeçalho:</b></p>
                    <pre style="background:#f7f7fa;padding:8px;border-radius:6px;font-size:0.95em;">nome,apelido,data_nascimento,telemovel,sexo,habilitacoes,curso</pre>
                    <p><b>Exemplo de linha de dados:</b></p>
                    <pre style="background:#f7f7fa;padding:8px;border-radius:6px;font-size:0.95em;">João,Silva,1990-05-12,912345678,M,Licenciatura,Engenharia</pre>
                </div>
            </div>
        </div>
        <div class="tabela-colaboradores-wrapper">
            <table class="tabela-colaboradores tabela-colaboradores-compacta">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Equipas</th>
                        <th style="min-width:90px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colaboradores as $col): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($col['nome']); ?></td>
                        <td><?php echo htmlspecialchars($col['username'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($col['email']); ?></td>
                        <td>
                            <?php
                            if (isset($col['perfil'])) {
                                $tipo = strtolower($col['perfil']);
                                if ($tipo === 'coordenador') {
                                    echo 'Coordenador';
                                } elseif ($tipo === 'colaborador') {
                                    echo 'Colaborador';
                                } elseif ($tipo === 'rh') {
                                    echo 'RH';
                                } elseif ($tipo === 'admin') {
                                    echo 'Administrador';
                                } else {
                                    echo ucfirst($tipo);
                                }
                            } else {
                                echo 'Colaborador';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($col['equipas']); ?></td>
                        <td>
                            <a href="../Colaborador/ficha_colaborador.php?id=<?php echo $col['id']; ?>" class="btn btn-sm">Ver</a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarRemocao('<?php echo htmlspecialchars($col['nome']); ?>', <?php echo $col['id']; ?>)">Remover</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="height: 60px;"></div>
    </main>

    <script>
    document.getElementById('btnImportarCSV').onclick = function() {
        document.getElementById('csvInfoModal').classList.add('active');
    };
    document.getElementById('closeCsvModal').onclick = function() {
        document.getElementById('csvInfoModal').classList.remove('active');
    };
    document.getElementById('csvInfoModal').onclick = function(e) {
        if (e.target === this) this.classList.remove('active');
    };
    </script>
</body>
</html>