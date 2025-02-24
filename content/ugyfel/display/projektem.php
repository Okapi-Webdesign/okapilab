<?php
$pageMeta = [
    'title' => 'Projektem',
];

$project = new Project($_SESSION['project']);
if ($project->getClient()->getId() != $user->getClient()->getId()) {
    alert_redirect('error', URL . 'ugyfel/profil/projektvalaszto');
}
?>

<div class="card">
    <div class="card-body">
        <div id="projectHeader" class="d-flex justify-content-md-between justify-content-start flex-column flex-md-row align-items-center pb-2 mb-2 border-bottom">
            <h2 class="display-4">
                <?= $project->getName() ?>
            </h2>
            <div class="action-buttons">
                <?php
                if ($project->isWordpress() && $project->getWordpressLogin() != NULL && $project->getUrl() != NULL) {
                ?>
                    <a data-bs-toggle="tooltip" title="WordPress adminisztrációs felület megnyitása (új oldalon)" href="<?= URL ?>ugyfel/process/project/wpLogin" class="btn btn-primary" target="_blank">
                        <i class="fa fa-brands fa-wordpress me-2"></i> WP Admin
                    </a>

                    <a data-bs-toggle="tooltip" title="Weboldal megtekintése (új oldalon)" href="<?= $project->getUrl() ?>" class="btn btn-secondary" target="_blank">
                        <i class="fa fa-globe"></i>
                    </a>
                <?php
                } else if ($project->getUrl() != NULL) {
                ?>
                    <a data-bs-toggle="tooltip" title="Weboldal megtekintése (új oldalon)" href="<?= $project->getUrl() ?>" class="btn btn-primary" target="_blank">
                        <i class="fa fa-globe me-2"></i> Weboldal
                    </a>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <img src="<?php
                            if ($project->getImageUri() === NULL) {
                                echo 'https://placehold.co/300x200/FF9E00/FEFEFE?font=raleway&text=' . str_replace(' ', '+', $project->getName());
                            } else {
                                echo $project->getImageUri() . '?a=' . time();
                            }
                            ?>" class="w-100 rounded shadow-sm <?= $project->getUrl() == NULL ? '' : 'webOpener" style="cursor:pointer;' ?>" id="websiteScreenshot">
            </div>
            <div class="col-12 col-md-8">
                <h3 class="h4">Adatok</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                    <div class="col">
                        <b>Weboldal:</b> <br>
                        <?php
                        $displayUrl = $project->getUrl();
                        if ($displayUrl !== NULL) {
                            $displayUrl = str_replace('http://', '', $displayUrl);
                            $displayUrl = str_replace('https://', '', $displayUrl);
                            // URL végéről / eltávolítása
                            if (substr($displayUrl, -1) == '/') {
                                $displayUrl = substr($displayUrl, 0, -1);
                            }
                            echo '<a href="' . $displayUrl . '" target="_blank" class="text-decoration-none">' . $displayUrl . '</a>';
                        } else {
                            echo 'Nincs megadva.';
                        }
                        ?>
                    </div>
                    <div class="col">
                        <b>Státusz:</b> <br>
                        <?php
                        if (!$project->isActive()) echo '<span class="badge text-bg-secondary"><i class="fa fa-archive"></i> Archív</span>';
                        else {
                            echo $project->getStatus()->print();
                        }
                        ?>
                    </div>
                    <div class="col">
                        <b>Igényelt szolgáltatások:</b> <br>
                        <ul>
                            <?php
                            foreach ($project->getServices() as $service) {
                                echo '<li>' . $service . '</li>';
                            }

                            if ($project->getServices() == NULL || count($project->getServices()) == 0) {
                                echo '<li>Nincs megadva.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <h3 class="h4 mt-3">Dokumentumok</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                    <?php
                    $docs = $project->getDocuments();
                    if ($docs == NULL || count($docs) == 0) {
                        echo '<div class="col">Nincs dokumentum feltöltve.</div>';
                    } else {
                        foreach ($docs as $doc) {
                            $current = $doc->getCurrent();
                            $file = $current->getFilename();

                    ?>
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $doc->getType()->getName() ?></h5>

                                        <p class="card-text">
                                            <b>Feltöltés dátuma:</b> <?= $current->getDate(true) ?> <br>
                                            <b>Állapot:</b> <?= $current->isActive() ? '<span class="badge bg-success">Aktuális</span>' : '<span class="badge bg-secondary">Elavult</span>' ?> <br>
                                            <b>Méret:</b> <?= round(filesize(ABS_PATH . 'storage/' . $project->getId() . '/' . $file) / 1024) ?> KB
                                        </p>

                                        <a href="<?= URL ?>ugyfel/process/project/download/<?= $current->getId() ?>" class="btn btn-primary">
                                            <i class="fa fa-download me-2"></i> Letöltés
                                        </a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('.webOpener').click(function() {
            window.open('<?= $project->getUrl() ?>', '_blank');
        });
    });
</script>