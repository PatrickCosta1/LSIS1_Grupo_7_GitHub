<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

// Inclui a BLL das formações (crie depois: BLL/RH/BLL_formacoes_gerir.php)
require_once '../../BLL/RH/BLL_formacoes_gerir.php';
$formacoesBLL = new RHFormacoesGerirManager();

// Processar ações de adicionar, editar, remover
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar'])) {
        // Adicionar nova formação
        $dados = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'data_inicio' => $_POST['data_inicio'] ?? '',
            'data_fim' => $_POST['data_fim'] ?? '',
            'horario_semanal' => $_POST['horario_semanal'] ?? '',
        ];
        if ($formacoesBLL->adicionarFormacao($dados)) {
            $success = "Formação adicionada com sucesso!";
        } else {
            $error = "Erro ao adicionar formação.";
        }
    } elseif (isset($_POST['editar']) && isset($_POST['formacao_id'])) {
        // Editar formação existente
        $id = intval($_POST['formacao_id']);
        $dados = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'data_inicio' => $_POST['data_inicio'] ?? '',
            'data_fim' => $_POST['data_fim'] ?? '',
            'horario_semanal' => $_POST['horario_semanal'] ?? '',
        ];
        if ($formacoesBLL->editarFormacao($id, $dados)) {
            $success = "Formação atualizada!";
        } else {
            $error = "Erro ao atualizar formação.";
        }
    } elseif (isset($_POST['remover']) && isset($_POST['formacao_id'])) {
        // Remover formação
        $id = intval($_POST['formacao_id']);
        if ($formacoesBLL->removerFormacao($id)) {
            $success = "Formação removida.";
        } else {
            $error = "Erro ao remover formação.";
        }
    }
}

// Listar todas as formações
$formacoes = $formacoesBLL->listarFormacoes();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerir Formações - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/gerir_formacoes.css"><!-- crie este ficheiro -->
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
    <main>
        <div class="portal-brand">
            <div class="color-bar">
                <div class="color-segment"></div>
                <div class="color-segment"></div>
                <div class="color-segment"></div>
            </div>
            <span class="portal-text">Portal Do Colaborador</span>
        </div>
        <h1>Gestão de Formações</h1>
        <?php if ($success): ?><div class="success-msg"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-msg"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <!-- Formulário para adicionar nova formação -->
        <section class="formacao-form-section">
            <h2>Adicionar Nova Formação</h2>
            <form method="post" class="formacao-form">
                <input type="text" name="nome" placeholder="Nome da formação" required>
                <textarea name="descricao" placeholder="Descrição" required></textarea>
                <div class="formacao-form-row">
                    <label>Data de Início: <input type="date" name="data_inicio" required></label>
                    <label>Data de Fim: <input type="date" name="data_fim" required></label>
                </div>
                <label>Horário Semanal (JSON): <textarea name="horario_semanal" placeholder='{"segunda":"09:00-13:00","terca":"09:00-13:00"}'></textarea></label>
                <button type="submit" name="adicionar" class="btn">Adicionar Formação</button>
            </form>
        </section>

        <!-- Lista de formações existentes -->
        <section class="formacoes-list-section">
            <h2>Formações Existentes</h2>
            <table class="formacoes-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Horário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($formacoes as $f): ?>
                    <tr>
                        <form method="post">
                            <td>
                                <input type="text" name="nome" value="<?= htmlspecialchars($f['nome']) ?>" required>
                            </td>
                            <td>
                                <textarea name="descricao" required><?= htmlspecialchars($f['descricao']) ?></textarea>
                            </td>
                            <td>
                                <input type="date" name="data_inicio" value="<?= htmlspecialchars($f['data_inicio']) ?>" required>
                            </td>
                            <td>
                                <input type="date" name="data_fim" value="<?= htmlspecialchars($f['data_fim']) ?>" required>
                            </td>
                            <td>
                                <textarea name="horario_semanal" placeholder='{"segunda":"09:00-13:00"}'><?= htmlspecialchars($f['horario_semanal']) ?></textarea>
                            </td>
                            <td>
                                <input type="hidden" name="formacao_id" value="<?= $f['id'] ?>">
                                <button type="submit" name="editar" class="btn btn-editar">Guardar</button>
                                <button type="submit" name="remover" class="btn btn-remover" onclick="return confirm('Remover esta formação?');">Remover</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
