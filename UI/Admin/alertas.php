<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_alertas.php';
require_once '../../DAL/Admin/DAL_utilizadores.php';
$alertasBLL = new AdminAlertasManager();
$dalUtil = new DAL_UtilizadoresAdmin();
$perfis = $dalUtil->getPerfis();

$success = '';
$error = '';

// Adicionar alerta
if (isset($_POST['add_tipo'], $_POST['add_descricao'], $_POST['add_periodicidade'], $_POST['add_ativo'], $_POST['add_perfis'])) {
    if ($alertasBLL->addAlerta($_POST['add_tipo'], $_POST['add_descricao'], $_POST['add_periodicidade'], $_POST['add_ativo'], $_POST['add_perfis'])) {
        $success = "Alerta criado!";
    } else {
        $error = "Erro ao criar alerta.";
    }
}

// Editar alerta
if (isset($_POST['edit_id'], $_POST['edit_tipo'], $_POST['edit_descricao'], $_POST['edit_periodicidade'], $_POST['edit_ativo'], $_POST['edit_perfis'])) {
    if ($alertasBLL->updateAlerta($_POST['edit_id'], $_POST['edit_tipo'], $_POST['edit_descricao'], $_POST['edit_periodicidade'], $_POST['edit_ativo'])) {
        $alertasBLL->updateAlertasPerfis($_POST['edit_id'], $_POST['edit_perfis']);
        $success = "Alerta atualizado!";
    } else {
        $error = "Erro ao atualizar alerta.";
    }
}

// Remover alerta
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    if ($alertasBLL->removeAlerta($_GET['remover'])) {
        $success = "Alerta removido!";
    } else {
        $error = "Erro ao remover alerta.";
    }
}

