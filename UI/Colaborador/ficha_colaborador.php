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
$upload_dir = __DIR__ . '/../../Uploads/comprovativos/';
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
        // Dados pessoais
        'nome' => $_POST['nome'] ?? '',
        'apelido' => $_POST['apelido'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telemovel' => $_POST['telemovel'] ?? '',
        'sexo' => $_POST['sexo'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura'] ?? '',
        // Morada
        'morada' => $_POST['morada'] ?? '',
        'localidade' => $_POST['localidade'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        // Documentos
        'cc' => $_POST['cc'] ?? '',
        'nif' => $_POST['nif'] ?? '',
        'niss' => $_POST['niss'] ?? '',
        'iban' => $_POST['iban'] ?? '',
        // IRS
        'situacao_irs' => $_POST['situacao_irs'] ?? '',
        'dependentes' => $_POST['dependentes'] ?? '',
        'irs_jovem' => $_POST['irs_jovem'] ?? '',
        'primeiro_ano_descontos' => $_POST['primeiro_ano_descontos'] ?? '',
        // Contacto de emergência
        'nome_contacto_emergencia' => $_POST['nome_contacto_emergencia'] ?? '',
        'grau_relacionamento' => $_POST['grau_relacionamento'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        // Vouchers
        'cartao_continente' => $_POST['cartao_continente'] ?? '',
        'numero_cliente_nos' => $_POST['numero_cliente_nos'] ?? '',
        // Só para RH/Admin
        'cargo' => $_POST['cargo'] ?? '',
        'nivel_hierarquico' => $_POST['nivel_hierarquico'] ?? '',
        'remuneracao' => $_POST['remuneracao'] ?? '',
        'nome_abreviado' => $_POST['nome_abreviado'] ?? '',
        'tipo_contrato' => $_POST['tipo_contrato'] ?? '',
        'regime_horario' => $_POST['regime_horario'] ?? ''
    ];

    // Upload comprovativo CC
    if (isset($_FILES['comprovativo_cc']) && $_FILES['comprovativo_cc']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_cc']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_cc_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_cc']['tmp_name'], $dest)) {
            $dados['comprovativo_cc'] = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo do CC.";
        }
    }

    // Upload comprovativo IBAN
    if (isset($_FILES['comprovativo_iban']) && $_FILES['comprovativo_iban']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_iban']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_iban_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_iban']['tmp_name'], $dest)) {
            $dados['comprovativo_iban'] = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo do IBAN.";
        }
    }

    // Upload comprovativo Estado Civil
    if (isset($_FILES['comprovativo_estado_civil']) && $_FILES['comprovativo_estado_civil']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_estado_civil']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_estado_civil_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_estado_civil']['tmp_name'], $dest)) {
            $dados['comprovativo_estado_civil'] = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo do Estado Civil.";
        }
    }

    // Upload comprovativo Cartão Continente
    if (isset($_FILES['comprovativo_cartao_continente']) && $_FILES['comprovativo_cartao_continente']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_cartao_continente']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_cartao_continente_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_cartao_continente']['tmp_name'], $dest)) {
            $dados['comprovativo_cartao_continente'] = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo do Cartão Continente.";
        }
    }

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
}

// Menu dinâmico com sino
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

