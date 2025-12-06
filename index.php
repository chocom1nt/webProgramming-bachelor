<?php
session_start();
ob_start();

require_once 'core/Database.php';
require_once 'core/TeacherTypeTable.php';
require_once 'core/TeacherTable.php';
require_once 'core/StudentTable.php';
require_once 'core/CourseTable.php';
require_once 'core/PaymentTable.php';

$table = $_GET['table'] ?? 'courses';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($table) {
        case 'courses':
            handleCoursePost($action, $id);
            break;
        case 'teachers':
            handleTeacherPost($action, $id);
            break;
        case 'teacher_types':
            handleTeacherTypePost($action, $id);
            break;
        case 'students':
            handleStudentPost($action, $id);
            break;
        case 'payments':
            handlePaymentPost($action, $id);
            break;
    }
}

include 'templates/header.html';

switch ($table) {
    case 'courses':
        handleCourseGet($action, $id);
        break;
    case 'teachers':
        handleTeacherGet($action, $id);
        break;
    case 'teacher_types':
        handleTeacherTypeGet($action, $id);
        break;
    case 'students':
        handleStudentGet($action, $id);
        break;
    case 'payments':
        handlePaymentGet($action, $id);
        break;
    default:
        showDashboard();
}

include 'templates/footer.html';

function handleCoursePost($action, $id) {
    $courseTable = new CourseTable();
    
    switch ($action) {
        case 'create':
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $courseTable->create(
                $_POST['title'],
                $_POST['image_url'],
                $_POST['image_alt'],
                $_POST['teacher_id'],
                $_POST['program'],
                $_POST['price'],
                $is_active
            );
            header('Location: index.php?table=courses');
            exit;
            
        case 'edit':
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $courseTable->update(
                $id,
                $_POST['title'],
                $_POST['image_url'],
                $_POST['image_alt'],
                $_POST['teacher_id'],
                $_POST['program'],
                $_POST['price'],
                $is_active
            );
            header('Location: index.php?table=courses');
            exit;
    }
}

function handleTeacherPost($action, $id) {
    $teacherTable = new TeacherTable();
    
    switch ($action) {
        case 'create':
            $teacherTable->create(
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['teacher_type_id']
            );
            header('Location: index.php?table=teachers');
            exit;
            
        case 'edit':
            $teacherTable->update(
                $id,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['teacher_type_id']
            );
            header('Location: index.php?table=teachers');
            exit;
    }
}

function handleTeacherTypePost($action, $id) {
    $typeTable = new TeacherTypeTable();
    
    switch ($action) {
        case 'create':
            $typeTable->create($_POST['type_name'], $_POST['description']);
            header('Location: index.php?table=teacher_types');
            exit;
            
        case 'edit':
            $typeTable->update($id, $_POST['type_name'], $_POST['description']);
            header('Location: index.php?table=teacher_types');
            exit;
    }
}

