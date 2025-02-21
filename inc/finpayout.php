<?php
class FinPayout
{
    private int $id;
    private User $user;
    private Project $project;
    private string $date;
    private int $amount;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;

        $user_id = $project_id = $amount = $date = 0;

        if ($stmt = $con->prepare('SELECT `id`, `account_id`, `project_id`, `amount`, `datetime` FROM `fin_payouts` WHERE `id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $user_id, $project_id, $amount, $date);
            $stmt->fetch();
            $stmt->close();

            $this->user = new User($user_id);
            $this->project = new Project($project_id);
            $this->date = $date;
            $this->amount = $amount;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getProject(): Project
    {
        return $this->project;
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

    public function getReason(): string
    {
        return 'Kifizetés ' . $this->user->getFullname() . ' részére a(z) ' . $this->project->getName() . ' projekthez';
    }

    public function getType(): string
    {
        return 'payout';
    }
}
