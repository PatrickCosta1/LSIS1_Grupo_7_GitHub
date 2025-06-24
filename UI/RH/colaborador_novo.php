<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();

// Buscar perfis possíveis (apenas colaborador e coordenador normalmente)
require_once '../../DAL/Admin/DAL_utilizadores.php';
$dalUtil = new DAL_UtilizadoresAdmin();
$perfis = $dalUtil->getPerfis();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'nome_abreviado' => $_POST['nome_abreviado'] ?? '',
        'morada' => $_POST['morada'] ?? '',
        'morada_fiscal' => $_POST['morada_fiscal'] ?? '',
        'localidade' => $_POST['localidade'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'estado_civil' => $_POST['estado_civil'] ?? '',
        'comprovativo_estado_civil' => '', // upload não tratado aqui
        'habilitacoes' => $_POST['habilitacoes'] ?? '',
        'curso' => $_POST['curso'] ?? '',
        'contacto_emergencia' => $_POST['contacto_emergencia'] ?? '',
        'grau_relacionamento' => $_POST['grau_relacionamento'] ?? '',
        'contacto_emergencia_numero' => $_POST['contacto_emergencia_numero'] ?? '',
        'matricula_viatura' => $_POST['matricula_viatura'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'data_entrada' => $_POST['data_entrada'] ?? date('Y-m-d'),
        'data_fim' => $_POST['data_fim'] ?? null,
        'genero' => $_POST['genero'] ?? '',
        'funcao' => $_POST['funcao'] ?? '',
        'geografia' => $_POST['geografia'] ?? '',
        'nivel_hierarquico' => $_POST['nivel_hierarquico'] ?? '',
        'remuneracao' => $_POST['remuneracao'] ?? '',
        'nif' => $_POST['nif'] ?? '',
        'niss' => $_POST['niss'] ?? '',
        'cc' => $_POST['cc'] ?? '',
        'nacionalidade' => $_POST['nacionalidade'] ?? '',
        'situacao_irs' => $_POST['situacao_irs'] ?? '',
        'dependentes' => $_POST['dependentes'] ?? '',
        'irs_jovem' => $_POST['irs_jovem'] ?? '',
        'primeiro_ano_descontos' => $_POST['primeiro_ano_descontos'] ?? '',
        'telemovel' => $_POST['telemovel'] ?? '',
        'iban' => $_POST['iban'] ?? '',
        'cartao_continente' => $_POST['cartao_continente'] ?? '',
        'voucher_nos' => $_POST['voucher_nos'] ?? '',
        'tipo_contrato' => $_POST['tipo_contrato'] ?? '',
        'regime_horario' => $_POST['regime_horario'] ?? '',
        // dados de utilizador
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'perfil_id' => $_POST['perfil_id'] ?? 0,
        'ativo' => isset($_POST['ativo']) ? 1 : 0,
        'password' => $_POST['password'] ?? ''
    ];
    if ($colabBLL->addColaborador($dados)) {
        $success = "Colaborador criado com sucesso!";
    } else {
        $error = "Erro ao criar colaborador.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Colaborador</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../../assets/teste.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo.png" alt="Logo Tlantic" class="logo-header">
        <nav>
            <a href="dashboard_rh.php">Dashboard</a>
            <a href="colaboradores_gerir.php">Colaboradores</a>
            <a href="equipas.php">Equipas</a>
            <a href="relatorios.php">Relatórios</a>
            <a href="exportar.php">Exportar</a>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <a href="../Comuns/perfil.php">Perfil</a>
            <a href="../Comuns/logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Novo Colaborador</h1>
        <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" class="ficha-form ficha-form-moderna">
            <div class="ficha-grid">
                <div class="ficha-campo">
                    <label>Nome:</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="ficha-campo">
                    <label>Nome Abreviado:</label>
                    <input type="text" name="nome_abreviado" required>
                </div>
                <div class="ficha-campo">
                    <label>Morada:</label>
                    <input type="text" name="morada" required>
                </div>
                <div class="ficha-campo">
                    <label>Morada Fiscal:</label>
                    <input type="text" name="morada_fiscal" required>
                </div>
                <div class="ficha-campo">
                    <label>Localidade:</label>
                    <input type="text" name="localidade" required>
                </div>
                <div class="ficha-campo">
                    <label>Código Postal:</label>
                    <input type="text" name="codigo_postal" required>
                </div>
                <div class="ficha-campo">
                    <label>Estado Civil:</label>
                    <input type="text" name="estado_civil">
                </div>
                <div class="ficha-campo">
                    <label>Comprovativo Estado Civil:</label>
                    <input type="file" name="comprovativo_estado_civil">
                </div>
                <div class="ficha-campo">
                    <label>Habilitações:</label>
                    <input type="text" name="habilitacoes">
                </div>
                <div class="ficha-campo">
                    <label>Curso:</label>
                    <input type="text" name="curso">
                </div>
                <div class="ficha-campo">
                    <label>Contacto Emergência:</label>
                    <input type="text" name="contacto_emergencia">
                </div>
                <div class="ficha-campo">
                    <label>Grau de Relacionamento:</label>
                    <input type="text" name="grau_relacionamento">
                </div>
                <div class="ficha-campo">
                    <label>Número de Contacto Emergência:</label>
                    <input type="text" name="contacto_emergencia_numero">
                </div>
                <div class="ficha-campo">
                    <label>Matrícula Viatura:</label>
                    <input type="text" name="matricula_viatura">
                </div>
                <div class="ficha-campo">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento">
                </div>
                <div class="ficha-campo">
                    <label>Data de Entrada:</label>
                    <input type="date" name="data_entrada" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="ficha-campo">
                    <label>Data de Fim:</label>
                    <input type="date" name="data_fim">
                </div>
                <div class="ficha-campo">
                    <label>Género:</label>
                    <select name="genero">
                        <option value="">Selecione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
                <div class="ficha-campo">
                    <label>Função:</label>
                    <input type="text" name="funcao">
                </div>
                <div class="ficha-campo">
                    <label>Geografia:</label>
                    <input type="text" name="geografia">
                </div>
                <div class="ficha-campo">
                    <label>Nível Hierárquico:</label>
                    <input type="text" name="nivel_hierarquico">
                </div>
                <div class="ficha-campo">
                    <label>Remuneração:</label>
                    <input type="number" name="remuneracao" step="0.01">
                </div>
                <div class="ficha-campo">
                    <label>NIF:</label>
                    <input type="text" name="nif">
                </div>
                <div class="ficha-campo">
                    <label>NISS:</label>
                    <input type="text" name="niss">
                </div>
                <div class="ficha-campo">
                    <label>CC:</label>
                    <input type="text" name="cc">
                </div>
                <div class="ficha-campo">
                    <label>Nacionalidade:</label>
                    <input type="text" name="nacionalidade">
                </div>
                <div class="ficha-campo">
                    <label>Situação IRS:</label>
                    <input type="text" name="situacao_irs">
                </div>
                <div class="ficha-campo">
                    <label>Dependentes:</label>
                    <input type="text" name="dependentes">
                </div>
                <div class="ficha-campo">
                    <label>IRS Jovem:</label>
                    <input type="text" name="irs_jovem">
                </div>
                <div class="ficha-campo">
                    <label>Primeiro Ano Descontos:</label>
                    <input type="text" name="primeiro_ano_descontos">
                </div>
                <div class="ficha-campo">
                    <label>Telemóvel:</label>
                    <input type="text" name="telemovel">
                </div>
                <div class="ficha-campo">
                    <label>IBAN:</label>
                    <input type="text" name="iban">
                </div>
                <div class="ficha-campo">
                    <label>Cartão Continente:</label>
                    <input type="text" name="cartao_continente">
                </div>
                <div class="ficha-campo">
                    <label>Voucher NOS:</label>
                    <input type="text" name="voucher_nos">
                </div>
                <div class="ficha-campo">
                    <label>Tipo de Contrato:</label>
                    <input type="text" name="tipo_contrato">
                </div>
                <div class="ficha-campo">
                    <label>Regime Horário:</label>
                    <input type="text" name="regime_horario">
                </div>
            </div>
            <div style="text-align:center; margin-top: 24px;">
                <button type="submit" class="btn">Criar Colaborador</button>
            </div>
        </form>
        <div style="text-align:center; margin-top: 16px;">
            <a href="colaboradores_gerir.php" class="btn">Voltar</a>
        </div>
    </main>
</body>
</html>