function handleStudentPost($action, $id) {
    $studentTable = new StudentTable();
    
    $phone = $_POST['phone'] ?? '';
    if ($phone && !preg_match('/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $_SESSION['error'] = 'Номер телефона должен быть в формате: +7 (XXX)-XXX-XX-XX';
        header('Location: index.php?table=students&action=' . ($action == 'edit' ? 'edit&id=' . $id : 'create'));
        exit;
    }
    
    switch ($action) {
        case 'create':
            $studentTable->create(
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $phone
            );
            header('Location: index.php?table=students');
            exit;
            
        case 'edit':
            $studentTable->update(
                $id,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $phone
            );
            header('Location: index.php?table=students');
            exit;
    }
}

function handlePaymentPost($action, $id) {
    $paymentTable = new PaymentTable();
    
    switch ($action) {
        case 'create':
            $paymentTable->create(
                $_POST['student_id'],
                $_POST['course_id'],
                $_POST['amount'],
                $_POST['payment_date'],
                $_POST['status']
            );
            header('Location: index.php?table=payments');
            exit;
            
        case 'edit':
            $paymentTable->update(
                $id,
                $_POST['student_id'],
                $_POST['course_id'],
                $_POST['amount'],
                $_POST['payment_date'],
                $_POST['status']
            );
            header('Location: index.php?table=payments');
            exit;
    }
}

function handleCourseGet($action, $id) {
    $courseTable = new CourseTable();
    $teacherTable = new TeacherTable();
    
    switch ($action) {
        case 'create':
            showCourseForm($teacherTable->getForDropdown());
            break;
            
        case 'edit':
            $course = $courseTable->getById($id);
            showCourseForm($teacherTable->getForDropdown(), $course);
            break;
            
        case 'delete':
            $courseTable->delete($id);
            header('Location: index.php?table=courses');
            exit;
            
        default:
            $courses = $courseTable->getAll();
            showCourseList($courses);
    }
}

function handleTeacherGet($action, $id) {
    $teacherTable = new TeacherTable();
    $typeTable = new TeacherTypeTable();
    
    switch ($action) {
        case 'create':
            showTeacherForm($typeTable->getAll());
            break;
            
        case 'edit':
            $teacher = $teacherTable->getById($id);
            showTeacherForm($typeTable->getAll(), $teacher);
            break;
            
        case 'delete':
            $teacherTable->delete($id);
            header('Location: index.php?table=teachers');
            exit;
            
        default:
            $teachers = $teacherTable->getAll();
            showTeacherList($teachers);
    }
}

function handleTeacherTypeGet($action, $id) {
    $typeTable = new TeacherTypeTable();
    
    switch ($action) {
        case 'create':
            showTeacherTypeForm();
            break;
            
        case 'edit':
            $type = $typeTable->getById($id);
            showTeacherTypeForm($type);
            break;
            
        case 'delete':
            $typeTable->delete($id);
            header('Location: index.php?table=teacher_types');
            exit;
            
        default:
            $types = $typeTable->getAll();
            showTeacherTypeList($types);
    }
}

function handleStudentGet($action, $id) {
    $studentTable = new StudentTable();
    
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
    
    switch ($action) {
        case 'create':
            showStudentForm();
            break;
            
        case 'edit':
            $student = $studentTable->getById($id);
            showStudentForm($student);
            break;
            
        case 'delete':
            $studentTable->delete($id);
            header('Location: index.php?table=students');
            exit;
            
        default:
            $students = $studentTable->getAll();
            showStudentList($students);
    }
}

function handlePaymentGet($action, $id) {
    $paymentTable = new PaymentTable();
    $studentTable = new StudentTable();
    $courseTable = new CourseTable();
    
    switch ($action) {
        case 'create':
            showPaymentForm($studentTable->getForDropdown(), $courseTable->getActiveCourses());
            break;
            
        case 'edit':
            $payment = $paymentTable->getById($id);
            showPaymentForm($studentTable->getForDropdown(), $courseTable->getActiveCourses(), $payment);
            break;
            
        case 'delete':
            $paymentTable->delete($id);
            header('Location: index.php?table=payments');
            exit;
            
        default:
            $payments = $paymentTable->getAll();
            showPaymentList($payments);
    }
}

function showDashboard() {
    ?>
    <div class="jumbotron">
        <h1 class="display-4">Добро пожаловать в систему управления курсами!</h1>
        <p class="lead">Здесь вы можете управлять курсами, преподавателями, студентами и платежами.</p>
        <hr class="my-4">
        <p>Выберите раздел в меню навигации для начала работы.</p>
        
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">📚 Курсы</h5>
                        <p class="card-text">Управление учебными курсами</p>
                        <a href="index.php?table=courses" class="btn btn-primary">Перейти</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👨‍🏫 Преподаватели</h5>
                        <p class="card-text">Управление преподавателями</p>
                        <a href="index.php?table=teachers" class="btn btn-primary">Перейти</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👨‍🎓 Студенты</h5>
                        <p class="card-text">Управление студентами</p>
                        <a href="index.php?table=students" class="btn btn-primary">Перейти</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">💰 Платежи</h5>
                        <p class="card-text">Управление платежами</p>
                        <a href="index.php?table=payments" class="btn btn-primary">Перейти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function showCourseForm($teachers, $course = null) {
    $isEdit = $course !== null;
    ?>
    <div class="card">
        <div class="card-header">
            <h4><?= $isEdit ? 'Редактирование курса' : 'Создание нового курса' ?></h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" id="courseForm">
                <div class="form-group">
                    <label>Название курса *</label>
                    <input type="text" class="form-control" name="title" 
                           value="<?= $isEdit ? htmlspecialchars($course['title']) : '' ?>" required>
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
                                   value="<?= $isEdit ? htmlspecialchars($course['image_url']) : '' ?>"
                                   placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div id="fileSection" class="source-section" style="display: none;">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image_file" 
                                       name="image_file" accept="image/*">
                                <label class="custom-file-label" for="image_file">Выберите файл</label>
                            </div>
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
                                       accept="image/*">
                            </div>
                        </div>
                        
                        <div class="image-preview mt-3" id="imagePreview" 
                             style="<?= $isEdit && $course['image_url'] ? '' : 'display: none;' ?>">
                            <img src="<?= $isEdit && $course['image_url'] ? htmlspecialchars($course['image_url']) : '' ?>" 
                                 alt="Предпросмотр" id="previewImage" 
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+'">
                        </div>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label>Alt текст для изображения</label>
                        <input type="text" class="form-control" name="image_alt" 
                               value="<?= $isEdit ? htmlspecialchars($course['image_alt']) : '' ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Преподаватель *</label>
                    <select class="form-control" name="teacher_id" required>
                        <option value="">Выберите преподавателя</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>" 
                                <?= $isEdit && $course['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($teacher['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Программа курса *</label>
                    <textarea class="form-control" name="program" rows="5" required><?= 
                        $isEdit ? htmlspecialchars($course['program']) : '' ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Стоимость курса (₽) *</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   name="price" value="<?= $isEdit ? $course['price'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Статус</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" 
                                       id="is_active" <?= (!$isEdit || ($isEdit && $course['is_active'])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Активен</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Сохранить изменения' : 'Создать курс' ?>
                </button>
                <a href="index.php?table=courses" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    
    <script>
    document.querySelectorAll('input[name="image_source"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.source-section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(this.value + 'Section').style.display = 'block';
        });
    });
    
    document.getElementById('image_url').addEventListener('input', function() {
        const preview = document.getElementById('previewImage');
        const previewContainer = document.getElementById('imagePreview');
        
        if (this.value) {
            preview.src = this.value;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    });
    
    document.getElementById('image_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('previewImage');
                preview.src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('image_url').value = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    
    const dragDropArea = document.getElementById('dragDropArea');
    const dragFileInput = document.getElementById('dragFileInput');
    
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
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('previewImage');
                preview.src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('image_url').value = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            alert('Пожалуйста, выберите изображение');
        }
    });
    
    dragFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('previewImage');
                preview.src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('image_url').value = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
    <?php
}

