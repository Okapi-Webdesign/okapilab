<?php
class Project
{
    private int $id;
    private string $name;
    private Client $client;
    private Status $status;
    private string|null $url;
    private array|null $tags;
    private array|null $services;
    private User|null $manager;
    private string|null $comment;
    private string|null $warranty;
    private bool $is_wordpress;
    private bool $is_active;
    private string|null $image_uri;
    private string $create_date;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $name = $url = $comment = $warranty = $image_uri = $create_date = $client_id = $status = $tags = $services = $manager_id = $is_wordpress = $is_active = null;

        if ($stmt = $con->prepare('SELECT `id`, `client_id`, `name`, `url`, `status`, `tags`, `services`, `manager_id`, `comment`, `warranty`, `is_wordpress`, `active`, `image_uri`, `create_date` FROM `projects` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $client_id, $name, $url, $status, $tags, $services, $manager_id, $comment, $warranty, $is_wordpress, $is_active, $image_uri, $create_date);
            if (!$stmt->fetch() || $stmt->num_rows === 0) {
                throw new Exception('A projekt nem található!');
            }
            $stmt->close();
        }

        $this->name = $name;
        $this->url = $url;
        $this->comment = $comment;
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

        $this->status = new Status($status);

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

    public static function getAll(): array
    {
        global $con;
        $projects = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `projects` WHERE status != (SELECT MAX(id) FROM projects_status) ORDER BY `name` ASC')) {
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

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(int $status): bool
    {
        global $con;
        $this->status = new Status($status);

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
}
