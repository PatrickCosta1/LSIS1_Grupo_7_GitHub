<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
$colabBLL = new ColaboradorFichaManager();

if (isset($_POST['aprovar'])) {
    $colabBLL->aprovarPedido($_POST['pedido_id']);
}
if (isset($_POST['recusar'])) {
    $colabBLL->recusarPedido($_POST['pedido_id']);
}

$pedidos = $colabBLL->listarPedidosPendentes();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Aprovação</title>
</head>
<body>
    <h1>Pedidos de Alteração de Ficha</h1>
    <table>
        <tr>
            <th>Colaborador</th>
            <th>Campo</th>
            <th>Valor Antigo</th>
            <th>Valor Novo</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($pedidos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['colaborador_nome']) ?></td>
            <td><?= htmlspecialchars($p['campo']) ?></td>
            <td><?= htmlspecialchars($p['valor_antigo']) ?></td>
            <td><?= htmlspecialchars($p['valor_novo']) ?></td>
            <td><?= htmlspecialchars($p['data_pedido']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                    <button type="submit" name="aprovar">Aprovar</button>
                    <button type="submit" name="recusar">Recusar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
