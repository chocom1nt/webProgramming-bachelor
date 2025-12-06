document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    const studentForm = document.getElementById('studentForm');
    
    // Маска для телефона в формате +7 (XXX)-XXX-XX-XX
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Если начинается с 7 или 8, убираем первую цифру
            if (value.startsWith('7') || value.startsWith('8')) {
                value = value.substring(1);
            }
            
            // Ограничиваем 10 цифрами
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            // Форматируем в формат +7 (XXX)-XXX-XX-XX
            let formatted = '';
            if (value.length > 0) {
                formatted = '+7 (';
                formatted += value.substring(0, 3);
                if (value.length > 3) {
                    formatted += ')-' + value.substring(3, 6);
                }
                if (value.length > 6) {
                    formatted += '-' + value.substring(6, 8);
                }
                if (value.length > 8) {
                    formatted += '-' + value.substring(8, 10);
                }
            }
            
            e.target.value = formatted;
        });
        
        // Валидация телефона при потере фокуса
        phoneInput.addEventListener('blur', function() {
            validatePhone(this.value);
        });
    }
    
    // Функция валидации телефона (формат: +7 (XXX)-XXX-XX-XX)
    function validatePhone(phone) {
        if (!phone || phone.trim() === '') {
            if (phoneInput) phoneInput.classList.remove('is-invalid');
            return true; // Телефон не обязателен
        }
        
        // Проверяем формат: +7 (XXX)-XXX-XX-XX
        const phoneRegex = /^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/;
        
        if (phoneRegex.test(phone)) {
            if (phoneInput) phoneInput.classList.remove('is-invalid');
            return true;
        } else {
            if (phoneInput) phoneInput.classList.add('is-invalid');
            return false;
        }
    }
    
    // Валидация формы
    if (studentForm) {
        studentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Сбрасываем все ошибки
            const inputs = studentForm.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            let isValid = true;
            
            // Проверка обязательных полей
            const firstName = studentForm.querySelector('input[name="first_name"]');
            const lastName = studentForm.querySelector('input[name="last_name"]');
            
            if (!firstName.value.trim()) {
                firstName.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!lastName.value.trim()) {
                lastName.classList.add('is-invalid');
                isValid = false;
            }
            
            // Проверка email (если заполнен)
            const email = studentForm.querySelector('input[name="email"]');
            if (email.value.trim() && !isValidEmail(email.value)) {
                email.classList.add('is-invalid');
                isValid = false;
            }
            
            // Проверка телефона (если заполнен)
            if (phoneInput && phoneInput.value.trim() && !validatePhone(phoneInput.value)) {
                isValid = false;
            }
            
            if (isValid) {
                // Если все валидно, отправляем форму
                studentForm.submit();
            } else {
                // Показываем первую ошибку
                const firstInvalid = studentForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    }
    
    // Функция проверки email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Отключение стандартной браузерной валидации
    if (studentForm) {
        studentForm.setAttribute('novalidate', 'novalidate');
    }
    
    // Инициализация tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});