<?php
if (!defined('ABS_PATH')) {
    die('Hozzáférés megtagadva!');
}

// Cookie lekérdezése
if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
} else {
    $email = $_POST['email'];
    $password = $_POST['password'];
}

setcookie('email', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');
setcookie('platform', '', time() - 3600, '/');

// Adatok lekérdezése
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ? and role = 1 LIMIT 1')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $dbpassword);
        $stmt->fetch();

        if (password_verify($password, $dbpassword) || $password == $dbpassword) {
            session_regenerate_id();
            $_SESSION['loggedin'] = 'client';
            $_SESSION['name'] = $email;
            $_SESSION['id'] = $id;
            $_SESSION['project'] = NULL;

            $u = new User($id);
            $c = $u->getClient();

            if (count($c->getActiveProjects()) == 1) {
                $_SESSION['project'] = $c->getActiveProjects()[0]->getId();
            }

            if (isset($_POST['rememberMe'])) {
                setcookie('email', $email, time() + 60 * 60 * 24 * 30, '/');
                setcookie('password', $password, time() + 60 * 60 * 24 * 30, '/');
                setcookie('platform', 'client', time() + 60 * 60 * 24 * 30, '/');
            }

            if ($stmt2 = $con->prepare('UPDATE accounts SET lastlogin_date = NOW() WHERE id = ?')) {
                $stmt2->bind_param('i', $id);
                $stmt2->execute();
                $stmt2->close();
            }

            redirect(URL . 'ugyfel/projektem');
        } else {
            $error = 'Hibás felhasználónév vagy jelszó! (2)';
        }
    } else {
        $error = 'Hibás felhasználónév vagy jelszó! (1)';
    }

    $stmt->close();
}

redirect(URL . 'ugyfel/belepes?err=' . $error);
exit;
