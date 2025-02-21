<?php
class Invoice
{
    private int $id;
    private string $invoice_id;
    private Project $project;
    private string $create_date;
    private string $deadline;
    private int $amount;
    private int $status;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $invoice_id = $project_id = $create_date = $deadline = $amount = $status = 0;

        if ($stmt = $con->prepare('SELECT `id`, `invoice_id`, `project_id`, `create_date`, `deadline`, `amount`, `status` FROM `invoices` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($id, $invoice_id, $project_id, $create_date, $deadline, $amount, $status);
            $stmt->fetch();
            $stmt->close();
        }

        $this->invoice_id = $invoice_id;
        $this->project = new Project($project_id);
        $this->create_date = $create_date;
        $this->deadline = $deadline;
        $this->amount = $amount;
        $this->status = $status;
    }

    public static function getAll(): array
    {
        global $con;
        $invoices = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `invoices`')) {
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $invoices[] = new Invoice($id);
            }
            $stmt->close();
        }
        return $invoices;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getCreateDate(bool $formatted = false): string
    {
        if (!$formatted) return $this->create_date;
        else return date('Y. m. d.', strtotime($this->create_date));
    }

    public function getDeadline(bool $formatted = false): string
    {
        if (!$formatted) return $this->deadline;
        else return date('Y. m. d.', strtotime($this->deadline));
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getStatus(int $formatted = 0): int|string
    {
        if ($formatted == 0) return $this->status;
        else if ($formatted == 1) {
            switch ($this->status) {
                case 0:
                    return 'Kiállítva';
                    break;
                case 1:
                    return 'Befizetve';
                    break;
                case 2:
                    return 'Sztornó';
                    break;
            }
        } else {
            switch ($this->status) {
                case 0:
                    return '<span class="badge text-bg-warning">Kiállítva</span>';
                    break;
                case 1:
                    return '<span class="badge text-bg-success">Befizetve</span>';
                    break;
                case 2:
                    return '<span class="badge text-bg-secondary">Sztornó</span>';
                    break;
            }
        }
    }

    public function getPayments(): array
    {
        global $con;
        $payments = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `fin_incomes` WHERE `invoice_id` = ? ORDER BY `datetime` DESC')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $payments[] = new FinIncome($id);
            }
            $stmt->close();
        }
        return $payments;
    }

    public function isPaid(): bool
    {
        global $con;
        $amount = 0;

        if ($stmt = $con->prepare('SELECT SUM(`amount`) FROM `fin_incomes` WHERE `invoice_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($amount);
            $stmt->fetch();
            $stmt->close();
        }

        return $amount >= $this->amount;
    }

    public function getRemaining(): int
    {
        global $con;
        $amount = 0;

        if ($stmt = $con->prepare('SELECT SUM(`amount`) FROM `fin_incomes` WHERE `invoice_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($amount);
            $stmt->fetch();
            $stmt->close();
        }

        return $this->amount - $amount;
    }

    public function getPaymentsSum(): int
    {
        global $con;
        $amount = 0;

        if ($stmt = $con->prepare('SELECT SUM(`amount`) FROM `fin_incomes` WHERE `invoice_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->bind_result($amount);
            $stmt->fetch();
            $stmt->close();
        }

        return $amount;
    }
}
