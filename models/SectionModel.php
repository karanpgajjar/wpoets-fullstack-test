<?php
class SectionModel
{
    private mysqli $db;
    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function getAll(): array
    {
        $sql  = "SELECT * FROM sections WHERE is_active = 1 ORDER BY sort_order ASC";
        $res  = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM sections WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


    public function getAllAdmin(): array
    {
        $sql = "SELECT * FROM sections ORDER BY sort_order ASC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare(
            "INSERT INTO sections (slug, label, icon_path, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'sssii',
            $data['slug'],
            $data['label'],
            $data['icon_path'],
            $data['sort_order'],
            $data['is_active']
        );
        $ok = $stmt->execute();
        $newId = $ok ? (int) $this->db->insert_id : false;
        $stmt->close();
        return $newId;
    }


    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE sections
                SET slug       = ?,
                    label      = ?,
                    icon_path  = ?,
                    sort_order = ?,
                    is_active  = ?
              WHERE id = ?"
        );
        $stmt->bind_param(
            'sssiii',
            $data['slug'],
            $data['label'],
            $data['icon_path'],
            $data['sort_order'],
            $data['is_active'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM sections WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    public function validate(array $data): array
    {
        $errors = [];
        if (empty(trim($data['slug'] ?? '')))  $errors[] = 'Slug is required.';
        if (empty(trim($data['label'] ?? ''))) $errors[] = 'Label is required.';
        return $errors;
    }
}
