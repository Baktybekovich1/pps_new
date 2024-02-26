## Запуск проекта

- Перейти в директорию docker
```bash 
cd docker
```
- Поднять контейнеры
```bash
docker compose up -d
```

## Перейти в внутрь php контейнера
- Посмотреть имя php контейнера
```bash
docker ps
```
- Выполнить команду с именем нужного контейнера
```bash
docker exec -it pps_kg_php bash
```