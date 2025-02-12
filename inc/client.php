<?php
class Client
{
    private int $id;
    private int $type;
    private string $name;
    private array $address;
    private string|null $tax_number;
    private string $registration_number;
    private string|null $company_form;
    private string $contact_lastname;
    private string $contact_firstname;
    private string $email;
    private string $phone;
    private string $create_date;
    private bool $active;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $type = $name = $tax_number = $registration_number = $company_form = $contact_lastname = $contact_firstname = $email = $phone = $create_date = '';
        if ($stmt = $con->prepare('SELECT `id`, `type`, `name`, `address`, `tax_number`, `registration_number`, `company_form`, `contact_lastname`, `contact_firstname`, `contact_email`, `contact_phone`, `active`, `create_date` FROM `clients` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $type, $name, $address, $tax_number, $registration_number, $company_form, $contact_lastname, $contact_firstname, $email, $phone, $active, $create_date);
            if ($stmt->num_rows == 0) {
                throw new Exception('Az ügyfél nem található!');
            }
            if (!$stmt->fetch()) {
                throw new Exception('Hiba történt az ügyfél beolvasása közben!');
            }
            $stmt->close();
        }

        $this->type = $type;
        if ($type == 1) {
            $this->name = $contact_lastname . ' ' . $contact_firstname;
        } else {
            $this->name = $name;
        }

        $this->address = json_decode($address, true);
        $this->tax_number = $tax_number;
        $this->registration_number = $registration_number;
        $this->company_form = $company_form;
        $this->contact_lastname = $contact_lastname;
        $this->contact_firstname = $contact_firstname;
        $this->email = $email;
        $this->phone = $phone;
        $this->active = $active == 1 ? true : false;
        $this->create_date = $create_date;
    }

    public static function getAll(bool $activeonly = true): array
    {
        global $con;
        if ($activeonly) $sql = 'SELECT `id` FROM `clients` WHERE `active` = 1 ORDER BY `name`';
        else $sql = 'SELECT `id` FROM `clients` ORDER BY `name`';
        $clients = [];
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $clients[] = new Client($id);
            }
            $stmt->close();
        }
        return $clients;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registration_number;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(bool $formatted = false): string
    {
        if ($formatted) {
            // formátum: XXX XX XXX XXXX
            return substr($this->phone, 0, 3) . ' ' . substr($this->phone, 3, 2) . ' ' . substr($this->phone, 5, 3) . ' ' . substr($this->phone, 8, 4);
        } else {
            return $this->phone;
        }
    }
}
