
<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit(); }
$remetente = $_SESSION['user_id'];
$destinatarioColaboradorId = $_POST['destinatario_id'] ?? null;
$assunto = trim($_POST['assunto'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

// Buscar o utilizador_id do colaborador
$destinatario = null;
if ($destinatarioColaboradorId) {
    require_once __DIR__ . '/../../DAL/Database.php';
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
    $stmt->execute([$destinatarioColaboradorId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && isset($row['utilizador_id'])) {
        $destinatario = $row['utilizador_id'];
    }
}

// Upload do anexo
$anexo = null;
if (!empty($_FILES['anexo']['name'])) {
    $uploadDir = __DIR__ . '/../../Uploads/Mensagens/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $nomeFicheiro = uniqid() . '_' . basename($_FILES['anexo']['name']);
    $caminho = $uploadDir . $nomeFicheiro;
    if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminho)) {
        $anexo = $nomeFicheiro;
    }
}

if ($destinatario && $mensagem && $assunto) {
    require_once '../../BLL/Comuns/BLL_mensagens.php';
    $msgBLL = new MensagensManager();
    $msgBLL->enviarMensagem($remetente, $destinatario, $assunto, $mensagem, $anexo);
    header('Location: ../Coordenador/equipa.php?msg=enviado');
    exit();
}
header('Location: ../Coordenador/equipa.php?msg=erro');
exit();
?>