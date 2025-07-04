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
    'morada_fiscal', 'sexo', 'situacao_irs', 'dependentes', 'iban', 'habilitacoes', 'curso','estado_civil',
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

    // Adicionar id do colaborador ao array de dados se RH/Admin estiver a editar outro colaborador
    if (($isRH || $isAdmin) && $editColabId) {
        $dados['id'] = $editColabId;
    }

    // Array para controlar quais comprovativos foram alterados
    $comprovantivosPedidos = [];

    // Upload comprovativo CC
    if (isset($_FILES['comprovativo_cc']) && $_FILES['comprovativo_cc']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_cc']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_cc_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_cc']['tmp_name'], $dest)) {
            $comprovantivosPedidos[] = [
                'tipo' => 'comprovativo_cc',
                'antigo' => $colab['comprovativo_cc'] ?? null,
                'novo' => $filename
            ];
        }
    }

    // Upload comprovativo IBAN
    if (isset($_FILES['comprovativo_iban']) && $_FILES['comprovativo_iban']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_iban']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_iban_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_iban']['tmp_name'], $dest)) {
            $comprovantivosPedidos[] = [
                'tipo' => 'comprovativo_iban',
                'antigo' => $colab['comprovativo_iban'] ?? null,
                'novo' => $filename
            ];
        }
    }

    // Upload comprovativo Estado Civil
    if (isset($_FILES['comprovativo_estado_civil']) && $_FILES['comprovativo_estado_civil']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_estado_civil']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_estado_civil_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_estado_civil']['tmp_name'], $dest)) {
            $comprovantivosPedidos[] = [
                'tipo' => 'comprovativo_estado_civil',
                'antigo' => $colab['comprovativo_estado_civil'] ?? null,
                'novo' => $filename
            ];
        }
    }

    // Upload comprovativo Morada Fiscal
    if (isset($_FILES['comprovativo_morada_fiscal']) && $_FILES['comprovativo_morada_fiscal']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_morada_fiscal']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_morada_fiscal_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_morada_fiscal']['tmp_name'], $dest)) {
            $comprovantivosPedidos[] = [
                'tipo' => 'comprovativo_morada_fiscal',
                'antigo' => $colab['comprovativo_morada_fiscal'] ?? null,
                'novo' => $filename
            ];
        }
    }

    // Upload comprovativo Cartão Continente
    if (isset($_FILES['comprovativo_cartao_continente']) && $_FILES['comprovativo_cartao_continente']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['comprovativo_cartao_continente']['name'], PATHINFO_EXTENSION);
        $filename = 'comprovativo_cartao_continente_' . $userId . '_' . time() . '.' . $ext;
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['comprovativo_cartao_continente']['tmp_name'], $dest)) {
            $comprovantivosPedidos[] = [
                'tipo' => 'comprovativo_cartao_continente',
                'antigo' => $colab['comprovativo_cartao_continente'] ?? null,
                'novo' => $filename
            ];
        }
    }

    // Processar pedidos de comprovativos
    if (!empty($comprovantivosPedidos) && !$canEditAll) {
        foreach ($comprovantivosPedidos as $comprovativo) {
            $colabBLL->criarPedidoComprovativo(
                $colab['id'],
                $comprovativo['tipo'],
                $comprovativo['antigo'],
                $comprovativo['novo']
            );
        }
        
        // Notificar RH sobre os comprovativos
        require_once '../../BLL/Comuns/BLL_notificacoes.php';
        $notBLL = new NotificacoesManager();
        $nomeColab = $colab['nome'] ?? '';
        $tipos = array_column($comprovantivosPedidos, 'tipo');
        $tiposTexto = implode(', ', $tipos);
        $notBLL->notificarRH("O colaborador $nomeColab enviou novos comprovativos ($tiposTexto) para aprovação.");
        
        $success_message = "Os seus comprovativos foram enviados para aprovação pelo RH.";
    } elseif (!empty($comprovantivosPedidos) && $canEditAll) {
        // RH/Admin pode aprovar diretamente
        foreach ($comprovantivosPedidos as $comprovativo) {
            $dados[$comprovativo['tipo']] = $comprovativo['novo'];
        }
        $success_message = "Comprovativos atualizados com sucesso!";
    }

    // Continuar com o resto da lógica de atualização...
    if ($colabBLL->updateColaboradorByUserId($targetUserId, $dados, $perfil)) {
        if ($canEditAll) {
            $success_message = "Dados atualizados com sucesso!";
        } else {
            // Notificar RH
            require_once '../../BLL/Comuns/BLL_notificacoes.php';
            $notBLL = new NotificacoesManager();
            if (in_array($perfil, ['rh', 'admin']) && $editColabId) {
                $colab = $colabBLL->getColaboradorById($editColabId);
            } else {
                $colab = $colabBLL->getColaboradorByUserId($userId);
            }
            $nomeColab = $colab['nome'] ?? '';
            $notBLL->notificarRH("O colaborador $nomeColab solicitou alteração de dados na ficha. Acesse a área de aprovações.");
            // Mensagem para popup
            $success_message = "O seu pedido de alteração foi enviado e será analisado pelo RH. Em breve terá uma resposta.";
        }
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
    
<!-- Container laranja arredondado no topo -->
<div id="header-hero">
    <div class="header-content">
        <img
            src="../../assets/tlantic-logo2.png"
            alt="Logo Tlantic"
            class="logo-header2"
            <?php if ($perfil === 'colaborador'): ?>
                onclick="window.location.href='pagina_inicial_colaborador.php';"
            <?php elseif ($perfil === 'coordenador'): ?> 
                     onclick="window.location.href='../Coordenador/pagina_inicial_coordenador.php';"
            <?php elseif ($perfil === 'rh'): ?> 
                     onclick="window.location.href='../RH/pagina_inicial_RH.php';"
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
                    <div class="dropdown-equipas">
                <a href="../RH/equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/relatorios.php">Relatórios</a>
                    <a href="../RH/dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="../RH/colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../RH/gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="../RH/gerir_formacoes.php">Gerir Formações</a>
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
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>
        <?php elseif ($perfil === 'rh'): ?>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
            <a href="../RH/equipas.php">Equipas</a>
            <a href="../RH/relatorios.php">Relatórios</a>
            <a href="../RH/exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/logout.php">Sair</a>
        <?php elseif ($perfil === 'admin'): ?>
            <a href="../Admin/utilizadores.php">Utilizadores</a>
            <a href="../Admin/permissoes.php">Permissões</a>
            <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
            <a href="../Admin/alertas.php">Alertas</a>
            <a href="../RH/colaboradores_gerir.php">Colaboradores</a>
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
<div class="portal-brand">
    <div class="color-bar">
        <div class="color-segment"></div>
        <div class="color-segment"></div>
        <div class="color-segment"></div>
    </div>
    <span class="portal-text">Portal Do Colaborador</span>
</div>
<form method="POST" enctype="multipart/form-data">
    <?php if (($isRH || $isAdmin) && $editColabId): ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editColabId); ?>">
    <?php endif; ?>
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
                <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($colab['data_nascimento'] ?? ''); ?>" <?php echo fieldAttr('data_nascimento', $canEditAll, $colabEditable); ?> max="2024-12-31">
            </div>
            <div class="ficha-campo">
                <label>Email Pessoal:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($colab['email'] ?? ''); ?>" <?php echo fieldAttr('email', $canEditAll, $colabEditable); ?>>
            </div>
            <div class="ficha-campo">
                <label>Nº Telemóvel:</label>
                <div class="telemovel-container">
                    <?php
                    // Separar DDI e número para mostrar corretamente
                    $telemovelCompleto = $colab['telemovel'] ?? '';
                    $ddi = '+351';
                    $numeroTel = '';
                    if ($telemovelCompleto) {
                        if (preg_match('/^(\+\d{2,3})(\d{9})$/', $telemovelCompleto, $m)) {
                            $ddi = $m[1];
                            $numeroTel = $m[2];
                        } elseif (preg_match('/^(\+\d{2,3})(\d{3,9})$/', $telemovelCompleto, $m)) {
                            $ddi = $m[1];
                            $numeroTel = $m[2];
                        } else {
                            // fallback: se não corresponder ao padrão, tenta separar os primeiros 4 caracteres
                            $ddi = substr($telemovelCompleto, 0, 4);
                            $numeroTel = substr($telemovelCompleto, 4);
                        }
                    }
                    ?>
                    <select name="ddi_telemovel" class="ddi-select" <?php echo selectAttr('telemovel', $canEditAll, $colabEditable); ?>>
                        <option value="+351" <?php if ($ddi === '+351') echo 'selected'; ?>>+351</option>
                        <option value="+34" <?php if ($ddi === '+34') echo 'selected'; ?>>+34</option>
                        <option value="+33" <?php if ($ddi === '+33') echo 'selected'; ?>>+33</option>
                        <option value="+44" <?php if ($ddi === '+44') echo 'selected'; ?>>+44</option>
                        <option value="+49" <?php if ($ddi === '+49') echo 'selected'; ?>>+49</option>
                    </select>
                    <input type="tel" name="numero_telemovel" pattern="[0-9]{9}" maxlength="9" placeholder="9 dígitos"
                           value="<?php echo htmlspecialchars($numeroTel); ?>"
                           <?php echo fieldAttr('telemovel', $canEditAll, $colabEditable); ?>>
                </div>
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
                <input type="text" name="matricula_viatura" pattern="[A-Z0-9]{6}" maxlength="6" placeholder="6 caracteres" class="matricula-input"
                       value="<?php echo htmlspecialchars($colab['matricula_viatura'] ?? ''); ?>" <?php echo fieldAttr('matricula_viatura', $canEditAll, $colabEditable); ?>>
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
                <div class="cc-container">
                    <input type="text" name="cc_numero" pattern="[0-9]{8}" maxlength="8" placeholder="8 números" 
                           value="<?php echo htmlspecialchars(substr($colab['cc'] ?? '', 0, 8)); ?>" 
                           <?php echo fieldAttr('cc', $canEditAll, []); ?>>
                    <input type="text" name="cc_verificacao" pattern="[0-9A-Z]{4}" maxlength="4" placeholder="4 caracteres" 
                           class="cc-verificacao" 
                           value="<?php echo htmlspecialchars(substr($colab['cc'] ?? '', 8, 4)); ?>" 
                           <?php echo fieldAttr('cc', $canEditAll, []); ?>>
                </div>
                <div class="comprovativo-section">
                    <label class="comprovativo-label">Comprovativo CC (PDF/JPG):</label>
                    <input type="file" name="comprovativo_cc" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($colab['comprovativo_cc'])): ?>
                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_cc']); ?>" target="_blank" class="comprovativo-link">Ver comprovativo atual</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ficha-campo">
                <label>NISS:</label>
                <input type="text" name="niss" pattern="[0-9]{11}" maxlength="11" placeholder="11 números" 
                       value="<?php echo htmlspecialchars($colab['niss'] ?? ''); ?>" <?php echo fieldAttr('niss', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>IBAN:</label>
                <div class="iban-container">
                    <input type="text" name="iban_pais" pattern="[A-Z]{2}" maxlength="2" placeholder="PT" 
                           class="iban-pais" 
                           value="<?php echo htmlspecialchars(substr($colab['iban'] ?? '', 0, 2)); ?>" 
                           <?php echo fieldAttr('iban', $canEditAll, ['iban']); ?>>
                    <input type="text" name="iban_numeros" pattern="[0-9]{21}" maxlength="21" placeholder="21 dígitos" 
                           value="<?php echo htmlspecialchars(substr($colab['iban'] ?? '', 2, 21)); ?>" 
                           <?php echo fieldAttr('iban', $canEditAll, ['iban']); ?>>
                </div>
                <div class="comprovativo-section">
                    <label class="comprovativo-label">Comprovativo IBAN (PDF/JPG):</label>
                    <input type="file" name="comprovativo_iban" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($colab['comprovativo_iban'])): ?>
                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_iban']); ?>" target="_blank" class="comprovativo-link">Ver comprovativo atual</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($isCoord): ?>
            <!-- Coordenador NÃO vê esta secção na ficha de outro colaborador -->
            <div class="ficha-campo" class="colaborador-vazio">
                Apenas os Recursos Humanos e o próprio colaborador podem visualizar informações fiscais.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ficha Informações Fiscais -->
