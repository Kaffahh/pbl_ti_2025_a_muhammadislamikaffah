<?php

require_once __DIR__ . '/AccountsModel.php';

class LoginModel
{
    private $accounts;

    public function __construct()
    {
        $this->accounts = new AccountsModel();
    }

    public function authenticate($email, $password)
    {
        $account = $this->accounts->findByEmail($email);

        if ($account === null || !password_verify($password, $account['password'])) {
            return [
                'account' => null,
                'error' => 'Email atau password salah.',
            ];
        }

        if ($account['status'] !== 'aktif') {
            return [
                'account' => null,
                'error' => 'Akun kamu sedang nonaktif. Hubungi administrator untuk mengaktifkannya kembali.',
            ];
        }

        return [
            'account' => $account,
            'error' => null,
        ];
    }
}
