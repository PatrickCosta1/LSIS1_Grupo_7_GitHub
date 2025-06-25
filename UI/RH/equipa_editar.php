<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();
require_once '../../BLL/RH/BLL_equipas.php';
$equipasBLL = new RHEquipasManager();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: equipas.php');
    exit();
}

// Buscar equipa atual
$equipas = $equipasBLL->getAllEquipas();
$equipa = null;
foreach ($equipas as $e) {
    if ($e['id'] == $id) {
        $equipa = $e;
        break;
    }
}
if (!$equipa) {
    header('Location: equipas.php');
    exit();
}

// Remover membro se solicitado via GET
if (isset($_GET['remover_membro']) && is_numeric($_GET['remover_membro'])) {
    $remover_id = (int)$_GET['remover_membro'];
    $equipasBLL->removerMembroEquipa($id, $remover_id);
    header("Location: equipa_editar.php?id=$id");
    exit();
}

// Buscar membros atuais da equipa (com perfil e função)
$membros_atuais = $equipasBLL->getMembrosEquipaDetalhado($id);
$membros_atuais_ids = array_column($membros_atuais, 'id');

// Buscar colaboradores disponíveis (não estão em nenhuma equipa)
$colaboradores_disponiveis = $equipasBLL->getColaboradoresDisponiveisParaEquipa();
$novos_membros_selecionados = $_POST['novos_membros'] ?? [];

