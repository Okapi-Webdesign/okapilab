<?php
$pageMeta = [
    'title' => 'Dokumentumok',
    'description' => 'Itt találod a projektedhez tartozó dokumentumokat.'
];

$project = new Project($_SESSION['project']);
$documents = $project->getDocuments();
?>

<div class="card p-2">
    <div class="card-body">
        <?php
        if (count($documents) == 0) {
        ?>
            <div class="alert alert-info">Nincsenek dokumentumok a projektedhez.</div>
        <?php
        }
        ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-5 g-3">
            <?php
            if ($documents != NULL) {
                foreach ($documents as $document) {
                    $current = $document->getCurrent();
            ?>
                    <div class="col d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= $document->getType()->getName() ?></h5>
                                <p class="card-text">
                                    <b>Dátum:</b> <?= $current->getDate(true) ?><br>
                                    <b>Méret:</b> <?= round(filesize(ABS_PATH . 'storage/' . $project->getId() . '/' . $current->getFilename()) / 1024, 2) ?> KB <br>
                                    <b>Verzió:</b> <?= count($document->getVersions()) ?>. verzió <?= $current->isActive() ? '<span class="text-success">(Aktuális)</span>' : '<span class="text-danger">(Elavult)</span>' ?>
                                </p>

                                <?php if ($current->isActive()) { ?>
                                    <a href="<?= URL ?>storage/<?= $project->getId() ?>/<?= $current->getFilename() ?>" class="btn btn-primary" download="<?= $current->getFilename() ?>" target="_blank"><i class="fa fa-download me-2"></i> Letöltés</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>
    </div>
</div>