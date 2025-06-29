<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cargo = $_POST['cargo'] ?? 'colaborador';
    $nivel_hierarquico = 1;
    $perfil_id = 2; // Default: colaborador
    switch ($cargo) {
        case 'coordenador':
            $nivel_hierarquico = 2;
            $perfil_id = 3;
            break;
        case 'rh':
            $nivel_hierarquico = 3;
            $perfil_id = 4;
            break;
        case 'admin':
            $nivel_hierarquico = 4;
            $perfil_id = 5;
            break;
        default:
            $nivel_hierarquico = 1;
            $perfil_id = 2;
    }
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'perfil_id' => $perfil_id,
        'ativo' => isset($_POST['ativo']) ? 1 : 0,
        'password' => $_POST['password'] ?? '',
        'cargo' => $cargo,
        'nivel_hierarquico' => $nivel_hierarquico
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
    <link rel="stylesheet" href="../../assets/CSS/RH/colaborador_novo.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
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
                    <label>Ativo:</label>
                    <input type="checkbox" name="ativo" checked>
                </div>
                <div class="ficha-campo">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="ficha-campo">
                    <label>Cargo:</label>
                    <select name="cargo" required>
                        <option value="colaborador">Colaborador</option>
                        <option value="coordenador">Coordenador</option>
                        <option value="rh">RH</option>
                        <option value="admin">Admin</option>
                    </select>
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