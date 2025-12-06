<div class="card">
    <div class="card-header">
        <h4><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="courseForm">
            <div class="form-group">
                <label>Название курса *</label>
                <input type="text" class="form-control" name="title" 
                       value="<?= isset($course['title']) ? htmlspecialchars($course['title']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Изображение курса</label>
                <div class="image-upload-area" id="imageUploadArea">
                    <div class="upload-options">
                        <div class="option">
                            <input type="radio" name="image_source" id="url_source" value="url" checked>
                            <label for="url_source">Ввести URL</label>
                        </div>
                        <div class="option">
                            <input type="radio" name="image_source" id="file_source" value="file">
                            <label for="file_source">Загрузить файл</label>
                        </div>
                        <div class="option">
                            <input type="radio" name="image_source" id="drag_source" value="drag">
                            <label for="drag_source">Drag & Drop</label>
                        </div>
                    </div>
                    
                    <div id="urlSection" class="source-section">
                        <input type="text" class="form-control" name="image_url" 
                               id="image_url" 
                               value="<?= isset($course['image_url']) ? htmlspecialchars($course['image_url']) : '' ?>"
                               placeholder="https://example.com/image.jpg">
                        <small class="form-text text-muted">Введите URL изображения или используйте загрузку файла</small>
                    </div>
                    
                    <div id="fileSection" class="source-section" style="display: none;">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image_file" 
                                   name="image_file" accept="image/*" data-max-size="5242880">
                            <label class="custom-file-label" for="image_file">Выберите файл (макс. 5MB)</label>
                            <div id="fileSizeError" class="invalid-feedback" style="display: none;">
                                Файл слишком большой. Максимальный размер: 5MB.
                            </div>
                        </div>
                        <small class="form-text text-muted">Файл будет сохранен на сервере</small>
                    </div>
                    
                    <div id="dragSection" class="source-section" style="display: none;">
                        <div class="drag-drop-area" id="dragDropArea">
                            <p>Перетащите изображение сюда</p>
                            <p>или</p>
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="document.getElementById('dragFileInput').click()">
                                Выберите файл
                            </button>
                            <input type="file" id="dragFileInput" style="display: none;" 
                                   accept="image/*" data-max-size="5242880">
                        </div>
                        <small class="form-text text-muted">Максимальный размер файла: 5MB</small>
                    </div>
                    
                    <div class="image-preview mt-3" id="imagePreview" 
                         style="<?= isset($course['image_url']) && $course['image_url'] ? '' : 'display: none;' ?>">
                        <img src="<?= isset($course['image_url']) ? htmlspecialchars($course['image_url']) : '' ?>" 
                             alt="Предпросмотр" id="previewImage" 
                             onerror="this.src='<?= IMAGE_NOT_FOUND ?>'">
                    </div>
                    
                    <!-- Скрытое поле для хранения загруженного изображения в base64 -->
                    <input type="hidden" name="base64_image" id="base64_image">
                </div>
                
                <div class="form-group mt-2">
                    <label>Alt текст для изображения</label>
                    <input type="text" class="form-control" name="image_alt" 
                           value="<?= isset($course['image_alt']) ? htmlspecialchars($course['image_alt']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Преподаватель *</label>
                <select class="form-control" name="teacher_id" required>
                    <option value="">Выберите преподавателя</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>" 
                            <?= isset($course['teacher_id']) && $course['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($teacher['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Программа курса *</label>
                <textarea class="form-control" name="program" rows="5" required><?= 
                    isset($course['program']) ? htmlspecialchars($course['program']) : '' ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Стоимость курса (₽) *</label>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               name="price" value="<?= isset($course['price']) ? $course['price'] : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Статус</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" 
                                   id="is_active" <?= (!isset($course['is_active']) || $course['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Активен</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= isset($course) ? 'Сохранить изменения' : 'Создать курс' ?>
            </button>
            <a href="/?table=courses" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>

<script src="/js/course-form.js"></script>