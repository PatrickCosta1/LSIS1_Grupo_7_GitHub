<?php
require_once __DIR__ . '/../../vendor/fpdf/fpdf.php';
require_once '../../BLL/RH/BLL_relatorios.php';
require_once '../../DAL/Database.php';

session_start();

// Buscar nome do RH à base de dados pelo user_id da sessão
$nome_rh = '';
if (isset($_SESSION['user_id'])) {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT username FROM utilizadores WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $nome_rh = $stmt->fetchColumn();
}
if (!$nome_rh) {
    die("Não foi possível identificar o utilizador RH.");
}

// Definir timezone para Lisboa e obter data/hora correta
date_default_timezone_set('Europe/Lisbon');
$datahora = date('d/m/Y H:i');

$tipo = $_GET['tipo'] ?? '';
$relatoriosBLL = new RHRelatoriosManager();
$title = '';
$header = [];
$data = [];

if ($tipo === 'aniversarios' && isset($_GET['eid'])) {
    $eid = intval($_GET['eid']);
    $colaboradores = $relatoriosBLL->getAniversariosPorEquipa($eid);
    $title = 'Relatorio de Aniversarios por Equipa';
    $header = ['Nome', 'Data de Nascimento', 'Aniversario'];
    foreach ($colaboradores as $col) {
        $data[] = [
            $col['nome'],
            date('d/m/Y', strtotime($col['data_nascimento'])),
            date('d/m', strtotime($col['data_nascimento']))
        ];
    }
} elseif ($tipo === 'alteracoes') {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT c.nome as colaborador_nome, p.campo, p.valor_antigo, p.valor_novo, p.estado, p.data_pedido, p.data_resposta
        FROM pedidos_alteracao_ficha p
        INNER JOIN colaboradores c ON p.colaborador_id = c.id
        ORDER BY p.data_pedido DESC
    ");
    $alteracoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $title = 'Relatorio de Alteracoes Contratuais';
    $header = ['Colaborador', 'Campo', 'De', 'Para', 'Estado', 'Data Pedido', 'Data Resposta'];
    foreach ($alteracoes as $a) {
        $data[] = [
            $a['colaborador_nome'],
            $a['campo'],
            $a['valor_antigo'],
            $a['valor_novo'],
            ucfirst($a['estado']),
            date('d/m/Y H:i', strtotime($a['data_pedido'])),
            ($a['data_resposta'] ? date('d/m/Y H:i', strtotime($a['data_resposta'])) : '-')
        ];
    }
} elseif ($tipo === 'vouchers') {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT c.nome as colaborador_nome, v.tipo, v.data_emissao
        FROM vouchers v
        LEFT JOIN colaboradores c ON v.colaborador_id = c.id
        ORDER BY v.data_emissao DESC
    ");
    $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $title = 'Relatorio de Vouchers Atribuidos';
    $header = ['Colaborador', 'Tipo', 'Data de Emissao'];
    foreach ($vouchers as $v) {
        $data[] = [
            $v['colaborador_nome'] ?? '-',
            $v['tipo'],
            date('d/m/Y', strtotime($v['data_emissao']))
        ];
    }
} else {
    die("Relatorio nao encontrado.");
}

// Definir largura das colunas (ajustado para caber mais informação)
function getColumnWidths($header) {
    $map = [
        'Nome' => 38,
        'Colaborador' => 38,
        'Campo' => 28,
        'De' => 22,
        'Para' => 22,
        'Estado' => 22,
        'Data Pedido' => 28,
        'Data Resposta' => 28,
        'Data de Nascimento' => 28,
        'Aniversario' => 22,
        'Tipo' => 28,
        'Data de Emissao' => 28,
    ];
    $widths = [];
    foreach ($header as $col) {
        $widths[] = $map[$col] ?? 28;
    }
    return $widths;
}

// Geração do PDF com FPDF
class RelatorioPDF extends FPDF {
    public $nome_rh;
    public $datahora;
    public $header_cols;
    public $data_rows;
    public $title;
    public $col_widths;
    function Header() {
        // Logo da Tlantic escuro no canto superior direito
        $logoPath = __DIR__ . '/../../assets/tlantic-logo-escuro.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 170, 8, 28, 0, 'PNG');
        }
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, 'Gerado por: ' . $this->nome_rh . '  |  Data: ' . $this->datahora, 0, 1, 'L');
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $this->title, 0, 1, 'C');
        $this->Ln(4);
        // Cabeçalho da tabela (não centrado, começa à esquerda)
        $this->SetFont('Arial', 'B', 10);
        foreach ($this->header_cols as $i => $col) {
            $col_sem_acentos = iconv('UTF-8', 'ASCII//TRANSLIT', $col);
            $this->Cell($this->col_widths[$i], 7, $col_sem_acentos, 1, 0, 'C');
        }
        $this->Ln();
    }
    function FancyTable() {
        $this->SetFont('Arial', '', 10);
        foreach ($this->data_rows as $row) {
            foreach ($row as $i => $col) {
                $this->Cell($this->col_widths[$i], 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

$pdf = new RelatorioPDF();
$pdf->nome_rh = $nome_rh;
$pdf->datahora = $datahora;
$pdf->header_cols = $header;
$pdf->data_rows = $data;
$pdf->title = $title;
$pdf->col_widths = getColumnWidths($header);
$pdf->AddPage();
$pdf->FancyTable();
$pdf->Output('I', $title . '.pdf');
exit;

