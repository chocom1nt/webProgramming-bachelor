<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Преподаватели</h2>
    <a href="index.php?table=teachers&action=create" class="btn btn-success">
        + Добавить преподавателя
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>
                    <a href="<?= $this->getSortLink('id', $sort, $order, 'teachers') ?>" class="sort-link">
                        ID <?= $this->getSortIcon('id', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('first_name', $sort, $order, 'teachers') ?>" class="sort-link">
                        Имя <?= $this->getSortIcon('first_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('last_name', $sort, $order, 'teachers') ?>" class="sort-link">
                        Фамилия <?= $this->getSortIcon('last_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('email', $sort, $order, 'teachers') ?>" class="sort-link">
                        Email <?= $this->getSortIcon('email', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('type_name', $sort, $order, 'teachers') ?>" class="sort-link">
                        Тип преподавателя <?= $this->getSortIcon('type_name', $sort, $order) ?>
                    </a>
                </th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?= $teacher['id'] ?></td>
                <td><?= htmlspecialchars($teacher['first_name']) ?></td>
                <td><?= htmlspecialchars($teacher['last_name']) ?></td>
                <td><?= htmlspecialchars($teacher['email'] ?? 'Не указан') ?></td>
                <td><?= htmlspecialchars($teacher['type_name'] ?? 'Не указан') ?></td>
                <td>
                    <a href="index.php?table=teachers&action=edit&id=<?= $teacher['id'] ?>" 
                       class="btn btn-sm btn-warning" title="Редактировать">
                        ✏️
                    </a>
                    <a href="index.php?table=teachers&action=delete&id=<?= $teacher['id'] ?>" 
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