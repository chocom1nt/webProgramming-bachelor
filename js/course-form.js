document.addEventListener('DOMContentLoaded', function() {
    const imageUrlInput = document.getElementById('image_url');
    const previewContainer = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const clearImageBtn = document.getElementById('clearImageBtn');
    
    // Предпросмотр при вводе URL
    if (imageUrlInput) {
        imageUrlInput.addEventListener('input', function() {
            if (this.value.trim()) {
                previewImage.src = this.value;
                previewContainer.style.display = 'block';
                if (clearImageBtn) clearImageBtn.style.display = 'block';
            } else {
                previewContainer.style.display = 'none';
                if (clearImageBtn) clearImageBtn.style.display = 'none';
            }
        });
        
        // Если при загрузке уже есть URL, показываем превью
        if (imageUrlInput.value.trim()) {
            previewContainer.style.display = 'block';
            if (clearImageBtn) clearImageBtn.style.display = 'block';
        }
    }
    
    // Кнопка очистки изображения
    if (clearImageBtn) {
        clearImageBtn.addEventListener('click', function() {
            if (imageUrlInput) {
                imageUrlInput.value = '';
                previewContainer.style.display = 'none';
                this.style.display = 'none';
            }
        });
    }
    
    // Обработка ошибок загрузки изображения
    if (previewImage) {
        previewImage.onerror = function() {
            this.src = IMAGE_NOT_FOUND;
        };
    }
});