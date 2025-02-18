<?php

/**
 * Az összes függvényt tartalmazó fájl.
 *
 * Tartalom:
 * 1. ÁTIRÁNYÍTÁS
 * 2. FÁJLKEZELÉS
 * 3. E-MAIL KÜLDÉS
 * 4. ÉRTESÍTÉSEK
 * 5. MENÜK
 * 6. EXTRA FÜGGVÉNYEK
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// --- 1. Átirányítás --- //
function redirect($target)
{
    if (!DEV_MODE) {
        if (!headers_sent()) {
            header('Location: ' . $target);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $target . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $target . '" />';
            echo '</noscript>';
            exit;
        }
    } else {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        echo "<br><br>Átirányítva a(z) " . $caller['file'] . " fájl " . $caller['line'] . ". sorában a következőre: <a href='$target'>$target</a>";
        exit;
    }
}

// --- 2. Fájlkezelés --- //

// 2.1. HTML betöltése
function html_load($section, $data = null)
{
    global $con, $user, $settings;
    if (file_exists(ABS_PATH . 'content/admin/display/' . $section . '.php')) {
        require_once ABS_PATH . 'content/admin/display/' . $section . '.php';
    } else {
        echo 'A fájl nem található!';
    }
}

// 2.2. Metaadatok kinyerése
function extractPageMeta($filePath)
{
    $content = file_get_contents($filePath);
    $tokens = token_get_all($content);

    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] === T_VARIABLE && $token[1] === '$pageMeta') {
            // A következő tokenek között található a tömbdefiníció
            $start = strpos($content, '[');
            $end = strpos($content, '];', $start);
            $arrayCode = substr($content, $start, $end - $start + 2);

            // Visszaadjuk a metaadatokat
            return eval("return $arrayCode;");
        }
    }

    return null; // Nem találtunk metaadatot
}

// --- 3. E-MAIL KÜLDÉS --- //

// 3.1. E-mail küldése
function mail_send(string $to, string $subject, string $body)
{
    if (!ENABLE_EMAILS) {
        if (DEV_MODE) echo "Email kiküldése letiltva.";
        return false;
    }
    try {
        if (!isset($mail)) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                if (DEV_MODE) $mail->SMTPDebug = 3;
                $mail->Host       = SMTP_HOST;                    // Érték: mail.bozaiakos.hu
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = "ssl";
                $mail->Username   = SMTP_USER;                   // Érték: noreply@bozaiakos.hu
                $mail->Password   = SMTP_PASS;                             // Érték: EmailBABozaiako0000
                $mail->Port       = SMTP_PORT;      // Érték: 465
                $mail->setFrom(SMTP_USER, APP_NAME);
            } catch (Exception $e) {
                if (DEV_MODE) echo "Hiba az email szerverrel való kommunikáció során.";
            }
        }
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->isHTML(true);
        $mail->addAddress("$to");
        if (ADMIN_EMAIL != false && DEV_MODE == true) $mail->addBCC(ADMIN_EMAIL);
        $header = ""; //file_get_contents(ABS_PATH . "/assets/emails/header.html");
        $footer = file_get_contents(ABS_PATH . "/assets/emails/footer.html");
        $body = $header . $body . $footer;
        $mail->Subject = $subject . ' - ' . APP_NAME;
        $mail->Body    = $body;
        $mail->AltBody = $body;
        $mail->send();
        if (DEV_MODE) echo "<br>Email kiküldve.";
        return true;
    } catch (Exception $e) {
        if (DEV_MODE) echo "<br>Hiba: $e";
        return false;
    }
}

// 3.2 Placeholderek helyettesítése
function bind_to_template($replacements, $template)
{
    $result = preg_replace_callback(
        '/{{(.+?)}}/',
        function ($matches) use ($replacements) {
            $key = $matches[1];
            if (isset($replacements[$key])) {
                return $replacements[$key];
            } else {
                return "(hiányzó adat)";
            }
        },
        $template
    );

    return $result;
}

// 3.3. Sablon alapján e-mail küldése
function mail_send_template(string $target, string $template, array $data, bool $sendmail = true)
{
    if (!file_exists(ABS_PATH . "assets/emails/$template.php")) return false;
    include ABS_PATH . "assets/emails/$template.php";
    $str = $email['body'];
    $email['message'] = bind_to_template($data, $str);
    if ($sendmail) return mail_send($target, $email['subject'], $email['message']);
    else {
        echo $email['message'];
        return false;
    }
}

// --- 4. ÉRTESÍTÉSEK --- //

// 4.1. Értesítés létrehozása
function alert_create($type, $message, $popup = false, $title = null)
{
    $_SESSION['alert'] = array(
        'type' => $type,
        'message' => $message,
        'title' => $title,
        'popup' => $popup
    );
}

// 4.2. Értesítés lekérése
function alert_get()
{
    if (!isset($_SESSION['alert'])) return false;
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
    return $alert;
}

// 4.3. Értesítés megjelenítése
function alert_show()
{
    $alert = alert_get();
    if (!$alert) {
        return false;
    }

    $type = $alert['type'];
    $message = $alert['message'];
    $title = $alert['title'];
    $popup = $alert['popup'];

    if ($popup) {
        if ($title == null) {
            switch ($type) {
                case 'success':
                    $type = 'success';
                    $title = 'Sikeres művelet!';
                    break;
                case 'error':
                    $type = 'error';
                    $title = 'Hiba történt!';
                    break;
                case 'warning':
                    $type = 'warning';
                    $title = 'Figyelem!';
                    break;
                case 'info':
                    $type = 'info';
                    $title = 'Információ';
                    break;
                default:
                    $type = 'info';
                    $title = 'Információ';
                    break;
            }
        }
        echo '<script>';
        echo 'Swal.fire({';
        echo 'icon: "' . $type . '",';
        echo 'title: "' . $title . '",';
        echo 'html: "' . $message . '"';
        echo '})';
        echo '</script>';
    } else {
        switch ($type) {
            case 'success':
                $type = 'success';
                $title = 'Sikeres művelet!';
                break;
            case 'error':
                $type = 'error';
                $title = 'Hiba történt!';
                break;
            case 'warning':
                $type = 'warning';
                $title = 'Figyelem!';
                break;
            case 'info':
                $type = 'info';
                $title = 'Információ';
                break;
            default:
                $type = 'info';
                $title = 'Információ';
                break;
        }
        echo '<script>';
        echo 'Toast.fire({';
        echo 'icon: "' . $type . '",';
        echo 'title: "' . $title . '",';
        echo 'html: "' . $message . '"';
        echo '})';
        echo '</script>';
    }
}

// 4.4. Értesítés átirányítással
function alert_redirect($type, $target, $message = null, $title = null, $popup = false)
{
    alert_create($type, $message, $popup, $title);
    if (!$target) $target = URL;
    if (DEV_MODE) {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        echo "<br><br>Átirányítva a(z) " . $caller['file'] . " fájl " . $caller['line'] . ". sorában a következőre: <a href='$target'>$target</a>";
        exit();
    }
    redirect($target);
}

// --- 5. MENÜK --- //

// 5.1. Menü elem hozzáadása
function addMenuItem($name, $url, $icon)
{
    global $user;
    if (isset($_GET['url'])) $active = strpos($_GET['url'], $url) !== false;
    else $active = false;
    if ($url == 'iranyitopult' && !isset($_GET['url'])) $active = true;
    $activeClass = $active ? 'active' : 'text-white';
    echo '<li class="nav-item">
                <a href="' . URL . 'admin/' . $url . '" class="nav-link ' . $activeClass . '" aria-current="page">
                    <i class="' . $icon . ' me-2"></i>
                    ' . $name . '
                </a>
            </li>';
}

// --- 6. EXTRA FÜGGVÉNYEK --- //

// 6.1. Dátum formázás
function dateformat($d)
{
    $date = new DateTime($d);
    return $date->format('Y. m. d.');
}

// 6.2. Szöveg lerövidítése
function shorten($str)
{
    $str = str_replace("<br />", " ", $str);
    $str = str_replace("  ", " ", $str);
    return "<span class='d-inline-block text-truncate'>$str</span>";
}

// 6.3. Biztonságos string létrehozása
function safeString($string)
{
    global $con;
    $string = htmlspecialchars($string);
    return $con->real_escape_string($string);
}

// 6.4. Jelszó generálása
function generatePassword(int $length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    return $result;
}

// 6.5. Beállítás lekérdezése
function getSetting(string $key)
{
    global $con;
    $value = "";
    if ($stmt = $con->prepare('SELECT `value` FROM `settings` WHERE `name` = ?')) {
        $stmt->bind_param('s', $key);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($value);
        $stmt->fetch();
        $stmt->close();
    }
    return $value;
}

// 6.6. Beállítás frissítése
function updateSetting(string $key, string $value)
{
    global $con;
    if ($stmt = $con->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ?')) {
        $stmt->bind_param('ss', $value, $key);
        $stmt->execute();
        $stmt->close();
    }
}

// 6.7. Világos szín?
function isLightColor(string $color)
{
    $color = str_replace('#', '', $color);
    $r = hexdec(substr($color, 0, 2));
    $g = hexdec(substr($color, 2, 2));
    $b = hexdec(substr($color, 4, 2));
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    return $brightness > 155;
}
