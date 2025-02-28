<?php
$pageMeta = [
    'title' => 'WordPress kezelőfelület',
];

$project = new Project($data[0]);

if (!$project->isWordpress()) {
    alert_redirect('error', URL . 'projektek/adatlap/d/' . $data[0], 'A projekt nem WordPress weboldal!');
}

$wp = new WordPressConnection($project);
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex gap-3 align-items-center justify-content-between mb-3 flex-column flex-md-row border-bottom pb-3">
            <div class="d-flex gap-3 align-items-start">
                <i class="fa-brands fa-wordpress me-1 mt-2 display-4"></i>
                <div>
                    <h2 class="display-4">
                        <?php echo $project->getName(); ?>
                    </h2>

                    <a href="<?= URL ?>admin/projektek/adatlap/d/<?= $project->getId() ?>" class="text-decoration-none d-none d-md-inline-block">
                        <i class="fa fa-arrow-left me-1"></i>
                        Vissza a projekthez
                    </a>
                </div>
            </div>

            <div class="action-buttons">
                <a href="<?= URL ?>admin/projektek/adatlap/d/<?= $project->getId() ?>" class="d-inline-block d-md-none btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i>
                    Vissza a projekthez
                </a>

                <a target="_blank" href="<?= URL ?>admin/process/projects/wp/login/d/<?= $project->getId() ?>" class="btn btn-primary btn-sm">
                    <i class="fa-brands fa-wordpress me-1"></i>
                    Kezelőfelület megnyitása
                </a>

                <button class="btn btn-danger btn-sm" onclick="modal_open('projektek/wpKapcsolatBontas', {id: <?= $project->getId() ?>})">
                    <i class="fa fa-unlink me-1"></i>
                    Kapcsolat bontása
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-4 d-none d-xl-block">
                <img src="<?php
                            if ($project->getImageUri() === NULL) {
                                echo 'https://placehold.co/300x200/FF9E00/FEFEFE?font=raleway&text=' . str_replace(' ', '+', $project->getName());
                            } else {
                                echo $project->getImageUri() . '?a=' . time();
                            }
                            ?>" class="w-100 rounded shadow-sm <?= $project->getUrl() == NULL ? '' : 'webOpener" style="cursor:pointer;' ?>" id="websiteScreenshot">
            </div>

            <div class="col-12 col-xl-8">
                <div class="row row-cols-1 row-cols-lg-2 g-3">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Adatkapcsolat</h5>
                            </div>
                            <div class="card-body">
                                <b>Kapcsolat állapota:</b> <?= $wp->testconnection() ? '<span class="badge bg-success">Kapcsolódva</span>' : '<span class="badge bg-danger">Nincs kapcsolat</span>' ?><br>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Karbantartási üzemmód</h5>
                            </div>

                            <div class="card-body">
                                <b>Karbantartási üzemmód:</b> <?= $wp->isMaintenanceMode() ? '<span class="badge text-bg-warning">Bekapcsolva</span>' : '<span class="badge bg-secondary">Kikapcsolva</span>' ?><br>

                                <button class="btn btn-<?= $wp->isMaintenanceMode() ? 'secondary' : 'warning' ?> btn-sm mt-3" onclick="modal_open('projektek/wpKarbantartas', {id: <?= $project->getId() ?>})">
                                    <i class="fa fa-wrench me-1"></i>
                                    <?= $wp->isMaintenanceMode() ? 'Karbantartás kikapcsolása' : 'Karbantartás bekapcsolása' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>