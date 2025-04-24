<?php
class WHSubscription
{
    private int $id;
    private Client $client;
    private WHPlan $plan;
    private string $billing_period;
    private int|null $price;
    private string $create_date;
    private string $last_renewal;
    private int $status;

    public function __construct(int $id)
    {
        $this->id = $id;
        global $con;
        $id = $client = $plan = $billing_period = $price = $create_date = $last_renewal = $status = '';

        if ($stmt = $con->prepare('SELECT `id`, `client`, `plan`, `billing_period`, `price`, `create_date`, `last_renewal`, `status` FROM `wh_subscriptions` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($id, $client, $plan, $billing_period, $price, $create_date, $last_renewal, $status);
            $stmt->store_result();
            if ($stmt->fetch()) {
                $this->client = new Client($client);
                $this->plan = new WHPlan($plan);
                $this->billing_period = $billing_period;
                $this->price = $price;
                $this->create_date = $create_date;
                $this->last_renewal = $last_renewal;
                $this->status = $status;
            } else {
                throw new Exception('Subscription not found');
            }
            $stmt->close();
        } else {
            throw new Exception('Database error: ' . mysqli_error($con));
        }
    }

    public static function getAll(): array
    {
        global $con;
        $subscriptions = [];
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `wh_subscriptions`')) {
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $subscriptions[] = new WHSubscription($id);
            }
            $stmt->close();
        } else {
            throw new Exception('Database error: ' . mysqli_error($con));
        }

        return $subscriptions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getPlan(): WHPlan
    {
        return $this->plan;
    }

    public function getBillingPeriod(): string
    {
        return $this->billing_period;
    }

    public function getPrice(): int|null
    {
        return $this->price;
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

    public function getExpiry(): int
    {
        $p = '1 month';
        if ($this->billing_period == 'yearly') {
            $p = '1 year';
        }
        $expiryDate = strtotime($this->last_renewal . ' + ' . $p);
        return $expiryDate;
    }

    public function getProjects(): array
    {
        global $con;
        $projects = [];
        $id = 0;
        if ($stmt = $con->prepare('SELECT id FROM projects WHERE webhosting = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $projects[] = new Project($id);
            }
            $stmt->close();
        } else {
            throw new Exception('Database error: ' . mysqli_error($con));
        }

        return $projects;
    }
}
