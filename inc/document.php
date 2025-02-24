<?php
class Document
{
    private int $id;
    private Project $project;
    private DocumentType $type;

    public function __construct(int $id)
    {
        global $con;
        $this->id = $id;
        $project_id = $type_id = 0;

        if ($stmt = $con->prepare('SELECT project_id, type FROM documents WHERE id = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($project_id, $type_id);
            if (!$stmt->fetch() || $stmt->num_rows === 0) {
                throw new Exception('A dokumentum nem található!');
            }
            $stmt->close();
        }

        $this->project = new Project($project_id);
        $this->type = new DocumentType($type_id);
    }

    public static function getAll(): array
    {
        global $con;
        $docs = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT id FROM documents')) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $docs[] = new Document($id);
            }
            $stmt->close();
        }

        return $docs;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getType(): DocumentType
    {
        return $this->type;
    }

    public function getCurrent(): DocumentVersion
    {
        global $con;
        $id = 0;

        if ($stmt = $con->prepare('SELECT id FROM documents_versions WHERE document_id = ? ORDER BY upload_date DESC LIMIT 1')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            if (!$stmt->fetch() || $stmt->num_rows === 0) {
                throw new Exception('A dokumentum aktuális verziója nem található!');
            }
            $stmt->close();
        }

        return new DocumentVersion($id);
    }

    public function getVersions(): array
    {
        global $con;
        $versions = [];
        $id = 0;

        if ($stmt = $con->prepare('SELECT id FROM documents_versions WHERE document_id = ? ORDER BY upload_date DESC')) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $versions[] = new DocumentVersion($id);
            }
            $stmt->close();
        }

        return $versions;
    }

    public function addVersion(array $file, string $changes = null): array
    {
        global $con, $user;
        $upload_user = $user->getId();

        $target_dir = ABS_PATH . 'storage/' . $this->getProject()->getId() . '/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = basename($file['name']);
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = preg_replace('/[^a-zA-Z0-9]/', '_', $filename);
        $filename = $filename . '_' . time();
        $filename = $filename . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

        $target_file = $target_dir . $filename;

        // kiterjesztés ellenőrzése (pdf vagy docx)
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_type !== 'pdf' && $file_type !== 'docx') {
            return [
                'status' => false,
                'message' => 'A fájl kiterjesztése nem megfelelő!'
            ];
        }

        // méret ellenőrzése (<=10MB)
        if ($file['size'] > 10485760) {
            return [
                'status' => false,
                'message' => 'A fájl mérete túl nagy!'
            ];
        }

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            if ($stmt = $con->prepare('UPDATE documents_versions SET active = 0 WHERE document_id = ?')) {
                $stmt->bind_param('i', $this->id);
                if (!$stmt->execute()) {
                    return [
                        'status' => false,
                        'message' => 'A verzió hozzáadása sikertelen!'
                    ];
                }
                $stmt->close();
            }

            if ($stmt = $con->prepare('INSERT INTO documents_versions (id, document_id, upload_date, upload_user, changes, filename, active) VALUES (NULL, ?, NOW(), ?, ?, ?, 1)')) {
                $stmt->bind_param('iiss', $this->id, $upload_user, $changes, $filename);
                if (!$stmt->execute()) {
                    return [
                        'status' => false,
                        'message' => 'A verzió hozzáadása sikertelen!'
                    ];
                }
                $stmt->close();
            }
        } else {
            return [
                'status' => false,
                'message' => 'A fájl feltöltése sikertelen!'
            ];
        }

        return [
            'status' => true,
            'message' => '',
            'url' => URL . 'storage/' . $this->getProject()->getId() . '/' . $filename
        ];
    }
}