$coordenadores = $equipasBLL->getCoordenadores();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $coordenador_id = $_POST['coordenador_id'] ?? null;
    $novos_membros = $_POST['novos_membros'] ?? [];
    if ($nome && $coordenador_id) {
        $ok = $equipasBLL->updateEquipa($id, $nome, $coordenador_id);
        $adicionados = 0;
        if (!empty($novos_membros)) {
            foreach ($novos_membros as $colab_id) {
                if ($equipasBLL->adicionarMembroEquipa($id, $colab_id)) {
                    $adicionados++;
                }
            }
        }
        if ($ok) {
            $success = "Equipa atualizada com sucesso!";
            if ($adicionados > 0) {
                $success .= " $adicionados novo(s) membro(s) adicionado(s).";
            }
            // Atualizar listas após alteração
            $membros_atuais = $equipasBLL->getMembrosEquipaDetalhado($id);
            $membros_atuais_ids = array_column($membros_atuais, 'id');
            $colaboradores_disponiveis = $equipasBLL->getColaboradoresDisponiveisParaEquipa();
        } else {
            $error = "Erro ao atualizar equipa.";
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}

// Menu dinâmico: admin tem menu igual ao dashboard admin, rh tem menu de rh
if ($_SESSION['profile'] === 'admin') {
    $menu = [
        'Dashboard' => '../Admin/dashboard_admin.php',
        'Utilizadores' => '../Admin/utilizadores.php',
        'Permissões' => '../Admin/permissoes.php',
        'Campos Personalizados' => '../Admin/campos_personalizados.php',
        'Alertas' => '../Admin/alertas.php',
        'Colaboradores' => 'colaboradores_gerir.php',
        'Equipas' => 'equipas.php',
        'Relatórios' => 'relatorios.php',
        'Perfil' => '../Comuns/perfil.php',
        'Sair' => '../Comuns/logout.php'
    ];
} else {
    $menu = [
        'Dashboard' => 'dashboard_rh.php',
        'Colaboradores' => 'colaboradores_gerir.php',
        'Equipas' => 'equipas.php',
        'Relatórios' => 'relatorios.php',
        'Exportar' => 'exportar.php',
        'Perfil' => '../Comuns/perfil.php',
        'Sair' => '../Comuns/logout.php'
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipa - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <!-- Não incluir menu_notificacoes.css para admin/rh -->
    <style>
        body {
            background: #f4f4fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .ficha-form-moderna {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 32px 36px 32px;
            max-width: 700px;
            margin: 36px auto 0 auto;
        }
        .ficha-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
        }
        .ficha-campo {
            flex: 1 1 100%;
            margin-bottom: 18px;
        }
        .ficha-campo label {
            font-weight: 600;
            color: #3a366b;
            margin-bottom: 6px;
            display: block;
        }
        .ficha-campo input[type="text"],
        .ficha-campo select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d5d3f1;
            border-radius: 7px;
            font-size: 1rem;
            background: #f8f8fc;
            color: #3a366b;
            margin-bottom: 6px;
        }
        .ficha-campo input[type="text"]:focus,
        .ficha-campo select:focus {
            border-color: #764ba2;
            outline: none;
        }
        .success-message, .error-message {
            text-align: center;
            margin-bottom: 18px;
            padding: 8px 0;
            border-radius: 6px;
            font-weight: 600;
        }
        .success-message { background: #e6fffa; color: #2c7a7b; }
        .error-message { background: #fff5f5; color: #c53030; }
        .membros-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 8px;
            background: #f9f9fb;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .membros-table th, .membros-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #ecebfa;
            text-align: left;
        }
        .membros-table th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
        }
        .membros-table tr:last-child td {
            border-bottom: none;
        }
        .membros-table tr:hover {
            background: #e6e6f7;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 22px;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 8px;
            margin-top: 10px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        @media (max-width: 700px) {
            .ficha-form-moderna { padding: 12px 4px; }
            .ficha-grid { gap: 12px; }
            .membros-table th, .membros-table td { padding: 7px 6px; font-size: 0.95rem; }
        }
        .membros-atuais-list {
            background: #ecebfa;
            border-radius: 7px;
            padding: 10px 14px;
            margin-bottom: 8px;
            color: #3a366b;
        }
        .membros-atuais-list li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php foreach ($menu as $label => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <main>
        <h1 style="text-align:center; color:#3a366b; margin-bottom:24px;">Editar Equipa</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome da Equipa:</label>
                    <input type="text" name="nome" required value="<?php echo htmlspecialchars($equipa['nome']); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Coordenador:</label>
                    <select name="coordenador_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($coordenadores as $c): ?>
                            <option value="<?php echo $c['utilizador_id']; ?>" <?php if ($equipa['coordenador'] == $c['nome']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($c['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Membros atuais da equipa:</label>
                    <table class="membros-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Perfil</th>
                                <th>Função</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($membros_atuais)): ?>
                            <?php foreach ($membros_atuais as $m): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($m['nome']); ?></td>
                                    <td><?php echo isset($m['perfil']) ? htmlspecialchars($m['perfil']) : ''; ?></td>
                                    <td><?php echo isset($m['cargo']) ? htmlspecialchars($m['cargo']) : ''; ?></td>
                                    <td>
                                        <a href="equipa_editar.php?id=<?php echo $id; ?>&remover_membro=<?php echo $m['id']; ?>"
                                           class="btn btn-danger"
                                           style="padding:2px 10px;font-size:0.95rem;"
                                           onclick="return confirm('Remover este membro da equipa?');">
                                            Remover
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4"><em>Nenhum membro nesta equipa.</em></td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="ficha-campo">
                    <label>Adicionar novos membros:</label>
                    <?php if (count($colaboradores_disponiveis) > 0): ?>
                        <table class="membros-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nome</th>
                                    <th>Perfil</th>
                                    <th>Função</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($colaboradores_disponiveis as $col): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="novos_membros[]" value="<?php echo $col['id']; ?>"
                                                <?php if (in_array($col['id'], $novos_membros_selecionados)) echo 'checked'; ?>>
                                        </td>
                                        <td><?php echo htmlspecialchars($col['nome']); ?></td>
                                        <td><?php echo isset($col['perfil']) ? htmlspecialchars($col['perfil']) : ''; ?></td>
                                        <td><?php echo htmlspecialchars($col['cargo']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="margin-bottom:8px;">
                            <button type="submit" class="btn" style="background:#764ba2;">Adicionar Selecionados</button>
                        </div>
                        <small>Selecione os colaboradores e clique em "Adicionar Selecionados".</small>
                    <?php else: ?>
                        <em>Não existem colaboradores disponíveis para adicionar.</em>
                    <?php endif; ?>
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Guardar Alterações</button>
            </div>
        </form>
        <div style="text-align:center; margin-top: 16px;">
            <a href="equipas.php" class="btn">Voltar</a>
        </div>
    </main>
</body>
</html>
