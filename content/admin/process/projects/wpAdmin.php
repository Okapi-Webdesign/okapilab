<?php
$p = new Project($data[0]);
$wpLogin = $p->getWordpressLogin();
if ($wpLogin == NULL) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $data[0], 'Nincs WordPress fiók hozzárendelve!');
}
?>

Átirányítás a WordPress-be...

<form action="<?= $p->getUrl() ?>wp-login.php" method="post" id="wpLoginForm">
    <input type="hidden" name="log" value="<?= $wpLogin->getUsername() ?>">
    <input type="hidden" name="pwd" value="<?= $wpLogin->getPassword() ?>">
</form>

<script>
    document.getElementById('wpLoginForm').submit();
</script>