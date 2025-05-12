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
                    <option value="teljesitesi">Teljesítési igazolás</option>
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
                                <label for="munkalap_szolgaltatasok" class="form-label">Szolgáltatások</label>
                                <select id="munkalap_szolgaltatasok" name="szolgaltatasok[]" class="form-select select2-tags" multiple>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_nyelv" class="form-label">Nyelv(ek)</label>
                                <select id="munkalap_nyelv" name="nyelv[]" class="form-select select2-tags" multiple>
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
                                <label for="munkalap_arculatterv" class="form-label">Arculattervezés</label>
                                <select id="munkalap_arculatterv" name="arculatterv" class="form-select">
                                    <option value="1">Igen</option>
                                    <option value="0">Nem</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="munkalap_stilus" class="form-label">Színvilág, stílus</label>
                                <input type="text" class="form-control" id="munkalap_stilus" name="stilus">
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_szoveges_tartalom" class="form-label">Szöveges tartalom</label>
                                <select id="munkalap_szoveges_tartalom" name="szoveges_tartalom" class="form-select">
                                    <option value="1">Biztosított</option>
                                    <option value="0">Nem biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_kepes_tartalom" class="form-label">Képes tartalom</label>
                                <select id="munkalap_kepes_tartalom" name="kepes_tartalom" class="form-select">
                                    <option value="1">Biztosított</option>
                                    <option value="0">Nem biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_tobbnyelvu" class="form-label">Többnyelvű</label>
                                <select id="munkalap_tobbnyelvu" name="tobbnyelvu" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="munkalap_funkciok" class="form-label">Egyedi funkciók</label>
                                <textarea id="munkalap_funkciok" name="funkciok" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Webshop -->
                        <div class="row mb-4 g-3">
                            <h5>Webshop</h5>
                            <div class="col-md-4">
                                <label for="munkalap_webshop" class="form-label">Webshop szükséges?</label>
                                <select id="munkalap_webshop" name="webshop" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-md-4 webshop_formContainer">
                                <label for="munkalap_webshop_termekek" class="form-label">Termékek száma</label>
                                <select id="munkalap_webshop_termekek" name="webshop_termekek" class="form-select">
                                    <option value="0">Egy termék</option>
                                    <option value="1">Több termék</option>
                                    <option value="2">Több termékkategória</option>
                                </select>
                            </div>
                            <div class="col-md-4 webshop_formContainer">
                                <label for="munkalap_webshop_fizetes" class="form-label">Fizetési módok</label>
                                <select id="munkalap_webshop_fizetes" name="webshop_fizetes[]" class="form-select select2" multiple>
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
                                <label for="munkalap_tartalomkezelo" class="form-label">Tartalomkezelő</label>
                                <select id="munkalap_tartalomkezelo" name="tartalomkezelo" class="form-select select2-tags">
                                    <option value=""></option>
                                    <option value="_wp">WordPress</option>
                                    <option value="_egyedi">Egyedi fejlesztés</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_webtarhely" class="form-label">Webtárhely</label>
                                <select id="munkalap_webtarhely" name="webtarhely" class="form-select">
                                    <option value="0">Igényelt</option>
                                    <option value="1">Biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_size" class="form-label">Webtárhely mérete</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="munkalap_size" name="webtarhely_meret" min="0">
                                    <span class="input-group-text">GB</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_cpanel" class="form-label">cPanel hozzáférés</label>
                                <select id="munkalap_cpanel" name="cpanel" class="form-select">
                                    <option value="0">Nem</option>
                                    <option value="1">Igen</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_domain" class="form-label">Domain</label>
                                <select id="munkalap_domain" name="domain" class="form-select">
                                    <option value="0">Igényelt</option>
                                    <option value="1">Biztosított</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="munkalap_domain_name" class="form-label">Domain név</label>
                                <input type="text" class="form-control" id="munkalap_domain_name" name="domain_name">
                            </div>
                        </div>

                        <!-- Megjegyzés -->
                        <div class="row mb-4 g-3">
                            <h5>Megjegyzés</h5>
                            <div class="col-12">
                                <textarea id="munkalap_megjegyzes" name="megjegyzes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="form_szerzodes_k">
                    <div class="innerFormContainer">
                        <!-- Név, székhely, nyilvántartási szám, adószám, képviselő -->
                        <div class="row mb-4 g-3">
                            <h5>Megrendelő adatai</h5>
                            <div class="col-12">
                                <label for="szerzodes_megrendelo_nev" class="form-label">Név</label>
                                <input type="text" class="form-control" id="szerzodes_megrendelo_nev" name="megrendelo_nev">
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_megrendelo_nytszam" class="form-label">Nyilvántartási szám</label>
                                <input type="text" class="form-control" id="szerzodes_megrendelo_nytszam" name="megrendelo_szam">
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_megrendelo_szekhely" class="form-label">Székhely</label>
                                <input type="text" class="form-control" id="szerzodes_megrendelo_szekhely" name="megrendelo_szekhely">
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_megrendelo_adoszam" class="form-label">Adószám</label>
                                <input type="text" class="form-control" id="szerzodes_megrendelo_adoszam" name="megrendelo_adoszam">
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_megrendelo_kepviselo" class="form-label">Képviselő</label>
                                <input type="text" class="form-control" id="szerzodes_megrendelo_kepviselo" name="megrendelo_kepviselo">
                            </div>
                        </div>

                        <!-- Weboldal és projekt neve, szerződés hatálya, szavatosság időtartama, vállalt határidő, megbízási díj, előleg mértéke és összege, kapcsolattartó neve és elérhetősége, webtárhely-csomag és domain név, számlázási időszak -->
                        <div class="row mb-4 g-3">
                            <h5>Szerződés adatai</h5>
                            <div class="col-md-8">
                                <label for="szerzodes_weboldal" class="form-label">Weboldal neve</label>
                                <input type="text" class="form-control" id="szerzodes_weboldal" name="weboldal">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_hataly" class="form-label">Szerződés hatálya</label>
                                <input type="text" class="form-control" id="szerzodes_hataly" name="hataly" value="Határozatlan ideig">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_szavatossag" class="form-label">Szavatosság időtartama</label>
                                <input type="text" class="form-control" id="szerzodes_szavatossag" name="szavatossag" value="6 hónap">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_hatarido" class="form-label">Vállalt határidő</label>
                                <input type="date" class="form-control" id="szerzodes_hatarido" name="hatarido" min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_megbizasi_dij" class="form-label">Megbízási díj</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="szerzodes_megbizasi_dij" name="megbizasi_dij" min="0">
                                    <span class="input-group-text">Ft</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_eloleg" class="form-label">Előleg mértéke</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="szerzodes_eloleg" name="eloleg" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_preload_osszeg" class="form-label">Előleg összege</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="szerzodes_preload_osszeg" name="preload_osszeg" min="0" disabled>
                                    <span class="input-group-text">Ft</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_kapcsolattarto" class="form-label">Kapcsolattartó neve</label>
                                <input type="text" class="form-control" id="szerzodes_kapcsolattarto" name="kapcsolattarto">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_kapcsolattarto_email" class="form-label">Kapcsolattartó email</label>
                                <input type="email" class="form-control" id="szerzodes_kapcsolattarto_email" name="kapcsolattarto_email">
                            </div>
                            <div class="col-md-4">
                                <label for="szerzodes_kapcsolattarto_tel" class="form-label">Kapcsolattartó telefon</label>
                                <input type="tel" class="form-control" id="szerzodes_kapcsolattarto_tel" name="kapcsolattarto_tel">
                            </div>
                        </div>

                        <div class="row mb-4 g-3" id="form_szerzodes_uzemeltetes">
                            <h5>Üzemeltetés</h5>
                            <div class="col-md-6">
                                <label for="szerzodes_webtarhely" class="form-label">Webtárhely-csomag</label>
                                <select id="szerzodes_webtarhely" name="webtarhely_csomag" class="form-select">
                                    <?php
                                    $webhostingPackages = WHPlan::getAll();
                                    foreach ($webhostingPackages as $package) {
                                        echo '<option value="' . $package->getId() . '">' . $package->getName() . ' csomag</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_domain" class="form-label">Domain név</label>
                                <input type="text" class="form-control" id="szerzodes_domain" name="domain_nev">
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_szamlazasi_idoszak" class="form-label">Számlázási időszak</label>
                                <select id="szerzodes_szamlazasi_idoszak" name="szamlazasi_idoszak" class="form-select">
                                    <option value="0">Havonta</option>
                                    <option value="1">Évente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="szerzodes_szamlazasi_idoszak_dij" class="form-label">Webtárhely díja időszakonként</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="szerzodes_szamlazasi_idoszak_dij" name="szamlazasi_idoszak_dij" min="0">
                                    <span class="input-group-text">Ft</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3" id="submitBtn">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="email_send" name="email_send" checked value="1">
                    <label class="form-check-label" for="email_send">
                        E-mail kiküldése az ügyfélnek
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Generálás</button>
            </div>
        </div>
    </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        function initializeForms() {
            $('#formContainer > div').hide(); // Hide all forms initially
            $("#submitBtn").hide(); // Hide the submit button initially
        }

        function handleDocumentTypeChange() {
            $('#documentType').change(function() {
                var selectedType = $(this).val();
                $('#formContainer > div').hide(); // Hide all forms
                $('#form_szerzodes_uzemeltetes').hide(); // Hide the szerzodes_uzemeltetes form
                $("#submitBtn").hide(); // Hide the submit button
                if (selectedType) {
                    if (selectedType == 'munkalap') {
                        $('#form_munkalap').show(); // Show the munkalap form
                    } else if (selectedType == 'szerzodes_k') {
                        $('#form_szerzodes_k').show(); // Show the szerzodes_k form
                    } else if (selectedType == 'szerzodes_ku') {
                        $('#form_szerzodes_k, #form_szerzodes_uzemeltetes').show(); // Show the szerzodes_ku form
                    }
                    $("#submitBtn").show(); // Show the submit button
                }
            }).trigger('change'); // Trigger change to set initial state
        }

        function munkalap_handleWebshopChange() {
            $('#munkalap_webshop').change(function() {
                if ($(this).val() == 1) {
                    $('.webshop_formContainer').show();
                } else {
                    $('.webshop_formContainer').hide();
                }
            }).trigger('change'); // Trigger change to set initial state
        }

        function handleProjectChange() {
            $('#project').change(function() {
                var projectId = $(this).val();
                if (projectId) {
                    $.ajax({
                        url: '<?= URL ?>assets/ajax/admin/projects/data.php',
                        type: 'POST',
                        data: {
                            project: projectId
                        },
                        dataType: 'json',
                        success: function(r) {
                            console.log(r);
                            data = r.munkalap;
                            if (data.services == null) {
                                data.services = [];
                            }
                            $('#munkalap_szolgaltatasok').empty();
                            $.each(data.services, function(index, service) {
                                $('#munkalap_szolgaltatasok').append('<option selected value="' + service + '">' + service + '</option>');
                            });

                            $('#munkalap_arculatterv').val(data.services.includes('Arculattervezés') ? 1 : 0).trigger('change');
                            $('#munkalap_webshop').val(data.services.includes('Webáruház') || data.services.includes('Webshop') ? 1 : 0).trigger('change');
                            $('#munkalap_tartalomkezelo').val(data.isWordpress ? '_wp' : '').trigger('change');

                            if (data.storage !== false) {
                                $('#munkalap_webtarhely').val(0).trigger('change');
                                $('#munkalap_size').val(data.storage);
                            } else {
                                $('#munkalap_webtarhely').val(1).trigger('change');
                                $('#munkalap_size').val('');
                            }

                            $('#munkalap_domain_name').val(data.domain).trigger('change');
                            $('#munkalap_megjegyzes').val(data.comment).trigger('change');

                            data = r.szerzodes;
                            $('#szerzodes_megrendelo_nev').val(data.client_name).trigger('change');
                            $('#szerzodes_megrendelo_nytszam').val(data.client_registration_number).trigger('change');
                            $('#szerzodes_megrendelo_szekhely').val(data.client_address).trigger('change');
                            $('#szerzodes_megrendelo_adoszam').val(data.client_tax_number).trigger('change');
                            $('#szerzodes_megrendelo_kepviselo').val(data.contact_name).trigger('change');
                            $('#szerzodes_weboldal').val(data.project_name).trigger('change');
                            $('#szerzodes_hatarido').val(data.project_deadline).trigger('change');
                            $('#szerzodes_kapcsolattarto').val(data.contact_name).trigger('change');
                            $('#szerzodes_kapcsolattarto_email').val(data.contact_email).trigger('change');
                            $('#szerzodes_kapcsolattarto_tel').val(data.contact_phone).trigger('change');
                            $('#szerzodes_domain').val(data.domain).trigger('change');
                        }
                    });
                }
            });
        }

        function handlePrerollChange() {
            $('#szerzodes_eloleg, #szerzodes_megbizasi_dij').change(function() {
                var megbizasi_dij = $('#szerzodes_megbizasi_dij').val();
                var eloleg = $('#szerzodes_eloleg').val();
                if (megbizasi_dij && eloleg) {
                    var preload_osszeg = Math.round((megbizasi_dij * parseFloat(eloleg)) / 100 / 1000) * 1000;
                    $('#szerzodes_preload_osszeg').val(preload_osszeg).prop('disabled', false);
                } else {
                    $('#szerzodes_preload_osszeg').val('').prop('disabled', true);
                }
            });
        }

        function initializeHandlers() {
            handleDocumentTypeChange();
            munkalap_handleWebshopChange();
            handleProjectChange();
            handlePrerollChange();
        }

        // Initialize the script
        initializeForms();
        initializeHandlers();
    });
</script>