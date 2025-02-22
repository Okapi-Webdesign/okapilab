<?php
$pageMeta = [
    'title' => 'Projekt adatlap',
    'packages' => ['select2']
];

$project = new Project($data[0]);
$client = $project->getClient();
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column justify-content-xl-between justify-content-center">
            <h2 class="display-4 mb-2 text-center">
                <i class="fa-brands fa-wordpress me-1 <?= $project->isWordpress() == false ? 'd-none' : '' ?>"></i> <?= $project->getName() ?>
            </h2>
            <div class="text-muted spinner-border float-end d-none" role="status" id="loadingSpinner">
                <span class="visually-hidden">Betöltés...</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column">
            <span class="mb-3 mb-xl-0 text-center text-xl-start">
                <span class="me-3 d-inline-block"><b>Ügyfél:</b> <a href="<?= URL ?>admin/ugyfelek/adatlap/d/<?= $project->getClient()->getId() ?>" class="text-decoration-none"><?= $project->getClient()->getName() ?></a></span>
                <span class="me-3 d-inline-block"><b>Weboldal:</b> <?php
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
                                                                    ?></span>
                <?php if ($project->isActive()) { ?> <span class="me-3 d-inline-block"><b>Határidő:</b> <?= $project->getDeadline() == NULL ? 'Nincs megadva.' : $project->getDeadline() ?></span> <?php } else { ?> <span class="me-3 d-inline-block"><b>Garancia határideje:</b> <?= $project->getWarranty() == NULL ? 'Nincs megadva.' : $project->getWarranty() ?></span> <?php } ?>
            </span>
            <div class="action-buttons">
                <?php
                if ($project->getWordpressLogin() !== NULL && $project->isWordpress()) {
                ?>
                    <a target="blank" href="<?= URL ?>admin/process/projects/wpAdmin/d/<?= $project->getId() ?>" title="<i class='fa-solid fa-up-right-from-square me-2'></i>Automatikus belépés a WordPress kezelőfelületére" data-bs-html="true" data-bs-toggle="tooltip" class="btn btn-sm btn-primary"><i class="fa fa-wordpress me-2"></i> WordPress admin</a>
                <?php
                }
                if ($project->isActive()) {
                ?>
                    <button title="Szerkesztés" data-bs-toggle="tooltip" class="btn btn-sm btn-warning" onclick="modal_open('projektek/szerkeszt', {id: <?= $project->getId() ?>})"><i class="fa fa-pencil"></i></button>
                    <button title="Archiválás" data-bs-toggle="tooltip" class="btn btn-sm btn-secondary" onclick="modal_open('projektek/archival', {id: <?= $project->getId() ?>})"><i class="fa fa-archive"></i></button>
                <?php } else { ?>
                    <button title="Visszaállítás" data-bs-toggle="tooltip" class="btn btn-sm btn-success" onclick="window.location.href='<?= URL ?>admin/process/projects/restore/d/<?= $project->getId() ?>'"><i class="fa fa-undo"></i></button>
                    <button title="Törlés" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" onclick="modal_open('projektek/torol', {id: <?= $project->getId() ?>})"><i class="fa fa-trash"></i></button>
                <?php } ?>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-4 d-none d-xl-block">
                <img src="<?php
                            if ($project->getImageUri() === NULL) {
                                echo 'https://placehold.co/300x200/FF9E00/FEFEFE?font=raleway&text=' . str_replace(' ', '+', $project->getName());
                            } else {
                                echo $project->getImageUri() . '?a=' . time();
                            }
                            ?>" class="w-100 rounded shadow-sm <?= $project->getUrl() == NULL ? '' : 'webOpener" style="cursor:pointer;' ?>" id="websiteScreenshot">
                <?php
                // access key
                $access_key = '82a2812f2d0143f7b0b6d8298a25f965';
                $file_content = file_get_contents('https://api.apiflash.com/v1/urltoimage/quota?access_key=82a2812f2d0143f7b0b6d8298a25f965');

                // convert to array
                $json = json_decode($file_content, true);
                if ($project->getUrl() !== NULL && $json['remaining'] > 5) {
                ?>
                    <p class="text-center mt-2 mb-0">
                        <small>
                            <a href="<?= URL ?>admin/process/projects/regenerateScreenshot/d/<?= $project->getId() ?>" title="<?= $json['remaining'] ?> lehetőség maradt a hónapban." data-bs-toggle="tooltip" class="text-decoration-none text-small" id="websiteScreenshotRegenerate"><i class="fa fa-rotate me-1"></i> Kép újragenerálása</a>
                        </small>
                    </p>
                <?php
                }

                if ($project->isActive()) {
                ?>

                    <div class="mt-4 text-center">
                        <?php
                        if ($project->getStatus()->getId() == ProjectStatus::getMax()->getId()) {
                            echo '<button onclick="modal_open(\'projektek/archival\', {id: ' . $project->getId() . '})" class="btn btn-secondary"><i class="fa fa-archive me-2"></i>Archiválás</button>';
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-12 col-xl-8">
                <h3 class="h4">Adatok</h3>
                <div class="row mb-3 g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Ügyfél</b> <br>
                        <?= $client->getName() ?> <br>
                        <a href="mailto:<?= $client->getEmail() ?>" class="text-decoration-none"><?= $client->getEmail() ?></a> <br>
                        <a href="tel:<?= $client->getPhone() ?>" class="text-decoration-none"><?= $client->getPhone(true) ?></a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Weboldal</b> <br>
                        <?php
                        if ($project->getUrl() !== NULL) {
                        ?><a href="<?= $project->getUrl() ?>" target="_blank" class="text-decoration-none"><?= $displayUrl ?></a> <br>
                        <?php } else {
                            echo 'Nincs megadva.';
                        } ?>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Státusz</b> <br>
                        <?= $project->isActive() ? '<span class="badge bg-success"><i class="fa fa-check me-1"></i> Aktív</span>' : '<span class="badge bg-secondary"><i class="fa fa-archive me-1"></i> Archív</span>' ?>
                    </div>
                    <div class="col-12">
                        <b>Státusz</b> <br>
                        <select id="statusSelect" class="select2" <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            $statuses = ProjectStatus::getAll();
                            foreach ($statuses as $status) {
                                echo '<option value="' . $status->getId() . '" ' . ($status->getId() == $project->getStatus()->getId() ? 'selected' : '') . '>' . $status->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Címkék</b>
                        <select name="tagsSelect" id="tagsSelect" class="select2-tags" multiple <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            if ($stmt = $con->prepare('SELECT `id`, `name` FROM `projects_tags` ORDER BY `name` ASC')) {
                                $stmt->execute();
                                $stmt->store_result();
                                $stmt->bind_result($id, $name);
                                $current_tags = $project->getTags();
                                if ($current_tags == NULL) {
                                    $current_tags = [];
                                }
                                while ($stmt->fetch()) {
                                    echo '<option value="' . $id . '" ' . (in_array($id, $current_tags) ? 'selected' : '') . '>' . $name . '</option>';
                                }
                                $stmt->close();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Szolgáltatás</b>
                        <select name="servicesSelect" id="servicesSelect" class="select2-tags" multiple <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            $services = [
                                'Arculattervezés',
                                'Webdesign',
                                'Webfejlesztés',
                                'Webáruház',
                                'Egyedi fejlesztés'
                            ];

                            $project_services = $project->getServices();
                            if ($project_services == NULL) {
                                $project_services = [];
                            }

                            foreach ($services as $service) {
                                echo '<option value="' . $service . '" ' . (in_array($service, $project_services) ? 'selected' : '') . '>' . $service . '</option>';
                                if (in_array($service, $project_services)) {
                                    $key = array_search($service, $project_services);
                                    unset($project_services[$key]);
                                }
                            }

                            foreach ($project_services as $service) {
                                echo '<option value="' . $service . '" selected>' . $service . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <b>Megjegyzés</b> <br>
                        <textarea id="comment" class="form-control" <?= $project->isActive() ? '' : 'disabled' ?>><?= $project->getComment() ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <?php
            if ($project->getTrelloId() !== false) {
            ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-trello-tab" data-bs-toggle="pill" data-bs-target="#pills-trello" type="button" role="tab" aria-controls="pills-trello" aria-selected="false">Feladatok</button>
                </li>
            <?php
            }
            ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $project->getTrelloId() === false ? 'active' : '' ?>" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-controls="pills-login" aria-selected="true">Bejelentkezési adatok</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-docs-tab" data-bs-toggle="pill" data-bs-target="#pills-docs" type="button" role="tab" aria-controls="pills-docs" aria-selected="false">Dokumentumok</button>
            </li>
        </ul>
        <div class="tab-content px-1" id="pills-tabcontent">
            <div class="tab-pane <?= $project->getTrelloId() === false ? 'active show' : '' ?>" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab" tabindex="0">
                <h3 class="h4 mb-3">
                    Bejelentkezési adatok
                </h3>
                <button class="btn btn-primary" onclick="modal_open('projektek/hozzaferesHozzaad', {id: <?= $project->getId() ?>})">
                    Új rekord
                </button>

                <div class="table-responsive mt-2">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:25%;">Felület</th>
                                <th style="width:25%;">Felhasználónév</th>
                                <th style="width:25%;">Jelszó</th>
                                <th style="width:25%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($project->isWordpress() && $project->getWordpressLogin() === NULL) {
                                echo '<tr><td colspan="4"><a href="#" onclick="modal_open(\'projektek/hozzaferesHozzaad\', {id: ' . $project->getId() . ', wp: 1})">WordPress belépési adat hozzáadása</a></td></tr>';
                            }

                            if (count($project->getLogins()) == 0) {
                                echo '<tr><td colspan="4">Nincs rögzített bejelentkezési adat.</td></tr>';
                            } else {
                                foreach ($project->getLogins() as $login) {
                                    echo '<tr>';
                                    echo '<td><a href="' . $login->getUrl() . '" target="_blank" class="text-decoration-none">' . $login->getName() . '</a></td>';
                                    echo '<td>' . $login->getUsername() . '</td>';
                                    echo '<td class="passwordTd" data-pw="' . $login->getPassword() . '" style="cursor:pointer;">********</td>';
                                    echo '<td class="text-end"><div class="action-buttons">';
                                    echo '<button class="btn btn-sm btn-warning" onclick="modal_open(\'projektek/hozzaferesSzerkeszt\', {id: ' . $login->getId() . '})"><i class="fa fa-pencil"></i></button> ';
                                    echo '<a href="' . URL . 'admin/process/projects/loginDelete/d/' . $login->getId() . '" onclick="loader_start();" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                                    echo '</div></td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="pills-docs" role="tabpanel" aria-labelledby="pills-docs-tab" tabindex="0">
                <h3 class="h4">Dokumentumok</h3>
                <?php if ($project->isActive()) { ?><button class="mt-2 btn btn-primary" onclick="modal_open('dokumentumok/letrehozas', {p: <?= $project->getId() ?>})">
                        Új dokumentum
                    </button> <?php } ?>

                <div class="mt-2 row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                    <?php
                    $docs = $project->getDocuments();

                    if (count($docs) == 0) {
                        echo '<div class="col">Nincs rögzített dokumentum.</div>';
                    } else {
                        foreach ($docs as $doc) {
                    ?>
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="<?= URL ?>admin/dokumentumok/adatlap/d/<?= $doc->getId() ?>" class="btn btn-sm btn-primary float-end rounded-pill"><i class="fa fa-eye"></i></a>
                                        <h5 class="card-title
                                        "><?= $doc->getType()->getName() ?></h5>

                                        <p class="card-text">
                                            <b>Dátum:</b> <?= $doc->getCurrent()->getDate() ?> <br>
                                            <b>Feltöltő:</b> <?= $doc->getCurrent()->getUser()->getFullname() ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="tab-pane <?= $project->getTrelloId() !== false ? 'active show' : '' ?>" id="pills-trello" role="tabpanel" aria-labelledby="pills-trello-tab" tabindex="0">
                <div class="d-flex align-items-center">
                    <strong role="status">Betöltés...</strong>
                    <div class="spinner-border ms-auto" aria-hidden="true"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('#websiteScreenshot.webOpener').click(function() {
            window.open('<?= $project->getUrl() ?>', '_blank');
        });

        $("#websiteScreenshotRegenerate").click(function() {
            loader_start();
        });

        $('#statusSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateStatus.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    status: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        }).then(() => {
                            if ($('#statusSelect').val() == '<?= ProjectStatus::getMax()->getId() ?>') {
                                location.reload();
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });

                        $('#statusSelect').val('<?= $project->getStatus()->getId() ?>');
                    }
                }
            });
        });

        $('#tagsSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateTags.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    tags: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });

        $('#servicesSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateServices.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    services: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });

        $('#comment').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateComment.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    comment: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });

        $('.passwordTd').click(function() {
            pw = $(this).data('pw');
            if ($(this).text() == pw) {
                $(this).text('********');
            } else {
                $(this).text(pw);
            }
        });

        $.ajax({
            url: '<?= URL ?>assets/ajax/admin/projects/getTrello.php',
            type: 'POST',
            data: {
                project: '<?= $project->getId() ?>'
            },
            success: function(response) {
                $('#pills-trello').html(response);
            }
        });
    });
</script>