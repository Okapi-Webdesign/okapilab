<?php
class WHPlan
{
    private int $id;
    private string $name;
    private int $size;
    private int $cost; // A mi költségünk
    private int $monthly_price;
    private int $yearly_price;
    private bool $wordpress;
    private bool $managed;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $name = $size = $cost = $monthly_price = $yearly_price = $wordpress = $managed = '';
        if ($stmt = $con->prepare('SELECT `id`, `name`, `size`, `cost`, `monthly_price`, `yearly_price`, `wordpress`, `managed` FROM `wh_plans` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $name, $size, $cost, $monthly_price, $yearly_price, $wordpress, $managed);
            if ($stmt->num_rows == 0) {
                throw new Exception('Nincs ilyen webtárhely csomag!');
            }
            $stmt->fetch();
            $stmt->close();
        } else {
            throw new Exception('Hiba a webtárhely csomag betöltésekor!');
        }

        $this->name = $name;
        $this->size = $size;
        $this->cost = $cost;
        $this->monthly_price = $monthly_price;
        $this->yearly_price = $yearly_price;
        $this->wordpress = $wordpress;
        $this->managed = $managed;
    }

    public static function getAll(): array
    {
        global $con;
        $plans = [];
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `wh_plans` ORDER BY `monthly_price` ASC')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $plans[] = new WHPlan($id);
            }
            $stmt->close();
        } else {
            throw new Exception('Hiba a webtárhely csomagok betöltésekor!');
        }

        return $plans;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(bool $formatted = false)
    {
        if ($formatted) {
            return number_format($this->size, 0, '.', ' ') . ' MB';
        } else {
            return $this->size;
        }
    }

    public function getCost(bool $formatted = false)
    {
        if ($formatted) {
            return number_format($this->cost, 0, '.', ' ') . ' Ft';
        } else {
            return $this->cost;
        }
    }

    public function getMonthlyPrice(bool $formatted = false)
    {
        if ($formatted) {
            return number_format($this->monthly_price, 0, '.', ' ') . ' Ft';
        } else {
            return $this->monthly_price;
        }
    }

    public function getYearlyPrice(bool $formatted = false)
    {
        if ($formatted) {
            return number_format($this->yearly_price, 0, '.', ' ') . ' Ft';
        } else {
            return $this->yearly_price;
        }
    }

    public function isWordpress(): bool
    {
        return $this->wordpress;
    }

    public function isManaged(): bool
    {
        return $this->managed;
    }
}