$menu = [];
switch ($_SESSION['profile']) {
    case 'admin':
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
            $icone_sino => '../Comuns/notificacoes.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'rh':
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
        break;
    case 'coordenador':
        $menu = [
            'Dashboard' => '../Coordenador/dashboard_coordenador.php',
            'Minha Ficha' => 'ficha_colaborador.php',
            'Minha Equipa' => '../Coordenador/equipa.php',
            $icone_sino => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'colaborador':
        $menu = [
            'Dashboard' => 'dashboard_colaborador.php',
            'Minha Ficha' => 'ficha_colaborador.php',
            $icone_sino => '../Comuns/notificacoes.php',
            'Perfil' => '../Comuns/perfil.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
    case 'convidado':
        $menu = [
            'Preencher Dados' => '../Convidado/onboarding_convidado.php',
            'Sair' => '../Comuns/logout.php'
        ];
        break;
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
        <nav>
            <?php foreach ($menu as $label => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <main>
        <h1>A Tua Ficha de Colaborador</h1>
        <?php if ($success_message): ?><div class="success-message"><?php echo $success_message; ?></div><?php endif; ?>
        <?php if ($error_message): ?><div class="error-message"><?php echo $error_message; ?></div><?php endif; ?>
        <form class="ficha-form ficha-form-moderna" method="POST" enctype="multipart/form-data">
            <div class="ficha-grid">
                <!-- Dados Pessoais -->
                <div class="ficha-campo">
                    <label>Primeiro Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Apelido:</label>
                    <input type="text" name="apelido" value="<?php echo htmlspecialchars($colab['apelido'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Telemóvel:</label>
                    <input type="text" name="telemovel" value="<?php echo htmlspecialchars($colab['telemovel'] ?? ''); ?>">
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
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Estado Civil:</label>
                    <select name="estado_civil">
                        <option value="">Selecione</option>
                        <option value="Solteiro" <?php if (($colab['estado_civil'] ?? '') === 'Solteiro') echo 'selected'; ?>>Solteiro</option>
                        <option value="Casado" <?php if (($colab['estado_civil'] ?? '') === 'Casado') echo 'selected'; ?>>Casado</option>
                        <option value="Divorciado" <?php if (($colab['estado_civil'] ?? '') === 'Divorciado') echo 'selected'; ?>>Divorciado</option>
                        <option value="Viúvo" <?php if (($colab['estado_civil'] ?? '') === 'Viúvo') echo 'selected'; ?>>Viúvo</option>
                    </select>
                    <label style="font-size:12px;">Comprovativo Estado Civil (PDF/JPG):</label>
                    <input type="file" name="comprovativo_estado_civil" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($colab['comprovativo_estado_civil'])): ?>
                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_estado_civil']); ?>" target="_blank">Ver comprovativo atual</a>
                    <?php endif; ?>
                </div>
                <div class="ficha-campo">
                    <label>Habilitações Literárias:</label>
                    <select name="habilitacoes">
                        <option value="">Selecione</option>
                        <option value="Ensino Básico" <?php if (($colab['habilitacoes'] ?? '') === 'Ensino Básico') echo 'selected'; ?>>Ensino Básico</option>
                        <option value="Ensino Secundário" <?php if (($colab['habilitacoes'] ?? '') === 'Ensino Secundário') echo 'selected'; ?>>Ensino Secundário</option>
                        <option value="Licenciatura" <?php if (($colab['habilitacoes'] ?? '') === 'Licenciatura') echo 'selected'; ?>>Licenciatura</option>
                        <option value="Mestrado" <?php if (($colab['habilitacoes'] ?? '') === 'Mestrado') echo 'selected'; ?>>Mestrado</option>
                        <option value="Doutoramento" <?php if (($colab['habilitacoes'] ?? '') === 'Doutoramento') echo 'selected'; ?>>Doutoramento</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Matrícula Viatura:</label>
                    <input type="text" name="matricula_viatura" value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>">
                </div>
            </div>

            <!-- Secção Morada -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#764ba2;">Morada</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>Endereço:</label>
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
                </div>
            </fieldset>

            <!-- Secção Documentos -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#764ba2;">Documentos</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>CC (Cartão de Cidadão):</label>
                        <input type="text" name="cc" value="<?php echo htmlspecialchars($colab['cc'] ?? ''); ?>">
                        <label style="font-size:12px;">Comprovativo CC (PDF/JPG):</label>
                        <input type="file" name="comprovativo_cc" accept=".pdf,.jpg,.jpeg,.png">
                        <?php if (!empty($colab['comprovativo_cc'])): ?>
                            <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_cc']); ?>" target="_blank">Ver comprovativo atual</a>
                        <?php endif; ?>
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
                        <label>IBAN:</label>
                        <input type="text" name="iban" value="<?php echo htmlspecialchars($colab['iban'] ?? ''); ?>">
                        <label style="font-size:12px;">Comprovativo IBAN (PDF/JPG):</label>
                        <input type="file" name="comprovativo_iban" accept=".pdf,.jpg,.jpeg,.png">
                        <?php if (!empty($colab['comprovativo_iban'])): ?>
                            <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_iban']); ?>" target="_blank">Ver comprovativo atual</a>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>

            <!-- Secção de IRS -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#764ba2;">Informações Fiscais (IRS)</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>Situação IRS:</label>
                        <select name="situacao_irs">
                            <option value="">Selecione</option>
                            <option value="Solteiro(a), separado(a) ou divorciado(a), sem dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Solteiro(a), separado(a) ou divorciado(a), sem dependentes') echo 'selected'; ?>>Solteiro(a), separado(a) ou divorciado(a), sem dependentes</option>
                            <option value="Solteiro(a), separado(a) ou divorciado(a), com 1 dependente" <?php if (($colab['situacao_irs'] ?? '') === 'Solteiro(a), separado(a) ou divorciado(a), com 1 dependente') echo 'selected'; ?>>Solteiro(a), separado(a) ou divorciado(a), com 1 dependente</option>
                            <option value="Solteiro(a), separado(a) ou divorciado(a), com 2 ou mais dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Solteiro(a), separado(a) ou divorciado(a), com 2 ou mais dependentes') echo 'selected'; ?>>Solteiro(a), separado(a) ou divorciado(a), com 2 ou mais dependentes</option>
                            <option value="Casado(a), 1 titular, sem dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 1 titular, sem dependentes') echo 'selected'; ?>>Casado(a), 1 titular, sem dependentes</option>
                            <option value="Casado(a), 1 titular, com 1 dependente" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 1 titular, com 1 dependente') echo 'selected'; ?>>Casado(a), 1 titular, com 1 dependente</option>
                            <option value="Casado(a), 1 titular, com 2 ou mais dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 1 titular, com 2 ou mais dependentes') echo 'selected'; ?>>Casado(a), 1 titular, com 2 ou mais dependentes</option>
                            <option value="Casado(a), 2 titulares, sem dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 2 titulares, sem dependentes') echo 'selected'; ?>>Casado(a), 2 titulares, sem dependentes</option>
                            <option value="Casado(a), 2 titulares, com 1 dependente" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 2 titulares, com 1 dependente') echo 'selected'; ?>>Casado(a), 2 titulares, com 1 dependente</option>
                            <option value="Casado(a), 2 titulares, com 2 ou mais dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Casado(a), 2 titulares, com 2 ou mais dependentes') echo 'selected'; ?>>Casado(a), 2 titulares, com 2 ou mais dependentes</option>
                            <option value="Viúvo(a), sem dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Viúvo(a), sem dependentes') echo 'selected'; ?>>Viúvo(a), sem dependentes</option>
                            <option value="Viúvo(a), com 1 dependente" <?php if (($colab['situacao_irs'] ?? '') === 'Viúvo(a), com 1 dependente') echo 'selected'; ?>>Viúvo(a), com 1 dependente</option>
                            <option value="Viúvo(a), com 2 ou mais dependentes" <?php if (($colab['situacao_irs'] ?? '') === 'Viúvo(a), com 2 ou mais dependentes') echo 'selected'; ?>>Viúvo(a), com 2 ou mais dependentes</option>
                        </select>
                    </div>
                    <div class="ficha-campo">
                        <label>Nº Dependentes:</label>
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
                        <label>Ano do Primeiro Desconto:</label>
                        <input type="number" name="primeiro_ano_descontos" value="<?php echo htmlspecialchars($colab['primeiro_ano_descontos'] ?? ''); ?>">
                    </div>
                </div>
            </fieldset>

            <!-- Secção de Contacto de Emergência -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#667eea;">Contacto de Emergência</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>Nome do Contacto:</label>
                        <input type="text" name="nome_contacto_emergencia" value="<?php echo htmlspecialchars($colab['nome_contacto_emergencia'] ?? ''); ?>">
                    </div>
                    <div class="ficha-campo">
                        <label>Grau de Parentesco:</label>
                        <select name="grau_relacionamento">
                            <option value="">Selecione</option>
                            <option value="Pai" <?php if (($colab['grau_relacionamento'] ?? '') === 'Pai') echo 'selected'; ?>>Pai</option>
                            <option value="Mãe" <?php if (($colab['grau_relacionamento'] ?? '') === 'Mãe') echo 'selected'; ?>>Mãe</option>
                            <option value="Filho" <?php if (($colab['grau_relacionamento'] ?? '') === 'Filho') echo 'selected'; ?>>Filho</option>
                            <option value="Filha" <?php if (($colab['grau_relacionamento'] ?? '') === 'Filha') echo 'selected'; ?>>Filha</option>
                            <option value="Irmão" <?php if (($colab['grau_relacionamento'] ?? '') === 'Irmão') echo 'selected'; ?>>Irmão</option>
                            <option value="Irmã" <?php if (($colab['grau_relacionamento'] ?? '') === 'Irmã') echo 'selected'; ?>>Irmã</option>
                            <option value="Avô" <?php if (($colab['grau_relacionamento'] ?? '') === 'Avô') echo 'selected'; ?>>Avô</option>
                            <option value="Avó" <?php if (($colab['grau_relacionamento'] ?? '') === 'Avó') echo 'selected'; ?>>Avó</option>
                            <option value="Neto" <?php if (($colab['grau_relacionamento'] ?? '') === 'Neto') echo 'selected'; ?>>Neto</option>
                            <option value="Neta" <?php if (($colab['grau_relacionamento'] ?? '') === 'Neta') echo 'selected'; ?>>Neta</option>
                            <option value="Tio" <?php if (($colab['grau_relacionamento'] ?? '') === 'Tio') echo 'selected'; ?>>Tio</option>
                            <option value="Tia" <?php if (($colab['grau_relacionamento'] ?? '') === 'Tia') echo 'selected'; ?>>Tia</option>
                            <option value="Sobrinho" <?php if (($colab['grau_relacionamento'] ?? '') === 'Sobrinho') echo 'selected'; ?>>Sobrinho</option>
                            <option value="Sobrinha" <?php if (($colab['grau_relacionamento'] ?? '') === 'Sobrinha') echo 'selected'; ?>>Sobrinha</option>
                            <option value="Primo" <?php if (($colab['grau_relacionamento'] ?? '') === 'Primo') echo 'selected'; ?>>Primo</option>
                            <option value="Prima" <?php if (($colab['grau_relacionamento'] ?? '') === 'Prima') echo 'selected'; ?>>Prima</option>
                            <option value="Cônjuge" <?php if (($colab['grau_relacionamento'] ?? '') === 'Cônjuge') echo 'selected'; ?>>Cônjuge</option>
                            <option value="Companheiro(a)" <?php if (($colab['grau_relacionamento'] ?? '') === 'Companheiro(a)') echo 'selected'; ?>>Companheiro(a)</option>
                            <option value="Outro" <?php if (($colab['grau_relacionamento'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                        </select>
                    </div>
                    <div class="ficha-campo">
                        <label>Número de Contacto:</label>
                        <input type="text" name="contacto_emergencia" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>">
                    </div>
                </div>
            </fieldset>

            <!-- Secção de Vouchers -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#764ba2;">Informações para Vouchers</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>Nº Cartão Continente:</label>
                        <input type="text" name="cartao_continente" value="<?php echo htmlspecialchars($colab['cartao_continente'] ?? ''); ?>">
                        <label style="font-size:12px;">Comprovativo Cartão Continente (PDF/JPG):</label>
                        <input type="file" name="comprovativo_cartao_continente" accept=".pdf,.jpg,.jpeg,.png">
                        <?php if (!empty($colab['comprovativo_cartao_continente'])): ?>
                            <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_cartao_continente']); ?>" target="_blank">Ver comprovativo atual</a>
                        <?php endif; ?>
                    </div>
                    <div class="ficha-campo">
                        <label>Nº Cliente NOS:</label>
                        <input type="text" name="numero_cliente_nos" value="<?php echo htmlspecialchars($colab['numero_cliente_nos'] ?? ''); ?>">
                    </div>
                </div>
            </fieldset>

            <?php if (in_array($perfil, ['rh', 'admin'])): ?>
            <!-- Secção apenas para RH/Admin -->
            <fieldset style="margin-top:24px; border:1px solid #e1e5e9; border-radius:10px; padding:18px;">
                <legend style="font-weight:600; color:#e67e22;">Gestão RH/Admin</legend>
                <div class="ficha-grid">
                    <div class="ficha-campo">
                        <label>Cargo:</label>
                        <select name="cargo">
                            <option value="">Selecione</option>
                            <option value="colaborador" <?php if (($colab['cargo'] ?? '') === 'colaborador') echo 'selected'; ?>>Colaborador</option>
                            <option value="coordenador" <?php if (($colab['cargo'] ?? '') === 'coordenador') echo 'selected'; ?>>Coordenador</option>
                            <option value="rh" <?php if (($colab['cargo'] ?? '') === 'rh') echo 'selected'; ?>>RH</option>
                            <option value="admin" <?php if (($colab['cargo'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="ficha-campo">
                        <label>Nível Hierárquico:</label>
                        <select name="nivel_hierarquico">
                            <option value="">Selecione</option>
                            <option value="1" <?php if (($colab['nivel_hierarquico'] ?? '') === '1') echo 'selected'; ?>>1</option>
                            <option value="2" <?php if (($colab['nivel_hierarquico'] ?? '') === '2') echo 'selected'; ?>>2</option>
                            <option value="3" <?php if (($colab['nivel_hierarquico'] ?? '') === '3') echo 'selected'; ?>>3</option>
                            <option value="4" <?php if (($colab['nivel_hierarquico'] ?? '') === '4') echo 'selected'; ?>>4</option>
                        </select>
                    </div>
                    <div class="ficha-campo">
                        <label>Remuneração:</label>
                        <input type="text" name="remuneracao" value="<?php echo htmlspecialchars($colab['remuneracao'] ?? ''); ?>">
                    </div>
                    <div class="ficha-campo">
                        <label>Nome Abreviado:</label>
                        <input type="text" name="nome_abreviado" value="<?php echo htmlspecialchars($colab['nome_abreviado'] ?? ''); ?>">
                    </div>
                    <div class="ficha-campo">
                        <label>Tipo de Contrato:</label>
                        <select name="tipo_contrato">
                            <option value="">Selecione</option>
                            <option value="estágio curricular" <?php if (($colab['tipo_contrato'] ?? '') === 'estágio curricular') echo 'selected'; ?>>Estágio Curricular</option>
                            <option value="estagio IEFP" <?php if (($colab['tipo_contrato'] ?? '') === 'estagio IEFP') echo 'selected'; ?>>Estágio IEFP</option>
                            <option value="termo certo" <?php if (($colab['tipo_contrato'] ?? '') === 'termo certo') echo 'selected'; ?>>Termo Certo</option>
                            <option value="termo incerto" <?php if (($colab['tipo_contrato'] ?? '') === 'termo incerto') echo 'selected'; ?>>Termo Incerto</option>
                            <option value="sem termo" <?php if (($colab['tipo_contrato'] ?? '') === 'sem termo') echo 'selected'; ?>>Sem Termo</option>
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
                </div>
            </fieldset>
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