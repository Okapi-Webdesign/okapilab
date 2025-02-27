<?php
class WordPressConnection
{
    private int $id;
    private Project $project;
    private string $wp_url;
    private string $wp_hash;
    private string $local_hash;

    public function __construct(int|Project $project)
    {
        global $con;
        if (is_int($project)) {
            $this->project = new Project($project);
        } else {
            $this->project = $project;
        }

        $wp_url = $this->project->getUrl();

        $id = $local_hash = $wp_hash = 0;
        $project_id = $this->project->getId();
        if ($stmt = $con->prepare('SELECT `id`, `local_hash`, `wp_hash` FROM `wordpress_connections` WHERE `project_id` = ?')) {
            $stmt->bind_param('i', $project_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $local_hash, $wp_hash);
            if ($stmt->num_rows > 0) {
                $this->id = $id;
                $this->wp_hash = $wp_hash;
                $this->local_hash = $local_hash;
            }
            $stmt->fetch();
            $stmt->close();
        }

        if ($id === 0) {
            return false;
        }
    }

    public function initialized(): bool
    {
        return isset($this->id) && $this->id > 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function connect(string $wp_hash): bool
    {
        global $con;
        $local_hash = bin2hex(random_bytes(16));
        $url = $this->project->getUrl();

        // Kérés küldése a WP felé
        $json_url = "$url/wp-json/okapilab/v1/connect";
        $postdata = [
            'wp_hash' => $wp_hash,
            'okapi_hash' => $local_hash
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($postdata)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($json_url, false, $context);
        $response = json_decode($response);

        if ($response === NULL) {
            return false;
        }

        if ($response === 'success') {
            $project_id = $this->project->getId();
            $stmt = $con->prepare('INSERT INTO `wordpress_connections` (`project_id`, `wp_hash`, `local_hash`) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $project_id, $wp_hash, $local_hash);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        return true;
    }
}
