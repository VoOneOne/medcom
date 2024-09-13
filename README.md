# Paste
Для запуска:
1) Установите все необходимые зависимости через composer install --no-dev --optimize-autoloader
2) Очистить кэш, если нужно APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
3) Выполните миграцию коммандой bin/console doctrine:migration:migrate
3) Запустите сервер коммандой  symfony server:start