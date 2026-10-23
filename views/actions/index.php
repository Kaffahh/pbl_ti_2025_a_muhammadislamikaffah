<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Actions</h4>
    <a href="<?= BASE_URL ?>/actions/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Actions
    </a>
</div>

<form method="GET" action="<?= BASE_URL ?>/actions" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <label for="actions-search-column" class="visually-hidden">Search column</label>
            <select id="actions-search-column" name="column" class="form-select" required>
            <option value="name" <?= ($searchColumn ?? 'name') === 'name' ? 'selected' : '' ?>>Name</option>
            <option value="description" <?= ($searchColumn ?? '') === 'description' ? 'selected' : '' ?>>Description</option>
            </select>
        </div>
        <div class="col">
            <label for="actions-search-keyword" class="visually-hidden">Search keyword</label>
            <input id="actions-search-keyword" type="search" name="keyword" class="form-control"
                   placeholder="Enter keyword"
                   value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </div>
        <div class="col-auto">
            <a href="<?= BASE_URL ?>/actions" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<?php if (empty($actions)): ?>
    <div class="card text-center p-5">
        <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
        <p class="mt-3 mb-3 text-muted">No actions found.</p>
        <a href="<?= BASE_URL ?>/actions/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add Actions
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
                        <th>Created At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actions as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><small class="text-muted"><?= htmlspecialchars($row['created_at']) ?></small></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/actions/<?= $row['id'] ?>/edit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/actions/<?= $row['id'] ?>/delete" method="POST" class="d-inline"
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

    <nav aria-label="Actions pagination" class="mt-3">
        <ul class="pagination justify-content-end">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="<?= BASE_URL ?>/actions?<?= http_build_query(['column' => $searchColumn, 'keyword' => $search, 'page' => $currentPage - 1]) ?>"
                   <?= $currentPage <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                    Previous
                </a>
            </li>

            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>"
                    <?= $page === $currentPage ? 'aria-current="page"' : '' ?>>
                    <a class="page-link"
                       href="<?= BASE_URL ?>/actions?<?= http_build_query(['column' => $searchColumn, 'keyword' => $search, 'page' => $page]) ?>">
                        <?= $page ?>
                    </a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="<?= BASE_URL ?>/actions?<?= http_build_query(['column' => $searchColumn, 'keyword' => $search, 'page' => $currentPage + 1]) ?>"
                   <?= $currentPage >= $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                    Next
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
