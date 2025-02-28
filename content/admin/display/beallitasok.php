<?php
$pageMeta = [
    'title' => 'Beállítások',
];
?>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <h3 class="h4">Címkék</h3>
                <ul class="list-group">
                    <?php
                    $labels = $con->query("SELECT * FROM `projects_tags` ORDER BY `name` ASC");
                    foreach ($labels as $row) {
                        echo "<a href='" . URL . "admin/process/settings/deleteTag/d/" . $row['id'] . "' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center deleteButton'>" . $row['name'] . " <i class='fa fa-trash deleteIcon'></i></a>";
                    }
                    ?>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <h3 class="h4">Dokumentumtípusok</h3>
                <form action="<?= URL ?>admin/process/settings/addDoctype" method="post" class="needs-validation" novalidate>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Dokumentumtípus" required>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i></button>
                    </div>
                </form>
                <ul class="list-group">
                    <?php
                    $labels = $con->query("SELECT * FROM `documents_types` ORDER BY `name` ASC");
                    foreach ($labels as $row) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . $row['name'] . "</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="row-12">
                <h3 class="h4">Felhasználók</h3>

                <button class="btn btn-primary my-3" onclick="modal_open('beallitasok/felhasznaloUj')">Új felhasználó</button>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Név</th>
                                <th>E-mail cím</th>
                                <th>Utolsó bejelentkezés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = User::getAll();

                            foreach ($users as $user) {
                                echo "<tr>";
                                echo "<td>" . $user->getId() . "</td>";
                                echo "<td>" . $user->getFullname() . "</td>";
                                echo "<td>" . $user->getEmail() . "</td>";
                                echo "<td>" . $user->getLastLogin() . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h3 class="h4">Műveletek</h3>
            <a href="<?= URL ?>admin/process/settings/gitpull" class="btn btn-primary">Frissítés</a>
        </div>
    </div>
</div>