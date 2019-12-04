## Тестовое задание от *Softwise* (ужиматор ссылок)

Для запуска использовать
```bash
sudo ./start.sh
```

Морда сайта http://localhost:8085/welcome

Для генерации короткой ссылки необходимо отправить запрос на http://localhost:8085/api/short
```json
{
	"url": "https://google.com"
}
```

В ответ прилетает
```json
{
    "shortUrl": "http://localhost:8085/5HbQ48"
}
```

Посмотреть текущие ссылки можно через phpredisadmin http://localhost:8086
