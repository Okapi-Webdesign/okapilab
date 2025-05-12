<?php
$pdf->AddPage();
$pdf->Template('szerzodes_k', 1);
$pdf->SetFont('SourceSans3', '', 11);
$pdf->SetTextColor(0, 0, 0);

$megbizo_nev = $_POST['megrendelo_nev'];
$megbizo_nytsz = $_POST['megrendelo_szam'];
$megbizo_cim = $_POST['megrendelo_szekhely'];
$megbizo_adoszam = $_POST['megrendelo_adoszam'];
$megbizo_kepviselo = $_POST['megrendelo_kepviselo'];
$weboldal = $_POST['weboldal'];
$hataly = $_POST['hataly'];
$szavatossag = $_POST['szavatossag'];
$hatarido = date('Y. m. d.', strtotime($_POST['hatarido']));
$megbizasi_dij = number_format(intval($_POST['megbizasi_dij']), 0, '', ' ');
$eloleg = $_POST['eloleg'] . '%';
$eloleg_osszeg = number_format(intval($_POST['preload_osszeg']), 0, '', ' ');
$kapcsolattarto_nev = $_POST['kapcsolattarto'];
$kapcsolattarto_tel = $_POST['kapcsolattarto_tel'];
$kapcsolattarto_email = $_POST['kapcsolattarto_email'];

// Megbízó
$x = 69;
$y = 67.75;
$pdf->SetXY($x, $y);
$pdf->Cell(115, 5.4, $megbizo_nev, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(115, 5.4, $megbizo_cim, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.5);
$pdf->Cell(115, 5.4, $megbizo_nytsz, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(115, 5.4, $megbizo_adoszam, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(115, 5.4, $megbizo_kepviselo, 0, 0, 'L');

// Adatok
$x = 101;
$y = 165.1;
$pdf->SetXY($x, $y);
$pdf->Cell(84, 5.4, date('Y. m. d.'), 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(84, 5.4, $weboldal, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(84, 5.4, $hataly, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(84, 5.4, $szavatossag, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.5);
$pdf->Cell(84, 5.4, $hatarido, 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(84, 5.4, $megbizasi_dij . ' Ft', 0, 0, 'L');
$pdf->SetXY($x, $y += 5.4);
$pdf->Cell(84, 5.4, "$eloleg ($eloleg_osszeg Ft)", 0, 0, 'L');
$pdf->SetXY($x, $y += 6);
$pdf->MultiCell(84, 4, "$kapcsolattarto_nev\n$kapcsolattarto_email\n$kapcsolattarto_tel", 0, 'L', 0);

// Azonosító
$id = date('Y', strtotime($project->getCreateDate())) . '/' . $project->getId();
$pdf->SetXY(26, 281.5);
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(40, 5.4, $id, 0, 0, 'L');

// Bélyegző
$pdf->SetFillColor(255, 158, 0);
$pdf->Rect(152.5, 270, 40, 20, 'DF');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(152.5, 273);
$pdf->SetFont('SourceSans3', 'B', 10);
$pdf->MultiCell(40, 3, 'Az OkapiLab használatával generálva.', 0, 'C');
$pdf->SetXY(152.5, 283);
$pdf->SetFont('SourceSans3', '', 10);
$pdf->Cell(40, 5, date('Y. m. d. H:i'), 0, 0, 'C');

// További oldalak
for ($i = 2; $i <= 9; $i++) {
    $pdf->AddPage();
    $pdf->Template('szerzodes_k', $i);
    if ($i > 6) continue;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(26, 281);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Cell(40, 5.4, $id, 0, 0, 'L');
}
