<?php
class ProjectLogin
{
    private int $id;
    private Project $project;
    private string $name;
    private string $url;
    private string $username;
    private string $password;
    private User|int $author;
    private bool $private;

    public function __construct(int $id)
    {
        global $con, $user;
        $this->id = $id;
        $project = $name = $url = $username = $password = $author = $private = 0;

        if ($stmt = $con->prepare('SELECT `project_id`, `name`, `url`, `username`, `password`, `author`, `private` FROM `projects_logins` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($project, $name, $url, $username, $password, $author, $private);
            if (!$stmt->fetch()) {
                throw new Exception('A login nem található!');
            }
            $stmt->close();
        }

        if ($private == 1 && $user->getId() !== $author) {
            throw new Exception('Nincs jogosultságod az adatpár megtekintéséhez!');
        }

        $this->project = new Project($project);
        $this->name = $name;
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
        if ($author !== 0) {
            $this->author = new User($author);
        } else {
            $this->author = null;
        }

        $this->private = $private == 1;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAuthor(): User|null
    {
        return $this->author;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }
}
