<?php
class Project
{
    private int $id;
    private string $name;
    private Client $client;
    private ProjectStatus $status;
    private string|null $url;
    private array|null $tags;
    private array|null $services;
    private User|null $manager;
    private string|null $comment;
    private string|null $deadline;
    private string|null $warranty;
    private bool $is_wordpress;
    private bool $is_active;
    private string|null $image_uri;
    private string $create_date;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $name = $url = $comment = $warranty = $image_uri = $create_date = $client_id = $status = $tags = $services = $manager_id = $is_wordpress = $is_active = $deadline = null;

        if ($stmt = $con->prepare('SELECT `id`, `client_id`, `name`, `url`, `status`, `tags`, `deadline`, `services`, `manager_id`, `comment`, `warranty`, `is_wordpress`, `active`, `image_uri`, `create_date` FROM `projects` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $client_id, $name, $url, $status, $tags, $deadline, $services, $manager_id, $comment, $warranty, $is_wordpress, $is_active, $image_uri, $create_date);
            if (!$stmt->fetch() || $stmt->num_rows === 0) {
                throw new Exception('A projekt nem található!');
            }
            $stmt->close();
        }

        $this->name = $name;
        $this->url = $url;
        $this->comment = $comment;
        $this->deadline = $deadline;
        $this->warranty = $warranty;
        $this->is_wordpress = $is_wordpress;
        $this->is_active = $is_active;
        $this->image_uri = $image_uri;
        $this->create_date = $create_date;

        if ($client_id) {
            $this->client = new Client($client_id);
        } else {
            $this->client = null;
        }

        $this->status = new ProjectStatus($status);

        if ($tags) {
            $this->tags = json_decode($tags, true);
        } else {
            $this->tags = null;
        }

        if ($services) {
            $this->services = json_decode($services, true);
        } else {
            $this->services = null;
        }

        $this->manager = new User($manager_id);
    }

    public static function getAll(bool $archived_too = false): array
    {
        global $con;
        $projects = [];
        $id = 0;

        if ($archived_too) {
            $sql = 'SELECT `id` FROM `projects` ORDER BY `name` ASC';
        } else {
            $sql = 'SELECT `id` FROM `projects` WHERE active = 1 ORDER BY `name` ASC';
        }

        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $projects[] = new Project($id);
            }
            $stmt->close();
        }

        return $projects;
    }

    public static function getArchive(): array
    {
        global $con;
        $projects = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `projects` WHERE active = 0 ORDER BY `name` ASC')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $projects[] = new Project($id);
            }
            $stmt->close();
        }

        return $projects;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(int $status): bool
    {
        global $con;
        $this->status = new ProjectStatus($status);

        if ($stmt = $con->prepare('UPDATE `projects` SET `status` = ? WHERE `id` = ?')) {
            $stmt->bind_param('ii', $status, $this->id);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        return false;
    }

    public function getTags(): array|null
    {
        return $this->tags;
    }

    public function setTags(array $tags): bool
    {
        global $con;
        $this->tags = $tags;

        $tags = json_encode($tags);

        if ($stmt = $con->prepare('UPDATE `projects` SET `tags` = ? WHERE `id` = ?')) {
            $stmt->bind_param('si', $tags, $this->id);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        return false;
    }

    public function getServices(): array|null
    {
        return $this->services;
    }

    public function setServices(array $services): bool
    {
        global $con;
        $this->services = $services;

        $services = json_encode($services);

        if ($stmt = $con->prepare('UPDATE `projects` SET `services` = ? WHERE `id` = ?')) {
            $stmt->bind_param('si', $services, $this->id);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        return false;
    }

    public function getUrl(): string|null
    {
        return $this->url;
    }

    public function getManager(): User
    {
        return $this->manager;
    }

    public function getImageUri(): string|null
    {
        if ($this->image_uri == NULL) {
            return null;
        }
        return URL . $this->image_uri;
    }

    public function updateImageUrl(string $filename): void
    {
        global $con;
        $this->image_uri = $filename;

        if ($stmt = $con->prepare('UPDATE `projects` SET `image_uri` = ? WHERE `id` = ?')) {
            $stmt->bind_param('si', $filename, $this->id);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function isWordpress(): bool
    {
        return $this->is_wordpress;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getCreateDate(): string
    {
        return $this->create_date;
    }

    public function getComment(): string|null
    {
        return $this->comment;
    }

    public function getDeadline(): string|null
    {
        if ($this->deadline == NULL) {
            return null;
        }
        return date('Y. m. d.', strtotime($this->deadline));
    }

    public function getWarranty(): string|null
    {
        if ($this->warranty == NULL) {
            return null;
        }
        return date('Y. m. d.', strtotime($this->warranty));
    }

    public function addLogin(string $name, string $url, string $username, string $password, bool $private): bool
    {
        global $con, $user;
        $private = $private ? 1 : 0;
        $uid = $user->getId();

        if ($stmt = $con->prepare('SELECT `id` FROM `projects_logins` WHERE `project_id` = ? AND `name` = ? AND (private = 0 OR author = ?)')) {
            $stmt->bind_param('isi', $this->id, $name, $uid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                return false;
            }
            $stmt->close();
        }

        if ($stmt = $con->prepare('INSERT INTO `projects_logins`(`id`, `project_id`, `name`, `url`, `username`, `password`,`author`,`private`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)')) {
            $stmt->bind_param('issssii', $this->id, $name, $url, $username, $password, $uid, $private);
            if (!$stmt->execute()) return false;
            $stmt->close();
            return true;
        }

        return false;
    }

    public function getLogins(): array
    {
        global $con, $user;
        $logins = [];
        $id = 0;
        $uid = $user->getId();

        if ($stmt = $con->prepare('SELECT `id` FROM `projects_logins` WHERE `project_id` = ? AND (`private` = 0 OR `author` = ?)')) {
            $stmt->bind_param('ii', $this->id, $uid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $logins[] = new ProjectLogin($id);
            }
            $stmt->close();
        }

        return $logins;
    }

    public function getWordpressLogin(): ProjectLogin|null
    {
        global $con, $user;
        $id = 0;
        $uid = $user->getId();

        if ($stmt = $con->prepare('SELECT `id` FROM `projects_logins` WHERE `project_id` = ? AND `name` = "WordPress" AND (`private` = 0 OR `author` = ?)')) {
            $stmt->bind_param('ii', $this->id, $uid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            if ($stmt->fetch()) {
                return new ProjectLogin($id);
            }
            $stmt->close();
        }

        return null;
    }

    public function getDocuments(): array
    {
        global $con;
        $documents = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `documents` WHERE `project_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $documents[] = new Document($id);
            }
            $stmt->close();
        }

        return $documents;
    }
}
