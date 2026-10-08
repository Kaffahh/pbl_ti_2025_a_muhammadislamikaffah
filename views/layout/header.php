<?php global $page; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi klean' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="app-shell" id="appShell">

        <div class="sidebar">
            <a href="<?= BASE_URL ?>/" class="sidebar-brand">
                <span class="brand-mark"><i class="bi bi-shield-lock"></i></span>
                Logo klean
            </a>

            <ul class="sidebar-nav">
                <li>
                    <a href="<?= BASE_URL ?>/accounts" class="nav-link <?= $page === 'accounts' ? 'active' : '' ?>">
                        <i class="bi bi-people"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/account-type" class="nav-link <?= $page === 'account-type' ? 'active' : '' ?>">
                        <i class="bi bi-person-badge"></i> Account Type
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/actions" class="nav-link <?= $page === 'actions' ? 'active' : '' ?>">
                        <i class="bi bi-lightning"></i> Actions
                    </a>
                </li>
            </ul>

        </div>

        <div class="main">
            <header class="topbar">
                <?php $user = Auth::user(); ?>
                <?php if ($user): ?>
                    <div class="d-flex flex-column gap-0">
                        <span class="text-muted">Hi, <?= htmlspecialchars($user['name']) ?></span>
                        <span class="text-muted small"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <form action="<?= BASE_URL ?>/login/logout" method="POST">
                        <button type="submit" class="btn btn-danger btn-md">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                <?php endif; ?>
            </header>
            <div class="content">
