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

        $id = $local_hash = $wp_hash = 0;
        $project_id = $this->project->getId();
        if ($stmt = $con->prepare('SELECT `id`, `local_hash`, `wp_hash` FROM `wordpress_connections` WHERE `project_id` = ?')) {
            $stmt->bind_param('i', $project_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $local_hash, $wp_hash);
            $stmt->fetch();
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

    public function rest_request($url)
    {
        $json_url = $this->project->getUrl() . 'wp-json/okapilab/v1/' . $url;
        $postdata = [
            'wp_hash' => $this->wp_hash,
            'okapi_hash' => $this->local_hash
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
        return $response;
    }

    public function connect(string $wp_hash): bool
    {
        global $con;
        $local_hash = bin2hex(random_bytes(16));

        $this->wp_hash = $wp_hash;
        $this->local_hash = $local_hash;

        $pid = $this->project->getId();
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `wordpress_connections` WHERE `wp_hash` = ? AND `local_hash` = ? AND `project_id` != ?')) {
            $stmt->bind_param('ssi', $wp_hash, $local_hash, $pid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                return false;
            }
            $stmt->close();
        }

        $response = $this->rest_request('connect');

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

    public function disconnect(): bool
    {
        global $con;
        if (!$this->initialized()) {
            return false;
        }

        $response = $this->rest_request('disconnect');

        $stmt = $con->prepare('DELETE FROM `wordpress_connections` WHERE `id` = ?');
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function testconnection(): bool
    {
        return true;
        if (!$this->initialized()) {
            return false;
        }

        $response = $this->rest_request('testconnection');

        if ($response === NULL) {
            return false;
        }

        return $response == "success";
    }

    public function isMaintenanceMode(): bool
    {
        if (!$this->initialized()) {
            return false;
        }

        $response = $this->rest_request('checkmaintenance');

        if ($response === NULL) {
            return false;
        }

        return $response == "1";
    }

    public function toggleMaintenance(): bool
    {
        if (!$this->initialized()) {
            return false;
        }

        $response = $this->rest_request('togglemaintenance');

        if ($response === NULL) {
            return false;
        }

        return $response == "success";
    }

    public function getLoginLink()
    {
        if (!$this->initialized()) {
            return '';
        }

        $response = $this->rest_request('login');

        return $response->login_url;
    }

    public function getVersion(): array
    {
        if (!$this->initialized()) {
            return [];
        }

        $response = $this->rest_request('getversion');

        return [
            'wp' => $response->wp,
            'plugin' => $response->plugin
        ];
    }

    public function isUpToDate()
    {
        if ($this->getVersion()['wp'] === WordPressConnection::getLatestWpVersion()) {
            return true;
        }
    }

    public static function getProjectByHash(string $wp_hash, string $connect_hash): Project
    {
        global $con;
        $project = false;
        $id = 0;
        if ($stmt = $con->prepare('SELECT `project_id` FROM `wordpress_connections` WHERE `wp_hash` = ? AND `local_hash` = ?')) {
            $stmt->bind_param('ss', $wp_hash, $connect_hash);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();
            if ($stmt->num_rows > 0) {
                $project = new Project($id);
            }
            $stmt->close();
        }

        return $project;
    }

    public static function getLatestWpVersion(): string
    {
        $response = file_get_contents('https://api.wordpress.org/core/version-check/1.7/');
        $response = json_decode($response);
        return $response->offers[0]->version;
    }

    public static function getConnectedSites(): array
    {
        global $con;
        $sites = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `projects`.`id` FROM `wordpress_connections` INNER JOIN `projects` ON `projects`.`id` = `wordpress_connections`.`project_id` ORDER BY `projects`.`name`')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $sites[] = new Project($id);
            }
            $stmt->close();
        }

        return $sites;
    }
}
