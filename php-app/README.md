# Счетчик посещений на php
Для работы приложения требуется два контейнера
1) само приложение
2) mysql

### Сборка
```bash
docker build -t php-app .
```

### Запуск
Запуск в "ручном" режиме осуществляется командами
1) Подготовка
- создание сети
```bash
docker network create php-app-net
```
- создание контейнера с базой данных
```bash
docker run -d --name mysql-db --network php-app-net -e MYSQL_ROOT_PASSWORD=rootpassword -e MYSQL_DATABASE=appdb -e MYSQL_USER=appuser -e MYSQL_PASSWORD=password -v mysql-data:/var/lib/mysql mysql:8.0
```
2) Запуск контейнера
```bash
docker run -d --name php-app  --network php-app-net  -p 85:80  -e APP_NAME="PHP App with MySQL"  -e ENVIRONMENT="production"  -e VERSION="1.0.0"  -e DB_HOST="mysql-db"  -e DB_NAME="appdb"  -e DB_USER="appuser"  -e DB_PASS="password"  php-app
```
### Проверка
```bash
curl http://localhost:85
```
Ожидаемый результат
```json
{
    "app_name": "PHP App with MySQL",
    "environment": "production",
    "version": "1.0.0",
    "database": "connected",
    "visits_count": 4,
    "hostname": "63d4229585b2",
    "timestamp": "2025-08-20T03:43:58+00:00",
    "client_ip": "172.18.0.1"
}
```

## Требования к Jenkins пайплайну
Пайплайн должен включать
1) возможность при запуске выбрать окружение для деплоя. Варианты выбора
- dev
- stage
- prod
2) возможность при запуске задать переменные EXTERNAL_PORT и INTERNAL_PORT для определения портов приложения
3) сборку docker image и push его в dockerhub
4) stage с проверкой приложения командой `curl`