<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Платежи</h2>
    <a href="index.php?table=payments&action=create" class="btn btn-success">
        + Добавить платеж
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>
                    <a href="<?= $this->getSortLink('id', $sort, $order, 'payments') ?>" class="sort-link">
                        ID <?= $this->getSortIcon('id', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('student_name', $sort, $order, 'payments') ?>" class="sort-link">
                        Студент <?= $this->getSortIcon('student_name', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('course_title', $sort, $order, 'payments') ?>" class="sort-link">
                        Курс <?= $this->getSortIcon('course_title', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('amount', $sort, $order, 'payments') ?>" class="sort-link">
                        Сумма <?= $this->getSortIcon('amount', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('payment_date', $sort, $order, 'payments') ?>" class="sort-link">
                        Дата <?= $this->getSortIcon('payment_date', $sort, $order) ?>
                    </a>
                </th>
                <th>
                    <a href="<?= $this->getSortLink('status', $sort, $order, 'payments') ?>" class="sort-link">
                        Статус <?= $this->getSortIcon('status', $sort, $order) ?>
                    </a>
                </th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?= $payment['id'] ?></td>
                <td><?= htmlspecialchars($payment['student_name']) ?></td>
                <td><?= htmlspecialchars($payment['course_title']) ?></td>
                <td><?= number_format($payment['amount'], 2, '.', ' ') ?> ₽</td>
                <td><?= date('d.m.Y', strtotime($payment['payment_date'])) ?></td>
                <td>
                    <?php 
                    $statusClass = [
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $statusText = [
                        'pending' => 'Ожидает',
                        'completed' => 'Оплачено',
                        'cancelled' => 'Отменено'
                    ];
                    ?>
                    <span class="badge badge-<?= $statusClass[$payment['status']] ?? 'secondary' ?> p-2" 
                          style="min-width: 80px; display: inline-block; color: white !important;">
                        <?= $statusText[$payment['status']] ?? $payment['status'] ?>
                    </span>
                </td>
                <td>
                    <a href="index.php?table=payments&action=edit&id=<?= $payment['id'] ?>" 
                       class="btn btn-sm btn-warning" title="Редактировать">
                        ✏️
                    </a>
                    <a href="index.php?table=payments&action=delete&id=<?= $payment['id'] ?>" 
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