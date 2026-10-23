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

    public function countActions($search = '', $searchColumn = 'name')
    {
        $sql = "SELECT COUNT(*) FROM actions WHERE deleted_at IS NULL";
        $params = [];

        $allowedColumns = ['name', 'description'];
        $searchColumn = in_array($searchColumn, $allowedColumns, true)
            ? $searchColumn
            : 'name';

        if ($search !== '') {
            $sql .= " AND {$searchColumn} LIKE ?";
            $keyword = '%' . $search . '%';
            $params = [$keyword];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function getActions($search = '', $limit = 10, $offset = 0, $searchColumn = 'name')
    {
        $sql = "SELECT * FROM actions WHERE deleted_at IS NULL";
        $params = [];

        $allowedColumns = ['name', 'description'];
        $searchColumn = in_array($searchColumn, $allowedColumns, true)
            ? $searchColumn
            : 'name';

        if ($search !== '') {
            $sql .= " AND {$searchColumn} LIKE ?";
            $keyword = '%' . $search . '%';
            $params = [$keyword];
        }

        $limit = max(1, (int) $limit);
        $offset = max(0, (int) $offset);
        $sql .= " ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getActionById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM actions
             WHERE id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function createAction($data)
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

    public function updateAction($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE actions
             SET name = ?, description = ?, updated_at = NOW()
             WHERE id = ? AND deleted_at IS NULL"
        );
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    public function deleteAction($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE actions
             SET deleted_at = NOW()
             WHERE id = ? AND deleted_at IS NULL"
        );
        return $stmt->execute([$id]);
    }
}
