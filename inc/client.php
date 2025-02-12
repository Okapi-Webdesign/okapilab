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
        $type = $name = $address = $tax_number = $registration_number = $company_form = $contact_lastname = $contact_firstname = $email = $phone = $create_date = '';
        $active = false;
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
        $id = 0;
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

    public function getTaxNumber(): string|null
    {
        return $this->tax_number;
    }

    public function getAddress(string $key = ''): string
    {
        if (empty($key)) {
            return $this->address['zip'] . ' ' . $this->address['city'] . ', ' . $this->address['address'] . ' ' . $this->address['address2'];
        } else {
            return $this->address[$key];
        }
    }

    public function getCompanyType(): string|null
    {
        return $this->company_form;
    }

    public function getCreateDate(bool $formatted = true): string
    {
        if ($formatted) {
            return date('Y. m. d.', strtotime($this->create_date));
        }
        return $this->create_date;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getContactName(): string
    {
        return $this->contact_lastname . ' ' . $this->contact_firstname;
    }

    public function getAccountId(): int
    {
        global $con;
        $id = 0;
        if ($stmt = $con->prepare('SELECT `id` FROM `accounts` WHERE `client_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            if (!$stmt->fetch()) {
                throw new Exception('Az ügyfélhez nem tartozik felhasználó!');
            }
            $stmt->close();
        }
        return $id;
    }

    public function getAllProjects(): array
    {
        global $con;
        $id = 0;
        $projects = [];
        if ($stmt = $con->prepare('SELECT `id` FROM `projects` WHERE `client_id` = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $projects[] = new Project($id);
            }
            $stmt->close();
        }
        return $projects;
    }
}
