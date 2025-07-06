<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh'])) {
    http_response_code(403);
    exit();
}

if (!isset($_GET['action'])) {
    http_response_code(400);
    exit('Ação não especificada.');
}

require_once '../../BLL/RH/BLL_relatorios.php';
$relatoriosBLL = new RHRelatoriosManager();

if ($_GET['action'] === 'equipas') {
    header('Content-Type: application/json');
    echo json_encode($relatoriosBLL->getEquipas());
    exit();
}

if ($_GET['action'] === 'aniversarios' && isset($_GET['eid'])) {
    $colaboradores = $relatoriosBLL->getAniversariosPorEquipa(intval($_GET['eid']));
    if (empty($colaboradores)) {
        echo '<p>Nenhum colaborador encontrado para esta equipa.</p>';
    } else {
        echo '<table class="aniversarios-table"><thead><tr><th>Nome</th><th>Data de Nascimento</th><th>Aniversário</th></tr></thead><tbody>';
        foreach ($colaboradores as $col) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($col['nome']) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($col['data_nascimento'])) . '</td>';
            echo '<td>' . date('d/m', strtotime($col['data_nascimento'])) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    exit();
}

if ($_GET['action'] === 'alteracoes') {
    $alteracoes = $relatoriosBLL->getAlteracoesContratuais();

    if (empty($alteracoes)) {
        echo '<p>Não existem alterações contratuais registadas.</p>';
    } else {
        echo '<table class="alteracoes-table"><thead><tr>
                <th>Colaborador</th>
                <th>Campo</th>
                <th>De</th>
                <th>Para</th>
                <th>Data Alteração</th>
                <th>Alterado Por</th>
            </tr></thead><tbody>';
        foreach ($alteracoes as $a) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($a['colaborador_nome']) . '</td>';
            echo '<td>' . htmlspecialchars($a['campo']) . '</td>';
            echo '<td>' . htmlspecialchars($a['valor_antigo'] ?: '-') . '</td>';
            echo '<td>' . htmlspecialchars($a['valor_novo']) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($a['data_alteracao'])) . '</td>';
            echo '<td>' . htmlspecialchars($a['alterado_por_nome']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    exit();
}

if ($_GET['action'] === 'vouchers') {
    require_once '../../DAL/Database.php';
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT v.id, v.colaborador_id, c.nome as colaborador_nome, v.tipo, v.data_emissao
        FROM vouchers v
        LEFT JOIN colaboradores c ON v.colaborador_id = c.id
        ORDER BY v.data_emissao DESC
    ");
    $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($vouchers)) {
        echo '<p>Não existem vouchers atribuídos registados.</p>';
    } else {
        echo '<table class="vouchers-table"><thead><tr>
                <th>Colaborador</th>
                <th>Tipo</th>
                <th>Data de Emissão</th>
            </tr></thead><tbody>';
        foreach ($vouchers as $v) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($v['colaborador_nome'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($v['tipo']) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($v['data_emissao'])) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    exit();
}
?>
?>
?>
}
?>
            echo '<td>' . htmlspecialchars($v['colaborador_nome'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($v['tipo']) . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($v['data_emissao'])) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    exit();
}
?>
?>
}
?>
