<div class="card">
    <div class="card-header">
        <h4><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Имя *</label>
                        <input type="text" class="form-control" name="first_name" 
                               value="<?= isset($teacher['first_name']) ? htmlspecialchars($teacher['first_name']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Фамилия *</label>
                        <input type="text" class="form-control" name="last_name" 
                               value="<?= isset($teacher['last_name']) ? htmlspecialchars($teacher['last_name']) : '' ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" 
                       value="<?= isset($teacher['email']) ? htmlspecialchars($teacher['email']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Тип преподавателя *</label>
                <select class="form-control" name="teacher_type_id" required>
                    <option value="">Выберите тип</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id'] ?>" 
                            <?= isset($teacher['teacher_type_id']) && $teacher['teacher_type_id'] == $type['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['type_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= isset($teacher) ? 'Сохранить изменения' : 'Создать преподавателя' ?>
            </button>
            <a href="index.php?table=teachers" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>