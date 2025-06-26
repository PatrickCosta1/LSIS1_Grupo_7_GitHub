<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_utilizadores.php';
$utilBLL = new AdminUtilizadoresManager();

$id = $_GET['id'] ?? null;
if ($id) {
    $utilBLL->removeUtilizador($id);
}
header('Location: utilizadores.php');
exit();
?>