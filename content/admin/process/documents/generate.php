<?php
require_once ABS_PATH . 'lib/tfpdf/tfpdf.php';
require_once ABS_PATH . 'lib/fpdi/src/autoload.php';
ob_end_clean();

class Pdf extends \setasign\Fpdi\Tfpdf\Fpdi
{
    /**
     * "Remembers" the template id of the imported page
     */
    protected $tplId;

    /**
     * Draw an imported PDF logo on every page
     */
    function Template(string $source, int $page = 1)
    {
        if ($this->tplId === null) {
            $this->setSourceFile(ABS_PATH . 'assets/documents/' . $source . '.pdf');
            $this->tplId = $this->importPage($page);
        }
        $this->useTemplate($this->tplId, 0, 0, 210, 297);
    }
}

// initiate PDF
$pdf = new Pdf();

$pdf->AddFont('SourceSans3', '', 'sourcesans3.ttf', true);
$pdf->AddFont('SourceSans3', 'B', 'sourcesans3-bold.ttf', true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->SetDisplayMode('fullpage', 'single');

$project = new Project($_POST['project']);
$type = $_POST['documentType'];
$types = ['munkalap', 'szerzodes_k', 'szerzodes_ku'];
if (!in_array($type, $types)) {
    die('Invalid document type');
}

require ABS_PATH . 'content/admin/process/documents/generate/' . $type . '.php';

$pdf->Output('I', 'dokumentum.pdf');
