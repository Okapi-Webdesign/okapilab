<?php
class User
{
    private int $id;
    private string|null $email;
    private int $role;
    private Client|null $client_id;

    public function __construct($id)
    {
        global $con;
        $email = $role = $link = 0;

        if ($id == 0) {
            $this->id = 0;
            return;
        }

        if ($stmt = $con->prepare('SELECT `email`, `role`, `client_id` FROM `accounts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($email, $role, $link);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) {
                $this->id = 0;
                return;
            }
            $stmt->close();
        }
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        if ($link != null) $this->client_id = new Client($link);
        else $this->client_id = null;
        return true;
    }

    function getFullname(): string
    {
        global $con;
        $lastname = $firstname = null;
        if ($stmt = $con->prepare('SELECT `lastname`, `firstname` FROM `accounts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($lastname, $firstname);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) return false;
            $stmt->close();
        }
        return $lastname . ' ' . $firstname;
    }

    function role(int $role = 0): bool|int
    {
        if ($role == 0) return $this->role;
        return $this->role >= $role;
    }

    function getProfilePicture(int $size = 150): string
    {
        $def = URL . 'assets/img/profile/default.png';

        // Check if the user has a profile picture
        $url = ABS_PATH . 'assets/img/profile/' . $this->id . '.png';
        if (file_exists($url)) {
            $url = URL . 'assets/img/profile/' . $this->id . '.png';
            return $url;
        }

        $url = ABS_PATH . 'assets/img/profile/' . $this->id . '.jpg';
        if (file_exists($url)) {
            $url = URL . 'assets/img/profile/' . $this->id . '.jpg';
            return $url;
        }

        $url = ABS_PATH . 'assets/img/profile/' . $this->id . '.jpeg';
        if (file_exists($url)) {
            $url = URL . 'assets/img/profile/' . $this->id . '.jpeg';
            return $url;
        }

        return $def;
    }

    function getEmail(): string|null
    {
        return $this->email;
    }

    function getLastLogin(): string|false
    {
        global $con;
        $last_login = null;
        if ($stmt = $con->prepare('SELECT `lastlogin_date` FROM `accounts` WHERE `id` = ?')) {
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

    function getId(): int
    {
        return $this->id;
    }

    function loggedin(): bool
    {
        return $this->role > 0 && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'admin';
    }

    function getClient(): Client|null
    {
        return $this->client_id;
    }

    static function getAll(): array
    {
        global $con;
        $users = [];
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `accounts` WHERE `role` >= 2 ORDER BY `lastname`, `firstname`')) {
            if (!$stmt->execute()) return [];
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $users[] = new User($id);
            }
            $stmt->close();
        }
        return $users;
    }

    function getTrelloId(): string
    {
        global $con;
        $trello_id = null;
        if ($stmt = $con->prepare('SELECT `trello_id` FROM `trello_accounts` WHERE `account_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            if (!$stmt->execute()) return false;
            $stmt->store_result();
            $stmt->bind_result($trello_id);
            if (!$stmt->fetch()) return false;
            if ($stmt->num_rows == 0) return false;
            $stmt->close();
        }
        return $trello_id;
    }
}
