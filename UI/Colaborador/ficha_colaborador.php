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
        'nome_abreviado' => $_POST['nome_abreviado'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'nif' => $_POST['nif'] ?? '',
        'niss' => $_POST['niss'] ?? '',
        'cc' => $_POST['cc'] ?? '',
        'sexo' => $_POST['sexo'] ?? '',
        'nacionalidade' => $_POST['nacionalidade'] ?? '',
        'situacao_irs' => $_POST['situacao_irs'] ?? '',
        'dependentes' => $_POST['dependentes'] ?? '',
        'irs_jovem' => $_POST['irs_jovem'] ?? '',
        'primeiro_ano_descontos' => $_POST['primeiro_ano_descontos'] ?? '',
        'morada' => $_POST['morada'] ?? '',
        'localidade' => $_POST['localidade'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'telemovel' => $_POST['telemovel'] ?? '',
        'email' => $_POST['email'] ?? '',
        'iban' => $_POST['iban'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        'grau_relacionamento' => $_POST['grau_relacionamento'] ?? '',
        'contacto_emergencia_telefone' => $_POST['contacto_emergencia_telefone'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura'] ?? '',
        'cartao_continente' => $_POST['cartao_continente'] ?? '',
        // ...outros campos já existentes...
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
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

    // Guardar campos personalizados
    $valores_post = $_POST['campo_personalizado'] ?? [];
    foreach ($campos_personalizados as $campo) {
        $valor = $valores_post[$campo['id']] ?? '';
        $valoresBLL->guardarValor($colab['id'], $campo['id'], $valor);
    }
}

// Buscar campos personalizados e valores do colaborador
require_once '../../BLL/Admin/BLL_campos_personalizados.php';
$camposBLL = new AdminCamposPersonalizadosManager();
$campos_personalizados = $camposBLL->getAllCampos();

require_once '../../BLL/Colaborador/BLL_campos_personalizados.php';
$valoresBLL = new ColaboradorCamposPersonalizadosManager();
$valores_personalizados = $valoresBLL->getValoresByColaboradorId($colab['id'] ?? 0);

// Mapear valores por campo_id
$valores_map = [];
foreach ($valores_personalizados as $v) {
    $valores_map[$v['campo_id']] = $v['valor'];
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
        <div class="ficha-colaborador-container">
            <h1 class="ficha-titulo">Minha Ficha de Colaborador</h1>
            <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
            <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
            <form class="ficha-form ficha-form-moderna" method="POST" enctype="multipart/form-data">
                <!-- Dados Pessoais -->
                <div class="ficha-section">
                    <div class="ficha-section-title">Dados Pessoais</div>
                    <div class="ficha-grid">
                        <div class="ficha-campo">
                            <label>Nome Completo:</label>
                            <input type="text" name="nome" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Nome Abreviado:</label>
                            <input type="text" name="nome_abreviado" value="<?php echo htmlspecialchars($colab['nome_abreviado'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Data de Nascimento:</label>
                            <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>NIF:</label>
                            <input type="text" name="nif" value="<?php echo htmlspecialchars($colab['nif'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>NISS:</label>
                            <input type="text" name="niss" value="<?php echo htmlspecialchars($colab['niss'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Cartão de Cidadão:</label>
                            <input type="text" name="cc" value="<?php echo htmlspecialchars($colab['cc'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Sexo:</label>
                            <select name="sexo">
                                <option value="">Selecione</option>
                                <option value="Masculino" <?php if (($colab['sexo'] ?? '') === 'Masculino') echo 'selected'; ?>>Masculino</option>
                                <option value="Feminino" <?php if (($colab['sexo'] ?? '') === 'Feminino') echo 'selected'; ?>>Feminino</option>
                                <option value="Outro" <?php if (($colab['sexo'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                            </select>
                        </div>
                        <div class="ficha-campo">
                            <label>Nacionalidade:</label>
                            <input type="text" name="nacionalidade" value="<?php echo htmlspecialchars($colab['nacionalidade'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Situação IRS:</label>
                            <select name="situacao_irs">
                                <option value="">Selecione</option>
                                <option value="Nao Casado" <?php if (($colab['situacao_irs'] ?? '') === 'Nao Casado') echo 'selected'; ?>>Não Casado</option>
                                <option value="Casado" <?php if (($colab['situacao_irs'] ?? '') === 'Casado') echo 'selected'; ?>>Casado</option>
                            </select>
                        </div>
                        <div class="ficha-campo">
                            <label>Dependentes:</label>
                            <input type="number" name="dependentes" value="<?php echo htmlspecialchars($colab['dependentes'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>IRS Jovem:</label>
                            <select name="irs_jovem">
                                <option value="">Selecione</option>
                                <option value="Sim" <?php if (($colab['irs_jovem'] ?? '') === 'Sim') echo 'selected'; ?>>Sim</option>
                                <option value="Não" <?php if (($colab['irs_jovem'] ?? '') === 'Não') echo 'selected'; ?>>Não</option>
                            </select>
                        </div>
                        <div class="ficha-campo">
                            <label>Primeiro ano de descontos:</label>
                            <input type="text" name="primeiro_ano_descontos" value="<?php echo htmlspecialchars($colab['primeiro_ano_descontos'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <!-- Morada e Contactos -->
                <div class="ficha-section">
                    <div class="ficha-section-title">Morada e Contactos</div>
                    <div class="ficha-grid">
                        <div class="ficha-campo">
                            <label>Morada:</label>
                            <input type="text" name="morada" value="<?php echo htmlspecialchars($colab['morada'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Localidade:</label>
                            <input type="text" name="localidade" value="<?php echo htmlspecialchars($colab['localidade'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Código Postal:</label>
                            <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($colab['codigo_postal'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Telemóvel:</label>
                            <input type="text" name="telemovel" value="<?php echo htmlspecialchars($colab['telemovel'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>IBAN:</label>
                            <input type="text" name="iban" value="<?php echo htmlspecialchars($colab['iban'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <!-- Contacto de Emergência -->
                <div class="ficha-section">
                    <div class="ficha-section-title">Contacto de Emergência</div>
                    <div class="ficha-grid">
                        <div class="ficha-campo">
                            <label>Nome:</label>
                            <input type="text" name="contacto_emergencia" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Grau de Relacionamento:</label>
                            <input type="text" name="grau_relacionamento" value="<?php echo htmlspecialchars($colab['grau_relacionamento'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Telefone:</label>
                            <input type="text" name="contacto_emergencia_telefone" value="<?php echo htmlspecialchars($colab['contacto_emergencia_telefone'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <!-- Outros Dados -->
                <div class="ficha-section">
                    <div class="ficha-section-title">Outros Dados</div>
                    <div class="ficha-grid">
                        <div class="ficha-campo">
                            <label>Matrícula do Carro:</label>
                            <input type="text" name="matricula_viatura" value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>">
                        </div>
                        <div class="ficha-campo">
                            <label>Continente (Nº Cartão):</label>
                            <input type="text" name="cartao_continente" value="<?php echo htmlspecialchars($colab['cartao_continente'] ?? ''); ?>">
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
                </div>
                <!-- Campos Personalizados -->
                <?php if (!empty($campos_personalizados)): ?>
                <div class="ficha-section">
                    <div class="ficha-section-title">Campos Personalizados</div>
                    <div class="ficha-grid">
                        <?php foreach ($campos_personalizados as $campo): ?>
                            <div class="ficha-campo">
                                <label><?php echo htmlspecialchars($campo['nome']); ?><?php if ($campo['obrigatorio']) echo ' *'; ?></label>
                                <?php if ($campo['tipo'] === 'texto'): ?>
                                    <input type="text" name="campo_personalizado[<?php echo $campo['id']; ?>]" value="<?php echo htmlspecialchars($valores_map[$campo['id']] ?? ''); ?>" <?php if ($campo['obrigatorio']) echo 'required'; ?>>
                                <?php elseif ($campo['tipo'] === 'numero'): ?>
                                    <input type="number" name="campo_personalizado[<?php echo $campo['id']; ?>]" value="<?php echo htmlspecialchars($valores_map[$campo['id']] ?? ''); ?>" <?php if ($campo['obrigatorio']) echo 'required'; ?>>
                                <?php elseif ($campo['tipo'] === 'data'): ?>
                                    <input type="date" name="campo_personalizado[<?php echo $campo['id']; ?>]" value="<?php echo htmlspecialchars($valores_map[$campo['id']] ?? ''); ?>" <?php if ($campo['obrigatorio']) echo 'required'; ?>>
                                <?php else: ?>
                                    <input type="text" name="campo_personalizado[<?php echo $campo['id']; ?>]" value="<?php echo htmlspecialchars($valores_map[$campo['id']] ?? ''); ?>" <?php if ($campo['obrigatorio']) echo 'required'; ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div style="text-align:center; margin-top: 24px;">
                    <button type="submit" class="btn">Guardar Alterações</button>
                </div>
            </form>
        </div>
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