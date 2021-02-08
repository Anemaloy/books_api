## Books and authors REST API (Test work)
**Описание:** Используя PHP 7 и фреймворк Symfony 5 (последние версии PHP 7.4 и Symfony 5.2), а также Doctrine ORM и с использованием Docker контейнера, написать REST API для создания и получения книг и авторов из базы данных в формате JSON. 

* Написать миграцию, засеивающую тестовые таблицы ~10 000 книгами и ~10 000 авторами
* Реализовать запросы на создание книги и автора в базе /book/create, /author/create
* Реализовать запрос на получение списка книг с автором из базы /book/search c поиском по названию книги
* Написать Unit-тест
* Используя возможности Symfony по локализации контента, сделать мультиязычный метод получения информации о книге /{lang}/book/{Id}, где {lang} = en|ru и {Id} = Id книги. Формат ответа: {Id: 1, 'Name':'War and Peace|Война и мир'} - поле Name выводить на языке локализации запроса.

### Инструкция по развертке

```php
// Запускаем докер сборку
docker-compose up --build -d
// Переходим в контейнер
docker exec -it symfony-app-cli bash
// Устанавливаем зависимости symfony, выполняем миграции и вставку данных
composer update
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixture:load
```
## API routes

**Добавление**
```php
POST http://localhost:8081/book/create
// Формат данных
{
    "name_en": "Book from API",
    "name_ru": "Книга из API"
}

POST http://localhost:8081/author/create
// Формат данных
{
    "name": "Имя автора из API"
}
```

**Список книг.** Для вывода необходимого количества данных используется GET параметр count(по умолчанию 10 книг).
```php
GET http://localhost:8081/book[?count=X]

```

**Поиск книги.**
```php
GET http://localhost:8081/book/search?query=Book_name

```