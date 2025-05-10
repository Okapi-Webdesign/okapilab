<?php
$pageMeta = [
    'title' => 'Dokumentum generálása',
    'packages' => ['select2']
];
?>

<form method="post" class="needs-validation" novalidate action="<?= URL ?>admin/process/documents/generate">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="documentType" class="form-label">Dokumentum típusa</label>
                <select id="documentType" class="form-control" name="documentType" required>
                    <option value="">Válassz típust</option>
                    <option value="munkalap">Munkalap</option>
                    <option value="szerzodes_k">Megbízási szerződés honlapkészítésre</option>
                    <option value="szerzodes_ku">Megbízási szerződés honlapkészítésre és -üzemeltetésre</option>
                </select>
            </div>
            <hr>
            <div class="form-group mb-3">
                <label for="project" class="form-label">Projekt</label>
                <select id="project" name="project" class="select2" required>
                    <option value="">Válassz projektet</option>
                    <?php
                    $projects = Project::getAll();
                    foreach ($projects as $project) {
                        echo '<option value="' . $project->getId() . '">' . $project->getName() . '</option>';
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Kérlek válassz egy projektet!
                </div>
            </div>
            <div id="formContainer">
                <div id="form_munkalap">
                    <div class="innerFormContainer">
                        <!-- Alapadatok -->
                        <div class="row mb-4 g-3">
                            <h5>Alapadatok</h5>
                            <div class="col-md-8">
                                <label for="szolgaltatasok" class="form-label">Szolgáltatások</label>
                                <select id="szolgaltatasok" name="szolgaltatasok[]" class="form-select select2-tags" multiple required>
                                    <option>Arculattervezés</option>
                                    <option>Webdesign</option>
                                    <option>Webfejlesztés</option>
                                    <option>Webshop-készítés</option>
                                    <option>Üzemeltetés</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="nyelv" class="form-label">Nyelv(ek)</label>
                                <select id="nyelv" name="nyelv[]" class="form-select select2-tags" multiple required>
                                    <option selected>Magyar</option>
                                    <option>Angol</option>
                                    <option>Német</option>
                                </select>
                            </div>
                        </div>

                        <!-- Weboldal tartalom -->
                        <div class="row mb-4 g-3">
                            <h5>Weboldal tartalom</h5>
                            <div class="col-md-4">
                                <label for="arculatterv" class="form-label">Arculattervezés</label>
                                <select id="arculatterv" name="arculatterv" class="form-select">
                                    <option value="1">Igen</option>
                                    <option value="0">Nem</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="stilus" class="form-label">Színvilág, stílus</label>
                                <input type="text" class="form-control" id="stilus" name="stilus">
                            </div>
                            <div class="col-md-4">
                                <label for="szoveges_tartalom" class="form-label">Szöveges tartalom</label>
                                <select id="szoveges_tartalom" name="szoveges_tartalom" class="form-select">
                                    <option value="1">Biztosított</option>
                                    <option value="0">Nem biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="kepes_tartalom" class="form-label">Képes tartalom</label>
                                <select id="kepes_tartalom" name="kepes_tartalom" class="form-select">
                                    <option value="1">Biztosított</option>
                                    <option value="0">Nem biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tobbnyelvu" class="form-label">Többnyelvű</label>
                                <select id="tobbnyelvu" name="tobbnyelvu" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="funkciok" class="form-label">Egyedi funkciók</label>
                                <textarea id="funkciok" name="funkciok" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Webshop -->
                        <div class="row mb-4 g-3">
                            <h5>Webshop</h5>
                            <div class="col-md-4">
                                <label for="webshop" class="form-label">Webshop szükséges?</label>
                                <select id="webshop" name="webshop" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-md-4 webshop_formContainer">
                                <label for="webshop_termekek" class="form-label">Termékek száma</label>
                                <select id="webshop_termekek" name="webshop_termekek" class="form-select">
                                    <option value="0">Egy termék</option>
                                    <option value="1">Több termék</option>
                                    <option value="2">Több termékkategória</option>
                                </select>
                            </div>
                            <div class="col-md-4 webshop_formContainer">
                                <label for="webshop_fizetes" class="form-label">Fizetési módok</label>
                                <select id="webshop_fizetes" name="webshop_fizetes[]" class="form-select select2" multiple>
                                    <option value="0">Banki átutalás</option>
                                    <option value="1">Készpénzes utánvét</option>
                                    <option value="2">Bankkártyás fizetés</option>
                                </select>
                            </div>
                        </div>

                        <!-- Technikai részletek -->
                        <div class="row mb-4 g-3">
                            <h5>Technikai részletek</h5>
                            <div class="col-md-4">
                                <label for="tartalomkezelo" class="form-label">Tartalomkezelő</label>
                                <select id="tartalomkezelo" name="tartalomkezelo" class="form-select select2-tags">
                                    <option value="_wp">WordPress</option>
                                    <option value="_egyedi">Egyedi fejlesztés</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="webtarhely" class="form-label">Webtárhely</label>
                                <select id="webtarhely" name="webtarhely" class="form-select">
                                    <option value="0">Igényelt</option>
                                    <option value="1">Biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="size" class="form-label">Webtárhely mérete</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="size" name="webtarhely_meret" min="0">
                                    <span class="input-group-text">GB</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="cpanel" class="form-label">cPanel hozzáférés</label>
                                <select id="cpanel" name="cpanel" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="domain" class="form-label">Domain</label>
                                <select id="domain" name="domain" class="form-select">
                                    <option value="0">Igényelt</option>
                                    <option value="1">Biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="domain_name" class="form-label">Domain név</label>
                                <input type="text" class="form-control" id="domain_name" name="domain_name">
                            </div>
                        </div>

                        <!-- Megjegyzés -->
                        <div class="row mb-4 g-3">
                            <h5>Megjegyzés</h5>
                            <div class="col-12">
                                <textarea id="megjegyzes" name="megjegyzes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="form_szerzodes_k">Szerződés K</div>
                <div id="form_szerzodes_ku">Szerződés KÜ</div>
            </div>

            <div class="form-group mt-3" id="submitBtn">
                <button type="submit" class="btn btn-primary float-end">Generálás</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('document').ready(function() {
        $('#formContainer > div').hide(); // Hide all forms initially
        $("#submitBtn").hide(); // Hide the submit button initially

        $('#documentType').change(function() {
            var selectedType = $(this).val();
            $('#formContainer > div').hide(); // Hide all forms
            $("#submitBtn").hide(); // Hide the submit button
            if (selectedType) {
                $('#form_' + selectedType).show(); // Show the selected form
                $("#submitBtn").show(); // Show the submit button
            }
        });

        $('#webshop').change(function() {
            if ($(this).val() == 1) {
                $('.webshop_formContainer').show();
            } else {
                $('.webshop_formContainer').hide();
            }
        });

        $('#webshop').trigger('change'); // Trigger change to set initial state
    });
</script>