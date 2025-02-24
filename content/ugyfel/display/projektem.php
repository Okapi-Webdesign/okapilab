<?php
$pageMeta = [
    'title' => 'Projektem',
];

$project = new Project($_SESSION['project']);
if ($project->getClient()->getId() != $user->getClient()->getId()) {
    alert_redirect('error', URL . 'ugyfel/profil/projektvalaszto');
}
?>

<div class="card p-2">
    <div class="card-body">
        <div id="projectHeader" class="d-flex justify-content-md-between justify-content-start flex-column flex-md-row align-items-center pb-2 border-bottom border-dark">
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

        <div class="row g-3 mt-2">
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

                        if ($project->isWordpress()) {
                            echo '<br><i class="fa-brands fa-wordpress me-1"></i> WordPress';
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

                <h3 class="h4 mt-3 pt-3 border-top">Bejelentkezési adatok</h3>
                <p class="text-muted">
                    A jelszó felfedéséhez kattints a mezőre!
                </p>

                <button class="btn btn-primary mb-3" onclick="modal_open('projekt/hozzaferesHozzaad')"><i class="fa fa-plus me-2"></i> Új adat</button>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:33%;">Platform</th>
                                <th style="width:33%;">Felhasználónév</th>
                                <th style="width:33%;">Jelszó</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($project->getLogins()) == 0) {
                                echo '<tr><td colspan="4">Nincs rögzített bejelentkezési adat.</td></tr>';
                            } else {
                                foreach ($project->getLogins() as $login) {
                                    echo '<tr>';
                                    echo '<td><a href="' . $login->getUrl() . '" target="_blank" class="text-decoration-none">' . $login->getName() . '</a></td>';
                                    echo '<td>' . $login->getUsername() . '</td>';
                                    echo '<td class="passwordTd" data-pw="' . $login->getPassword() . '" style="cursor:pointer;">********</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
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

        $('.passwordTd').click(function() {
            pw = $(this).data('pw');
            if ($(this).text() == pw) {
                $(this).text('********');
            } else {
                $(this).text(pw);
            }
        });
    });
</script>