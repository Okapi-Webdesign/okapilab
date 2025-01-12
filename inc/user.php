<?php
class User
{
    private int $id;
    private string|null $email;
    private int $role;
    private int $link;

    public function __construct($id)
    {
        global $con;
        $email = $role = $link = null;
        if ($stmt = $con->prepare('SELECT `email`, `role`, `link` FROM `accounts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($email, $role, $link);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) return false;
            $stmt->close();
        }
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->link = $link;
        return true;
    }

    function role(int $role = 0): bool|int
    {
        if ($role == 0) return $this->role;
        return $this->role >= $role;
    }

    function getProfilePicture(): string
    {
        // Ha létezik $this-id nevű jpg, png vagy jpeg kiterjesztésű fájl az assets/img/profile mappában, adja vissza
        $extensions = ['jpg', 'png', 'jpeg'];
        foreach ($extensions as $ext) {
            $path = ABS_PATH . 'assets/img/profile/' . $this->id . '.' . $ext;
            if (file_exists($path)) return URL . 'assets/img/profile/' . $this->id . '.' . $ext;
        }

        // Ha nem létezik, adja vissza az assets/img/profile/default.jpg fájlt
        return URL . 'assets/img/profile/default.jpg';
    }

    function getEmail(): string|null
    {
        return $this->email;
    }

    function getLastLogin(): string|false
    {
        global $con;
        $last_login = null;
        if ($stmt = $con->prepare('SELECT `lastlogin` FROM `accounts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($last_login);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) return false;
            $stmt->close();
        }
        if ($last_login == null) return 'Soha';
        return $last_login;
    }

    function getUsername(): string
    {
        global $con;
        $id = $this->id;
        $username = null;
        if ($stmt = $con->prepare('SELECT `username` FROM `accounts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($username);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) return false;
            $stmt->close();
        }
        return $username;
    }

    function getId(): int
    {
        return $this->id;
    }

    function loggedin(): bool
    {
        return $this->role > 0 && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'admin';
    }
}
