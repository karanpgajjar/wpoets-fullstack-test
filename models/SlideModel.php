<?php
class SlideModel
{
    private mysqli $db;
    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function getAll(): array
    {
        $sql = "SELECT sl.*, se.label AS section_label, se.slug AS section_slug
                FROM   slides  sl
                JOIN   sections se ON se.id = sl.section_id
                WHERE  sl.is_active = 1
                ORDER  BY sl.sort_order ASC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT sl.*, se.label AS section_label
             FROM   slides sl
             JOIN   sections se ON se.id = sl.section_id
             WHERE  sl.id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


    public function getAllAdmin(): array
    {
        $sql = "SELECT sl.*, se.label AS section_label
                FROM   slides  sl
                JOIN   sections se ON se.id = sl.section_id
                ORDER  BY sl.sort_order ASC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare(
            "INSERT INTO slides (section_id, category, title, link_url, image_path, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'issssii',
            $data['section_id'],
            $data['category'],
            $data['title'],
            $data['link_url'],
            $data['image_path'],
            $data['sort_order'],
            $data['is_active']
        );
        $ok    = $stmt->execute();
        $newId = $ok ? (int) $this->db->insert_id : false;
        $stmt->close();
        return $newId;
    }


    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE slides
                SET section_id = ?,
                    category   = ?,
                    title      = ?,
                    link_url   = ?,
                    image_path = ?,
                    sort_order = ?,
                    is_active  = ?
              WHERE id = ?"
        );

        $stmt->bind_param(
            'issssiii',
            $data['section_id'],
            $data['category'],
            $data['title'],
            $data['link_url'],
            $data['image_path'],
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
        $stmt = $this->db->prepare("DELETE FROM slides WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    public function validate(array $data): array
    {
        $errors = [];
        if (empty($data['section_id']))            $errors[] = 'Section is required.';
        if (empty(trim($data['category'] ?? '')))  $errors[] = 'Category is required.';
        if (empty(trim($data['title'] ?? '')))     $errors[] = 'Title is required.';
        return $errors;
    }
}
