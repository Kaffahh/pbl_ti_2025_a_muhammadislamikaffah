<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

class ActionsModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getAll()
    {
        $stmt = $this->db->query(
            "SELECT * FROM actions
             WHERE deleted_at IS NULL
             ORDER BY name ASC"
        );
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM actions
             WHERE id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare(
            "INSERT INTO actions
             (id, name, description, created_at)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->execute([$id, $data['name'], $data['description']]);

        return $id;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE actions
             SET name = ?, description = ?, updated_at = NOW()
             WHERE id = ? AND deleted_at IS NULL"
        );
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE actions
             SET deleted_at = NOW()
             WHERE id = ? AND deleted_at IS NULL"
        );
        return $stmt->execute([$id]);
    }
}
