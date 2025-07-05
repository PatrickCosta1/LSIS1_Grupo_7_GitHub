<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_equipa_editar.php';

$equipaId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($equipaId <= 0) {
    header('Location: equipas.php');
    exit();
}

$equipasBLL = new RHEquipaEditarManager();
$equipa = $equipasBLL->getEquipaById($equipaId);
if (!$equipa) {
    header('Location: equipas.php');
    exit();
}

// Determinar o tipo da equipa
$tipoEquipa = $equipa['tipo'] ?? 'colaboradores';

// Buscar membros disponíveis conforme o tipo da equipa
function getMembrosDisponiveis($tipo, $equipasBLL) {
    if ($tipo === 'colaboradores') {
        // Apenas colaboradores (perfil_id = 2)
        return $equipasBLL->getColaboradoresSemEquipaSoColaboradores();
    } elseif ($tipo === 'coordenadores') {
        // Apenas coordenadores (perfil_id = 3)
        require_once '../../DAL/Database.php';
        $pdo = Database::getConnection();
        $sql = "SELECT c.id as colaborador_id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3 AND u.ativo = 1
                  AND c.id NOT IN (
                      SELECT colaborador_id FROM equipa_colaboradores
                  )";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($tipo === 'rh') {
        // Apenas RH (perfil_id = 4)
        require_once '../../DAL/Database.php';
        $pdo = Database::getConnection();
        $sql = "SELECT c.id as colaborador_id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 4 AND u.ativo = 1
                  AND c.id NOT IN (
                      SELECT colaborador_id FROM equipa_colaboradores
                  )";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

// Buscar responsáveis corretos conforme o tipo da equipa
function getResponsaveisDisponiveis($tipo, $equipaId = null, $responsavelIdAtual = null) {
    require_once '../../DAL/Database.php';
    $pdo = Database::getConnection();
    $responsaveis = [];
    if ($tipo === 'colaboradores' || $tipo === 'coordenadores') {
        $sql = "SELECT c.id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3 AND u.ativo = 1";
        $responsaveis = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($tipo === 'rh') {
        $sql = "SELECT c.id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 4 AND u.ativo = 1";
        $responsaveis = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    // Garante que o responsável atual aparece na lista (mesmo se não ativo)
    if ($responsavelIdAtual) {
        $existe = false;
        foreach ($responsaveis as $r) {
            if ($r['id'] == $responsavelIdAtual) {
                $existe = true;
                break;
            }
        }
        if (!$existe) {
            // Buscar o responsável atual pelo ID
            $stmt = $pdo->prepare("SELECT c.id, c.nome FROM colaboradores c WHERE c.id = ?");
            $stmt->execute([$responsavelIdAtual]);
            $atual = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($atual) {
                $responsaveis[] = $atual;
            }
        }
    }
    return $responsaveis;
}

$coordenadores = getResponsaveisDisponiveis($tipoEquipa, $equipaId, $equipa['responsavel_id'] ?? null);
$colaboradoresFora = getMembrosDisponiveis($tipoEquipa, $equipasBLL);
$colaboradoresEquipa = $equipasBLL->getColaboradoresDaEquipa($equipaId);

// Remover o responsável da lista de adicionar/remover caso seja equipa de coordenadores ou rh
if (in_array($tipoEquipa, ['coordenadores', 'rh']) && !empty($equipa['responsavel_id'])) {
    // Remover o responsável dos colaboradores da equipa
    $colaboradoresEquipa = array_filter($colaboradoresEquipa, function($colab) use ($equipa) {
        $id = isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : null);
        return $id != $equipa['responsavel_id'];
    });
    $colaboradoresEquipa = array_values($colaboradoresEquipa);

    // Remover o responsável dos colaboradores disponíveis para adicionar
    $colaboradoresFora = array_filter($colaboradoresFora, function($colab) use ($equipa) {
        $id = isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : null);
        return $id != $equipa['responsavel_id'];
    });
    $colaboradoresFora = array_values($colaboradoresFora);
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = '';
    $error = '';
    if (isset($_POST['remover_colab_id'])) {
        $colabId = intval($_POST['remover_colab_id']);
        $okRemover = $equipasBLL->removerColaboradorDaEquipa($equipaId, $colabId);
        if ($okRemover) {
            $success = "Membro removido da equipa.";
        } else {
            $error = "Erro ao remover membro.";
        }
    }
    if (isset($_POST['adicionar_colab_id'])) {
        $colabId = intval($_POST['adicionar_colab_id']);
        $okAdicionar = $equipasBLL->adicionarColaboradorAEquipa($equipaId, $colabId);
        if ($okAdicionar) {
            $success = "Membro adicionado à equipa.";
        } else {
            $error = "Erro ao adicionar membro.";
        }
    }
    if (isset($_POST['nome']) && isset($_POST['responsavel_id'])) {
        $novoNome = trim($_POST['nome']);
        $novoResp = intval($_POST['responsavel_id']); // ID do colaborador
        $okAtualizar = $equipasBLL->atualizarNomeCoordenador($equipaId, $novoNome, $novoResp, $tipoEquipa);
        if ($okAtualizar) {
            $success = "Equipa atualizada com sucesso!";
        } else {
            $error = "Erro ao atualizar equipa. Verifique se o coordenador selecionado é válido.";
        }
    }
    if (isset($_POST['remover_equipa'])) {
        if ($equipasBLL->removerEquipa($equipaId)) {
            header('Location: equipas.php?removida=1');
            exit();
        } else {
            $error = "Erro ao remover a equipa.";
        }
    }
    $equipa = $equipasBLL->getEquipaById($equipaId);
    $tipoEquipa = $equipa['tipo'] ?? 'colaboradores';
    $colaboradoresEquipa = $equipasBLL->getColaboradoresDaEquipa($equipaId);
    $colaboradoresFora = getMembrosDisponiveis($tipoEquipa, $equipasBLL);
    // Remover o responsável das listas após alteração, se necessário
    if (in_array($tipoEquipa, ['coordenadores', 'rh']) && !empty($equipa['responsavel_id'])) {
        $colaboradoresEquipa = array_filter($colaboradoresEquipa, function($colab) use ($equipa) {
            $id = isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : null);
            return $id != $equipa['responsavel_id'];
        });
        $colaboradoresEquipa = array_values($colaboradoresEquipa);

        $colaboradoresFora = array_filter($colaboradoresFora, function($colab) use ($equipa) {
            $id = isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : null);
            return $id != $equipa['responsavel_id'];
        });
        $colaboradoresFora = array_values($colaboradoresFora);
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipa</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/equipa_editar.css">
</head>
<body>
<header>
    <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='equipas.php';">
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
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="recibos_submeter.php">Recibos</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
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
    <h1>Editar Equipa</h1>
    <?php if ($success): ?><div class="success-message"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-message"><?= $error ?></div><?php endif; ?>

    <form class="ficha-form-moderna" method="POST" style="margin-bottom:32px;">
        <div class="ficha-grid">
            <div class="ficha-campo">
                <label for="nome">Nome da Equipa:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($equipa['nome']) ?>" required>
            </div>
            <div class="ficha-campo">
                <label for="responsavel_id">Coordenador:</label>
                <select name="responsavel_id" id="responsavel_id" required>
                    <?php foreach ($coordenadores as $coord): ?>
                        <option value="<?= $coord['id'] ?>" <?= $coord['id'] == $equipa['responsavel_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($coord['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="perfil-edit-actions" style="margin-top:24px;">
            <button type="submit" class="btn">Guardar Alterações</button>
            <button type="submit" name="remover_equipa" class="btn" style="background:linear-gradient(135deg,#e53e3e 0%,#c53030 100%);color:#fff;margin-left:16px;" onclick="return confirm('Tem a certeza que deseja remover esta equipa?');">Remover Equipa</button>
        </div>
    </form>

    <div class="equipa-tabelas">
        <div class="tabela-colab-box">
            <h2>Colaboradores na Equipa</h2>
            <table class="tabela-colabs">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colaboradoresEquipa)): ?>
                        <tr><td colspan="2" style="color:#888;">Nenhum colaborador nesta equipa.</td></tr>
                    <?php else: ?>
                        <?php foreach ($colaboradoresEquipa as $colab): ?>
                            <tr>
                                <td><?= htmlspecialchars($colab['nome']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="remover_colab_id" value="<?= $colab['id'] ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Remover este colaborador da equipa?');">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="tabela-colab-box">
            <h2>Colaboradores disponíveis para adicionar</h2>
            <table class="tabela-colabs">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colaboradoresFora)): ?>
                        <tr><td colspan="2" style="color:#888;">Nenhum colaborador disponível para adicionar.</td></tr>
                    <?php else: ?>
                        <?php foreach ($colaboradoresFora as $colab): ?>
                            <tr>
                                <td><?= htmlspecialchars($colab['nome']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="adicionar_colab_id" value="<?= isset($colab['colaborador_id']) ? $colab['colaborador_id'] : (isset($colab['id']) ? $colab['id'] : '') ?>">
                                        <button type="submit" class="btn btn-add">Adicionar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="voltar-btn">
        <a href="equipas.php" class="btn">Voltar</a>
    </div>
</main>
</body>
</html>