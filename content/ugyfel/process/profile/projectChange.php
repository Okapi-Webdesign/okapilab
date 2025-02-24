<?php
$p = new Project($data[0]);
$c = $user->getClient();

if ($p->getClient()->getId() != $c->getId()) {
    alert_redirect('error', URL . 'ugyfel/profil/projektvalaszto');
}

$_SESSION['project'] = $p->getId();
alert_redirect('success', URL . 'ugyfel');
