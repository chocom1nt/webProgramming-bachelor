<div class="card">
    <div class="card-header">
        <h4><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Студент *</label>
                        <select class="form-control" name="student_id" required>
                            <option value="">Выберите студента</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>" 
                                    <?= isset($payment['student_id']) && $payment['student_id'] == $student['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($student['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Курс *</label>
                        <select class="form-control" name="course_id" required>
                            <option value="">Выберите курс</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>" 
                                    <?= isset($payment['course_id']) && $payment['course_id'] == $course['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['title']) ?> (<?= $course['price'] ?> ₽)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Сумма платежа (₽) *</label>
                        <input type="number" step="0.01" class="form-control" name="amount" 
                               value="<?= isset($payment['amount']) ? $payment['amount'] : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Дата платежа *</label>
                        <input type="date" class="form-control" name="payment_date" 
                               value="<?= isset($payment['payment_date']) ? $payment['payment_date'] : date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Статус *</label>
                <select class="form-control" name="status" required>
                    <option value="pending" <?= isset($payment['status']) && $payment['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                    <option value="completed" <?= isset($payment['status']) && $payment['status'] == 'completed' ? 'selected' : '' ?>>Оплачено</option>
                    <option value="cancelled" <?= isset($payment['status']) && $payment['status'] == 'cancelled' ? 'selected' : '' ?>>Отменено</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= isset($payment) ? 'Сохранить изменения' : 'Создать платеж' ?>
            </button>
            <a href="index.php?table=payments" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>