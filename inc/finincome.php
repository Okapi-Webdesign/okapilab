<?php
class FinIncome
{
    private int $id;
    private Invoice $invoice;
    private int $amount;
    private string $paymentDate;

    public function __construct(int $id)
    {
        $this->id = $id;
        global $con;
        $invoice_id = $amount = $paymentDate = 0;

        if ($stmt = $con->prepare('SELECT invoice_id, amount, datetime FROM fin_incomes WHERE id = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($invoice_id, $amount, $paymentDate);
            $stmt->fetch();
            $stmt->close();

            $this->invoice = new Invoice($invoice_id);
            $this->amount = $amount;
            $this->paymentDate = $paymentDate;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPaymentDate(bool $time = false): string
    {
        return date('Y. m. d' . ($time ? ' H:i' : ''), strtotime($this->paymentDate));
    }

    public static function getAll(): array
    {
        global $con;
        $incomes = [];
        $id = $invoice_id = $amount = $paymentDate = 0;

        if ($stmt = $con->prepare('SELECT id, invoice_id, amount, datetime FROM fin_incomes')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $invoice_id, $amount, $paymentDate);

            while ($stmt->fetch()) {
                $incomes[] = new FinIncome($id);
            }

            $stmt->close();
        }

        return $incomes;
    }
}
