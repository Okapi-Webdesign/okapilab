<?php
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($con, 'utf8');

if (mysqli_connect_errno()) {
    $_SESSION['error'] = mysqli_connect_error();
    redirect(ABS_PATH . 'error.php');
}
