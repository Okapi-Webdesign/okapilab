<?php
if ($_SESSION['project'] == null) {
    alert_redirect('error', URL . 'ugyfel/profil/projektvalaszto');
}

$project = new Project($_SESSION['project']);

if (!$project->isWordpress() || $project->getWordpressLogin() == NULL) {
    alert_redirect('error', URL . 'ugyfel/profil/projektvalaszto');
}

$wpLogin = $project->getWordpressLogin();
?>

Átirányítás a WordPress-be...

<form action="<?= $project->getUrl() ?>wp-login.php" method="post" id="wpLoginForm">
    <input type="hidden" name="log" value="<?= $wpLogin->getUsername() ?>">
    <input type="hidden" name="pwd" value="<?= $wpLogin->getPassword() ?>">
</form>

<script>
    document.getElementById('wpLoginForm').submit();
</script>