<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipas.php';
$equipasBLL = new RHEquipasManager();

$coordenadores = $equipasBLL->getCoordenadores();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $coordenador_id = $_POST['coordenador_id'] ?? null;
    if ($nome && $coordenador_id) {
        if ($equipasBLL->addEquipa($nome, $coordenador_id)) {
            $success = "Equipa criada com sucesso!";
        } else {
            $error = "Erro ao criar equipa.";
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Equipa</title>
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
        <h1>Criar Nova Equipa</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome da Equipa:</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="ficha-campo">
                    <label>Coordenador:</label>
                    <select name="coordenador_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($coordenadores as $c): ?>
                            <option value="<?php echo $c['utilizador_id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Equipa</button>
            </div>
        </form>
        <div style="text-align:center; margin-top: 16px;">
            <a href="equipas.php" class="btn">Voltar</a>
        </div>
    </main>
</body>
</html>
