<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Új projekt
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>
<form action="<?= URL ?>admin/process/projects/add" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="name" class="form-label">Név</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="client" class="form-label">Ügyfél</label>
                <select id="client" name="client" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $clients = Client::getAll();
                    foreach ($clients as $client) {
                        echo '<option value="' . $client->getId() . '">' . $client->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="url" class="form-label">Weboldal <span class="text-muted">(ha van)</span></label>
                <input type="url" id="url" name="url" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label for="status" class="form-label">Státusz</label>
                <select id="status" name="status" class="form-select" required>
                    <?php
                    $sql = 'SELECT `id`, `name` FROM `projects_status` WHERE `name` <> "Befejezett" AND `name` <> "Archív" ORDER BY `id`';
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="manager" class="form-label">Projektvezető</label>
                <select name="manager" id="manager" class="form-select" required>
                    <?php
                    $users = User::getAll();
                    foreach ($users as $_user) {
                        echo '<option value="' . $_user->getId() . '">' . $_user->getFullName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="email_send" name="email_send" checked value="1">
                    <label class="form-check-label" for="email_send">
                        E-mail kiküldése az ügyfélnek
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>