function showTeacherForm($types, $teacher = null) {
    $isEdit = $teacher !== null;
    ?>
    <div class="card">
        <div class="card-header">
            <h4><?= $isEdit ? 'Редактирование преподавателя' : 'Создание нового преподавателя' ?></h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Имя *</label>
                            <input type="text" class="form-control" name="first_name" 
                                   value="<?= $isEdit ? htmlspecialchars($teacher['first_name']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Фамилия *</label>
                            <input type="text" class="form-control" name="last_name" 
                                   value="<?= $isEdit ? htmlspecialchars($teacher['last_name']) : '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" 
                           value="<?= $isEdit ? htmlspecialchars($teacher['email']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label>Тип преподавателя *</label>
                    <select class="form-control" name="teacher_type_id" required>
                        <option value="">Выберите тип</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id'] ?>" 
                                <?= $isEdit && $teacher['teacher_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['type_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Сохранить изменения' : 'Создать преподавателя' ?>
                </button>
                <a href="index.php?table=teachers" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    <?php
}

function showTeacherTypeForm($type = null) {
    $isEdit = $type !== null;
    ?>
    <div class="card">
        <div class="card-header">
            <h4><?= $isEdit ? 'Редактирование типа преподавателя' : 'Создание нового типа' ?></h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Название типа *</label>
                    <input type="text" class="form-control" name="type_name" 
                           value="<?= $isEdit ? htmlspecialchars($type['type_name']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Описание</label>
                    <textarea class="form-control" name="description" rows="3"><?= 
                        $isEdit ? htmlspecialchars($type['description']) : '' ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Сохранить изменения' : 'Создать тип' ?>
                </button>
                <a href="index.php?table=teacher_types" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    <?php
}

function showStudentForm($student = null) {
    $isEdit = $student !== null;
    ?>
    <div class="card">
        <div class="card-header">
            <h4><?= $isEdit ? 'Редактирование студента' : 'Создание нового студента' ?></h4>
        </div>
        <div class="card-body">
            <form method="POST" id="studentForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Имя *</label>
                            <input type="text" class="form-control" name="first_name" 
                                   value="<?= $isEdit ? htmlspecialchars($student['first_name']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Фамилия *</label>
                            <input type="text" class="form-control" name="last_name" 
                                   value="<?= $isEdit ? htmlspecialchars($student['last_name']) : '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?= $isEdit ? htmlspecialchars($student['email']) : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="tel" class="form-control" name="phone" 
                                   id="phone"
                                   pattern="\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}"
                                   title="Формат: +7 (XXX)-XXX-XX-XX"
                                   placeholder="+7 (XXX)-XXX-XX-XX"
                                   value="<?= $isEdit ? htmlspecialchars($student['phone']) : '' ?>">
                            <small class="form-text text-muted">Формат: +7 (XXX)-XXX-XX-XX</small>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Сохранить изменения' : 'Создать студента' ?>
                </button>
                <a href="index.php?table=students" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    
    <script>
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.startsWith('7') || value.startsWith('8')) {
            value = value.substring(1);
        }
        
        if (value.length > 0) {
            value = '+7 (' + value.substring(0, 3);
            if (value.length > 8) {
                value += ')-' + value.substring(8, 11);
            }
            if (value.length > 13) {
                value += '-' + value.substring(13, 15);
            }
            if (value.length > 16) {
                value += '-' + value.substring(16, 18);
            }
        } else {
            value = '+7 (';
        }
        
        e.target.value = value;
    });
    
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('phone');
        const phonePattern = /^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/;
        
        if (phoneInput.value && !phonePattern.test(phoneInput.value)) {
            e.preventDefault();
            alert('Номер телефона должен быть в формате: +7 (XXX)-XXX-XX-XX');
            phoneInput.focus();
        }
    });
    </script>
    <?php
}

