<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../vendor/fpdf/fpdf.php';
require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    header('Location: ../Comuns/erro.php');
    exit();
}
$coordBLL = new CoordenadorDashboardManager();

// Nome do coordenador
$nomeCoord = '';
if (isset($_SESSION['user_id'])) {
    require_once '../../DAL/Database.php';
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['nome'])) {
        $nomeCoord = $row['nome'];
    }
}
if (!$nomeCoord) $nomeCoord = 'Coordenador';

date_default_timezone_set('Europe/Lisbon');
$dataHora = date('d/m/Y H:i');

class PDF_Relatorio extends FPDF {
    public $nomeCoord;
    public $dataHora;
    public $reportTitle;
    function Header() {
        $logoPath = __DIR__ . '/../../assets/tlantic-logo-escuro.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 170, 8, 28, 0, 'PNG');
        }
        if (!empty($this->reportTitle)) {
            $this->SetY(15);
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, utf8_decode($this->reportTitle), 0, 1, 'C');
            $this->Ln(5);
        } else {
            $this->SetY(25);
        }
    }
    function Footer() {
        $this->SetY(-18);
        $this->SetFont('Arial', 'I', 9);
        $txt = utf8_decode("Gerado por: {$this->nomeCoord} | {$this->dataHora}");
        $this->Cell(0, 8, $txt, 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 8, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }
    function CheckPageBreak($height) {
        if($this->GetY() + $height > $this->PageBreakTrigger) {
            $this->AddPage();
            return true;
        }
        return false;
    }
    function TableHeader($headers, $widths) {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        for($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 8, utf8_decode($headers[$i]), 1, 0, 'C', true);
        }
        $this->Ln();
    }
    function TableRow($data, $widths, $fontSize = 9) {
        $this->SetFont('Arial', '', $fontSize);
        $this->SetFillColor(255, 255, 255);
        $this->CheckPageBreak(8);
        for($i = 0; $i < count($data); $i++) {
            $this->Cell($widths[$i], 8, utf8_decode($data[$i]), 1, 0, 'C');
        }
        $this->Ln();
    }
}

$tipo = $_GET['tipo'] ?? '';
$title = 'Relatório';

$pdf = new PDF_Relatorio();
$pdf->nomeCoord = $nomeCoord;
$pdf->dataHora = $dataHora;
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();

if ($tipo === 'aniversarios' && isset($_GET['eid'])) {
    $eid = intval($_GET['eid']);
    $colaboradores = $coordBLL->getAniversariosPorEquipaCoordenador($eid, $_SESSION['user_id']);
    $title = 'Relatório de Aniversários por Equipa';
    $pdf->reportTitle = $title;
    $headers = ['Nome', 'Data de Nascimento', 'Aniversário'];
    $widths = [80, 60, 40];
    $pdf->TableHeader($headers, $widths);
    foreach ($colaboradores as $col) {
        $data = [
            $col['nome'],
            date('d/m/Y', strtotime($col['data_nascimento'])),
            date('d/m', strtotime($col['data_nascimento']))
        ];
        $pdf->TableRow($data, $widths);
    }
} elseif ($tipo === 'alteracoes') {
    $alteracoes = $coordBLL->getAlteracoesContratuaisCoordenador($_SESSION['user_id']);
    $title = 'Relatório de Alterações Contratuais';
    $pdf->reportTitle = $title;
    $headers = ['Colaborador', 'Campo', 'De', 'Para', 'Data Alteração'];
    $widths = [35, 25, 25, 25, 35];
    $pdf->TableHeader($headers, $widths);
    foreach ($alteracoes as $a) {
        $data = [
            $a['colaborador_nome'],
            $a['campo'],
            $a['valor_antigo'] ?: '-',
            $a['valor_novo'],
            date('d/m/Y H:i', strtotime($a['data_alteracao'])),
        ];
        $pdf->TableRow($data, $widths, 8);
    }
} elseif ($tipo === 'vouchers') {
    $vouchers = $coordBLL->getVouchersCoordenador($_SESSION['user_id']);
    $title = 'Relatório de Vouchers Atribuídos';
    $pdf->reportTitle = $title;
    $headers = ['Colaborador', 'Tipo', 'Data de Emissão'];
    $widths = [80, 60, 60];
    $pdf->TableHeader($headers, $widths);
    foreach ($vouchers as $v) {
        $data = [
            $v['colaborador_nome'] ?? '-',
            $v['tipo'],
            date('d/m/Y', strtotime($v['data_emissao']))
        ];
        $pdf->TableRow($data, $widths);
    }
} else {
    $title = 'Relatório não encontrado';
    $pdf->reportTitle = $title;
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Relatório não encontrado.'), 0, 1, 'C');
}

$pdf->Output(utf8_decode($title . ' - ' . $nomeCoord . '.pdf'), 'I');
