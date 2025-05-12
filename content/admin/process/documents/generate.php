<?php
require_once ABS_PATH . 'lib/tfpdf/tfpdf.php';
require_once ABS_PATH . 'lib/fpdi/src/autoload.php';
ob_end_clean();

class Pdf extends \setasign\Fpdi\Tfpdf\Fpdi
{
    function Template(string $source, int $page = 1)
    {
        $this->setSourceFile(ABS_PATH . 'assets/documents/' . $source . '.pdf');;
        $this->useTemplate($this->importPage($page), 0, 0, 210, 297);
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
$types = ['munkalap', 'szerzodes_k', 'szerzodes_ku', 'teljesitesi'];
if (!in_array($type, $types)) {
    die('Invalid document type');
}

require ABS_PATH . 'content/admin/process/documents/generate/' . $type . '.php';

$pdf->Output('I', 'document.pdf');
exit();

$tempDir = ABS_PATH . 'storage/temp';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

$type = $_POST['documentType'] == 'munkalap' ? 'Munkalap' : 'Szerződés';
$fileName = $type . '.pdf';

$project_id = $project->getId();

$pdf->Output('F', $tempDir . '/' . $fileName);
$type = DocumentType::getByName($type);
$type = $type->getId();
if ($stmt = $con->prepare('INSERT INTO `documents`(`id`, `project_id`, `type`) VALUES (NULL, ?, ?)')) {
    $stmt->bind_param('ii', $project_id, $type);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/dokumentumok', 'A dokumentum létrehozása sikertelen!');
    }
    $id = $stmt->insert_id;
    $stmt->close();
}

$document = new Document($id);

$output = $document->addGeneratedVersion($tempDir . '/' . $fileName);
if (!$output['status']) {
    alert_redirect('error', URL . 'admin/dokumentumok', $output['message']);
}

unlink($tempDir . '/' . $fileName);
if (isset($_POST['email_send']) && $_POST['email_send']) {
    if (!mail_send_template($project->getClient()->getEmail(), 'document_created', [
        'name' => $project->getClient()->getContactName(),
        'type' => $document->getType()->getName(),
        'author' => $user->getFullname(),
        'url' => $output['url']
    ])) {
        alert_redirect('warning', URL . 'admin/dokumentumok');
    }
}

alert_redirect('success', URL . 'admin/dokumentumok/adatlap/d/' . $document->getId());
