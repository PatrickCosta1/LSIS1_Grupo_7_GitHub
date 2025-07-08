<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'colaborador') {
    http_response_code(403);
    exit('Acesso negado.');
}

require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
require_once '../../vendor/autoload.php'; // Garante FPDF disponível via Composer

$colabBLL = new ColaboradorFichaManager();
$colab = $colabBLL->getColaboradorByUserId($_SESSION['user_id']);

if (!$colab) {
    exit('Dados do colaborador não encontrados.');
}

if (!class_exists('FPDF')) {
    exit('FPDF não encontrado. Instale via Composer: composer require setasign/fpdf');
}

class Mod99PDF extends FPDF {
    function Header() {
        // Logo (opcional, se existir)
        // $this->Image('../../assets/tlantic-logo2.png', 10, 8, 35);
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(3, 96, 233); // Azul
        $this->Cell(0,12,utf8_decode('Declaração Mod. 99 - Alteração de Dados'),0,1,'C');
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',11);
        $this->Cell(0,8,utf8_decode('Documento gerado automaticamente pelo Portal Tlantic'),0,1,'C');
        $this->Ln(4);
        $this->SetDrawColor(3, 96, 233);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(6);
    }
    function Footer() {
        $this->SetY(-18);
        $this->SetFont('Arial','I',9);
        $this->SetTextColor(120,120,120);
        $this->Cell(0,8,utf8_decode('Portal Tlantic | https://www.tlantic.com'),0,1,'C');
        $this->Cell(0,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="mod99.pdf"');

$pdf = new Mod99PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(30,30,30);

// Dados pessoais
$pdf->SetFillColor(240,245,255);
$pdf->SetDrawColor(3,96,233);
$pdf->SetLineWidth(0.3);

function campo($pdf, $label, $valor) {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(55,10,utf8_decode($label),0,0,'L',true);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,utf8_decode($valor),0,1,'L');
}

$pdf->Ln(2);
campo($pdf, 'Nome completo:', $colab['nome'] . ' ' . $colab['apelido']);
campo($pdf, 'NIF:', $colab['nif']);
campo($pdf, 'Morada:', $colab['morada_fiscal'] ?: $colab['morada']);
campo($pdf, 'Código Postal:', $colab['codigo_postal']);
campo($pdf, 'Localidade:', $colab['localidade']);
campo($pdf, 'Estado Civil:', $colab['estado_civil']);
campo($pdf, 'Data de Nascimento:', $colab['data_nascimento']);
campo($pdf, 'Telemóvel:', $colab['telemovel']);
campo($pdf, 'Email:', $colab['email']);

$pdf->Ln(8);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(40,40,40);
$pdf->MultiCell(0,8,utf8_decode("Declaro para os devidos efeitos que os dados acima correspondem à minha situação atual e autorizo a sua utilização para efeitos legais e fiscais."));

$pdf->Ln(18);

// Data atual
$dataAtual = date('d/m/Y');
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,10,utf8_decode('Data: ') . $dataAtual,0,1,'L');
$pdf->Ln(8);

// Assinatura
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode('Assinatura: __________________________________________'),0,1,'L');

$pdf->Output('D', 'mod99.pdf');
exit;
?>
