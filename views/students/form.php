<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="POST" id="studentForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Имя *</label>
                        <input type="text" class="form-control" name="first_name" 
                               value="<?= isset($student['first_name']) ? htmlspecialchars($student['first_name']) : '' ?>" 
                               required>
                        <div class="invalid-feedback">Пожалуйста, введите имя.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Фамилия *</label>
                        <input type="text" class="form-control" name="last_name" 
                               value="<?= isset($student['last_name']) ? htmlspecialchars($student['last_name']) : '' ?>" 
                               required>
                        <div class="invalid-feedback">Пожалуйста, введите фамилию.</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= isset($student['email']) ? htmlspecialchars($student['email']) : '' ?>">
                        <div class="invalid-feedback">Пожалуйста, введите корректный email.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" class="form-control" name="phone" 
                               id="phone"
                               placeholder="+7 (XXX) XXX-XX-XX"
                               value="<?= isset($student['phone']) ? htmlspecialchars($student['phone']) : '' ?>"
                               data-toggle="tooltip" 
                               title="Формат: +7 (XXX) XXX-XX-XX">
                        <div class="invalid-feedback">Неверный формат телефона. Используйте: +7 (XXX) XXX-XX-XX</div>
                        <small class="form-text text-muted">Формат: +7 (XXX) XXX-XX-XX</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <?= isset($student) ? 'Сохранить изменения' : 'Создать студента' ?>
                </button>
                <a href="/?table=students" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
</div>

<script src="/js/student-form.js"></script>