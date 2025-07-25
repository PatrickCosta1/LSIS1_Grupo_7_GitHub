<?php
session_start();
$perfil = $_SESSION['profile'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

// Define variáveis de perfil ANTES de usar
$isColab = ($perfil === 'colaborador');
$isCoord = ($perfil === 'coordenador');
$isRH = ($perfil === 'rh');
$isAdmin = ($perfil === 'admin');

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
    // Colaborador só pode ver a própria ficha
    if ($isColab && $editColabId && $editColabId != $userId) {
        header('Location: ../Comuns/erro.php');
        exit();
    }
    // Coordenador pode ver a ficha de outros (adicionar validação de equipa se necessário)
    if ($isCoord && $editColabId) {
        $colab = $colabBLL->getColaboradorById($editColabId);
    } else {
        $colab = $colabBLL->getColaboradorByUserId($userId);
    }
}

// Permissões de edição
$isColab = ($perfil === 'colaborador');
$isCoord = ($perfil === 'coordenador');
$isRH = ($perfil === 'rh');
$isAdmin = ($perfil === 'admin');

// Só pode editar tudo se for RH/Admin
$canEditAll = $isRH || $isAdmin;

$coordenadorViewFields = [
    'num_mecanografico', 'nome', 'apelido', 'data_nascimento', 'nome_abreviado', 'morada_fiscal',
    'sexo', 'nacionalidade', 'habilitacoes', 'curso',
    'nome_contacto_emergencia', 'grau_relacionamento', 'contacto_emergencia',
    'data_inicio_contrato', 'data_fim_contrato'
];

$isOwnFicha = ($editColabId == '' || $editColabId == $userId);

// Lista de campos que colaborador/coordenador pode editar
$colabEditable = [
    'morada_fiscal', 'sexo', 'situacao_irs', 'dependentes', 'iban', 'habilitacoes', 'curso',
    'telemovel', 'matricula_viatura', 'nome_contacto_emergencia', 'grau_relacionamento',
    'contacto_emergencia', 'cartao_continente'
];



