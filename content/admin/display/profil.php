<?php
$pageMeta = [
    'title' => 'Profil',
    'role' => 2,
    'description' => 'Ezen az oldalon szerkesztheted a profiladataidat.'
];
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6 mb-3 mb-md-0 border-end bordercol">
                <form action="<?= URL ?>admin/process/profile/update" method="post" class="needs-validation" novalidate>
                    <label for="email">E-mail cím</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user->getEmail() ?>" required>
                    <div class="invalid-feedback">Az e-mail cím megadása kötelező!</div>

                    <button type="submit" class="btn btn-primary mt-3">Mentés</button>
                </form>
            </div>
            <div class="col-12 col-md-6">
                <form action="<?= URL ?>admin/process/profile/password" method="post" class="needs-validation" novalidate>
                    <label for="password">Jelenlegi jelszó</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">A jelszó megadása kötelező!</div>

                    <label for="password2" class="mt-3">Új jelszó</label>
                    <input type="password" class="form-control" id="password2" name="password2" required>
                    <div class="invalid-feedback">Az új jelszó megadása kötelező!</div>

                    <label for="password3" class="mt-3">Új jelszó mégegyszer</label>
                    <input type="password" class="form-control" id="password3" name="password3" required>
                    <div class="invalid-feedback">Az új jelszó megadása kötelező!</div>

                    <button type="submit" class="btn btn-primary mt-3">Mentés</button>
                </form>
            </div>
        </div>
    </div>
</div>