<div class="card">
    <div class="card-header">
        <h4><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Название типа *</label>
                <input type="text" class="form-control" name="type_name" 
                       value="<?= isset($type['type_name']) ? htmlspecialchars($type['type_name']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea class="form-control" name="description" rows="3"><?= 
                    isset($type['description']) ? htmlspecialchars($type['description']) : '' ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= isset($type) ? 'Сохранить изменения' : 'Создать тип' ?>
            </button>
            <a href="index.php?table=teacher_types" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>