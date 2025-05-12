<?php
$pdf->AddPage();
$pdf->Template('munkalap', 1);

$szolgaltatasok = implode(', ', $_POST['szolgaltatasok']);
$nyelv = mb_strtolower(implode(', ', $_POST['nyelv']));
$arculatterv = $_POST['arculatterv'] == 1;
$stilus = $_POST['stilus'];
$szoveges_tartalom = $_POST['szoveges_tartalom'] == 1;
$kepes_tartalom = $_POST['kepes_tartalom'] == 1;
$tobbnyelvu = $_POST['tobbnyelvu'] == 1;
$funkciok = $_POST['funkciok'];
$webshop = $_POST['webshop'] == 1;
$webshop_termekek = $_POST['webshop_termekek'];
$webshop_fizetes = $_POST['webshop_fizetes'];
$tartalomkezelo = $_POST['tartalomkezelo'];
$webtarhely = $_POST['webtarhely'] == 1;
$webtarhely_meret = $_POST['webtarhely_meret'];
$cpanel = $_POST['cpanel'] == 1;
$domain = $_POST['domain'] == 1;
$domain_nev = $_POST['domain_name'];
$megjegyzes = $_POST['megjegyzes'];

$field_x = 68;

// --- 0. Jelölések ---

$pdf->SetFont('Helvetica', 'B', 6);
$pdf->SetTextColor(0, 0, 0);

function drawCheckbox($x, $y, $checked)
{
    global $pdf;
    if (!$checked) return;
    $pdf->SetXY($x, $y);
    $pdf->Cell(4, 4, 'X', 0, 0, 'C');
}

// Arculatterv
drawCheckbox(69.25, 88.25, $arculatterv);
drawCheckbox(94.25, 88.25, !$arculatterv);

// Tartalom
drawCheckbox(69.25, 112.5, $szoveges_tartalom);
drawCheckbox(94.25, 112.5, !$szoveges_tartalom);
drawCheckbox(69.25, 117.5, $kepes_tartalom);
drawCheckbox(94.25, 117.5, !$kepes_tartalom);
drawCheckbox(69.25, 122.75, $tobbnyelvu);
drawCheckbox(94.25, 122.75, !$tobbnyelvu);

// Webáruház
drawCheckbox(89.25, 142, $webshop);
drawCheckbox(106.75, 142, !$webshop);

if ($webshop) {
    drawCheckbox(106.75, 163.10, $webshop_termekek == 0);
    drawCheckbox(131.75, 163.10, $webshop_termekek == 1);
    drawCheckbox(156.75, 163.10, $webshop_termekek == 2);
    if (!is_array($webshop_fizetes)) {
        $webshop_fizetes = [];
    }
    drawCheckbox(106.75, 168, in_array(0, $webshop_fizetes));
    drawCheckbox(131.75, 168, in_array(1, $webshop_fizetes));
    drawCheckbox(157.5, 168, in_array(2, $webshop_fizetes));
} else {
    $pdf->Line(67.5, 172.5, 192.5, 162.5);
}

drawCheckbox(69.25, 173, $tartalomkezelo == '_wp');
drawCheckbox(106.75, 173, $tartalomkezelo == '_egyedi');
drawCheckbox(144.25, 173, ($tartalomkezelo != '_wp' && $tartalomkezelo != '_egyedi'));

// Technikai adatok
drawCheckbox(69.25, 197.75, $webtarhely);
drawCheckbox(94.25, 197.75, !$webtarhely);
drawCheckbox(157, 197.75, $cpanel);
drawCheckbox(173, 197.75, !$cpanel);

drawCheckbox(69.25, 202.75, $domain);
drawCheckbox(94.25, 202.75, !$domain);

$pdf->SetFont('SourceSans3', '', 11);

// --- 1. Projektadatok ---
$current_y = 58.25;
$pdf->SetXY($field_x, $current_y);
$pdf->Cell(124.5, 5, date('Y', strtotime($project->getCreateDate())) . '/' . $project->getId(), 0, 0, 'L');
$pdf->SetXY($field_x, $current_y += 5);
$pdf->Cell(124.5, 5, $szolgaltatasok, 0, 0, 'L');
$pdf->SetXY($field_x, $current_y += 5);
$pdf->Cell(124.5, 5, $nyelv, 0, 0, 'L');

// --- 2. Tervezés ---
$pdf->SetXY($field_x, $current_y += 24.5);
$pdf->Cell(124.5, 5, $stilus, 0, 0, 'L');

// --- 4. Funkciók ---
$pdf->SetXY($field_x, $current_y += 53.5);
$pdf->MultiCell(124.5, 4, $funkciok, 0, 'L');

if ($tartalomkezelo != '_wp' && $tartalomkezelo != '_egyedi') {
    $pdf->SetXY(143, $current_y += 31.5);
    $pdf->Cell(48, 5, $tartalomkezelo, 0, 0, 'L');
} else {
    $current_y += 31.5;
}

// --- 5. Technikai információk ---
$pdf->SetXY(130, $current_y += 19.5);
if (empty($webtarhely_meret)) {
    $webtarhely_meret = 'N/A';
} else {
    $webtarhely_meret = "$webtarhely_meret GB";
}
$pdf->Cell(13, 5, $webtarhely_meret, 0, 0, 'L');

// --- Megjegyzés ---
$pdf->SetXY($field_x, $current_y += 15.75);
$pdf->SetFont('SourceSans3', '', 9);
$pdf->MultiCell(124.5, 4, $megjegyzes, 0, 'L');

$pdf->SetXY($field_x, $current_y += 32.75);
$months = [
    1 => 'január',
    2 => 'február',
    3 => 'március',
    4 => 'április',
    5 => 'május',
    6 => 'június',
    7 => 'július',
    8 => 'augusztus',
    9 => 'szeptember',
    10 => 'október',
    11 => 'november',
    12 => 'december'
];
$month = $months[date('n')];
$kelt = 'Körmend, ' . date('Y.') . " $month " . date('d.');
$pdf->SetFont('SourceSans3', '', 11);
$pdf->Cell(124.5, 5, $kelt, 0, 0, 'L');

// Bélyegző
$pdf->SetFillColor(255, 158, 0);
$pdf->Rect(152.5, 10, 40, 20, 'DF');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(152.5, 13);
$pdf->SetFont('SourceSans3', 'B', 10);
$pdf->MultiCell(40, 3, 'Az OkapiLab használatával generálva.', 0, 'C');
$pdf->SetXY(152.5, 23);
$pdf->SetFont('SourceSans3', '', 10);
$pdf->Cell(40, 5, date('Y. m. d. H:i'), 0, 0, 'C');
