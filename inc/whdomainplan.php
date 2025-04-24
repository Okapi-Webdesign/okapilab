<?php
class WHDomainPlan
{
    private int $id;
    private string $tld;
    private int $yearly_price;

    public function __construct(int $id)
    {
        $this->id = $id;
        global $con;
        $tld = $yearly_price = '';
        if ($stmt = $con->prepare("SELECT `id`, `tld`, `yearly_price` FROM `wh_domainprices` WHERE `id` = ?")) {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $tld, $yearly_price);
            if ($stmt->num_rows == 0) {
                throw new Exception("Domain plan not found.");
            }
            $stmt->fetch();
            $stmt->close();
        } else {
            throw new Exception("Database error: " . $con->error);
        }

        $this->tld = $tld;
        $this->yearly_price = $yearly_price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTld(): string
    {
        return $this->tld;
    }

    public function getPrice(bool $formatted = false): int|string
    {
        if ($formatted) {
            return number_format($this->yearly_price, 0, '.', ' ') . ' Ft';
        }
        return $this->yearly_price;
    }

    public static function getAll(): array
    {
        global $con;
        $id = 0;
        $plans = array();
        if ($stmt = $con->prepare("SELECT `id` FROM `wh_domainprices` ORDER BY `tld`")) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $plans[] = new WHDomainPlan($id);
            }
            $stmt->close();
        } else {
            throw new Exception("Database error: " . $con->error);
        }
        return $plans;
    }

    public static function getByTld(string $tld): ?WHDomainPlan
    {
        global $con;
        $id = 0;
        if ($stmt = $con->prepare("SELECT `id` FROM `wh_domainprices` WHERE `tld` = ?")) {
            $stmt->bind_param("s", $tld);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            if ($stmt->num_rows == 0) {
                return null;
            }
            $stmt->fetch();
            $stmt->close();
        } else {
            throw new Exception("Database error: " . $con->error);
        }
        return new WHDomainPlan($id);
    }
}
