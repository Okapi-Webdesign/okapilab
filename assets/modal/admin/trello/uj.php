<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['project']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-brands fa-trello me-2"></i> Új feladat
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/projects/addCard" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Név</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="deadline" class="form-label">Határidő</label>
                <input type="date" id="deadline" name="deadline" class="form-control" max="9999-12-31">
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Leírás</label>
                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12">
                <label for="list" class="form-label">Lista</label>
                <select name="list" id="list" class="form-select" required>
                    <?php
                    $trello = new TrelloTable();
                    $lists = $trello->getLists();
                    foreach ($lists as $list) {
                        if ($list['name'] == 'Kész') {
                            continue;
                        }
                        echo '<option value="' . $list['id'] . '">' . $list['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="project" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>