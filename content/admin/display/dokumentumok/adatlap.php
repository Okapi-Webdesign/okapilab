<?php
$pageMeta = [
    'title' => 'Dokumentum adatlap',
];

$document = new Document($data[0]);
?>

<div class="card">
    <div class="card-body">
        <h2 class="display-4 mb-2 text-center text-xl-start">
            <?= $document->getCurrent()->isActive() ? '' : '<s>' ?>
            <?= $document->getType()->getName() == 'Egyéb' ? 'Dokumentum #' . $document->getId() : $document->getType()->getName() ?>
            <?= $document->getCurrent()->isActive() ? '' : '</s>' ?>
        </h2>
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column">
            <span class="mb-2 mb-xl-0 text-center text-xl-start">
                <span class="me-3 d-inline-block"><b>Projekt:</b> <a class="text-decoration-none" href="<?= URL ?>admin/projektek/adatlap/d/<?= $document->getProject()->getId() ?>"><?= $document->getProject()->getName() ?></a></span>
                <span class="me-3 d-inline-block"><b>Ügyfél:</b> <a class="text-decoration-none" href="<?= URL ?>admin/ugyfelek/adatlap/d/<?= $document->getProject()->getClient()->getId() ?>"><?= $document->getProject()->getClient()->getName() ?></a></span>
                <span class="d-inline-block"><b>Verziószám:</b> <?= $document->getCurrent()->getVersion() ?>. verzió</span>
            </span>
            <div class="action-buttons">
                <?php
                if ($document->getCurrent()->isActive()) {
                ?>
                    <a target="_blank" title="Letöltés" data-bs-toggle="tooltip" href="<?= URL ?>admin/process/documents/download/d/<?= $document->getCurrent()->getId() ?>" class="btn btn-sm btn-primary"><i class="fa fa-download me-2"></i> Letöltés</a>
                <?php } ?>
            </div>
        </div>
        <hr>
        <h3 class="h4 mb-3">
            Dokumentumverziók
        </h3>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Verziószám</th>
                        <th>Feltöltés dátuma</th>
                        <th>Feltöltő</th>
                        <th>Méret</th>
                        <th>Módosítások</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($document->getVersions() as $version) : ?>
                        <tr>
                            <td>
                                <?= $version->isCurrent() ? '<span class="badge text-bg-success me-1">Aktuális</span> ' : '<span class="badge text-bg-secondary me-1">Korábbi</span>' ?>
                                <?= $version->getVersion() ?>. verzió
                            </td>
                            <td><?= $version->getDate(true) ?></td>
                            <td><?= $version->getUser()->getFullName() ?></td>
                            <td><?= number_format($version->getSize() / 1048576, 2) ?> MB</td>
                            <td><?= $version->getChanges() == NULL && $version->getVersion() > 1 ? 'Nincs megadva' : $version->getChanges() ?></td>
                            <td class="text-end">
                                <?php if ($version->isCurrent()) { ?> <div class="action-buttons">
                                        <a target="_blank" title="Letöltés" data-bs-toggle="tooltip" href="<?= URL ?>admin/process/documents/download/d/<?= $version->getId() ?>" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
                                        <a title="Érvénytelenítés" data-bs-toggle="tooltip" href="<?= URL ?>admin/process/documents/invalidation/d/<?= $version->getId() ?>" class="btn btn-sm btn-secondary"><i class="fa fa-times"></i></a>
                                    <?php } else { ?>
                                        <a target="_blank" title="Letöltés" data-bs-toggle="tooltip" href="<?= URL ?>admin/process/documents/download/d/<?= $version->getId() ?>" class="btn btn-sm btn-secondary"><i class="fa fa-download"></i></a>
                                    <?php } ?>
                                    </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3 class="h4 my-3">
            Új verzió feltöltése
        </h3>

        <form action="<?= URL ?>admin/process/documents/addVersion" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="file" class="form-label">Fájl</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div class="form-group mb-3">
                <label for="changes" class="form-label">Módosítások</label>
                <textarea class="form-control" id="changes" name="changes" rows="3" class="form-control" required></textarea>
            </div>

            <input type="hidden" name="document_id" value="<?= $document->getId() ?>">
            <button onclick="loader_start()" type="submit" class="btn btn-primary float-end">Feltöltés</button>
        </form>
    </div>
</div>