# Микросервис на golang
Работает в standalone режиме.

### Сборка
```bash
docker build -t go-app .
```

### Запуск
Пример команды
```
docker run -d -p 8080:8080 \
  -e APP_NAME="My Go App" \
  -e ENVIRONMENT="production" \
  -e VERSION="2.0.0" \
  -e PORT="8080" \
  --name go-app-container \
  go-app
```

### Проверка
```bash
curl http://localhost:8080/
curl http://localhost:8080/health
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