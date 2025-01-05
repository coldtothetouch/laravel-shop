# Laravel Shop

Интернет-магазин компьютерной техники

## Установка, запуск и использование

Для запуска приложения используется [Laravel Sail](https://laravel.com/docs/11.x/sail).

### Установка
Установите зависимости

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Скопируйте конфигурационный файл
```bash
cp .env.example .env
```

Запустите контейнеры и выполните команду установки проекта
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan project:install
```

Команда `project:install`
- Сгенерирует ключ приложения
- Создаст ссылку на директорию storage в папке public
- Выполнит миграции и сиды

### Запуск

```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```

### Использование

Данные для входа в аккаунт тестового пользователя

Эл. почта: `test@example.com`  
Пароль: `password`
