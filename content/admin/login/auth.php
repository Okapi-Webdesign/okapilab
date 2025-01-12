<?php
if (!defined('ABS_PATH')) {
    die('Hozzáférés megtagadva!');
}

// Cookie lekérdezése
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
} else {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

// Adatok lekérdezése
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE (username = ? or email = ?) and role >= 2 LIMIT 1')) {
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $dbpassword);
        $stmt->fetch();

        if (password_verify($password, $dbpassword) || $password == $dbpassword) {
            session_regenerate_id();
            $_SESSION['loggedin'] = 'admin';
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;

            setcookie('username', $username, time() + 60 * 60 * 24 * 30, '/');
            setcookie('password', $dbpassword, time() + 60 * 60 * 24 * 30, '/');
            setcookie('platform', 'admin', time() + 60 * 60 * 24 * 30, '/');

            if ($stmt2 = $con->prepare('UPDATE accounts SET lastlogin = ? WHERE id = ?')) {
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

setcookie('username', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');
setcookie('platform', '', time() - 3600, '/');
redirect(URL . 'admin/belepes');
