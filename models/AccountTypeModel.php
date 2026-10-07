<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

class AccountTypeModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM accounttype ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM accounttype WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare("INSERT INTO accounttype (id, name) VALUES (?, ?)");
        $stmt->execute([$id, $data['name']]);

        return $id;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE accounttype SET name = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM accounttype WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