$alertas = $alertasBLL->getAllAlertas();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alertas - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/teste.css">
    <style>
        body {
            background: #f4f4fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .campos-container {
            max-width: 900px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(102,126,234,0.10), 0 1.5px 6px rgba(118,75,162,0.07);
            padding: 38px 40px 40px 40px;
        }
        .campos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .campos-header h1 {
            font-size: 2.2rem;
            color: #3a366b;
            margin: 0;
            letter-spacing: 1px;
        }
        .success-message, .error-message {
            text-align: center;
            margin-bottom: 18px;
            padding: 10px 0;
            border-radius: 7px;
            font-weight: 600;
            font-size: 1.08rem;
        }
        .success-message { background: #e6fffa; color: #2c7a7b; }
        .error-message { background: #fff5f5; color: #c53030; }
        form[method="POST"] {
            background: #ecebfa;
            border-radius: 12px;
            padding: 18px 22px 12px 22px;
            margin-bottom: 28px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.07);
            display: flex;
            flex-wrap: wrap;
            gap: 18px 24px;
            align-items: flex-end;
        }
        form[method="POST"] input[type="text"],
        form[method="POST"] input[type="number"],
        form[method="POST"] select {
            padding: 8px 10px;
            border: 1px solid #d5d3f1;
            border-radius: 7px;
            font-size: 1rem;
            background: #f8f8fc;
            color: #3a366b;
            margin-bottom: 4px;
            min-width: 160px;
        }
        form[method="POST"] input[type="text"]:focus,
        form[method="POST"] input[type="number"]:focus,
        form[method="POST"] select:focus {
            border-color: #764ba2;
            outline: none;
        }
        .perfis-checkbox-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 28px;
            margin: 6px 0 0 0;
        }
        .perfis-checkbox-list label {
            font-weight: 500;
            color: #4a468a;
            margin-right: 10px;
            cursor: pointer;
            background: #f4f4fa;
            border-radius: 6px;
            padding: 4px 12px 4px 8px;
            transition: background 0.2s;
        }
        .perfis-checkbox-list input[type="checkbox"] {
            accent-color: #764ba2;
            margin-right: 5px;
        }
        .tabela-campos {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #f9f9fb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(102,126,234,0.06);
        }
        .tabela-campos th, .tabela-campos td {
            padding: 14px 16px;
            text-align: left;
        }
        .tabela-campos th {
            background: #ecebfa;
            color: #4a468a;
            font-weight: 600;
            border-bottom: 2px solid #d5d3f1;
            font-size: 1.05rem;
        }
        .tabela-campos tr:nth-child(even) {
            background: #f4f4fa;
        }
        .tabela-campos tr:hover {
            background: #e6e6f7;
        }
        .tabela-campos td {
            color: #3a366b;
            font-size: 1rem;
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
            margin-top: 4px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b47b6 100%);
        }
        .btn-danger {
            background: #e53e3e;
        }
        .btn-danger:hover {
            background: #c53030;
        }
        @media (max-width: 900px) {
            .campos-container { padding: 12px 4px; }
            .campos-header h1 { font-size: 1.3rem; }
            form[method="POST"] { flex-direction: column; gap: 10px; }
            .tabela-campos th, .tabela-campos td { padding: 8px 6px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="campos_personalizados.php">Campos Personalizados</a>
            <a href="alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <div class="campos-container">
        <div class="campos-header">
            <h1>Gestão de Alertas</h1>
        </div>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>

        <!-- Formulário adicionar alerta -->
        <form method="POST">
            <input type="text" name="add_tipo" placeholder="Tipo" required>
            <input type="text" name="add_descricao" placeholder="Descrição" required>
            <input type="number" name="add_periodicidade" placeholder="Periodicidade (meses)" min="0" required>
            <select name="add_ativo">
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
            <div style="flex:1 1 100%;">
                <span style="font-weight:600;color:#4a468a;">Destinatários:</span>
                <div class="perfis-checkbox-list">
                    <?php foreach ($perfis as $p): ?>
                        <label>
                            <input type="checkbox" name="add_perfis[]" value="<?php echo $p['id']; ?>">
                            <?php echo htmlspecialchars($p['nome']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small style="color:#764ba2;">Selecione um ou mais perfis que irão receber este alerta.</small>
            </div>
            <button type="submit" class="btn" style="margin-top:0;">Adicionar Alerta</button>
        </form>

        <table class="tabela-campos">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Periodicidade (meses)</th>
                    <th>Ativo</th>
                    <th>Destinatários</th>
                    <th style="min-width:120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alertas as $a): 
                    $alerta_perfis = $alertasBLL->getPerfisByAlerta($a['id']);
                    $alerta_perfis_ids = array_column($alerta_perfis, 'id');
                ?>
                <tr>
                    <form method="POST" style="display:inline;">
                        <td>
                            <input type="hidden" name="edit_id" value="<?php echo $a['id']; ?>">
                            <input type="text" name="edit_tipo" value="<?php echo htmlspecialchars($a['tipo']); ?>" required style="width:110px;">
                        </td>
                        <td>
                            <input type="text" name="edit_descricao" value="<?php echo htmlspecialchars($a['descricao']); ?>" required style="width:180px;">
                        </td>
                        <td>
                            <input type="number" name="edit_periodicidade" value="<?php echo htmlspecialchars($a['periodicidade_meses']); ?>" min="0" required style="width:70px;">
                        </td>
                        <td>
                            <select name="edit_ativo">
                                <option value="1" <?php if ($a['ativo']) echo 'selected'; ?>>Ativo</option>
                                <option value="0" <?php if (!$a['ativo']) echo 'selected'; ?>>Inativo</option>
                            </select>
                        </td>
                        <td>
                            <div class="perfis-checkbox-list">
                                <?php foreach ($perfis as $p): ?>
                                    <label>
                                        <input type="checkbox" name="edit_perfis[]" value="<?php echo $p['id']; ?>"
                                            <?php if (in_array($p['id'], $alerta_perfis_ids)) echo 'checked'; ?>>
                                        <?php echo htmlspecialchars($p['nome']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td>
                            <button type="submit" class="btn">Guardar</button>
                            <a href="?remover=<?php echo $a['id']; ?>" class="btn btn-danger" onclick="return confirm('Remover este alerta?');">Remover</a>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="chatbot-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;">
      <button id="open-chatbot" style="
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          border: none;
          border-radius: 50%;
          width: 60px;
          height: 60px;
          box-shadow: 0 4px 16px rgba(0,0,0,0.15);
          font-size: 28px;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          ">
        ?
      </button>
      <iframe
        id="chatbot-iframe"
        src="https://www.chatbase.co/chatbot-iframe/SHUUk9C_zO-W-kHarKtWh"
        title="Ajuda Chatbot"
        width="350"
        height="500"
        style="display: none; position: absolute; bottom: 70px; right: 0; border: none; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
      </iframe>
    </div>
    <script src="../../assets/chatbot.js"></script>
</body>
</html>