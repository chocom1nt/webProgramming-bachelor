<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Студенты</h2>
    <a href="index.php?table=students&action=create" class="btn btn-success">
        + Добавить студента
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>
                    <a href="<?= $this->getSortLink('id', $sort, $order, 'students') ?>" class="sort-link">
                        ID <?= $this->getSortIcon('id', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('first_name', $sort, $order, 'students') ?>" class="sort-link">
                        Имя <?= $this->getSortIcon('first_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('last_name', $sort, $order, 'students') ?>" class="sort-link">
                        Фамилия <?= $this->getSortIcon('last_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('email', $sort, $order, 'students') ?>" class="sort-link">
                        Email <?= $this->getSortIcon('email', $sort, $order) ?>
                    </a>
                </th>
                <th>Телефон</th>
                <th>
                    <a href="<?= $this->getSortLink('created_at', $sort, $order, 'students') ?>" class="sort-link">
                        Дата регистрации <?= $this->getSortIcon('created_at', $sort, $order) ?>
                    </a>
                </th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= htmlspecialchars($student['first_name']) ?></td>
                <td><?= htmlspecialchars($student['last_name']) ?></td>
                <td><?= htmlspecialchars($student['email'] ?? 'Не указан') ?></td>
                <td><?= htmlspecialchars($student['phone'] ?? 'Не указан') ?></td>
                <td><?= date('d.m.Y', strtotime($student['created_at'])) ?></td>
                <td>
                    <a href="index.php?table=students&action=edit&id=<?= $student['id'] ?>" 
                       class="btn btn-sm btn-warning" title="Редактировать">
                        ✏️
                    </a>
                    <a href="index.php?table=students&action=delete&id=<?= $student['id'] ?>" 
                       class="btn btn-sm btn-danger" title="Удалить"
                       onclick="return confirmDelete()">
                        🗑️
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>