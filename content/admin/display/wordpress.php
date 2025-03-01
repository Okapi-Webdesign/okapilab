<?php
$pageMeta = [
    'title' => 'WordPress kezelőfelület',
];
?>

<div class="card">
    <div class="card-body">
        <dic class="row g-3">
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card shadow-none border mb-3">
                    <div class="card-body">
                        <h3 class="h4">WordPress verzió</h3>
                        <p>
                            <b>Legfrissebb WordPress verzió:</b> <?= WordPressConnection::getLatestWpVersion() ?><br>
                        </p>

                        <ul class="list-group">
                            <?php
                            foreach (WordPressConnection::getConnectedSites() as $site) {
                                $wp = new WordPressConnection($site);
                            ?>
                                <a href="<?= URL ?>admin/projektek/wordpress/d/<?= $site->getId() ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?= $site->getName() ?>
                                    <span class="wpCoreVersion" data-project="<?= $site->getId() ?>">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Betöltés...</span>
                                        </div>
                                    </span>
                                </a>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-none border mb-3">
                    <div class="card-body">
                        <h3 class="h4">OkapiLab plugin verzió</h3>
                        <p>
                            <b>Legfrissebb plugin verzió:</b> <a href="#" class="text-decoration-none" onclick="modal_open('wp/pluginVerzio')">
                                <?= getSetting('wp_plugin_version') ?>
                            </a>
                        </p>

                        <ul class="list-group">
                            <?php
                            foreach (WordPressConnection::getConnectedSites() as $site) {
                                $wp = new WordPressConnection($site);
                            ?>
                                <a href="<?= URL ?>admin/projektek/wordpress/d/<?= $site->getId() ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?= $site->getName() ?>
                                    <span class="wpOLPluginVersion" data-project="<?= $site->getId() ?>">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Betöltés...</span>
                                        </div>
                                    </span>
                                </a>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </dic>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('.wpCoreVersion').each(function() {
            var project = $(this).data('project');
            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/wp/getWPVersion',
                method: 'POST',
                data: {
                    project: project,
                    formatted: true
                },
                success: function(data) {
                    $('.wpCoreVersion[data-project="' + project + '"]').html(data);
                }
            });
        });

        $('.wpOLPluginVersion').each(function() {
            var project = $(this).data('project');
            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/wp/getOLPluginVersion',
                method: 'POST',
                data: {
                    project: project,
                    formatted: true
                },
                success: function(data) {
                    $('.wpOLPluginVersion[data-project="' + project + '"]').html(data);
                }
            });
        });
    });
</script>