// Funções helper para os atributos dos campos
function fieldAttr($field, $canEditAll, $colabEditable) {
    return ($canEditAll || in_array($field, $colabEditable)) ? '' : 'readonly';
}
function selectAttr($field, $canEditAll, $colabEditable) {
    return ($canEditAll || in_array($field, $colabEditable)) ? '' : 'disabled';
}
function fileAttr($field, $canEditAll, $colabEditable) {
    return ($canEditAll || in_array($field, $colabEditable)) ? '' : 'style="display:block;"';
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
    'nome_abreviado' => $_POST['nome_abreviado'] ?? '',
    'num_mecanografico' => $_POST['num_mecanografico'] ?? '',
    'data_nascimento' => $_POST['data_nascimento'] ?? '',
    'morada_fiscal' => $_POST['morada_fiscal'] ?? '',
    'email' => $_POST['email'] ?? '',
    'telemovel' => $_POST['telemovel'] ?? '',
    'sexo' => $_POST['sexo'] ?? '',
    'estado_civil' => $_POST['estado_civil'] ?? '',
    'habilitacoes' => $_POST['habilitacoes'] ?? '',
    'curso' => $_POST['curso'] ?? '',
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
    // Vouchers
    'cartao_continente' => $_POST['cartao_continente'] ?? '',
    'voucher_nos' => $_POST['voucher_nos'] ?? '',
    // Contacto de emergência
    'nome_contacto_emergencia' => $_POST['nome_contacto_emergencia'] ?? '',
    'grau_relacionamento' => $_POST['grau_relacionamento'] ?? '',
    'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
    // Contratual
    'cargo' => $_POST['cargo'] ?? '',
    'nivel_hierarquico' => $_POST['nivel_hierarquico'] ?? '',
    'data_inicio_contrato' => $_POST['data_inicio_contrato'] ?? '',
    'data_fim_contrato' => $_POST['data_fim_contrato'] ?? '',
    'remuneracao' => $_POST['remuneracao'] ?? '',
    'tipo_contrato' => $_POST['tipo_contrato'] ?? '',
    'regime_horario' => $_POST['regime_horario'] ?? '',
    // Comprovativos (ficheiros)
    'comprovativo_morada_fiscal' => $colab['comprovativo_morada_fiscal'] ?? '',
    'comprovativo_cc' => $colab['comprovativo_cc'] ?? '',
    'comprovativo_iban' => $colab['comprovativo_iban'] ?? '',
    'comprovativo_cartao_continente' => $colab['comprovativo_cartao_continente'] ?? ''
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

    // Upload comprovativo Morada Fiscal (Mod. 99)
    if (isset($_FILES['comprovativo_morada_fiscal']) && $_FILES['comprovativo_morada_fiscal']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_morada_fiscal']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_morada_fiscal_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_morada_fiscal']['tmp_name'], $dest)) {
            $dados['comprovativo_morada_fiscal'] = $filename;
        } else {
            $error_message = "Erro ao fazer upload do comprovativo da Morada Fiscal.";
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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/CSS/Colaborador/ficha_colaborador.css">
</head>
<body>
    
<!-- Container azul arredondado no topo -->
<div id="header-hero">
    <div class="header-content">
        <img
            src="../../assets/tlantic-logo2.png"
            alt="Logo Tlantic"
            class="logo-header2"
            <?php if ($perfil === 'colaborador'): ?>
                style="cursor:pointer;" onclick="window.location.href='pagina_inicial_colaborador.php';"
            <?php elseif ($perfil === 'coordenador'): ?> 
                     style="cursor:pointer;" onclick="window.location.href='../Coordenador/pagina_inicial_coordenador.php';"
            <?php elseif ($perfil === 'rh'): ?> 
                     style="cursor:pointer;" onclick="window.location.href='../RH/pagina_inicial_RH.php';"
            <?php endif; ?>
        >
        <nav class="nav-links">
            <?php if ($perfil === 'colaborador'): ?>
                <a href="ficha_colaborador.php">A Minha Ficha</a>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <div class="dropdown-perfil">
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                        <span class="seta-baixo">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="ficha_colaborador.php">Ficha Colaborador</a>
                        <a href="beneficios.php">Benefícios</a>
                        <a href="ferias.php">Férias</a>
                        <a href="formacoes.php">Formações</a>
                        <a href="recibos.php">Recibos</a>
                    </div>
                </div>
                <a href="../Comuns/logout.php">Sair</a>
            <?php elseif ($perfil === 'coordenador'): ?>
                <?php
                    // Corrigir link da equipa para incluir o id da equipa do coordenador
                    require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';
                    $coordBLL = new CoordenadorDashboardManager();
                    $equipas = $coordBLL->getEquipasByCoordenador($_SESSION['user_id']);
                    $equipaLink = "../Coordenador/equipa.php";
                    if (!empty($equipas) && isset($equipas[0]['id'])) {
                        $equipaLink = "../Coordenador/equipa.php?id=" . urlencode($equipas[0]['id']);
                    }
                ?>
                <div class="dropdown-equipa">
                    <a href="<?php echo $equipaLink; ?>" class="equipa-link">
                        Equipa
                        <span class="seta-baixo">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Coordenador/dashboard_coordenador.php">Dashboard</a>
                        <a href="../Coordenador/relatorios_equipa.php">Relatórios Equipa</a>
                    </div>
                </div>
                <a href="../Comuns/notificacoes.php">Notificações</a>
                <div class="dropdown-perfil">
                    <a href="../Comuns/perfil.php" class="perfil-link">
                        Perfil
                        <span class="seta-baixo">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="../Colaborador/ficha_colaborador.php">Ficha Colaborador</a>
                        <a href="../Colaborador/beneficios.php">Benefícios</a>
                        <a href="../Colaborador/ferias.php">Férias</a>
                        <a href="../Colaborador/formacoes.php">Formações</a>
                        <a href="../Colaborador/recibos.php">Recibos</a>
                        <!-- Adiciona mais opções se quiseres -->
                    </div>
                </div>
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
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php endif; ?>
        </nav>
    </div>
    
</div>

<!-- Barra sticky branca (aparece ao fazer scroll) -->
<div id="header-sticky">
    <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header-small logo-sticky-branco">
    <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header-small logo-sticky-azul">
    <nav class="nav-links">
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
            <a href="../Admin/utilizadores.php">Utilizadores</a>
            <a href="../Admin/permissoes.php">Permissões</a>
            <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
            <a href="../Admin/alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        <?php else: ?>
            <a href="dashboard_colaborador.php">Dashboard</a>
            <a href="ficha_colaborador.php">A Minha Ficha</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        <?php endif; ?>
    </nav>
</div>

<!-- Menu lateral (mantém como está) -->
<div class="menu-lateral-fichas">
    <button type="button" class="menu-link active" data-scroll="#ficha-dados-pessoais">Dados Pessoais</button>
    <button type="button" class="menu-link" data-scroll="#ficha-morada">Morada</button>
    <button type="button" class="menu-link" data-scroll="#ficha-documentos">Documentos</button>
    <button type="button" class="menu-link" data-scroll="#ficha-fiscais">Informações Fiscais</button>
    <button type="button" class="menu-link" data-scroll="#ficha-vouchers">Informações Vouchers</button>
    <button type="button" class="menu-link" data-scroll="#ficha-emergencia">Contacto Emergência</button>
    <button type="button" class="menu-link" data-scroll="#ficha-contratual">Situação Contratual</button>
</div>

<main>
<form method="POST" enctype="multipart/form-data">

<div id="ficha-dados-pessoais" class="ficha-container ficha-container-primarios">
    <div class="ficha-section-titulo">Dados Pessoais</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
            <!-- Todos os campos para RH/Admin, colaborador ou a própria ficha -->
            <div class="ficha-campo">
                <label>Primeiro Nome:</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>" <?php echo fieldAttr('nome', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Apelido:</label>
                <input type="text" name="apelido" value="<?php echo htmlspecialchars($colab['apelido'] ?? ''); ?>" <?php echo fieldAttr('apelido', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Nome Abreviado:</label>
                <input type="text" name="nome_abreviado" value="<?php echo htmlspecialchars($colab['nome_abreviado'] ?? ''); ?>" <?php echo fieldAttr('nome_abreviado', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Nº Mecanográfico:</label>
                <input type="text" name="num_mecanografico" value="<?php echo htmlspecialchars($colab['num_mecanografico'] ?? ''); ?>" <?php echo fieldAttr('num_mecanografico', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Data de Nascimento:</label>
                <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>" <?php echo fieldAttr('data_nascimento', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Morada Fiscal:</label>
                <input type="text" name="morada_fiscal" value="<?php echo htmlspecialchars($colab['morada_fiscal'] ?? ''); ?>" <?php echo fieldAttr('morada_fiscal', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Email Pessoal:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>" <?php echo fieldAttr('email', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Nº Telemóvel:</label>
                <input type="text" name="telemovel" value="<?php echo htmlspecialchars($colab['telemovel'] ?? ''); ?>" <?php echo fieldAttr('telemovel', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label style="font-size:12px;">Comprovativo Morada Fiscal (Mod. 99) (PDF/JPG):</label>
                <?php if ($canEditAll || in_array('morada_fiscal', $colabEditable)): ?>
                    <input type="file" name="comprovativo_morada_fiscal" accept=".pdf,.jpg,.jpeg,.png">
                <?php endif; ?>
                <?php if (!empty($colab['comprovativo_morada_fiscal'])): ?>
                    <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_morada_fiscal']); ?>" target="_blank">Ver comprovativo atual</a>
                <?php endif; ?>
            </div>
            <div class="ficha-campo">
                <label>Género:</label>
                <select name="sexo" <?php echo selectAttr('sexo', $canEditAll, $colabEditable); ?>>
                    <option value="">Selecione</option>
                    <option value="Masculino" <?php if (($colab['sexo'] ?? '') === 'Masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="Feminino" <?php if (($colab['sexo'] ?? '') === 'Feminino') echo 'selected'; ?>>Feminino</option>
                    <option value="Outro" <?php if (($colab['sexo'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                </select>
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
            </div>
            <div class="ficha-campo">
                <label>Habilitações Literárias:</label>
                <select name="habilitacoes" <?php echo selectAttr('habilitacoes', $canEditAll, $colabEditable); ?>>
                    <option value="">Selecione</option>
                    <option value="12º ano" <?php if (($colab['habilitacoes'] ?? '') === '12º ano') echo 'selected'; ?>>12º ano</option>
                    <option value="Licenciatura" <?php if (($colab['habilitacoes'] ?? '') === 'Licenciatura') echo 'selected'; ?>>Licenciatura</option>
                    <option value="Mestrado" <?php if (($colab['habilitacoes'] ?? '') === 'Mestrado') echo 'selected'; ?>>Mestrado</option>
                    <option value="Outro" <?php if (($colab['habilitacoes'] ?? '') === 'Outro') echo 'selected'; ?>>Outro</option>
                </select>
            </div>
            <div class="ficha-campo">
                <label>Curso:</label>
                <input type="text" name="curso" value="<?php echo htmlspecialchars($colab['curso'] ?? ''); ?>" <?php echo fieldAttr('curso', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Matrícula do Carro:</label>
                <input type="text" name="matricula_viatura" value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>" <?php echo fieldAttr('matricula_viatura', $canEditAll, $colabEditable); ?>>
            </div>
        <?php elseif ($isCoord): ?>
            <!-- Apenas campos permitidos ao coordenador quando vê ficha de outro -->
            <div class="ficha-campo">
                <label>Nº Mecanográfico:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['num_mecanografico'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Primeiro Nome:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['nome'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Apelido:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['apelido'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Data de Nascimento:</label>
                <input type="date" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Nome Abreviado:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['nome_abreviado'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Morada Fiscal:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['morada_fiscal'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Género:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['sexo'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Nacionalidade:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['nacionalidade'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Habilitações Literárias:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['habilitacoes'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Curso:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['curso'] ?? ''); ?>" readonly>
            </div>
        <?php endif; ?>
    </div>
</div>



      <!-- Ficha Morada -->

<!-- Ficha Morada -->
<div id="ficha-morada" class="ficha-container">
    <div class="ficha-section-titulo">Morada</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
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
        <?php elseif ($isCoord): ?>
            <div class="ficha-campo">
                <label>Endereço:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['morada'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Localidade:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['localidade'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Código Postal:</label>
                <input type="text" value="<?php echo htmlspecialchars($colab['codigo_postal'] ?? ''); ?>" readonly>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ficha Documentos -->
<div id="ficha-documentos" class="ficha-container">
    <div class="ficha-section-titulo">Documentos</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
            <div class="ficha-campo">
                <label>CC (Cartão de Cidadão):</label>
                <input type="text" name="cc" value="<?php echo htmlspecialchars($colab['cc'] ?? ''); ?>" <?php echo fieldAttr('cc', $canEditAll, []); ?>>
                <label style="font-size:12px;">Comprovativo CC (PDF/JPG):</label>
                <input type="file" name="comprovativo_cc" accept=".pdf,.jpg,.jpeg,.png">
                <?php if (!empty($colab['comprovativo_cc'])): ?>
                    <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_cc']); ?>" target="_blank">Ver comprovativo atual</a>
                <?php endif; ?>
            </div>
            <div class="ficha-campo">
                <label>NIF:</label>
                <input type="text" name="nif" value="<?php echo htmlspecialchars($colab['nif'] ?? ''); ?>" <?php echo fieldAttr('nif', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>NISS:</label>
                <input type="text" name="niss" value="<?php echo htmlspecialchars($colab['niss'] ?? ''); ?>" <?php echo fieldAttr('niss', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>IBAN:</label>
                <input type="text" name="iban" value="<?php echo htmlspecialchars($colab['iban'] ?? ''); ?>" <?php echo fieldAttr('iban', $canEditAll, ['iban']); ?>>
                <label style="font-size:12px;">Comprovativo IBAN (PDF/JPG):</label>
                <input type="file" name="comprovativo_iban" accept=".pdf,.jpg,.jpeg,.png">
                <?php if (!empty($colab['comprovativo_iban'])): ?>
                    <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_iban']); ?>" target="_blank">Ver comprovativo atual</a>
                <?php endif; ?>
            </div>
        <?php elseif ($isCoord): ?>
            <!-- Coordenador NÃO vê esta secção na ficha de outro colaborador -->
            <div class="ficha-campo" style="color:#888; font-style:italic;">
                Apenas os Recursos Humanos e o próprio colaborador podem visualizar informações fiscais.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ficha Informações Fiscais -->
<div id="ficha-fiscais" class="ficha-container">
    <div class="ficha-section-titulo">Informações Fiscais (IRS)</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
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
        <?php elseif ($isCoord): ?>
            <!-- Coordenador NÃO vê esta secção na ficha de outro colaborador -->
            <div class="ficha-campo" style="color:#888; font-style:italic;">
                Apenas os Recursos Humanos e o próprio colaborador podem visualizar informações fiscais.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ficha Vouchers -->
<div id="ficha-vouchers" class="ficha-container">
    <div class="ficha-section-titulo">Informações Vouchers</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
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
                <label>Voucher NOS (Data):</label>
                <input type="date" name="voucher_nos" value="<?php echo htmlspecialchars($colab['voucher_nos'] ?? ''); ?>" <?php echo fieldAttr('voucher_nos', $canEditAll, []); ?>>
            </div>
        <?php elseif ($isCoord): ?>
            <!-- Coordenador NÃO vê esta secção na ficha de outro colaborador -->
            <div class="ficha-campo" style="color:#888; font-style:italic;">
                Apenas os Recursos Humanos e o próprio colaborador podem visualizar informações fiscais.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ficha Contacto Emergência -->
<div id="ficha-emergencia" class="ficha-container">
    <div class="ficha-section-titulo">Contacto de Emergência</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha || $isCoord): ?>
            <div class="ficha-campo">
                <label>Nome do Contacto:</label>
                <input type="text" name="nome_contacto_emergencia" value="<?php echo htmlspecialchars($colab['nome_contacto_emergencia'] ?? ''); ?>" <?php echo ($isCoord && !$isOwnFicha && !$canEditAll) ? 'readonly' : ''; ?>>
            </div>
            <div class="ficha-campo">
                <label>Grau de Parentesco:</label>
                <select name="grau_relacionamento" <?php echo ($isCoord && !$isOwnFicha && !$canEditAll) ? 'disabled' : ''; ?>>
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
                <input type="text" name="contacto_emergencia" value="<?php echo htmlspecialchars($colab['contacto_emergencia'] ?? ''); ?>" <?php echo ($isCoord && !$isOwnFicha && !$canEditAll) ? 'readonly' : ''; ?>>
            </div>
        <?php endif; ?>
    </div>
</div>

 <!-- Ficha Situação Contratual -->
<div id="ficha-contratual" class="ficha-container ficha-container-contratual">
    <div class="ficha-section-titulo">Situação Contratual</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
            <div class="ficha-campo">
                <label>Cargo:</label>
                <input type="text" name="cargo" value="<?php echo htmlspecialchars($colab['cargo'] ?? ''); ?>" <?php echo fieldAttr('cargo', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Data de Início do Contrato:</label>
                <input type="date" name="data_inicio_contrato" value="<?php echo htmlspecialchars($colab['data_inicio_contrato'] ?? ''); ?>" <?php echo fieldAttr('data_inicio_contrato', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Data de Fim do Contrato:</label>
                <input type="date" name="data_fim_contrato" value="<?php echo htmlspecialchars($colab['data_fim_contrato'] ?? ''); ?>" <?php echo fieldAttr('data_fim_contrato', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Remuneração:</label>
                <input type="text" name="remuneracao" value="<?php echo htmlspecialchars($colab['remuneracao'] ?? ''); ?>" <?php echo fieldAttr('remuneracao', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Tipo de Contrato:</label>
                <select name="tipo_contrato" <?php echo selectAttr('tipo_contrato', $canEditAll, []); ?>>
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
                <select name="regime_horario" <?php echo selectAttr('regime_horario', $canEditAll, []); ?>>
                    <option value="">Selecione</option>
                    <option value="10%" <?php if (($colab['regime_horario'] ?? '') === '10%') echo 'selected'; ?>>10%</option>
                    <option value="20%" <?php if (($colab['regime_horario'] ?? '') === '20%') echo 'selected'; ?>>20%</option>
                    <option value="50%" <?php if (($colab['regime_horario'] ?? '') === '50%') echo 'selected'; ?>>50%</option>
                    <option value="100%" <?php if (($colab['regime_horario'] ?? '') === '100%') echo 'selected'; ?>>100%</option>
                </select>
            </div>
        <?php elseif ($isCoord): ?>
            <div class="ficha-campo">
                <label>Data de Início do Contrato:</label>
                <input type="date" value="<?php echo htmlspecialchars($colab['data_inicio_contrato'] ?? ''); ?>" readonly>
            </div>
            <div class="ficha-campo">
                <label>Data de Fim do Contrato:</label>
                <input type="date" value="<?php echo htmlspecialchars($colab['data_fim_contrato'] ?? ''); ?>" readonly>
            </div>
        <?php endif; ?>
    </div>
</div>
       

            <div id="btn-flutuante-guardar">
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
    <script>
    window.addEventListener('scroll', function() {
        const sticky = document.getElementById('header-sticky');
        const hero = document.getElementById('header-hero');
        if (window.scrollY > (hero.offsetHeight - 1)) {
            sticky.style.display = 'flex';
        } else {
            sticky.style.display = 'none';
        }
    });
    </script>

    <script>
    document.querySelectorAll('.menu-link').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.menu-link').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const target = document.querySelector(this.getAttribute('data-scroll'));
            if (target) {
                const targetRect = target.getBoundingClientRect();
                const scrollTo = window.scrollY + targetRect.top - (window.innerHeight / 2) + (targetRect.height / 2);
                window.scrollTo({
                    top: scrollTo,
                    behavior: 'smooth'
                });
            }
        });
    });
    // Highlight automático ao fazer scroll
    window.addEventListener('scroll', function() {
        const sections = [
            '#ficha-dados-pessoais',
            '#ficha-morada',
            '#ficha-documentos',
            '#ficha-fiscais',
            '#ficha-vouchers',
            '#ficha-emergencia',
            '#ficha-contratual'
        ];
        let scrollPos = window.scrollY + window.innerHeight / 2;
        let active = 0;
        sections.forEach((id, idx) => {
            const el = document.querySelector(id);
            if (el && (el.offsetTop <= scrollPos)) active = idx;
        });
        document.querySelectorAll('.menu-link').forEach((b, i) => {
            b.classList.toggle('active', i === active);
        });
    });
    </script>


<script>
window.addEventListener('scroll', function() {
    const sticky = document.getElementById('header-sticky');
    const hero = document.getElementById('header-hero');
    if (window.scrollY > 0) {
        sticky.style.display = 'flex';
        // Muda para azul quando passa o container azul
        if (window.scrollY > (hero.offsetHeight - 1)) {
            sticky.classList.add('azul');
        } else {
            sticky.classList.remove('azul');
        }
    } else {
        sticky.style.display = 'none';
        sticky.classList.remove('azul');
    }
});
</script>


</body>


</html>