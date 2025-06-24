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
        'morada_fiscal' => $_POST['morada_fiscal'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
        'curso' => $_POST['curso'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura' ?? ''],
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'funcao' => $_POST['funcao'] ?? '',
        'geografia' => $_POST['geografia' ?? ''],
        'comprovativo_estado_civil' => $_POST['comprovativo_estado_civil'] ?? '',
        'nome_abreviado' => $_POST['nome_abreviado'] ?? '',
        'nif' => $_POST['nif'] ?? '',
        'niss' => $_POST['niss'] ?? '',
        'cc' => $_POST['cc'] ?? '',
        'nacionalidade' => $_POST['nacionalidade'] ?? '',
        'situacao_irs' => $_POST['situacao_irs'] ?? '',
        'dependentes' => $_POST['dependentes' ?? ''],
        'irs_jovem' => $_POST['irs_jovem'] ?? '',
        'primeiro_ano_descontos' => $_POST['primeiro_ano_descontos'] ?? '',
        'localidade' => $_POST['localidade'] ?? '',
        'codigo_postal' => $_POST['codigo_postal' ?? ''],
        'telemovel' => $_POST['telemovel'] ?? '',
        'iban' => $_POST['iban' ?? ''],
        'grau_relacionamento' => $_POST['grau_relacionamento'] ?? '',
        'cartao_continente' => $_POST['cartao_continente' ?? ''],
        'voucher_nos' => $_POST['voucher_nos'] ?? '',
        'tipo_contrato' => $_POST['tipo_contrato'] ?? '',
        'regime_horario' => $_POST['regime_horario'] ?? '',
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

require_once '../../BLL/Admin/BLL_campos_personalizados.php';
$camposBLL = new AdminCamposPersonalizadosManager();
$campos = $camposBLL->getAllCampos();
$valores = $camposBLL->getValoresByColaborador($colab['id'] ?? 0);

// Salvar campos personalizados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $campo) {
        $campo_id = $campo['id'];
        $valor = $_POST['campo_personalizado_' . $campo_id] ?? '';
        $camposBLL->setValorCampo($colab['id'], $campo_id, $valor);
    }
    // Recarregar valores atualizados
    $valores = $camposBLL->getValoresByColaborador($colab['id'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Ficha - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
    <link rel="stylesheet" href="../../assets/menu_notificacoes.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <?php
        // Menu dinâmico com sino para coordenador e outros perfis
        require_once '../../BLL/Admin/BLL_alertas.php';
        require_once '../../DAL/Admin/DAL_utilizadores.php';
        $alertasBLL = new AdminAlertasManager();
        $dalUtil = new DAL_UtilizadoresAdmin();
        $user_alerta = $dalUtil->getUtilizadorById($_SESSION['user_id']);
        $perfil_id_alerta = $user_alerta['perfil_id'];
        $user_id_alerta = $_SESSION['user_id'];
        $alertas = $alertasBLL->getAlertasParaUtilizador($perfil_id_alerta);
        $tem_nao_lidas = false;
        foreach ($alertas as $a) {
            if (!$alertasBLL->isAlertaLido($a['id'], $user_id_alerta)) {
                $tem_nao_lidas = true;
                break;
            }
        }
        $icone_sino = '<span style="position:relative;display:inline-block;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4a468a" viewBox="0 0 24 24" style="vertical-align:middle;">
                <path d="M12 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 5 14h14a1 1 0 0 0 .707-1.707L19 11.586V8a6 6 0 0 0-6-6zm0 20a2.978 2.978 0 0 0 2.816-2H9.184A2.978 2.978 0 0 0 12 22z"/>
            </svg>';
        if ($tem_nao_lidas) {
            $icone_sino .= '<span style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:#e53e3e;border-radius:50%;border:2px solid #fff;"></span>';
        }
        $icone_sino .= '</span>';
        if ($perfil === 'coordenador') {
            $menu = [
                'Dashboard' => '../Coordenador/dashboard_coordenador.php',
                'Minha Ficha' => '../Colaborador/ficha_colaborador.php',
                'Minha Equipa' => '../Coordenador/equipa.php',
                $icone_sino => '../Comuns/notificacoes.php',
                'Perfil' => '../Comuns/perfil.php',
                'Sair' => '../Comuns/logout.php'
            ];
        } elseif ($perfil === 'colaborador') {
            $menu = [
                'Dashboard' => 'dashboard_colaborador.php',
                'Minha Ficha' => 'ficha_colaborador.php',
                $icone_sino => '../Comuns/notificacoes.php',
                'Perfil' => '../Comuns/perfil.php',
                'Sair' => '../Comuns/logout.php'
            ];
        } elseif ($perfil === 'rh') {
            $menu = [
                'Dashboard' => '../RH/dashboard_rh.php',
                'Colaboradores' => '../RH/colaboradores_gerir.php',
                'Equipas' => '../RH/equipas.php',
                'Relatórios' => '../RH/relatorios.php',
                'Exportar' => '../RH/exportar.php',
                $icone_sino => '../Comuns/notificacoes.php',
                'Perfil' => '../Comuns/perfil.php',
                'Sair' => '../Comuns/logout.php'
            ];
        } elseif ($perfil === 'admin') {
            $menu = [
                'Dashboard' => '../Admin/dashboard_admin.php',
                'Utilizadores' => '../Admin/utilizadores.php',
                'Permissões' => '../Admin/permissoes.php',
                'Campos Personalizados' => '../Admin/campos_personalizados.php',
                'Alertas' => '../Admin/alertas.php',
                'Colaboradores' => '../RH/colaboradores_gerir.php',
                'Equipas' => '../RH/equipas.php',
                'Relatórios' => '../RH/relatorios.php',
                'Perfil' => '../Comuns/perfil.php',
                'Sair' => '../Comuns/logout.php'
            ];
        } else {
            $menu = [];
        }
        ?>
        <nav>
            <?php foreach ($menu as $label => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <main>
        <h1>Minha Ficha de Colaborador</h1>
        <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
        <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
        <form class="ficha-form ficha-form-moderna" method="POST" enctype="multipart/form-data">
            <div class="ficha-grid">
                <!-- Dados Pessoais -->
                <div class="ficha-campo">
                    <label>Nome:</label>
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
                    <label>Género:</label>
                    <select name="genero">
                        <option value="">Selecione</option>
                        <option value="Masculino" <?php if (($colab['genero'] ?? '') === 'Masculino') echo 'selected'; ?>>Masculino</option>
                        <option value="Feminino" <?php if (($colab['genero'] ?? '') === 'Feminino') echo 'selected'; ?>>Feminino</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Nacionalidade:</label>
                    <input type="text" name="nacionalidade" value="<?php echo htmlspecialchars($colab['nacionalidade'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Morada:</label>
                    <input type="text" name="morada" value="<?php echo htmlspecialchars($colab['morada'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Morada Fiscal:</label>
                    <input type="text" name="morada_fiscal" value="<?php echo htmlspecialchars($colab['morada_fiscal'] ?? ''); ?>">
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
                    <label>IBAN:</label>
                    <input type="text" name="iban" value="<?php echo htmlspecialchars($colab['iban'] ?? ''); ?>">
                </div>
                <!-- Documentos -->
                <div class="ficha-campo">
                    <label>NIF:</label>
                    <input type="text" name="nif" value="<?php echo htmlspecialchars($colab['nif'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>NISS:</label>
                    <input type="text" name="niss" value="<?php echo htmlspecialchars($colab['niss'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>CC:</label>
                    <input type="text" name="cc" value="<?php echo htmlspecialchars($colab['cc'] ?? ''); ?>">
                </div>
                <!-- Situação Fiscal -->
                <div class="ficha-campo">
                    <label>Situação IRS:</label>
                    <select name="situacao_irs">
                        <option value="">Selecione</option>
                        <option value="Declaração certa" <?php if (($colab['situacao_irs'] ?? '') === 'Declaração certa') echo 'selected'; ?>>Declaração certa</option>
                        <option value="Declaração com anomalias" <?php if (($colab['situacao_irs'] ?? '') === 'Declaração com anomalias') echo 'selected'; ?>>Declaração com anomalias</option>
                        <option value="Declaração substituída" <?php if (($colab['situacao_irs'] ?? '') === 'Declaração substituída') echo 'selected'; ?>>Declaração substituída</option>
                        <option value="Declaração com reembolso" <?php if (($colab['situacao_irs'] ?? '') === 'Declaração com reembolso') echo 'selected'; ?>>Declaração com reembolso</option>
                        <option value="Declaração não liquidável" <?php if (($colab['situacao_irs'] ?? '') === 'Declaração não liquidável') echo 'selected'; ?>>Declaração não liquidável</option>
                        <option value="Liquidação processada" <?php if (($colab['situacao_irs'] ?? '') === 'Liquidação processada') echo 'selected'; ?>>Liquidação processada</option>
                        <option value="Reembolso emitido" <?php if (($colab['situacao_irs'] ?? '') === 'Reembolso emitido') echo 'selected'; ?>>Reembolso emitido</option>
                        <option value="Pagamento confirmado" <?php if (($colab['situacao_irs'] ?? '') === 'Pagamento confirmado') echo 'selected'; ?>>Pagamento confirmado</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Dependentes:</label>
                    <input type="number" name="dependentes" value="<?php echo htmlspecialchars($colab['dependentes'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>IRS Jovem:</label>
                    <input type="text" name="irs_jovem" value="<?php echo htmlspecialchars($colab['irs_jovem'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Primeiro Ano de Descontos:</label>
                    <input type="text" name="primeiro_ano_descontos" value="<?php echo htmlspecialchars($colab['primeiro_ano_descontos'] ?? ''); ?>">
                </div>
                <!-- Dados Contratuais -->
                <div class="ficha-campo">
                    <label>Data de Entrada:</label>
                    <input type="date" name="data_entrada" value="<?php echo htmlspecialchars($colab['data_entrada'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                </div>
                <div class="ficha-campo">
                    <label>Tipo de Contrato:</label>
                    <select name="tipo_contrato">
                        <option value="">Selecione</option>
                        <option value="Estágio Curricular" <?php if (($colab['tipo_contrato'] ?? '') === 'Estágio Curricular') echo 'selected'; ?>>Estágio Curricular</option>
                        <option value="Estágio IEFP" <?php if (($colab['tipo_contrato'] ?? '') === 'Estágio IEFP') echo 'selected'; ?>>Estágio IEFP</option>
                        <option value="A termo certo" <?php if (($colab['tipo_contrato'] ?? '') === 'A termo certo') echo 'selected'; ?>>A termo certo</option>
                        <option value="A termo incerto" <?php if (($colab['tipo_contrato'] ?? '') === 'A termo incerto') echo 'selected'; ?>>A termo incerto</option>
                        <option value="Sem termo" <?php if (($colab['tipo_contrato'] ?? '') === 'Sem termo') echo 'selected'; ?>>Sem termo</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Regime Horário:</label>
                    <select name="regime_horario">
                        <option value="">Selecione</option>
                        <option value="10%" <?php if (($colab['regime_horario'] ?? '') === '10%') echo 'selected'; ?>>10%</option>
                        <option value="20%" <?php if (($colab['regime_horario'] ?? '') === '20%') echo 'selected'; ?>>20%</option>
                        <option value="50%" <?php if (($colab['regime_horario'] ?? '') === '50%') echo 'selected'; ?>>50%</option>
                    </select>
                </div>
                <!-- Profissional -->
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
                    <input type="number" step="0.01" name="remuneracao" value="<?php echo htmlspecialchars($colab['remuneracao'] ?? ''); ?>">
                </div>
                <!-- Habilitações -->
                <div class="ficha-campo">
                    <label>Habilitações Literárias:</label>
                    <select name="habilitacoes">
                        <option value="">Selecione</option>
                        <option value="12º Ano" <?php if (($colab['habilitacoes'] ?? '') === '12º Ano') echo 'selected'; ?>>12º Ano</option>
                        <option value="Licenciatura" <?php if (($colab['habilitacoes'] ?? '') === 'Licenciatura') echo 'selected'; ?>>Licenciatura</option>
                        <option value="Mestrado" <?php if (($colab['habilitacoes'] ?? '') === 'Mestrado') echo 'selected'; ?>>Mestrado</option>
                        <option value="Outro" <?php if (($colab['habilitacoes'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Curso:</label>
                    <input type="text" name="curso" value="<?php echo htmlspecialchars($colab['curso'] ?? ''); ?>">
                </div>
                <!-- Contacto Emergência -->
                <div class="ficha-campo">
                    <label>Contacto de Emergência:</label>
                    <input type="text" name="contacto_emergencia" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Grau de Relacionamento:</label>
                    <select name="grau_relacionamento">
                        <option value="">Selecione</option>
                        <option value="Companheiro/a" <?php if (($colab['grau_relacionamento'] ?? '') === 'Companheiro/a') echo 'selected'; ?>>Companheiro/a</option>
                        <option value="Pai/Mãe" <?php if (($colab['grau_relacionamento'] ?? '') === 'Pai/Mãe') echo 'selected'; ?>>Pai/Mãe</option>
                        <option value="Irmão/ã" <?php if (($colab['grau_relacionamento'] ?? '') === 'Irmão/ã') echo 'selected'; ?>>Irmão/ã</option>
                        <option value="Outro" <?php if (($colab['grau_relacionamento'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                    </select>
                </div>
                <!-- Outros -->
                <div class="ficha-campo">
                    <label>Matrícula Viatura:</label>
                    <input type="text" name="matricula_viatura" value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Cartão Continente:</label>
                    <input type="text" name="cartao_continente" value="<?php echo htmlspecialchars($colab['cartao_continente'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Voucher NOS:</label>
                    <input type="text" name="voucher_nos" value="<?php echo htmlspecialchars($colab['voucher_nos'] ?? ''); ?>">
                </div>
                <!-- Comprovativo Estado Civil -->
                <div class="ficha-campo">
                    <label>Comprovativo Estado Civil (PDF/JPG):</label>
                    <input type="file" name="comprovativo_estado_civil" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if ($comprovativo_atual): ?>
                        <div style="margin-top:4px;">
                            <a href="../../uploads/comprovativos/<?php echo htmlspecialchars($comprovativo_atual); ?>" target="_blank" style="color:#667eea;">Ver comprovativo atual</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($campos)): ?>
            <h2 style="margin-top:24px;">Campos Personalizados</h2>
            <div class="ficha-grid">
                <?php foreach ($campos as $campo): 
                    $valor = '';
                    foreach ($valores as $v) {
                        if ($v['campo_id'] == $campo['id']) {
                            $valor = $v['valor'];
                            break;
                        }
                    }
                ?>
                <div class="ficha-campo">
                    <label><?php echo htmlspecialchars($campo['nome']); ?>:</label>
                    <?php if ($campo['tipo'] === 'texto'): ?>
                        <input type="text" name="campo_personalizado_<?php echo $campo['id']; ?>" value="<?php echo htmlspecialchars($valor); ?>">
                    <?php elseif ($campo['tipo'] === 'numero'): ?>
                        <input type="number" name="campo_personalizado_<?php echo $campo['id']; ?>" value="<?php echo htmlspecialchars($valor); ?>">
                    <?php elseif ($campo['tipo'] === 'data'): ?>
                        <input type="date" name="campo_personalizado_<?php echo $campo['id']; ?>" value="<?php echo htmlspecialchars($valor); ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
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