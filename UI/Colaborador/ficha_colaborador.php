<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !in_array($perfil, ['colaborador', 'coordenador', 'rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}

require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
$colabBLL = new ColaboradorFichaManager();

$editColabId = $_GET['id'] ?? null;
$targetUserId = $userId;

// Corrigir: RH/Admin pode editar qualquer colaborador via ?id= (id do colaborador, não utilizador)
if (in_array($perfil, ['rh', 'admin']) && $editColabId) {
    $colab = $colabBLL->getColaboradorById($editColabId);
    if ($colab && isset($colab['utilizador_id'])) {
        $targetUserId = $colab['utilizador_id'];
    } else {
        header('Location: ../Comuns/erro.php');
        exit();
    }
} else {
    // Colaborador/Coordenador só pode editar a própria ficha
    if ($editColabId && $editColabId != $userId) {
        header('Location: ../Comuns/erro.php');
        exit();
    }
    $colab = $colabBLL->getColaboradorByUserId($userId);
}

$success_message = '';
$error_message = '';

// Diretório para uploads
$upload_dir = __DIR__ . '/../../uploads/comprovativos/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Verifica se já existe comprovativo
$comprovativo_atual = '';
if (isset($colab['comprovativo_estado_civil']) && $colab['comprovativo_estado_civil']) {
    $comprovativo_atual = $colab['comprovativo_estado_civil'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'morada' => $_POST['morada'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'funcao' => $_POST['funcao'] ?? '',
        'geografia' => $_POST['geografia'] ?? '',
        'nivel_hierarquico' => $_POST['nivel_hierarquico'] ?? '',
        'remuneracao' => $_POST['remuneracao'] ?? '',
        'genero' => $_POST['genero'] ?? ''
    ];
    if ($colabBLL->updateColaboradorByUserId($targetUserId, $dados)) {
        $success_message = "Dados atualizados com sucesso!";
        // Recarregar dados após atualização
        if (in_array($perfil, ['rh', 'admin']) && $editColabId) {
            $colab = $colabBLL->getColaboradorById($editColabId);
        } else {
            $colab = $colabBLL->getColaboradorByUserId($userId);
        }
    } else {
        $error_message = "Erro ao atualizar dados.";
    }
    // Upload de comprovativo se estado civil for alterado
    if (isset($_FILES['comprovativo_estado_civil']) && $_FILES['comprovativo_estado_civil']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_estado_civil']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_estado_civil']['tmp_name'], $dest)) {
            $dados['comprovativo_estado_civil'] = $filename;
            $comprovativo_atual = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Ficha - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <?php if ($perfil === 'coordenador'): ?>
                <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                <a href="ficha_colaborador.php">Minha Ficha</a>
                <a href="../Coordenador/equipa.php">Minha Equipa</a>
                <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($perfil === 'rh'): ?>
                <a href="../RH/dashboard_rh.php">Dashboard</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../RH/exportar.php">Exportar</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($perfil === 'admin'): ?>
                <a href="../Admin/dashboard_admin.php">Dashboard</a>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../RH/equipas.php">Equipas</a>
                <a href="../RH/relatorios.php">Relatórios</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
                <a href="dashboard_colaborador.php">Dashboard</a>
                <a href="ficha_colaborador.php">Minha Ficha</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h1>Minha Ficha de Colaborador</h1>
        <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
        <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
        <form class="ficha-form ficha-form-moderna" method="POST" enctype="multipart/form-data">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Morada:</label>
                    <input type="text" name="morada" value="<?php echo htmlspecialchars($colab['morada'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Estado Civil:</label>
                    <input type="text" name="estado_civil" value="<?php echo htmlspecialchars($colab['estado_civil'] ?? ''); ?>">
                    <div style="margin-top:6px;">
                        <label>Comprovativo Estado Civil (PDF/JPG):</label>
                        <input type="file" name="comprovativo_estado_civil" accept=".pdf,.jpg,.jpeg,.png">
                        <?php if ($comprovativo_atual): ?>
                            <div style="margin-top:4px;">
                                <a href="../../uploads/comprovativos/<?php echo htmlspecialchars($comprovativo_atual); ?>" target="_blank" style="color:#667eea;">Ver comprovativo atual</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ficha-campo">
                    <label>Habilitações:</label>
                    <input type="text" name="habilitacoes" value="<?php echo htmlspecialchars($colab['habilitacoes'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Contacto Emergência:</label>
                    <input type="text" name="contacto_emergencia" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Matrícula Viatura:</label>
                    <input type="text" name="matricula_viatura" value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Função:</label>
                    <input type="text" name="funcao" value="<?php echo htmlspecialchars($colab['funcao'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Geografia:</label>
                    <input type="text" name="geografia" value="<?php echo htmlspecialchars($colab['geografia'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Nível Hierárquico:</label>
                    <input type="text" name="nivel_hierarquico" value="<?php echo htmlspecialchars($colab['nivel_hierarquico'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Remuneração:</label>
                    <input type="number" name="remuneracao" value="<?php echo htmlspecialchars($colab['remuneracao'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Género:</label>
                    <select name="genero">
                        <option value="">Selecione</option>
                        <option value="Masculino" <?php if (($colab['genero'] ?? '') === 'Masculino') echo 'selected'; ?>>Masculino</option>
                        <option value="Feminino" <?php if (($colab['genero'] ?? '') === 'Feminino') echo 'selected'; ?>>Feminino</option>
                        <option value="Outro" <?php if (($colab['genero'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Data de Entrada:</label>
                    <input type="date" value="<?php echo htmlspecialchars($colab['data_entrada'] ?? ''); ?>" readonly>
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Guardar Alterações</button>
            </div>
        </form>
    </main>
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