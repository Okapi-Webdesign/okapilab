<?php
$pageMeta = [
    'title' => 'Projekt adatlap',
];

$project = new Project($data[0]);
?>

<div class="card">
    <div class="card-body">
        <h2 class="display-6">
            <i class="fa-brands fa-wordpress me-2 <?= $project->isWordpress() == false ? 'd-none' : '' ?>"></i> <?= $project->getName() ?>
        </h2>
        <p>
            <span class="me-3"><b>Ügyfél:</b> <a href="<?= URL ?>admin/ugyfelek/adatlap/d/<?= $project->getClient()->getId() ?>" class="text-decoration-none"><?= $project->getClient()->getName() ?></a></span>
            <span class="me-3"><b>Weboldal:</b> <?php
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
                                                    echo '-';
                                                }
                                                ?></span>
            <span><b>Státusz:</b> <?= $project->getStatus()->getName() ?></span>
        </p>
        <hr>

        <div class="row">
            <div class="col-4 d-none d-lg-block">
                <img class="w-100 d-none rounded shadow-sm" id="websiteScreenshot">
                <?php
                if ($project->getUrl() !== NULL) {
                ?>
                    <p class="text-center mt-2 mb-0">
                        <small>
                            <a href="#" class="text-decoration-none text-small" id="websiteScreenshotRegenerate"><i class="fa fa-rotate me-1"></i> Kép újragenerálása</a>
                        </small>
                    </p>
                <?php
                }
                ?>
            </div>
            <div class="col-12 col-lg-8"></div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $.ajax({
            url: '<?= URL ?>assets/ajax/admin/projects/getScreenshot.php',
            type: 'POST',
            data: {
                project: '<?= $project->getId() ?>'
            },
            success: function(response) {
                $('#websiteScreenshot').attr('src', response);
                $('#websiteScreenshot').removeClass('d-none');
            }
        });

        $.ajax({
            url: 'https://api.apiflash.com/v1/urltoimage/quota',
            type: 'GET',
            data: {
                access_key: '82a2812f2d0143f7b0b6d8298a25f965'
            },
            success: function(response) {
                if (response.remaining <= 0) {
                    $('#websiteScreenshotRegenerate').remove();
                }
            }
        });

        $('#websiteScreenshotRegenerate').click(function() {
            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/regenerateScreenshot.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>'
                },
                success: function(response) {
                    if (response == 'error') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt a kép generálása során!'
                        });
                        return;
                    }
                    $('#websiteScreenshot').attr('src', response);
                }
            });
        });
    });
</script>