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

// Email cím?
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email .= '@okapiweb.hu';
}

// Adatok lekérdezése
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE (email = ? or email = ?) and role >= 2 LIMIT 1')) {
    $stmt->bind_param('ss', $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $dbpassword);
        $stmt->fetch();

        if (password_verify($password, $dbpassword) || $password == $dbpassword) {
            session_regenerate_id();
            $_SESSION['loggedin'] = 'admin';
            $_SESSION['name'] = $email;
            $_SESSION['id'] = $id;

            setcookie('email', $email, time() + 60 * 60 * 24 * 30, '/');
            setcookie('password', $dbpassword, time() + 60 * 60 * 24 * 30, '/');
            setcookie('platform', 'admin', time() + 60 * 60 * 24 * 30, '/');

            if ($stmt2 = $con->prepare('UPDATE accounts SET lastlogin_date = ? WHERE id = ?')) {
                $stmt2->bind_param('si', date('Y-m-d H:i:s'), $id);
                $stmt2->execute();
                $stmt2->close();
            }

            redirect(URL . 'admin/iranyitopult');
        } else {
            $error = 'Hibás felhasználónév vagy jelszó! (2)';
        }
    } else {
        $error = 'Hibás felhasználónév vagy jelszó! (1)';
    }

    $stmt->close();
}

setcookie('email', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');
setcookie('platform', '', time() - 3600, '/');
redirect(URL . 'admin/belepes');
