<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

class AccountsModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getAccounts($search = '')
    {
        $sql = "
            SELECT a.*, at.name AS account_type_name
            FROM accounts a
            INNER JOIN account_type at ON at.id = a.account_type_id
            WHERE a.deleted_at IS NULL
              AND at.deleted_at IS NULL
        ";
        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    a.name LIKE ?
                    OR a.email LIKE ?
                    OR a.identification_number LIKE ?
                )
            ";
            $keyword = '%' . $search . '%';
            $params = [$keyword, $keyword, $keyword];
        }

        $sql .= ' ORDER BY a.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getAccountById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM accounts WHERE id = ? AND deleted_at IS NULL'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM accounts
             WHERE email = ?
             AND deleted_at IS NULL'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function emailExists($email, $exceptId = null)
    {
        $sql = 'SELECT id FROM accounts WHERE email = ? AND deleted_at IS NULL';
        $params = [$email];

        if ($exceptId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    public function createAccount($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare(
            'INSERT INTO accounts
            (id, name, email, password, account_type_id, status,
             identification_number, identification_type, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $id,
            $data['name'],
            $data['email'],
            $data['password'],
            $data['account_type_id'],
            $data['status'],
            $data['identification_number'],
            $data['identification_type'],
        ]);

        return $id;
    }

    public function updateAccount($id, $data)
    {
        $sql = "
            UPDATE accounts
            SET name = ?, email = ?, account_type_id = ?, status = ?,
                identification_number = ?, identification_type = ?,
                updated_at = NOW()
        ";
        $params = [
            $data['name'],
            $data['email'],
            $data['account_type_id'],
            $data['status'],
            $data['identification_number'],
            $data['identification_type'],
        ];

        if (!empty($data['password'])) {
            $sql .= ', password = ?';
            $params[] = $data['password'];
        }

        $sql .= ' WHERE id = ? AND deleted_at IS NULL';
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteAccount($id)
    {
        $stmt = $this->db->prepare(
            'UPDATE accounts SET deleted_at = NOW()
             WHERE id = ? AND deleted_at IS NULL'
        );

        return $stmt->execute([$id]);
    }
}
