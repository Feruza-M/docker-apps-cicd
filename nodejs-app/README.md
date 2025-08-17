# Аутентификационный сервис с MongoDB
Для работы приложения требуется два контейнера
1) само приложение
2) mongodb

Запуск в "ручном" режиме осуществляется командами
```bash
$ docker network create auth-net

$ docker run -d --name mongo-db --network auth-net -e MONGO_INITDB_ROOT_USERNAME=admin -e MONGO_INITDB_ROOT_PASSWORD=secret -v mongo-data:/data/db mongo:6

$ docker build -t auth-app .

$ docker run -d --name auth-app \
  --network auth-net \
  -p 3000:3000 \
  -e PORT=3000 \
  -e MONGO_HOST=mongo-db \
  -e MONGO_PORT=27017 \
  -e MONGO_DB=auth \
  -e MONGO_USER=admin \
  -e MONGO_PASS=secret \
  -e NODE_ENV=production \
  auth-app
```

Проверка осуществляется командой
```bash
curl http://localhost:3000/status
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