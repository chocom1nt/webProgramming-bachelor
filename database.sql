-- Создание таблицы видов преподавателей
CREATE TABLE teacher_types (
    id SERIAL PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- Создание таблицы преподавателей
CREATE TABLE teachers (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    teacher_type_id INTEGER REFERENCES teacher_types(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы студентов
CREATE TABLE students (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы курсов
CREATE TABLE courses (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    image_url TEXT, 
    image_alt VARCHAR(200),
    teacher_id INTEGER REFERENCES teachers(id) ON DELETE SET NULL,
    program TEXT,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);


-- Создание таблицы платежей
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    student_id INTEGER REFERENCES students(id) ON DELETE CASCADE,
    course_id INTEGER REFERENCES courses(id) ON DELETE CASCADE,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Индексы для ускорения запросов
CREATE INDEX idx_courses_teacher ON courses(teacher_id);
CREATE INDEX idx_teachers_type ON teachers(teacher_type_id);
CREATE INDEX idx_payments_student ON payments(student_id);
CREATE INDEX idx_payments_course ON payments(course_id);

-- Тестовые данные
INSERT INTO teacher_types (type_name, description) VALUES
('Профессор', 'Высшая научная квалификация'),
('Доцент', 'Ученое звание'),
('Старший преподаватель', 'Опытный преподаватель');

INSERT INTO teachers (first_name, last_name, email, teacher_type_id) VALUES
('Иван', 'Петров', 'i.petrov@university.ru', 1),
('Мария', 'Сидорова', 'm.sidorova@university.ru', 2),
('Алексей', 'Иванов', 'a.ivanov@university.ru', 3);

INSERT INTO students (first_name, last_name, email, phone) VALUES
('Анна', 'Смирнова', 'a.smirnova@university.ru', '+79161234567'),
('Дмитрий', 'Кузнецов', 'd.kuznetsov@university.ru', '+79167654321'),
('Елена', 'Попова', 'e.popova@university.ru', '+79165557788');

INSERT INTO courses (title, image_url, image_alt, teacher_id, program, price) VALUES
('Введение в Web-программирование', '/include/img/web-basic.jpg', 'Курс по основам веб-разработки', 1, 'HTML, CSS, JavaScript, PHP, базы данных', 25000.00),
('Продвинутый PHP и фреймворки', '/include/img/php-advanced.jpg', 'Курс по продвинутому PHP', 2, 'ООП, паттерны проектирования, Laravel, тестирование', 45000.00),
('Базы данных и SQL', '/include/img/database.jpg', 'Курс по базам данных', 3, 'Проектирование БД, SQL, оптимизация запросов', 30000.00);

INSERT INTO payments (student_id, course_id, amount, payment_date, status) VALUES
(1, 1, 25000.00, '2024-01-15', 'completed'),
(2, 2, 45000.00, '2024-02-20', 'completed'),
(3, 3, 30000.00, '2024-03-10', 'pending');