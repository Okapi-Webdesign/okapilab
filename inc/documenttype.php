<?php
class DocumentType
{
    private int $id;
    private string $name;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $name = '';

        if ($stmt = $con->prepare('SELECT name FROM documents_types WHERE id = ?')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($name);
            $stmt->fetch();
            $stmt->close();
        }

        $this->name = $name;
    }

    public static function getAll(): array
    {
        global $con;
        $types = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT id FROM documents_types ORDER BY name ASC')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);

            while ($stmt->fetch()) {
                $types[] = new DocumentType($id);
            }

            $stmt->close();
        }

        return $types;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
