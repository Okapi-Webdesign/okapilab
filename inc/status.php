<?php
class Status
{
    private int $id;
    private string $name;
    private string $color;

    public function __construct(int|null $id)
    {
        global $con;
        $this->id = $id;
        $name = $color = null;

        if ($id !== NULL) {
            if ($stmt = $con->prepare('SELECT `name`, `color` FROM `projects_status` WHERE `id` = ?')) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($name, $color);
                if (!$stmt->fetch()) {
                    throw new Exception('A státusz nem található!');
                }

                $found = true;
                if ($stmt->num_rows === 0) {
                    $found = false;
                }
                $stmt->close();
            }
        } else {
            $found = false;
        }

        if (!$found) {
            $name = 'Ismeretlen';
            $color = '#fefefe';
        }

        $this->name = $name;
        $this->color = '#' . $color;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function print(): string
    {
        if (isLightColor($this->color)) {
            $color = '#000';
        } else {
            $color = '#fff';
        }
        return '<span class="badge" style="color:' . $color . ';background-color: ' . $this->color . '">' . $this->name . '</span>';
    }

    public static function getAll(): array
    {
        global $con;
        $statuses = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT `id` FROM `projects_status` WHERE `active` = 1 ORDER BY id ASC')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $statuses[] = new Status($id);
            }
            $stmt->close();
        }

        return $statuses;
    }
}
