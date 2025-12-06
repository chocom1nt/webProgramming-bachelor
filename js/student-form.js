document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    const clearPhoneBtn = document.getElementById('clearPhoneBtn');
    const studentForm = document.getElementById('studentForm');
    
    // Очистка телефона
    if (clearPhoneBtn) {
        clearPhoneBtn.addEventListener('click', function() {
            if (phoneInput) {
                phoneInput.value = '';
                phoneInput.classList.remove('is-invalid');
            }
        });
    }
    
    // Улучшенная маска для телефона
    if (phoneInput) {
        // Форматирование телефона при вводе
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Если начинается с 7 или 8, оставляем как есть
            if (value.startsWith('7') || value.startsWith('8')) {
                value = value.substring(1); // Убираем первую цифру
            }
            
            // Ограничиваем 10 цифрами
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            // Форматируем по мере ввода
            let formatted = '+7 ';
            if (value.length > 0) {
                formatted += '(' + value.substring(0, 3);
            }
            if (value.length >= 4) {
                formatted += ') ' + value.substring(3, 6);
            }
            if (value.length >= 7) {
                formatted += '-' + value.substring(6, 8);
            }
            if (value.length >= 9) {
                formatted += '-' + value.substring(8, 10);
            }
            
            e.target.value = formatted;
        });
        
        // Валидация телефона при потере фокуса
        phoneInput.addEventListener('blur', function() {
            validatePhone(this.value);
        });
    }
    
    // Функция валидации телефона
    function validatePhone(phone) {
        if (!phone || phone.trim() === '') {
            return true; // Телефон не обязателен
        }
        
        // Удаляем все нецифровые символы
        const digits = phone.replace(/\D/g, '');
        
        // Проверяем различные форматы:
        // 1. Российский номер: 7XXXXXXXXXX или 8XXXXXXXXXX (11 цифр)
        // 2. Международный формат: +7XXXXXXXXXX
        if (digits.length === 11 && (digits.startsWith('7') || digits.startsWith('8'))) {
            return true;
        }
        
        if (digits.length === 10 && phone.startsWith('+7')) {
            return true;
        }
        
        // Если номер не пустой и не соответствует форматам, показываем ошибку
        phoneInput.classList.add('is-invalid');
        return false;
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