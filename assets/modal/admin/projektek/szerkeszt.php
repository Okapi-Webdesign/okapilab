<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-pencil me-2"></i> Projekt szerkesztése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/projects/edit" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Név</label>
                <input type="text" id="name" class="form-control" disabled value="<?= $project->getName() ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="client" class="form-label">Ügyfél</label>
                <input type="text" id="client" class="form-control" disabled value="<?= $project->getClient()->getName() ?>">
            </div>
            <div class="col-12 col-md-8">
                <label for="url" class="form-label">Weboldal <span class="text-muted">(ha van)</span></label>
                <input type="url" id="url" name="url" class="form-control" value="<?= $project->getUrl() ?>">
            </div>
            <div class="col-12 col-md-4">
                <label for="wordpress" class="form-label">WordPress</label>
                <select id="wordpress" name="wordpress" class="form-select" required>
                    <option value="1" <?= $project->isWordpress() ? 'selected' : '' ?>>Igen</option>
                    <option value="0" <?= !$project->isWordpress() ? 'selected' : '' ?>>Nem</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="manager" class="form-label">Projektvezető</label>
                <select id="manager" name="manager" class="form-select" required>
                    <option value="">Válassz...</option>
                    <?php foreach (User::getAll() as $_user) : ?>
                        <option value="<?= $_user->getId() ?>" <?= $project->getManager()->getId() == $_user->getId() ? 'selected' : '' ?>>
                            <?= $_user->getFullName() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="deadline" class="form-label">Határidő</label>
                <input type="date" id="deadline" name="deadline" class="form-control" value="<?= $project->getDeadline() ?>" max="9999-12-31">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>