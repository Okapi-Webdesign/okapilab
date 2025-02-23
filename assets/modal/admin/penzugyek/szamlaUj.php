<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

if (isset($_POST['p'])) {
    $p = new Project($_POST['p']);
    $p = $p->getId();
}
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Új számla
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/finances/invoiceAdd" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="project" class="form-label">Projekt</label>
                <select id="project" name="project" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $projects = Project::getAll();
                    foreach ($projects as $project) {
                        $s = '';
                        if (isset($p)) {
                            $s = $project->getId() == $p ? ' selected' : '';
                        }
                        echo '<option ' . $s . ' value="' . $project->getId() . '">' . $project->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="file" class="form-label">Fájl</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="invoice_id" class="form-label">Sorszám</label>
                <input type="text" id="invoice_id" name="invoice_id" class="form-control text-uppercase" required value="IOOJN-<?= date('Y') ?>-">
            </div>
            <div class="col-12 col-md-6">
                <label for="amount" class="form-label">Összeg</label>
                <div class="input-group">
                    <input type="number" id="amount" name="amount" class="form-control" required min="0">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="create_date" class="form-label">Kiállítás dátuma</label>
                <input type="date" id="create_date" name="create_date" class="form-control" required max="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="deadline" class="form-label">Befizetés határideje</label>
                <input type="date" id="deadline" name="deadline" class="form-control" required max="9999-12-31">
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

<script>
    $('document').ready(function() {
        $('#create_date').change(function() {
            $('#deadline').attr('min', $(this).val());
            // 8 nap hozzáadása
            let date = new Date($(this).val());
            date.setDate(date.getDate() + 8);
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            if (day < 10) {
                day = '0' + day;
            }

            if (month < 10) {
                month = '0' + month;
            }

            $('#deadline').val(year + '-' + month + '-' + day);
        });
    });
</script>