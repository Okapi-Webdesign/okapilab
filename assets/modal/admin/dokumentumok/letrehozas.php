<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
if (isset($_POST['p'])) {
    $p = new Project($_POST['p']);
    $p = $p->getId();
}
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Dokumentum létrehozása
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/documents/add" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="project" class="form-label">Projekt</label>
                <select id="project" name="project" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $projects = Project::getAll();
                    foreach ($projects as $project) {
                        $s = '';
                        if (isset($p)) {
                            $s = $project->getId() == $p ? ' selected' : '';
                        }
                        echo '<option ' . $s . ' value="' . $project->getId() . '">' . $project->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="type" class="form-label">Típus</label>
                <select id="type" name="type" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $types = DocumentType::getAll();
                    foreach ($types as $type) {
                        echo '<option value="' . $type->getId() . '">' . $type->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12">
                <label for="file" class="form-label">Fájl</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Létrehozás</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>