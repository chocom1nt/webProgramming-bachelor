<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление курсами</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>css/styles.css">
    <style>
        .navbar-nav .nav-item.active .nav-link {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            font-weight: bold;
        }
        .sort-link {
            color: white;
            text-decoration: none;
        }
        .sort-link:hover {
            color: #cce5ff;
            text-decoration: underline;
        }
        .sort-icon {
            margin-left: 5px;
            font-weight: bold;
        }
        .user-info {
            color: white;
            margin-right: 15px;
        }
    </style>
    <script>
        const IMAGE_NOT_FOUND = '<?= IMAGE_NOT_FOUND ?>';
    </script>   
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_PATH ?>">
                🎓 Управление курсами
                <?php if (isset($_SESSION['username'])): ?>
                    <small class="text-light ml-2" style="font-size: 0.8em;">
                        (<?= htmlspecialchars($_SESSION['username']) ?>)
                    </small>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= $current_table == 'courses' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= BASE_PATH ?>?table=courses">Курсы</a>
                    </li>
                    <li class="nav-item <?= $current_table == 'teachers' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= BASE_PATH ?>?table=teachers">Преподаватели</a>
                    </li>
                    <li class="nav-item <?= $current_table == 'teacher_types' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= BASE_PATH ?>?table=teacher_types">Типы преподавателей</a>
                    </li>
                    <li class="nav-item <?= $current_table == 'students' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= BASE_PATH ?>?table=students">Студенты</a>
                    </li>
                    <li class="nav-item <?= $current_table == 'payments' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= BASE_PATH ?>?table=payments">Платежи</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">