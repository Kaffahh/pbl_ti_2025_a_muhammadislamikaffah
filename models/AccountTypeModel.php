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

    public function getAccountType($search = '')
    {
        $sql = "SELECT * FROM account_type WHERE deleted_at IS NULL";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $keyword = '%' . $search . '%';
            $params = [$keyword, $keyword];
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAccountTypeById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM account_type WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function createAccountType($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare("INSERT INTO account_type (id, name, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$id, $data['name'], $data['description']]);

        return $id;
    }

    public function updateAccountType($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE account_type SET name = ?, description = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    public function deleteAccountType($id)
    {
        $stmt = $this->db->prepare("UPDATE account_type SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        return $stmt->execute([$id]);
    }
}
