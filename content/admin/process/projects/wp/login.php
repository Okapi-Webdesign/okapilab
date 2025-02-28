<?php
$p = new Project($data[0]);

$wp = new WordPressConnection($p);

$login_url = $wp->getLoginLink();

if ($login_url == '') {
    alert_redirect('error', URL . 'admin/projektek/wordpress/d/' . $p->getId());
}

header('Location: ' . $login_url);
