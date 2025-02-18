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
    private string|null $logo_url;
    private string $create_date;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $name = $url = $comment = $warranty = $logo_url = $create_date = $client_id = $status = $tags = $services = $manager_id = $is_wordpress = $is_active = null;

        if ($stmt = $con->prepare('SELECT `id`, `client_id`, `name`, `url`, `status`, `tags`, `services`, `manager_id`, `comment`, `warranty`, `is_wordpress`, `active`, `logo_url`, `create_date` FROM `projects` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $client_id, $name, $url, $status, $tags, $services, $manager_id, $comment, $warranty, $is_wordpress, $is_active, $logo_url, $create_date);
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
        $this->logo_url = $logo_url;
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

        if ($manager_id) {
            $this->manager = new User($manager_id);
        } else {
            $this->manager = null;
        }
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
}
