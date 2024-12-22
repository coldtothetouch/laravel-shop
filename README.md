# Laravel Shop

Интернет-магазин компьютерной техники

## Установка, настройка и использование

Для запуска приложения используется [Laravel Sail](https://laravel.com/docs/11.x/sail).

### Установка

Установка зависимостей

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

### Запуск

```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```
