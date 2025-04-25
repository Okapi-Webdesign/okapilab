<?php
$p = new Project($_SESSION['project']);

$wp = new WordPressConnection($p);

$login_url = $wp->getLoginLink();
if ($login_url == '') {
    alert_redirect('error', URL . 'ugyfel');
}

header('Location: ' . $login_url);
