<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../vendor/fpdf/fpdf.php';
require_once '../../BLL/RH/BLL_relatorios.php';

session_start();
// Corrigir: obter nome do RH pelo utilizador logado (tabela colaboradores)
$nomeRH = '';
if (isset($_SESSION['user_id'])) {
    require_once '../../DAL/Database.php';
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['nome'])) {
        $nomeRH = $row['nome'];
    }
}
if (!$nomeRH) $nomeRH = 'RH';

date_default_timezone_set('Europe/Lisbon');
$dataHora = date('d/m/Y H:i');

class PDF_Relatorio extends FPDF {
    public $nomeRH;
    public $dataHora;
    public $reportTitle;
    
    function Header() {
        // Logo escuro da Tlantic (corrigir caminho)
        $logoPath = __DIR__ . '/../../assets/tlantic-logo-escuro.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 170, 8, 28, 0, 'PNG');
        }
        
        // Título do relatório
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
        $txt = utf8_decode("Gerado por: {$this->nomeRH} | {$this->dataHora}");
        $this->Cell(0, 8, $txt, 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 8, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }
    
    // Método para verificar se precisa de nova página
    function CheckPageBreak($height) {
        if($this->GetY() + $height > $this->PageBreakTrigger) {
            $this->AddPage();
            return true;
        }
        return false;
    }
    
    // Método para criar cabeçalho de tabela
    function TableHeader($headers, $widths) {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        for($i = 0; $i < count($headers); $i++) {
            $this->Cell($widths[$i], 8, utf8_decode($headers[$i]), 1, 0, 'C', true);
        }
        $this->Ln();
    }
    
    // Método para criar linha de tabela
    function TableRow($data, $widths, $fontSize = 9) {
        $this->SetFont('Arial', '', $fontSize);
        $this->SetFillColor(255, 255, 255);
        
        // Verificar se precisa de nova página
        $this->CheckPageBreak(8);
        
        for($i = 0; $i < count($data); $i++) {
            $this->Cell($widths[$i], 8, utf8_decode($data[$i]), 1, 0, 'C');
        }
        $this->Ln();
    }
}

$rhBLL = new RHRelatoriosManager();

$tipo = $_GET['tipo'] ?? '';
$title = 'Relatório';

$pdf = new PDF_Relatorio();
$pdf->nomeRH = $nomeRH;
$pdf->dataHora = $dataHora;
$pdf->SetAutoPageBreak(true, 25); // Margem inferior de 25mm
$pdf->AddPage();

if ($tipo === 'aniversarios' && isset($_GET['eid'])) {
    $eid = intval($_GET['eid']);
    $colaboradores = $rhBLL->getAniversariosPorEquipa($eid);
    $title = 'Relatório de Aniversários por Equipa';
    $pdf->reportTitle = $title;
    
    // Cabeçalho da tabela
    $headers = ['Nome', 'Data de Nascimento', 'Aniversário'];
    $widths = [80, 60, 40];
    $pdf->TableHeader($headers, $widths);
    
    // Dados da tabela
    foreach ($colaboradores as $col) {
        $data = [
            $col['nome'],
            date('d/m/Y', strtotime($col['data_nascimento'])),
            date('d/m', strtotime($col['data_nascimento']))
        ];
        $pdf->TableRow($data, $widths);
    }
    
} elseif ($tipo === 'alteracoes') {
    $alteracoes = $rhBLL->getAlteracoesContratuais();
    $title = 'Relatório de Alterações Contratuais';
    $pdf->reportTitle = $title;
    
    // Cabeçalho da tabela
    $headers = ['Colaborador', 'Campo', 'De', 'Para', 'Data Alteração', 'Alterado Por'];
    $widths = [35, 25, 25, 25, 35, 35];
    $pdf->TableHeader($headers, $widths);
    
    // Dados da tabela
    foreach ($alteracoes as $a) {
        $data = [
            $a['colaborador_nome'],
            $a['campo'],
            $a['valor_antigo'] ?: '-',
            $a['valor_novo'],
            date('d/m/Y H:i', strtotime($a['data_alteracao'])),
            $a['alterado_por_nome']
        ];
        $pdf->TableRow($data, $widths, 8);
    }
    
} elseif ($tipo === 'vouchers') {
    require_once '../../DAL/Database.php';
    $pdo = Database::getConnection();
    $stmt = $pdo->query("
        SELECT c.nome as colaborador_nome, v.tipo, v.data_emissao
        FROM vouchers v
        LEFT JOIN colaboradores c ON v.colaborador_id = c.id
        ORDER BY v.data_emissao DESC
    ");
    $vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $title = 'Relatório de Vouchers Atribuídos';
    $pdf->reportTitle = $title;
    
    // Cabeçalho da tabela
    $headers = ['Colaborador', 'Tipo', 'Data de Emissão'];
    $widths = [80, 60, 60];
    $pdf->TableHeader($headers, $widths);
    
    // Dados da tabela
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

$pdf->Output(utf8_decode($title . ' - ' . $nomeRH . '.pdf'), 'I');
