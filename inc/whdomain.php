<?php
class WHDomain
{
    private int $id;
    private Client $client;
    private string $domain;
    private WHDomainPlan $tld;
    private string $create_date;
    private string $last_renewal;
    private int $status;

    public function __construct(int $id)
    {
        $this->id = $id;
        global $con;
        $client = $domain = $tld = $create_date = $last_renewal = $status = '';

        if ($stmt = $con->prepare('SELECT `id`, `client`, `domain`, `tld`, `create_date`, `last_renewal`, `status` FROM `wh_domains` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($id, $client, $domain, $tld, $create_date, $last_renewal, $status);
            $stmt->store_result();
            if ($stmt->fetch()) {
                $this->client = new Client($client);
                $this->domain = $domain;
                $this->tld = new WHDomainPlan($tld);
                $this->create_date = $create_date;
                $this->last_renewal = $last_renewal;
                $this->status = $status;
            } else {
                throw new Exception('Domain not found');
            }
            $stmt->close();
        } else {
            throw new Exception('Database error: ' . mysqli_error($con));
        }
    }

    public static function getAll(): array
    {
        global $con;
        $domains = [];
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `wh_domains`')) {
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $domains[] = new WHDomain($id);
            }
            $stmt->close();
        } else {
            throw new Exception('Database error: ' . mysqli_error($con));
        }

        return $domains;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getTld(): WHDomainPlan
    {
        return $this->tld;
    }

    public function getCreateDate(): string
    {
        return $this->create_date;
    }

    public function getLastRenewal(): string
    {
        return $this->last_renewal;
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function getExpiry(): string
    {
        return strtotime($this->last_renewal . ' +1 year');
    }
}
