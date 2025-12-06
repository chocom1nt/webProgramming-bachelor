# Web-Programming

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blueviolet)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-12%2B-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4.5-purple)
![jQuery](https://img.shields.io/badge/jQuery-3.5-blue)
![Docker](https://img.shields.io/badge/Docker-✓-blue)
![Docker Compose](https://img.shields.io/badge/Docker%20Compose-✓-blue)
![Architecture](https://img.shields.io/badge/Architecture-MVC-orange)


### Технологический стек
- Backend: PHP 7.4
- База данных: PostgreSQL 12
- Frontend: HTML5, CSS3, JavaScript (jQuery)
- Стили: Bootstrap 4.5
- Архитектура: MVC (Model-View-Controller)
- Контейнеризация: Docker & Docker Compose

### Запуск через Docker
1. Клонирование репозитория
```bash
git clone git@github.com:chocom1nt/webProgramming-bachelor.git
cd webProgramming-bachelor
```
2. Создание `.env` файла
```docker
DB_HOST=db
DB_NAME=courses_db
DB_USER=user
DB_PASSWORD=password
BASE_URL=http://localhost:8080
```
3. Запуск приложения
```bash
docker-compose up -d
```
4. Приложение будет работать по адресу: [localhost:8080](https://localhost:8080)