function showPaymentForm($students, $courses, $payment = null) {
    $isEdit = $payment !== null;
    ?>
    <div class="card">
        <div class="card-header">
            <h4><?= $isEdit ? 'Редактирование платежа' : 'Создание нового платежа' ?></h4>
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
                                        data-price="<?= $student['course_price'] ?? 0 ?>"
                                        <?= $isEdit && $payment['student_id'] == $student['id'] ? 'selected' : '' ?>>
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
                                        data-price="<?= $course['price'] ?>"
                                        <?= $isEdit && $payment['course_id'] == $course['id'] ? 'selected' : '' ?>>
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
                                   value="<?= $isEdit ? $payment['amount'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Дата платежа *</label>
                            <input type="date" class="form-control" name="payment_date" 
                                   value="<?= $isEdit ? $payment['payment_date'] : date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Статус *</label>
                    <select class="form-control" name="status" required>
                        <option value="pending" <?= $isEdit && $payment['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                        <option value="completed" <?= $isEdit && $payment['status'] == 'completed' ? 'selected' : '' ?>>Оплачено</option>
                        <option value="cancelled" <?= $isEdit && $payment['status'] == 'cancelled' ? 'selected' : '' ?>>Отменено</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Сохранить изменения' : 'Создать платеж' ?>
                </button>
                <a href="index.php?table=payments" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
    <?php
}

function showCourseList($courses) {
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Курсы</h2>
        <a href="index.php?table=courses&action=create" class="btn btn-success">
            + Добавить курс
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Изображение</th>
                    <th>Преподаватель</th>
                    <th>Тип преподавателя</th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= $course['id'] ?></td>
                    <td><?= htmlspecialchars($course['title']) ?></td>
                    <td>
                        <?php 
                        $imageUrl = !empty($course['image_url']) ? htmlspecialchars($course['image_url']) : '';
                        $imageAlt = !empty($course['image_alt']) ? htmlspecialchars($course['image_alt']) : 'Изображение курса';
                        ?>
                        <img src="<?= $imageUrl ? $imageUrl : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSIgZmlsbD0iIzk5OSI+Tm8gaW1hZ2U8L3RleHQ+PC9zdmc+' ?>" 
                             alt="<?= $imageAlt ?>" 
                             style="width: 50px; height: 50px; object-fit: cover;"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSIgZmlsbD0iIzk5OSI+RXJyb3I8L3RleHQ+PC9zdmc+'">
                    </td>
                    <td><?= htmlspecialchars($course['teacher_name'] ?? 'Не указан') ?></td>
                    <td><?= htmlspecialchars($course['teacher_type'] ?? 'Не указан') ?></td>
                    <td><?= number_format($course['price'], 2, '.', ' ') ?> ₽</td>
                    <td>
                        <span class="badge badge-<?= $course['is_active'] ? 'success' : 'secondary' ?>">
                            <?= $course['is_active'] ? 'Активен' : 'Не активен' ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?table=courses&action=edit&id=<?= $course['id'] ?>" 
                           class="btn btn-sm btn-warning" title="Редактировать">
                            ✏️
                        </a>
                        <a href="index.php?table=courses&action=delete&id=<?= $course['id'] ?>" 
                           class="btn btn-sm btn-danger" title="Удалить"
                           onclick="return confirmDelete()">
                            🗑️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function showTeacherList($teachers) {
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Преподаватели</h2>
        <a href="index.php?table=teachers&action=create" class="btn btn-success">
            + Добавить преподавателя
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Email</th>
                    <th>Тип преподавателя</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?= $teacher['id'] ?></td>
                    <td><?= htmlspecialchars($teacher['first_name']) ?></td>
                    <td><?= htmlspecialchars($teacher['last_name']) ?></td>
                    <td><?= htmlspecialchars($teacher['email'] ?? 'Не указан') ?></td>
                    <td><?= htmlspecialchars($teacher['type_name'] ?? 'Не указан') ?></td>
                    <td>
                        <a href="index.php?table=teachers&action=edit&id=<?= $teacher['id'] ?>" 
                           class="btn btn-sm btn-warning" title="Редактировать">
                            ✏️
                        </a>
                        <a href="index.php?table=teachers&action=delete&id=<?= $teacher['id'] ?>" 
                           class="btn btn-sm btn-danger" title="Удалить"
                           onclick="return confirmDelete()">
                            🗑️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function showTeacherTypeList($types) {
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Типы преподавателей</h2>
        <a href="index.php?table=teacher_types&action=create" class="btn btn-success">
            + Добавить тип
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Название типа</th>
                    <th>Описание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                <tr>
                    <td><?= $type['id'] ?></td>
                    <td><?= htmlspecialchars($type['type_name']) ?></td>
                    <td><?= htmlspecialchars($type['description'] ?? 'Нет описания') ?></td>
                    <td>
                        <a href="index.php?table=teacher_types&action=edit&id=<?= $type['id'] ?>" 
                           class="btn btn-sm btn-warning" title="Редактировать">
                            ✏️
                        </a>
                        <a href="index.php?table=teacher_types&action=delete&id=<?= $type['id'] ?>" 
                           class="btn btn-sm btn-danger" title="Удалить"
                           onclick="return confirmDelete()">
                            🗑️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function showStudentList($students) {
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Студенты</h2>
        <a href="index.php?table=students&action=create" class="btn btn-success">
            + Добавить студента
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['id'] ?></td>
                    <td><?= htmlspecialchars($student['first_name']) ?></td>
                    <td><?= htmlspecialchars($student['last_name']) ?></td>
                    <td><?= htmlspecialchars($student['email'] ?? 'Не указан') ?></td>
                    <td><?= htmlspecialchars($student['phone'] ?? 'Не указан') ?></td>
                    <td><?= date('d.m.Y', strtotime($student['created_at'])) ?></td>
                    <td>
                        <a href="index.php?table=students&action=edit&id=<?= $student['id'] ?>" 
                           class="btn btn-sm btn-warning" title="Редактировать">
                            ✏️
                        </a>
                        <a href="index.php?table=students&action=delete&id=<?= $student['id'] ?>" 
                           class="btn btn-sm btn-danger" title="Удалить"
                           onclick="return confirmDelete()">
                            🗑️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function showPaymentList($payments) {
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Платежи</h2>
        <a href="index.php?table=payments&action=create" class="btn btn-success">
            + Добавить платеж
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Студент</th>
                    <th>Курс</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= $payment['id'] ?></td>
                    <td><?= htmlspecialchars($payment['student_name']) ?></td>
                    <td><?= htmlspecialchars($payment['course_title']) ?></td>
                    <td><?= number_format($payment['amount'], 2, '.', ' ') ?> ₽</td>
                    <td><?= date('d.m.Y', strtotime($payment['payment_date'])) ?></td>
                    <td>
                        <?php 
                        $statusClass = [
                            'pending' => 'warning',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ];
                        ?>
                        <span class="badge badge-<?= $statusClass[$payment['status']] ?? 'secondary' ?>">
                            <?= $payment['status'] == 'pending' ? 'Ожидает' : 
                               ($payment['status'] == 'completed' ? 'Оплачено' : 'Отменено') ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?table=payments&action=edit&id=<?= $payment['id'] ?>" 
                           class="btn btn-sm btn-warning" title="Редактировать">
                            ✏️
                        </a>
                        <a href="index.php?table=payments&action=delete&id=<?= $payment['id'] ?>" 
                           class="btn btn-sm btn-danger" title="Удалить"
                           onclick="return confirmDelete()">
                            🗑️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>