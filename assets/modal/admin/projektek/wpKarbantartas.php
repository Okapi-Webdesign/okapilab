<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['id']);
$wp = new WordPressConnection($project);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-wrench me-2"></i> Karbantartási üzemmód
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>
<form action="<?= URL ?>admin/process/projects/wp/maintenance" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <p class="mb-0">Biztosan szeretnéd <?= $wp->isMaintenanceMode() ? 'kikapcsolni' : 'bekapcsolni' ?> a karbantartási üzemmódot?</p>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Megerősítés</button>
    </div>
</form>