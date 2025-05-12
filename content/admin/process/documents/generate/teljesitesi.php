<?php
$pdf->AddPage();
$pdf->Template('teljesitesi', 1);
$pdf->SetFont('SourceSans3', 'B', 11);
$client = $project->getClient();

$field_x = 67;
$current_y = 47;
$pdf->SetXY($field_x, $current_y);
$pdf->Cell(124.5, 5, $client->getName(), 0, 0, 'L');
$pdf->SetFont('SourceSans3', '', 11);
$pdf->SetXY($field_x, $current_y += 5);
$pdf->Cell(124.5, 5, $client->getAddress('zip') . ' ' . $client->getAddress('city'), 0, 0, 'L');
$pdf->SetXY($field_x, $current_y += 5);
$pdf->Cell(124.5, 5, $client->getAddress('address') . ' ' . $client->getAddress('address2'), 0, 0, 'L');
$pdf->SetXY($field_x, $current_y += 5);
$pdf->Cell(124.5, 5, $client->getTaxNumber(), 0, 0, 'L');

$pdf->SetXY($field_x, $current_y += 25.5);
$pdf->Cell(124.5, 5, date('Y', strtotime($project->getCreateDate())) . '/' . $project->getId() . ' - ' . $project->getName(), 0, 0, 'L');

$pdf->SetXY($field_x, $current_y += 26.75);
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
$pdf->Cell(124.5, 5, $kelt, 0, 0, 'L');

// Bélyegző
$pdf->SetFillColor(255, 158, 0);
$pdf->Rect(152.5, 265, 40, 20, 'DF');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(152.5, 268);
$pdf->SetFont('SourceSans3', 'B', 10);
$pdf->MultiCell(40, 3, 'Az OkapiLab használatával generálva.', 0, 'C');
$pdf->SetXY(152.5, 278);
$pdf->SetFont('SourceSans3', '', 10);
$pdf->Cell(40, 5, date('Y. m. d. H:i'), 0, 0, 'C');
