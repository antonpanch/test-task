# Тестове завдання

### Вибір архітектури та дизайну
Дане тестове завдання було реалізовано з прагненням максимально відділити бізнес логіку від деталей реалізації 
(яке саме сховище даних буде вибрано, який фреймворк використано тощо).
Тому за основу було взято розробку згідно **Domain Driven Design**.
Таким чином, вихідний код програми складається з 3 рівнів:

1) **Domain Layer** - тут описані бізнес обʼєкти, їх обмеження та правила валідації. Даний рівень 
не залежить від будь-якого пакету, фреймворку чи сховища.
Domain Layer не використовує ніяких класів з Application Layer чи Infrastructure Layer. 
Тобто його код не залежить від інших рівнів.
Валідація обʼєктів відбувається за допомогою ValueObjects. Перевагою такого підходу(валідація у конструкторі) є те, що 
обʼєкт створюється вже провалідованим і тому він повинен бути валідним у будь-який момент.   
<br/>

2) **Application Layer** - тут описані бізнес правила взаємодії обʼєктів та сервісів між собою для реалізації визначеної бізнес 
логіки. Application Layer використовує код з Domain Layer, але не використовує код з Infrastructure Layer. Тобто код 
Application Layer також не залежить від будь-яких пакетів, фреймворків, сховища.   
<br/>

3) **Infrastructure layer** - тут описані конкретні реалізації (наприклад, репозиторій на Mysql), що необхідні для роботи 
з бізнес логікою та для збереження даних. Саме на цьому рівні відбувається робота з фреймворком Symfony.
<br/>

### Тести
Окремо від вихідного коду знаходиться папка з тестами (функціональні, інтеграційні, модульні).
Найбільшу увагу було приділено функціональним тестам, оскільки вони тестують повну роботу системи від запиту до 
API і до відповіді користувачу.  
Окремо було написано інтеграційні тести для тестування репозиторію для роботи з MySql.  
Юніт тести були написані для тестування правил валідації. 

## Як встановити та запустити проект 
Для запуску проекту згідно опису нижче необхідно, щоб у Вас був встановлений **docker compose**.  
Для встановлення та запуску проекту необхідно: 
#### 1) склонувати проект з Github
```shell
git clone https://github.com/antonpanch/test-task.git
cd test-task
```
#### 2) встановити необхідні залежності за допомогою composer  
```shell
docker compose run -it --rm composer composer install 
```
#### 3) Відкрити порти для доступу до БД в файлі compose.yml при необхідності
Якщо Ви хочете мати доступ до БД напряму з якогось клієнта, Вам необхідно відкрити відповідні порти у файлі compose.yml.  
По замовченню порти закоментовані, щоб не створювати конфліктів.  

#### 4) після встановлення пакетів необхідно запустити проект:
```shell
docker compose up -d
```
#### 5) Необхідно створити таблиці в БД. Це можна зробити:
- *~~запустивши міграції~~* запустивши команду
```shell
docker compose exec -it application bin/console db:create-tables 
```
- або вручну скопіювавши sql запити з файлу `application/dump/create-tables-with-roles.sql` 
та виконавши дані sql запити в БД
#### 6) створити тестові дані. Це можна зробити:
- *~~запустивши фікстури~~* запустивши команду
```shell
docker compose exec -it application bin/console db:create-test-data
```
- або виконавши вручну запити з файлу `application/dump/create_test_users_and_tokens.sql` в БД  

Буде створено 15 користувачів та по одному токен для кожного користувача:

| Id  | Логін  | Пароль   | Роль | Токен            |  
|-----|--------|----------|------|------------------|
| 1   | root1  | rootpass | root | token_for_root1  |
| 2   | root2  | rootpass | root | token_for_root2  |
| 3   | root3  | rootpass | root | token_for_root3  |
| 4   | root4  | rootpass | root | token_for_root4  |
| 5   | root5  | rootpass | root | token_for_root5  |
| 6   | user1  | userpass | user | token_for_user1  |
| 7   | user2  | userpass | user | token_for_user2  |
| 8   | user3  | userpass | user | token_for_user3  |
| 9   | user4  | userpass | user | token_for_user4  |
| 10  | user5  | userpass | user | token_for_user5  |
| 11  | user6  | userpass | user | token_for_user6  |
| 12  | user7  | userpass | user | token_for_user7  |
| 13  | user8  | userpass | user | token_for_user8  |
| 14  | user9  | userpass | user | token_for_user9  |
| 15  | user10 | userpass | user | token_for_user10 |


Ці дані можна використовувати для того, щоб робити запити до API.  
Можна робити наступні запити:


| Метод | Ендпоінт                                             | Обовʼязкові поля при запиті | Обов'язкові заголовки                                            | 
|-------|------------------------------------------------------|-----------------------------|------------------------------------------------------------------|
| POST  | *~~/api/v1/auth/token~~* <br/>/v1/api/auth/token     | login, pass                 | Content-Type: application/json  | 
| GET   | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | -                           | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| POST | *~~/api/v1/users~~*  <br/>/v1/api/users              | login, pass, phone          | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| DELETE | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | -                           | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| PUT | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | login, pass, phone          | Content-Type: application/json<br/>Authorization: Bearer {token} | 

Сервер запускається за адресою **http://localhost:8800**  
Приклад запиту: **GET http://localhost:8800/v1/api/users?id=1**  

## Тести
Щоб перевірити, що проект працює коректно, можна виконати тести.  
Запустити тести можна за допомогою наступної команди:
```shell
docker compose exec -it application php vendor/bin/phpunit
```
Або можна запускати окремо кожен вид(suite) тестів (функціональні, інтеграційні, юніт):
```shell
docker compose exec -it application php vendor/bin/phpunit --testsuite Unit
docker compose exec -it application php vendor/bin/phpunit --testsuite Integration
docker compose exec -it application php vendor/bin/phpunit --testsuite Functional
```

## Приклади запитів
Для роботи з API можна використовувати різні інструменти, наприклад curl або Postman.  
Нижче буде наведено приклади запитів у форматі curl команди, які можна відправляти на API.    
При необхідності такий запит можні імпортувати в Postman.  

### Створити нового користувача (POST запит)
```shell
curl http://localhost:8800/v1/api/users --request POST  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"login-0", "pass":"pass-0", "phone":"phone-0"}'
```
### Отримати інформацію про користувача з id = 1 (GET запит)
```shell
curl http://localhost:8800/v1/api/users?id=1 --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```
### Відредагувати користувача з id=5 (PUT запит)
```shell
curl http://localhost:8800/v1/api/users?id=5 --request PUT  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"5-login", "pass":"5-pass", "phone":"5-phone"}'
```
### Видалити користувача з id = 12 (DELETE запит)
```shell
curl http://localhost:8800/v1/api/users?id=12 --request DELETE  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```