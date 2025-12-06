<?php
require_once 'config/constants.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Курсы</h2>
    <a href="<?= BASE_PATH ?>?table=courses&action=create" class="btn btn-success">
        + Добавить курс
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>
                    <a href="<?= $this->getSortLink('id', $sort, $order, 'courses') ?>" class="sort-link">
                        ID <?= $this->getSortIcon('id', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('title', $sort, $order, 'courses') ?>" class="sort-link">
                        Название <?= $this->getSortIcon('title', $sort, $order) ?>
                    </a>
                </th>
                <th>Изображение</th>
                <th>
                    <a href="<?= $this->getSortLink('teacher_name', $sort, $order, 'courses') ?>" class="sort-link">
                        Преподаватель <?= $this->getSortIcon('teacher_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('teacher_type', $sort, $order, 'courses') ?>" class="sort-link">
                        Тип преподавателя <?= $this->getSortIcon('teacher_type', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('price', $sort, $order, 'courses') ?>" class="sort-link">
                        Стоимость <?= $this->getSortIcon('price', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('is_active', $sort, $order, 'courses') ?>" class="sort-link">
                        Статус <?= $this->getSortIcon('is_active', $sort, $order) ?>
                    </a>
                </th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): 
                // Обрабатываем boolean значение из PostgreSQL
                $is_active = false;
                if ($course['is_active'] === true || $course['is_active'] === 't' || $course['is_active'] == 1) {
                    $is_active = true;
                }
            ?>
            <tr>
                <td><?= $course['id'] ?></td>
                <td><?= htmlspecialchars($course['title']) ?></td>
                <td>
                    <?php 
                    $imageUrl = !empty($course['image_url']) ? htmlspecialchars($course['image_url']) : BASE_PATH . IMAGE_NOT_FOUND;
                    $imageAlt = !empty($course['image_alt']) ? htmlspecialchars($course['image_alt']) : 'Изображение курса';
                    ?>
                    <img src="<?= $imageUrl ?>" 
                         alt="<?= $imageAlt ?>" 
                         style="width: 50px; height: 50px; object-fit: cover;"
                         onerror="this.src='<?= IMAGE_NOT_FOUND ?>'">
                </td>
                <td><?= htmlspecialchars($course['teacher_name'] ?? 'Не указан') ?></td>
                <td><?= htmlspecialchars($course['teacher_type'] ?? 'Не указан') ?></td>
                <td><?= number_format($course['price'], 2, '.', ' ') ?> ₽</td>
                <td>
                    <span class="badge badge-<?= $is_active ? 'success' : 'secondary' ?>">
                        <?= $is_active ? 'Активен' : 'Не активен' ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_PATH ?>?table=courses&action=edit&id=<?= $course['id'] ?>" 
                       class="btn btn-sm btn-warning" title="Редактировать">
                        ✏️
                    </a>
                    <a href="<?= BASE_PATH ?>?table=courses&action=delete&id=<?= $course['id'] ?>" 
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