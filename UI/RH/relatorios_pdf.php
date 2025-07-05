<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../BLL/RH/BLL_relatorios.php';

$tipo = $_GET['tipo'] ?? '';
$relatoriosBLL = new RHRelatoriosManager();
$html = '';
$title = '';

if ($tipo === 'aniversarios' && isset($_GET['eid'])) {
    $eid = intval($_GET['eid']);
    $colaboradores = $relatoriosBLL->getAniversariosPorEquipa($eid);
    $title = 'Relatório de Aniversários por Equipa';
    $html .= "<h2 style='color:#19365f;'>$title</h2>";
    $html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'><thead><tr>
        <th>Nome</th><th>Data de Nascimento</th><th>Aniversário</th></tr></thead><tbody>";
    foreach ($colaboradores as $col) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($col['nome']) . "</td>";
        $html .= "<td>" . date('d/m/Y', strtotime($col['data_nascimento'])) . "</td>";
        $html .= "<td>" . date('d/m', strtotime($col['data_nascimento'])) . "</td>";
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
} elseif ($tipo === 'alteracoes') {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT c.nome as colaborador_nome, p.campo, p.valor_antigo, p.valor_novo, p.estado, p.data_pedido, p.data_resposta
        FROM pedidos_alteracao_ficha p
        INNER JOIN colaboradores c ON p.colaborador_id = c.id
        ORDER BY p.data_pedido DESC
    ");
    $alteracoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $title = 'Relatório de Alterações Contratuais';
    $html .= "<h2 style='color:#19365f;'>$title</h2>";
    $html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'><thead><tr>
        <th>Colaborador</th><th>Campo</th><th>De</th><th>Para</th><th>Estado</th><th>Data Pedido</th><th>Data Resposta</th>
        </tr></thead><tbody>";
    foreach ($alteracoes as $a) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($a['colaborador_nome']) . "</td>";
        $html .= "<td>" . htmlspecialchars($a['campo']) . "</td>";
        $html .= "<td>" . htmlspecialchars($a['valor_antigo']) . "</td>";
        $html .= "<td>" . htmlspecialchars($a['valor_novo']) . "</td>";
        $html .= "<td>" . htmlspecialchars(ucfirst($a['estado'])) . "</td>";
        $html .= "<td>" . date('d/m/Y H:i', strtotime($a['data_pedido'])) . "</td>";
        $html .= "<td>" . ($a['data_resposta'] ? date('d/m/Y H:i', strtotime($a['data_resposta'])) : '-') . "</td>";
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
} elseif ($tipo === 'vouchers') {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT c.nome as colaborador_nome, v.tipo, v.data_emissao
        FROM vouchers v
        LEFT JOIN colaboradores c ON v.colaborador_id = c.id
        ORDER BY v.data_emissao DESC
    ");
    $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $title = 'Relatório de Vouchers Atribuídos';
    $html .= "<h2 style='color:#19365f;'>$title</h2>";
    $html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'><thead><tr>
        <th>Colaborador</th><th>Tipo</th><th>Data de Emissão</th>
        </tr></thead><tbody>";
    foreach ($vouchers as $v) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($v['colaborador_nome'] ?? '-') . "</td>";
        $html .= "<td>" . htmlspecialchars($v['tipo']) . "</td>";
        $html .= "<td>" . date('d/m/Y', strtotime($v['data_emissao'])) . "</td>";
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
} else {
    $html = "<h2>Relatório não encontrado.</h2>";
}

// Antes de usar a classe Mpdf, certifique-se que a biblioteca está instalada.
// Execute no terminal, na raiz do projeto:
// composer require mpdf/mpdf

// Se não tiver o composer.json ou a pasta vendor, instale o Composer e execute o comando acima.
// O ficheiro vendor/autoload.php deve existir e ser carregado corretamente.

try {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output($title . '.pdf', 'I');
} catch (\Throwable $e) {
    echo "<h3 style='color:red'>Erro ao gerar PDF: " . htmlspecialchars($e->getMessage()) . "</h3>";
    exit();
}
