<?php
class FinExpense
{
    private int $id;
    private string $reason;
    private string $date;
    private int $amount;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $reason = $date = $amount = 0;

        if ($stmt = $con->prepare('SELECT `id`, `reason`, `amount`, `datetime` FROM `fin_expenses` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $reason, $amount, $date);
            $stmt->fetch();
            $stmt->close();
        }

        $this->reason = $reason;
        $this->date = $date;
        $this->amount = $amount;
    }

    public static function getAll(): array
    {
        global $con;
        $expenses = [];
        $id = $date = $type = 0;

        if ($stmt = $con->prepare('SELECT id, datetime, "expense" AS type FROM fin_expenses UNION ALL SELECT id, datetime, "payout" AS type FROM fin_payouts ORDER BY datetime;')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $date, $type);

            while ($stmt->fetch()) {
                if ($type == 'expense') {
                    $expenses[] = new FinExpense($id);
                } else {
                    $expenses[] = new FinPayout($id);
                }
            }

            $stmt->close();
        }

        return $expenses;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getDate(bool $formatted = false): string
    {
        if ($formatted) {
            return date('Y. m. d.', strtotime($this->date));
        }

        return $this->date;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return 'expense';
    }
}
