<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">AccountType</h4>
    <a href="<?= BASE_URL ?>/account-type/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add AccountType
    </a>
</div>

<form method="GET" action="<?= BASE_URL ?>/account-type" class="mb-3">
    <div class="input-group">
        <input type="search" name="q" class="form-control"
               placeholder="Search name or description"
               value="<?= htmlspecialchars($search ?? '') ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
        <a href="<?= BASE_URL ?>/account-type" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<?php if (empty($accounttype)): ?>
    <div class="card text-center p-5">
        <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
        <p class="mt-3 mb-3 text-muted">No account type found.</p>
        <a href="<?= BASE_URL ?>/account-type/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add AccountType
        </a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounttype as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/account-type/<?= $row['id'] ?>/edit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/account-type/<?= $row['id'] ?>/delete" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure?')">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
