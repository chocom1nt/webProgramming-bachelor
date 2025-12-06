document.addEventListener('DOMContentLoaded', function() {
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB в байтах
    let currentFile = null;
    
    // Обработка выбора источника изображения
    const imageSources = document.querySelectorAll('input[name="image_source"]');
    if (imageSources.length > 0) {
        imageSources.forEach(radio => {
            radio.addEventListener('change', function() {
                // Скрываем все секции
                document.querySelectorAll('.source-section').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Показываем выбранную секцию
                const section = document.getElementById(this.value + 'Section');
                if (section) {
                    section.style.display = 'block';
                }
                
                // Сбрасываем файл при переключении
                currentFile = null;
                resetFileInputs();
            });
        });
    }
    
    // Сброс всех файловых инпутов
    function resetFileInputs() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.value = '';
            const label = input.nextElementSibling;
            if (label && label.classList.contains('custom-file-label')) {
                label.textContent = 'Выберите файл (макс. 5MB)';
            }
        });
        document.getElementById('base64_image').value = '';
        document.getElementById('image_url').value = '';
    }
    
    // Проверка размера файла
    function validateFileSize(file) {
        if (file.size > MAX_FILE_SIZE) {
            showFileSizeError();
            return false;
        }
        hideFileSizeError();
        return true;
    }
    
    function showFileSizeError() {
        const errorElement = document.getElementById('fileSizeError');
        if (errorElement) {
            errorElement.style.display = 'block';
        }
    }
    
    function hideFileSizeError() {
        const errorElement = document.getElementById('fileSizeError');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }
    
    // Предпросмотр для URL
    const imageUrlInput = document.getElementById('image_url');
    if (imageUrlInput) {
        imageUrlInput.addEventListener('input', function() {
            const preview = document.getElementById('previewImage');
            const previewContainer = document.getElementById('imagePreview');
            
            if (this.value) {
                if (preview) preview.src = this.value;
                if (previewContainer) previewContainer.style.display = 'block';
            } else {
                if (previewContainer) previewContainer.style.display = 'none';
            }
        });
    }
    
    // Обработка выбора файла
    const imageFileInput = document.getElementById('image_file');
    if (imageFileInput) {
        imageFileInput.addEventListener('change', function(e) {
            currentFile = e.target.files[0];
            if (currentFile && validateFileSize(currentFile)) {
                updateFileName(currentFile.name);
                processFile(currentFile);
            } else {
                currentFile = null;
                resetFileInputs();
            }
        });
    }
    
    // Drag & Drop
    const dragDropArea = document.getElementById('dragDropArea');
    const dragFileInput = document.getElementById('dragFileInput');
    
    if (dragDropArea) {
        dragDropArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f0f8ff';
        });
        
        dragDropArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
        });
        
        dragDropArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
            
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                currentFile = file;
                if (validateFileSize(currentFile)) {
                    updateFileName(currentFile.name);
                    processFile(currentFile);
                } else {
                    currentFile = null;
                    resetFileInputs();
                }
            } else {
                alert('Пожалуйста, выберите изображение (PNG, JPG, JPEG, GIF)');
                resetFileInputs();
            }
        });
    }
    
    if (dragFileInput) {
        dragFileInput.addEventListener('change', function(e) {
            currentFile = e.target.files[0];
            if (currentFile && validateFileSize(currentFile)) {
                updateFileName(currentFile.name);
                processFile(currentFile);
            } else {
                currentFile = null;
                resetFileInputs();
            }
        });
    }
    
    // Обновление имени файла в label
    function updateFileName(fileName) {
        const labels = document.querySelectorAll('.custom-file-label');
        labels.forEach(label => {
            if (label.classList.contains('custom-file-label')) {
                label.textContent = fileName;
            }
        });
    }
    
    // Обработка файла
    function processFile(file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('previewImage');
            const previewContainer = document.getElementById('imagePreview');
            const base64Input = document.getElementById('base64_image');
            
            if (preview) preview.src = e.target.result;
            if (previewContainer) previewContainer.style.display = 'block';
            
            // Сохраняем base64 в скрытом поле
            if (base64Input) {
                base64Input.value = e.target.result;
            }
            
            // Также заполняем поле URL (но обрезаем если очень длинное)
            const urlInput = document.getElementById('image_url');
            if (urlInput) {
                if (e.target.result.length > 1000) {
                    // Если base64 очень длинный, сохраняем только префикс
                    urlInput.value = 'data:image/jpeg;base64,[...]';
                } else {
                    urlInput.value = e.target.result;
                }
            }
        };
        
        reader.onerror = function() {
            alert('Ошибка чтения файла');
            resetFileInputs();
        };
        
        reader.readAsDataURL(file);
    }
    
    // Обработка отправки формы
    const courseForm = document.getElementById('courseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', function(e) {
            // Проверяем, не слишком ли большой файл
            if (currentFile && currentFile.size > MAX_FILE_SIZE) {
                e.preventDefault();
                showFileSizeError();
                alert('Файл слишком большой. Максимальный размер: 5MB.');
                return false;
            }
            
            // Если выбрана опция файла, но файл не выбран
            const fileSource = document.querySelector('input[name="image_source"]:checked');
            if (fileSource && (fileSource.value === 'file' || fileSource.value === 'drag')) {
                if (!currentFile) {
                    e.preventDefault();
                    alert('Пожалуйста, выберите файл изображения');
                    return false;
                }
            }
            
            return true;
        });
    }
});