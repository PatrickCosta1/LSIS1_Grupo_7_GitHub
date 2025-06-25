<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();

// Buscar perfis possíveis (apenas colaborador e coordenador normalmente)
require_once '../../DAL/Admin/DAL_utilizadores.php';
$dalUtil = new DAL_UtilizadoresAdmin();
$perfis = $dalUtil->getPerfis();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'perfil_id' => $_POST['perfil_id'] ?? 0,
        'ativo' => isset($_POST['ativo']) ? 1 : 0,
        'password' => $_POST['password'] ?? '',
        'cargo' => $_POST['cargo'] ?? '',
        'morada' => $_POST['morada'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'genero' => $_POST['genero'] ?? '',
        'data_entrada' => $_POST['data_entrada'] ?? date('Y-m-d'),
        'geografia' => $_POST['geografia'] ?? '',
        'nivel_hierarquico' => $_POST['nivel_hierarquico'] ?? '',
        'remuneracao' => $_POST['remuneracao'] ?? ''
    ];
    if ($colabBLL->addColaborador($dados)) {
        $success = "Colaborador criado com sucesso!";
    } else {
        $error = "Erro ao criar colaborador.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Colaborador</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_rh.php">Dashboard</a>
            <a href="colaboradores_gerir.php">Colaboradores</a>
            <a href="equipas.php">Equipas</a>
            <a href="relatorios.php">Relatórios</a>
            <a href="exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Novo Colaborador</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="ficha-campo">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="ficha-campo">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="ficha-campo">
                    <label>Perfil:</label>
                    <select name="perfil_id" required>
                        <?php foreach ($perfis as $p): ?>
                            <?php if (in_array(strtolower($p['nome']), ['colaborador', 'coordenador'])): ?>
                                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Ativo:</label>
                    <input type="checkbox" name="ativo" checked>
                </div>
                <div class="ficha-campo">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="ficha-campo">
                    <label>Cargo:</label>
                    <input type="text" name="cargo">
                </div>
                <div class="ficha-campo">
                    <label>Morada:</label>
                    <input type="text" name="morada">
                </div>
                <div class="ficha-campo">
                    <label>Estado Civil:</label>
                    <input type="text" name="estado_civil">
                </div>
                <div class="ficha-campo">
                    <label>Habilitações:</label>
                    <input type="text" name="habilitacoes">
                </div>
                <div class="ficha-campo">
                    <label>Contacto Emergência:</label>
                    <input type="text" name="contacto_emergencia">
                </div>
                <div class="ficha-campo">
                    <label>Matrícula Viatura:</label>
                    <input type="text" name="matricula_viatura">
                </div>
                <div class="ficha-campo">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento">
                </div>
                <div class="ficha-campo">
                    <label>Género:</label>
                    <select name="genero">
                        <option value="">Selecione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Data de Entrada:</label>
                    <input type="date" name="data_entrada" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Geografia:</label>
                    <input type="text" name="geografia">
                </div>
                <div class="ficha-campo">
                    <label>Nível Hierárquico:</label>
                    <input type="text" name="nivel_hierarquico">
                </div>
                <div class="ficha-campo">
                    <label>Remuneração:</label>
                    <input type="number" name="remuneracao" step="0.01">
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Colaborador</button>
            </div>
        </form>
        <div style="text-align:center; margin-top: 16px;">
            <a href="colaboradores_gerir.php" class="btn">Voltar</a>
        </div>
    </main>
</body>
</html>