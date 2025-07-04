<?php
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
require_once '../../BLL/Comuns/BLL_notificacoes.php';

$colabBLL = new RHColaboradoresManager();
$notBLL = new NotificacoesManager();

$token = $_GET['token'] ?? '';
$erro = '';
$success = '';
$onboarding = null;

if ($token) {
    $onboarding = $colabBLL->getOnboardingTempByToken($token);
    if (!$onboarding || $onboarding['estado'] !== 'pendente') {
        $erro = "Link inválido ou já utilizado.";
    }
} else {
    $erro = "Token em falta.";
}

function validarDadosOnboarding($dados) {
    if (!preg_match('/^[A-Za-zÀ-ÿ\'\-\s]{2,80}$/u', $dados['nome'])) return "Primeiro nome inválido.";
    if (!preg_match('/^[A-Za-zÀ-ÿ\'\-\s]{2,80}$/u', $dados['apelido'])) return "Apelido inválido.";
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['data_nascimento'])) return "Data de nascimento inválida.";
    $dataNasc = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
    if (!$dataNasc) return "Data de nascimento inválida.";
    $hoje = new DateTime();
    $idade = $hoje->diff($dataNasc)->y;
    if ($idade < 16) return "Tem de ter pelo menos 16 anos.";
    if (strlen(trim($dados['morada'])) < 5 || strlen($dados['morada']) > 120) return "Morada inválida.";
    if (strlen(trim($dados['localidade'])) < 2 || strlen($dados['localidade']) > 80) return "Localidade inválida.";
    if (!preg_match('/^\d{4}-\d{3}$/', $dados['codigo_postal'])) return "Código postal inválido.";
    if (!preg_match('/^9\d{8}$/', $dados['telemovel'])) return "Telemóvel inválido.";
    if (!in_array($dados['sexo'], ['Masculino','Feminino','Outro'])) return "Sexo inválido.";
    if (!in_array($dados['estado_civil'], ['Solteiro','Casado','Divorciado','Viúvo'])) return "Estado civil inválido.";
    if (strlen(trim($dados['habilitacoes'])) < 2 || strlen($dados['habilitacoes']) > 80) return "Habilitações inválidas.";
    if (strlen(trim($dados['curso'])) < 2 || strlen($dados['curso']) > 80) return "Curso inválido.";
    if (!preg_match('/^[1235689]\d{8}$/', $dados['nif'])) return "NIF inválido.";
    if (!preg_match('/^\d{11}$/', $dados['niss'])) return "NISS inválido.";
    if (!preg_match('/^PT50\d{21}$/', $dados['iban'])) return "IBAN inválido (deve começar por PT50 e ter 25 caracteres).";
    if (strlen(trim($dados['nome_contacto_emergencia'])) < 2 || strlen($dados['nome_contacto_emergencia']) > 80) return "Nome do contacto de emergência inválido.";
    if (strlen(trim($dados['grau_relacionamento'])) < 2 || strlen($dados['grau_relacionamento']) > 40) return "Grau de parentesco inválido.";
    if (!preg_match('/^[29]\d{8}$/', $dados['contacto_emergencia'])) return "Contacto de emergência inválido.";
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $onboarding) {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'apelido' => $_POST['apelido'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'morada' => $_POST['morada'] ?? '',
        'localidade' => $_POST['localidade'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'telemovel' => $_POST['telemovel'] ?? '',
        'sexo' => $_POST['sexo' ?? ''],
        'estado_civil' => $_POST['estado_civil' ?? ''],
        'habilitacoes' => $_POST['habilitacoes' ?? ''],
        'curso' => $_POST['curso' ?? ''],
        'nif' => $_POST['nif' ?? ''],
        'niss' => $_POST['niss' ?? ''],
        'iban' => $_POST['iban' ?? ''],
        'nome_contacto_emergencia' => $_POST['nome_contacto_emergencia' ?? ''],
        'grau_relacionamento' => $_POST['grau_relacionamento' ?? ''],
        'contacto_emergencia' => $_POST['contacto_emergencia' ?? ''],
    ];
    foreach ($dados as $campo => $valor) {
        if (empty($valor)) {
            $erro = "Preencha todos os campos obrigatórios.";
            break;
        }
    }
    if (!$erro) {
        $erro = validarDadosOnboarding($dados);
    }
    if (!$erro) {
        $ok = $colabBLL->submeterOnboardingTemp($token, $dados);
        if ($ok) {
            $notBLL->notificarRH("Novo onboarding submetido por {$onboarding['nome']} ({$onboarding['email_pessoal']}).");
            $success = "Dados submetidos com sucesso! Aguarde aprovação do RH.";
        } else {
            $erro = "Erro ao submeter dados.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Onboarding - Preencher Dados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../assets/CSS/Convidado/onboarding_convidado.css">
    <style>
    /* Modern, compact, professional, attractive, intuitive */
    body {
        background: linear-gradient(135deg, #eaf2ff 0%, #f7faff 100%);
        min-height: 100vh;
        margin: 0;
    }
    .onboarding-container {
        max-width: 410px;
        margin: 48px auto 0 auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(3,96,233,0.13);
        padding: 0;
        border: 1.5px solid #e6eaf7;
        overflow: hidden;
        animation: fadeInUp 0.7s;
    }
    .onboarding-header {
        background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
        padding: 28px 0 18px 0;
        text-align: center;
        color: #fff;
        border-bottom: 1.5px solid #e6eaf7;
    }
    .onboarding-header img {
        height: 38px;
        margin-bottom: 8px;
    }
    .onboarding-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .onboarding-form {
        padding: 28px 22px 18px 22px;
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .onboarding-form label {
        display: block;
        margin-bottom: 12px;
        color: #1c3c69;
        font-weight: 500;
        font-size: 0.99rem;
        letter-spacing: 0.1px;
    }
    .onboarding-form input[type="text"],
    .onboarding-form input[type="date"],
    .onboarding-form select {
        width: 100%;
        padding: 9px 12px;
        border: 1.2px solid #c3cfe2;
        border-radius: 7px;
        font-size: 0.99rem;
        margin-top: 5px;
        background: #f5f7fa;
        color: #1c3c69;
        transition: border 0.2s;
        box-sizing: border-box;
    }
    .onboarding-form input:focus,
    .onboarding-form select:focus {
        border: 1.5px solid #0360e9;
        outline: none;
        background: #eaf2ff;
    }
    .onboarding-form .form-row {
        display: flex;
        gap: 12px;
    }
    .onboarding-form .form-row > label {
        flex: 1;
        margin-bottom: 0;
    }
    .onboarding-form .btn {
        background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 12px 0;
        font-size: 1.08rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 18px;
        width: 100%;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(3,96,233,0.08);
        text-decoration: none;
        display: block;
        letter-spacing: 0.2px;
    }
    .onboarding-form .btn:hover {
        background: linear-gradient(135deg, #1c3c69 0%, #0360e9 100%);
        box-shadow: 0 4px 16px rgba(3,96,233,0.13);
    }
    .success-message, .error-message {
        padding: 12px 18px;
        border-radius: 8px;
        margin: 18px 22px 0 22px;
        font-weight: 600;
        text-align: center;
        font-size: 1rem;
    }
    .success-message {
        background: #e6fffa;
        color: #0360e9;
        border: 1px solid #36b3e9;
    }
    .error-message {
        background: #ffeaea;
        color: #e53e3e;
        border: 1px solid #e53e3e;
    }
    @media (max-width: 600px) {
        .onboarding-container {
            margin: 0;
            border-radius: 0;
            max-width: 100vw;
        }
        .onboarding-header {
            padding: 18px 0 10px 0;
        }
        .onboarding-form {
            padding: 14px 4vw 10px 4vw;
        }
        .success-message, .error-message {
            margin: 12px 4vw 0 4vw;
        }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px);}
        to { opacity: 1; transform: translateY(0);}
    }
    </style>
</head>
<body>
<div class="onboarding-container">
    <div class="onboarding-header">
        <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic">
        <h1>Onboarding - Preencher Dados</h1>
    </div>
    <?php if ($erro): ?>
        <div class="error-message"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($onboarding): ?>
    <form method="POST" autocomplete="off" class="onboarding-form">
        <div class="form-row">
            <label>Primeiro Nome:
                <input type="text" name="nome" required maxlength="80" pattern="[A-Za-zÀ-ÿ'\-\s]{2,80}" placeholder="Ex: Ana Maria">
            </label>
            <label>Apelido:
                <input type="text" name="apelido" required maxlength="80" pattern="[A-Za-zÀ-ÿ'\-\s]{2,80}" placeholder="Ex: Silva">
            </label>
        </div>
        <div class="form-row">
            <label>Data de Nascimento:
                <input type="date" name="data_nascimento" required>
            </label>
            <label>Sexo:
                <select name="sexo" required>
                    <option value="">Selecione</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>
            </label>
        </div>
        <label>Morada:
            <input type="text" name="morada" required maxlength="120" placeholder="Ex: Rua das Flores, 123">
        </label>
        <div class="form-row">
            <label>Localidade:
                <input type="text" name="localidade" required maxlength="80" placeholder="Ex: Porto">
            </label>
            <label>Código Postal:
                <input type="text" name="codigo_postal" required maxlength="8" pattern="\d{4}-\d{3}" placeholder="Ex: 4000-123">
            </label>
        </div>
        <div class="form-row">
            <label>Telemóvel:
                <input type="text" name="telemovel" required maxlength="9" pattern="9\d{8}" placeholder="Ex: 912345678">
            </label>
            <label>Estado Civil:
                <select name="estado_civil" required>
                    <option value="">Selecione</option>
                    <option value="Solteiro">Solteiro</option>
                    <option value="Casado">Casado</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Viúvo">Viúvo</option>
                </select>
            </label>
        </div>
        <div class="form-row">
            <label>Habilitações Literárias:
                <input type="text" name="habilitacoes" required maxlength="80" placeholder="Ex: Licenciatura">
            </label>
            <label>Curso:
                <input type="text" name="curso" required maxlength="80" placeholder="Ex: Engenharia Informática">
            </label>
        </div>
        <div class="form-row">
            <label>NIF:
                <input type="text" name="nif" required maxlength="9" pattern="[1235689]\d{8}" placeholder="Ex: 123456789">
            </label>
            <label>NISS:
                <input type="text" name="niss" required maxlength="11" pattern="\d{11}" placeholder="Ex: 12345678901">
            </label>
        </div>
        <label>IBAN:
            <input type="text" name="iban" required maxlength="25" pattern="PT50\d{21}" placeholder="Ex: PT50123456789012345678901">
        </label>
        <div class="form-row">
            <label>Nome Contacto Emergência:
                <input type="text" name="nome_contacto_emergencia" required maxlength="80" placeholder="Ex: João Silva">
            </label>
            <label>Grau de Parentesco:
                <input type="text" name="grau_relacionamento" required maxlength="40" placeholder="Ex: Pai">
            </label>
        </div>
        <label>Número de Contacto Emergência:
            <input type="text" name="contacto_emergencia" required maxlength="9" pattern="[29]\d{8}" placeholder="Ex: 912345678">
        </label>
        <button type="submit" class="btn">Submeter Dados</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>