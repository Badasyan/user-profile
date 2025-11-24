 User Profile API

RESTful API для управления профилями пользователей, построенный на Laravel 10 с использованием Laravel Passport для аутентификации.

📋 Содержание

- [Особенности](особенности)
- [Технологический стек](технологический-стек)
- [Архитектура](архитектура)
- [Требования](требования)
- [Установка](установка)
- [Конфигурация](конфигурация)
- [API Endpoints](api-endpoints)
- [Тестирование](тестирование)
- [Структура проекта](структура-проекта)

 ✨ Особенности

- 🔐 Аутентификация через Laravel Passport (OAuth2)
- 👤 Регистрация и управление профилем пользователя
- 🔑 Восстановление пароля через email
- 📧 Уведомления по email
- 🏗️ Архитектура Service-Repository с DTOs
- ✅ Валидация через Form Requests
- 📦 API Resources для форматирования ответов
- 🧪 Unit и Feature тесты

 🛠 Технологический стек

- Framework: Laravel 10.x
- PHP: ^8.1
- Authentication: Laravel Passport 11.x
- Database: MySQL
- Testing: PHPUnit 10.x
- Code Style: Laravel Pint


 📦 Требования

- PHP >= 8.1
- Composer
- MySQL >= 5.7 или MariaDB >= 10.3
- Node.js & NPM (для фронтенд-ресурсов)

 🚀 Установка

 1. Клонирование репозитория

bash
git clone <repository-url>
cd user-profile


 2. Установка зависимостей

bash
composer install
npm install


 3. Настройка окружения

bash
cp .env.example .env
php artisan key:generate


 4. Настройка базы данных

Отредактируйте файл `.env`:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_profile
DB_USERNAME=root
DB_PASSWORD=your_password


 5. Миграции и Passport

bash
php artisan migrate
php artisan passport:install


 6. Настройка почты (опционально)

Для разработки можно использовать `log` драйвер:

env
MAIL_MAILER=log


Для продакшена настройте SMTP:

env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"


 7. Запуск сервера

bash
php artisan serve


API будет доступен по адресу: `http://localhost:8000`

 ⚙️ Конфигурация

 Passport Configuration

После установки Passport, убедитесь что в `config/auth.php` настроен guard:

php
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],


 Queue Configuration

Для обработки email уведомлений в фоне:

bash
 В .env
QUEUE_CONNECTION=database

 Создать таблицы для очередей
php artisan queue:table
php artisan migrate

 Запустить worker
php artisan queue:work


 📡 API Endpoints

 Публичные endpoints

 Регистрация
http
POST /api/register
Content-Type: application/json

{
  "first_name": "Иван",
  "last_name": "Иванов",
  "email": "ivan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}


Успешный ответ:
json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "message": "User registered successfully"
}


 Вход
http
POST /api/login
Content-Type: application/json

{
  "email": "ivan@example.com",
  "password": "password123"
}


Успешный ответ:
json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "message": "Login successful"
}


 Забыли пароль
http
POST /api/forgot-password
Content-Type: application/json

{
  "email": "ivan@example.com"
}


Успешный ответ:
json
{
  "message": "Password reset link sent to your email"
}


 Сброс пароля
http
POST /api/reset-password
Content-Type: application/json

{
  "email": "ivan@example.com",
  "token": "reset_token_from_email",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}


Успешный ответ:
json
{
  "message": "Password has been reset successfully"
}


 Защищенные endpoints (требуют аутентификации)

Для всех защищенных endpoints добавьте заголовок:
http
Authorization: Bearer {your_access_token}


 Получить профиль пользователя
http
GET /api/user-profile
Authorization: Bearer {token}


Успешный ответ:
json
{
  "data": {
    "id": 1,
    "first_name": "Иван",
    "last_name": "Иванов",
    "email": "ivan@example.com",
    "created_at": "2024-11-24T08:00:00.000000Z"
  }
}


 Обновить профиль
http
POST /api/profile/update
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "Петр",
  "last_name": "Петров",
  "email": "petr@example.com"
}


Успешный ответ:
json
{
  "message": "Profile updated successfully"
}


 Выход
http
POST /api/logout
Authorization: Bearer {token}


Успешный ответ:
json
{
  "message": "Successfully logged out"
}


 Формат ошибок

При ошибках API возвращает:

json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}


 🧪 Тестирование

 Запуск всех тестов

bash
php artisan test


 Запуск конкретного теста

bash
php artisan test --filter=AuthTest


 Запуск с покрытием кода

bash
php artisan test --coverage


 📁 Структура проекта


app/
├── DTOs/                            Data Transfer Objects
│   ├── Auth/
│   │   ├── LoginDTO.php
│   │   ├── RegisterDTO.php
│   │   └── ResetPasswordDTO.php
│   └── User/
│       └── UpdateProfileDTO.php
├── Exceptions/                      Обработчики исключений
│   └── Handler.php
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       └── ProfileController.php
│   ├── Requests/                    Form Request валидация
│   │   ├── ForgotPasswordRequest.php
│   │   ├── LoginRequest.php
│   │   ├── RegisterRequest.php
│   │   ├── ResetPasswordRequest.php
│   │   └── UpdateProfileRequest.php
│   └── Resources/                   API Resources
│       └── UserResource.php
├── Models/
│   └── User.php
├── Notifications/
│   └── ResetPasswordNotification.php
├── Repositories/
│   ├── Contracts/
│   │   └── UserRepositoryInterface.php
│   └── UserRepository.php
└── Services/
    ├── AuthService.php
    ├── PasswordResetService.php
    └── UserService.php

database/
├── factories/
│   └── UserFactory.php
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2014_10_12_100000_create_password_reset_tokens_table.php
│   └── [passport migrations]
└── seeders/
    └── DatabaseSeeder.php

routes/
├── api.php                          API маршруты
└── web.php                          Web маршруты

tests/
├── Feature/                         Feature тесты
└── Unit/                           Unit тесты


 🔒 Безопасность

- Все пароли хешируются с использованием bcrypt
- OAuth2 токены через Laravel Passport
- CSRF защита для web маршрутов
- Валидация всех входящих данных
- Rate limiting на API endpoints