<div id="ficha-fiscais" class="ficha-container">
    <div class="ficha-section-titulo">Informações Fiscais</div>
    <div class="ficha-grid">
        <?php if ($canEditAll || $isColab || $isOwnFicha): ?>
            <!-- Campos movidos para esta seção -->
            <div class="ficha-campo">
                <label>Morada Fiscal:</label>
                <input type="text" name="morada_fiscal" value="<?php echo htmlspecialchars($colab['morada_fiscal'] ?? ''); ?>" 
                       pattern="[^º]*" title="Não é permitido o símbolo º" 
                       <?php echo fieldAttr('morada_fiscal', $canEditAll, $colabEditable); ?>>
                <div class="comprovativo-section">
                    <label class="comprovativo-label">Comprovativo (Mod. 99) (PDF):</label>
                    <?php if ($canEditAll || in_array('morada_fiscal', $colabEditable)): ?>
                        <input type="file" name="comprovativo_morada_fiscal" accept=".pdf,.jpg,.jpeg,.png">
                    <?php endif; ?>
                    <?php if (!empty($colab['comprovativo_morada_fiscal'])): ?>
                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_morada_fiscal']); ?>" target="_blank" class="comprovativo-link">Ver comprovativo atual</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ficha-campo">
                <label>Estado Civil:</label>
                <select name="estado_civil" <?php echo selectAttr('estado_civil', $canEditAll, $colabEditable); ?>>
                    <option value="">Selecione</option>
                    <option value="Solteiro" <?php if (($colab['estado_civil'] ?? '') === 'Solteiro') echo 'selected'; ?>>Solteiro</option>
                    <option value="Casado" <?php if (($colab['estado_civil'] ?? '') === 'Casado') echo 'selected'; ?>>Casado</option>
                    <option value="Divorciado" <?php if (($colab['estado_civil'] ?? '') === 'Divorciado') echo 'selected'; ?>>Divorciado</option>
                    <option value="Viúvo" <?php if (($colab['estado_civil'] ?? '') === 'Viúvo') echo 'selected'; ?>>Viúvo</option>
                </select>
            </div>
            <div class="ficha-campo">
                <label>NIF:</label>
                <input type="text" name="nif" pattern="[0-9]{9}" maxlength="9" placeholder="9 números" 
                       value="<?php echo htmlspecialchars($colab['nif'] ?? ''); ?>" <?php echo fieldAttr('nif', $canEditAll, []); ?>>
            </div>
            <!-- Campos originais da seção fiscal -->
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
                <input type="number" name="dependentes" min="0" max="20" 
                       value="<?php echo htmlspecialchars($colab['dependentes'] ?? ''); ?>">
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
            <div class="ficha-campo" class="colaborador-vazio">
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
                <div class="comprovativo-section">
                    <label class="comprovativo-label">Comprovativo Cartão Continente (PDF/JPG):</label>
                    <input type="file" name="comprovativo_cartao_continente" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($colab['comprovativo_cartao_continente'])): ?>
                        <a href="../../Uploads/comprovativos/<?php echo htmlspecialchars($colab['comprovativo_cartao_continente']); ?>" target="_blank" class="comprovativo-link">Ver comprovativo atual</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ficha-campo">
                <label>Voucher NOS (Data):</label>
                <input type="date" name="voucher_nos" value="<?php echo htmlspecialchars($colab['voucher_nos'] ?? ''); ?>" 
                       title="Data da próxima emissão" <?php echo fieldAttr('voucher_nos', $canEditAll, []); ?> max="2024-12-31">
            </div>
        <?php elseif ($isCoord): ?>
            <!-- Coordenador NÃO vê esta secção na ficha de outro colaborador -->
            <div class="ficha-campo" class="colaborador-vazio">
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
                    <option value="Companheiro(a)" <?php if (($colab['grau_relacionamento'] ?? '') === 'Companheiro(a)') echo 'selected'; ?>>Companheiro(a)</option>
                    <option value="Pai" <?php if (($colab['grau_relacionamento'] ?? '') === 'Pai') echo 'selected'; ?>>Pai</option>
                    <option value="Mãe" <?php if (($colab['grau_relacionamento'] ?? '') === 'Mãe') echo 'selected'; ?>>Mãe</option>
                    <option value="Irmão" <?php if (($colab['grau_relacionamento'] ?? '') === 'Irmão') echo 'selected'; ?>>Irmão</option>
                    <option value="Irmã" <?php if (($colab['grau_relacionamento'] ?? '') === 'Irmã') echo 'selected'; ?>>Irmã</option>
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
                <input type="date" name="data_inicio_contrato" value="<?php echo htmlspecialchars($colab['data_inicio_contrato'] ?? ''); ?>" 
                       max="<?php echo date('Y-m-d'); ?>" <?php echo fieldAttr('data_inicio_contrato', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Data de Fim do Contrato:</label>
                <input type="date" name="data_fim_contrato" value="<?php echo htmlspecialchars($colab['data_fim_contrato'] ?? ''); ?>" 
                       min="<?php echo date('Y-m-d'); ?>" <?php echo fieldAttr('data_fim_contrato', $canEditAll, []); ?>>
            </div>
            <div class="ficha-campo">
                <label>Remuneração:</label>
                <div class="remuneracao-container">
                    <span class="euro-symbol">€</span>
                    <input type="number" name="remuneracao" step="0.01" min="0" placeholder="0.00" 
                           value="<?php echo htmlspecialchars(str_replace('€', '', $colab['remuneracao'] ?? '')); ?>" 
                           <?php echo fieldAttr('remuneracao', $canEditAll, []); ?>>
                </div>
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
          background: linear-gradient(135deg,rgb(255, 203, 120) 0%,rgb(251, 155, 0) 100%);
          color:rgb(255, 255, 255);
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

<?php if ($success_message): ?>
<div id="popup-success" class="popup-overlay">
    <div class="popup-content">
        <button onclick="document.getElementById('popup-success').style.display='none';" class="popup-close">&times;</button>
        <div class="popup-message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
        <div class="popup-actions">
            <button onclick="document.getElementById('popup-success').style.display='none';" class="popup-btn">Fechar</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Menu lateral hover functionality
document.addEventListener('DOMContentLoaded', function() {
    const menuLateral = document.querySelector('.menu-lateral-fichas');
    
    // Criar zona de trigger invisível
    const triggerZone = document.createElement('div');
    triggerZone.className = 'menu-trigger-zone';
    document.body.appendChild(triggerZone);
    
    let hoverTimeout;
    
    // Função para mostrar o menu
    function showMenu() {
        clearTimeout(hoverTimeout);
        menuLateral.classList.add('show');
    }
    
    // Função para esconder o menu (com delay)
    function hideMenu() {
        hoverTimeout = setTimeout(() => {
            menuLateral.classList.remove('show');
        }, 200); // Delay para evitar flicker
    }
    
    // Event listeners para a zona de trigger
    triggerZone.addEventListener('mouseenter', showMenu);
    triggerZone.addEventListener('mouseleave', hideMenu);
    
    // Event listeners para o próprio menu
    menuLateral.addEventListener('mouseenter', showMenu);
    menuLateral.addEventListener('mouseleave', hideMenu);
    
    // Esconder o menu quando o rato sai completamente da área
    document.addEventListener('mousemove', function(e) {
        const menuRect = menuLateral.getBoundingClientRect();
        const triggerRect = triggerZone.getBoundingClientRect();
        
        const isOverMenu = (
            e.clientX >= menuRect.left && 
            e.clientX <= menuRect.right && 
            e.clientY >= menuRect.top && 
            e.clientY <= menuRect.bottom
        );
        
        const isOverTrigger = (
            e.clientX >= triggerRect.left && 
            e.clientX <= triggerRect.right && 
            e.clientY >= triggerRect.top && 
            e.clientY <= triggerRect.bottom
        );
        
        if (!isOverMenu && !isOverTrigger) {
            hideMenu();
        } else if (isOverMenu || isOverTrigger) {
            showMenu();
        }
    });
});

// Header sticky functionality
window.addEventListener('scroll', function() {
    const sticky = document.getElementById('header-sticky');
    const hero = document.getElementById('header-hero');
    if (window.scrollY > (hero.offsetHeight - 1)) {
        sticky.style.display = 'flex';
    } else {
        sticky.style.display = 'none';
    }
});

// Menu navigation functionality
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

// Auto highlight on scroll
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

// Header color change on scroll
window.addEventListener('scroll', function() {
    const sticky = document.getElementById('header-sticky');
    const hero = document.getElementById('header-hero');
    if (window.scrollY > 0) {
        sticky.style.display = 'flex';
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

<script>
// Validações personalizadas
document.addEventListener('DOMContentLoaded', function() {
    // Validação de morada fiscal (não permitir º)
    const moradaFiscal = document.querySelector('input[name="morada_fiscal"]');
    if (moradaFiscal) {
        moradaFiscal.addEventListener('input', function(e) {
            if (e.target.value.includes('º')) {
                e.target.value = e.target.value.replace(/º/g, '');
                showFieldError(e.target, 'O símbolo º não é permitido na morada fiscal');
            }
        });
    }

    // Validação de matrícula (forçar maiúsculas)
    const matricula = document.querySelector('input[name="matricula_viatura"]');
    if (matricula) {
        matricula.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }

    // Validação de IBAN país (forçar maiúsculas)
    const ibanPais = document.querySelector('input[name="iban_pais"]');
    if (ibanPais) {
        ibanPais.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }

    // Validação de CC verificação (forçar maiúsculas)
    const ccVerificacao = document.querySelector('input[name="cc_verificacao"]');
    if (ccVerificacao) {
        ccVerificacao.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }

    // Validação dinâmica do telemóvel baseada no DDI
    const ddiSelect = document.querySelector('select[name="ddi_telemovel"]');
    const numeroTel = document.querySelector('input[name="numero_telemovel"]');
    
    function updateTelValidation() {
        if (ddiSelect && numeroTel) {
            const ddi = ddiSelect.value;
            if (ddi === '+351') {
                // Portugal: 9 dígitos
                numeroTel.pattern = '[0-9]{9}';
                numeroTel.maxLength = '9';
                numeroTel.placeholder = '9 dígitos';
            } else {
                // Outros países: 3-9 dígitos
                numeroTel.pattern = '[0-9]{3,9}';
                numeroTel.maxLength = '9';
                numeroTel.placeholder = '3-9 dígitos';
            }
        }
    }
    
    if (ddiSelect) {
        ddiSelect.addEventListener('change', updateTelValidation);
        updateTelValidation(); // Aplicar na carga inicial
    }

    // Validação antes do submit
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            let hasErrors = false;

            // Validar data de nascimento
            const dataNasc = document.querySelector('input[name="data_nascimento"]');
            if (dataNasc && dataNasc.value) {
                const year = new Date(dataNasc.value).getFullYear();
                if (year >= 2025) {
                    showFieldError(dataNasc, 'O ano de nascimento deve ser anterior a 2025');
                    hasErrors = true;
                }
            }

            // Validar telemóvel (sempre 9 dígitos)
            const numeroTel = document.querySelector('input[name="numero_telemovel"]');
            if (numeroTel && numeroTel.value && numeroTel.value.length !== 9) {
                showFieldError(numeroTel, 'O número de telemóvel deve ter exatamente 9 dígitos');
                hasErrors = true;
            }

            // Validar CC (8 números + 4 caracteres)
            const ccNumero = document.querySelector('input[name="cc_numero"]');
            const ccVerif = document.querySelector('input[name="cc_verificacao"]');
            if (ccNumero && ccNumero.value && ccNumero.value.length !== 8) {
                showFieldError(ccNumero, 'O número do CC deve ter exatamente 8 dígitos');
                hasErrors = true;
            }
            if (ccVerif && ccVerif.value && ccVerif.value.length !== 4) {
                showFieldError(ccVerif, 'O código de verificação do CC deve ter exatamente 4 caracteres');
                hasErrors = true;
            }

            // Validar NIF (9 números)
            const nif = document.querySelector('input[name="nif"]');
            if (nif && nif.value && nif.value.length !== 9) {
                showFieldError(nif, 'O NIF deve ter exatamente 9 dígitos');
                hasErrors = true;
            }

            // Validar NISS (11 números)
            const niss = document.querySelector('input[name="niss"]');
            if (niss && niss.value && niss.value.length !== 11) {
                showFieldError(niss, 'O NISS deve ter exatamente 11 dígitos');
                hasErrors = true;
            }

            // Validar IBAN (2 letras + 21 dígitos)
            const ibanPais = document.querySelector('input[name="iban_pais"]');
            const ibanNum = document.querySelector('input[name="iban_numeros"]');
            if (ibanPais && ibanPais.value && ibanPais.value.length !== 2) {
                showFieldError(ibanPais, 'O código do país deve ter exatamente 2 letras');
                hasErrors = true;
            }
            if (ibanNum && ibanNum.value && ibanNum.value.length !== 21) {
                showFieldError(ibanNum, 'O IBAN deve ter exatamente 21 dígitos');
                hasErrors = true;
            }

            // Validar matrícula (6 caracteres)
            const matriculaField = document.querySelector('input[name="matricula_viatura"]');
            if (matriculaField && matriculaField.value && matriculaField.value.length !== 6) {
                showFieldError(matriculaField, 'A matrícula deve ter exatamente 6 caracteres');
                hasErrors = true;
            }

            // Validar datas de contrato
            const dataInicio = document.querySelector('input[name="data_inicio_contrato"]');
            const dataFim = document.querySelector('input[name="data_fim_contrato"]');
            const hoje = new Date().toISOString().split('T')[0];
            
            if (dataInicio && dataInicio.value && dataInicio.value > hoje) {
                showFieldError(dataInicio, 'A data de início do contrato deve ser no passado');
                hasErrors = true;
            }
            
            if (dataFim && dataFim.value && dataFim.value < hoje) {
                showFieldError(dataFim, 'A data de fim do contrato deve ser no futuro');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
            }
        });
    }

    // Combinar campos divididos antes do envio
    form.addEventListener('submit', function(e) {
        // Combinar telemóvel
        const ddi = document.querySelector('select[name="ddi_telemovel"]');
        const numero = document.querySelector('input[name="numero_telemovel"]');
        if (ddi && numero && numero.value) {
            const hiddenTel = document.createElement('input');
            hiddenTel.type = 'hidden';
            hiddenTel.name = 'telemovel';
            hiddenTel.value = ddi.value + numero.value;
            form.appendChild(hiddenTel);
        }

        // Combinar CC
        const ccNum = document.querySelector('input[name="cc_numero"]');
        const ccVer = document.querySelector('input[name="cc_verificacao"]');
        if (ccNum && ccVer && ccNum.value && ccVer.value) {
            const hiddenCC = document.createElement('input');
            hiddenCC.type = 'hidden';
            hiddenCC.name = 'cc';
            hiddenCC.value = ccNum.value + ccVer.value;
            form.appendChild(hiddenCC);
        }

        // Combinar IBAN
        const ibanPais = document.querySelector('input[name="iban_pais"]');
        const ibanNum = document.querySelector('input[name="iban_numeros"]');
        if (ibanPais && ibanNum && ibanPais.value && ibanNum.value) {
            const hiddenIBAN = document.createElement('input');
            hiddenIBAN.type = 'hidden';
            hiddenIBAN.name = 'iban';
            hiddenIBAN.value = ibanPais.value + ibanNum.value;
            form.appendChild(hiddenIBAN);
        }

        // Adicionar € à remuneração
        const remuneracao = document.querySelector('input[name="remuneracao"]');
        if (remuneracao && remuneracao.value) {
            remuneracao.value = '€' + remuneracao.value;
        }

        // Adicionar id do colaborador ao POST se RH/Admin estiver a editar outro colaborador
        <?php if (($isRH || $isAdmin) && $editColabId): ?>
        const hiddenId = document.createElement('input');
        hiddenId.type = 'hidden';
        hiddenId.name = 'id';
        hiddenId.value = '<?php echo htmlspecialchars($editColabId); ?>';
        form.appendChild(hiddenId);
        <?php endif; ?>
    });

    function showFieldError(field, message) {
        // Remove mensagem de erro anterior
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Adiciona nova mensagem de erro
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.cssText = 'color: #e53e3e; font-size: 0.8rem; margin-top: 4px;';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);

        // Destaca o campo com erro
        field.style.borderColor = '#e53e3e';
        
        // Remove o destaque após 3 segundos
        setTimeout(() => {
            field.style.borderColor = '';
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 3000);
    }
});
</script>

</body>
</html>