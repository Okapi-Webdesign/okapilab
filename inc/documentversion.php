<?php
class DocumentVersion
{
    private int $id;
    private Document $document;
    private string $date;
    private User $user;
    private int $version;
    private string|null $changes;
    private string $filename;
    private bool $active;

    public function __construct(int $id)
    {
        global $con;
        $document_id = $user_id = $version = $date = $changes = $filename = $active = 0;

        if ($stmt = $con->prepare('SELECT `id`, `document_id`, `upload_date`, `upload_user`, `changes`, `filename`, `active` FROM `documents_versions` WHERE `id` = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $document_id, $date, $user_id, $changes, $filename, $active);
            if (!$stmt->fetch() || $stmt->num_rows === 0) {
                throw new Exception('A dokumentumverzió nem található!');
            }
            $stmt->close();
        }

        $this->id = $id;
        $this->document = new Document($document_id);
        $this->date = $date;
        $this->user = new User($user_id);
        $this->changes = $changes;
        $this->filename = $filename;
        $this->active = $active == 1;

        if ($stmt = $con->prepare('SELECT COUNT(`id`) FROM `documents_versions` WHERE `document_id` = ? AND `upload_date` <= ?')) {
            $stmt->bind_param('is', $document_id, $date);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($version);
            $stmt->fetch();
            $stmt->close();
        }

        $this->version = $version;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getChanges(): string|null
    {
        return $this->changes;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getDate(bool $formatted = false): string
    {
        if ($formatted) {
            return date('Y. m. d. H:i', strtotime($this->date));
        }
        return $this->date;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isCurrent(): bool
    {
        return $this->document->getCurrent()->getId() === $this->id && $this->active;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getSize(): int
    {
        return filesize(ABS_PATH . 'storage/' . $this->getDocument()->getProject()->getId() . '/' . $this->filename);
    }
}
