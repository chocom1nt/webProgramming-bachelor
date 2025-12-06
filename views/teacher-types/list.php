<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Типы преподавателей</h2>
    <a href="index.php?table=teacher_types&action=create" class="btn btn-success">
        + Добавить тип
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>
                    <a href="<?= $this->getSortLink('id', $sort, $order, 'teacher_types') ?>" class="sort-link">
                        ID <?= $this->getSortIcon('id', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('type_name', $sort, $order, 'teacher_types') ?>" class="sort-link">
                        Название типа <?= $this->getSortIcon('type_name', $sort, $order) ?>
                    </a>
                </th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($types as $type): ?>
            <tr>
                <td><?= $type['id'] ?></td>
                <td><?= htmlspecialchars($type['type_name']) ?></td>
                <td><?= htmlspecialchars($type['description'] ?? 'Нет описания') ?></td>
                <td>
                    <a href="index.php?table=teacher_types&action=edit&id=<?= $type['id'] ?>" 
                       class="btn btn-sm btn-warning" title="Редактировать">
                        ✏️
                    </a>
                    <a href="index.php?table=teacher_types&action=delete&id=<?= $type['id'] ?>" 
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