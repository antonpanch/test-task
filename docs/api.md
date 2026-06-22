## Побудова API
В умові задачі написано, що необхідно побудувати REST API.
Крім того описані деякі обмеження щодо **одного ендпоінту**, **одного урл** та **обовʼязкових атрибутів**.  
Для мене вимога щодо одного url виглядає заплутаною, оскільки не дуже зрозуміло, що дозволено використовувати в рамках 
даного обмеження:
- чи можна використовувати для побудови REST API `/api/v1/users` та `/api/v1/users/{id}`? Це ж не один урл?
- чи можна використовувати для побудови REST API `/api/v1/users` та `/api/v1/users?id={id}`? Це ж теж різні урли?
- робити всі запити до урл `/api/v1/users` без path параметрів чи query параметрів  (а передавати дані, наприклад, в body) 
не бачу сенсу розглядати хоча б з тієї причини, що у REST ресурсу повинен бути унікальний URI.

### Я реалізував наступний REST API згідно свого розуміння, як він має виглядати.

| Метод  | URL                | Описання                            |
|--------|--------------------|-------------------------------------|
| POST   | /api/v2/auth/token | Отримати токен                      |
| POST   | /api/v2/users      | Створити користувача                |
| GET    | /api/v2/users/{id} | Отримати інформацію про користувача |
| DELETE | /api/v2/users/{id} | Видалити користувача                |
| PUT    | /api/v2/users/{id} | Змінити інформацію про користувача  |
| GET    | /api/v2/users      | Отримати список користувачів        |

Сервер запускається за адресою **http://localhost:8800**  
Приклад запиту: **GET http://localhost:8800/api/v2/users/1**

Тут було додано можливість отримувати список користувачів, хоча в умовах завдання цього немає. Але якщо дотримуватися 
даного підходу, то ніщо не заважає реалізувати такий функціонал. 


#### Деталі реалізації:

| Метод  | Ендпоінт           | Обовʼязкові поля при запиті | Опціональні поля при запиті | Обов'язкові заголовки                                             | 
|--------|--------------------|-----------------------------|-----------------------------|-------------------------------------------------------------------|
| POST   | /api/v2/auth/token | login, pass                 | -                           | Content-Type: application/json                                    | 
| GET    | /api/v2/users/{id} | -                           | -                           | Content-Type: application/json<br/>Authorization: Bearer {token}  | 
| POST   | /api/v2/users      | login, pass, phone          | -                           | Content-Type: application/json<br/>Authorization: Bearer {token}  | 
| DELETE | /api/v2/users/{id} | -                           | -                           | Content-Type: application/json<br/>Authorization: Bearer {token}  | 
| PUT    | /api/v2/users/{id} | login, pass, phone          | -                           | Content-Type: application/json<br/>Authorization: Bearer {token}  | 
| GET    | /api/v2/users      | -                           | perPage, afterId            | Content-Type: application/json<br/>Authorization: Bearer {token}  |


При отриманні списка користувачів пагінація зроблена за допомогою id,
щоб уникнути проблем з швидкістю при реалізації на limit + offset
на великих обʼємах даних.  
Можна вказувати опціональний параметр `perPage`, щоб зазначити розмір сторінки.   
Щоб отримати наступну сторінку в списку, необхідно взяти останній (найбільший) id з відповіді і 
вказати його у параметрі `afterId` в наступному запиті.  
Якщо користувач з роллю `root` хоче отримати список користувачів - він отримає повний список.    
Якщо користувач з роллю `user` хоче отримати список користувачів - він отримає список, у якому будуть лише його дані.


## Приклади запитів
Для роботи з API можна використовувати різні інструменти, наприклад curl або Postman.  
Нижче буде наведено приклади запитів у форматі curl команди, які можна відправляти на API.    
При необхідності такий запит можні імпортувати в Postman.

### Приклади запитів:


#### Створити користувача
```shell
curl http://localhost:8800/api/v2/users --request POST  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"login-0", "pass":"pass-2", "phone":"phone-0"}'
```

#### Отримати інформацію про користувача:
```shell
curl http://localhost:8800/api/v2/users/1 --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```

#### Відредагувати користувача:
```shell
curl http://localhost:8800/api/v2/users/5 --request PUT  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"5-login", "pass":"5-pass", "phone":"5-phone"}'
```

#### Видалити користувача:
```shell
curl http://localhost:8800/api/v2/users/12 --request DELETE  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```

#### Отримати список користувачів:

#### Отримання першої сторінки:
```shell
curl "http://localhost:8800/api/v2/users?perPage=3" --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```

#### Отримання наступної сторінки:
```shell
curl "http://localhost:8800/api/v2/users?perPage=3&afterId=3" --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```

В умовах задачі вказано **про один ендпоінт** і **один url**.  
`/api/v2/users` і `/api/v2/users/{id}` це різні url, тому не знаю, 
наскільки такий дизайн задовільняє умовам задачі. 

### Альтернативна версія
Було створено ще одну альтернативну версію API, яка не використовує path параметрів. 
Можливо, дана версія краще задовільняє умовам задачі (наскільки я їх зрозумів).   
Я вважаю дану версію гіршою за попередню і такою, яку не варто використовувавти.  

#### Описання альтернативної версії API:


| Метод | Ендпоінт                                             | Обовʼязкові поля при запиті | Обов'язкові заголовки                                            | 
|-------|------------------------------------------------------|-----------------------------|------------------------------------------------------------------|
| POST  | *~~/api/v1/auth/token~~* <br/>/v1/api/auth/token     | login, pass                 | Content-Type: application/json  | 
| GET   | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | -                           | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| POST | *~~/api/v1/users~~*  <br/>/v1/api/users              | login, pass, phone          | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| DELETE | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | -                           | Content-Type: application/json<br/>Authorization: Bearer {token} | 
| PUT | *~~/api/v1/users/{id}~~*  <br/>/v1/api/users?id={id} | login, pass, phone          | Content-Type: application/json<br/>Authorization: Bearer {token} | 

Сервер запускається за адресою **http://localhost:8800**  
Приклад запиту: **GET http://localhost:8800/v1/api/users?id=1**

### Приклади запитів:

#### Створити нового користувача (POST запит)
```shell
curl http://localhost:8800/v1/api/users --request POST  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"login-0", "pass":"pass-0", "phone":"phone-0"}'
```

#### Отримати інформацію про користувача з id = 1 (GET запит)
```shell
curl http://localhost:8800/v1/api/users?id=1 --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```

#### Відредагувати користувача з id=5 (PUT запит)
```shell
curl http://localhost:8800/v1/api/users?id=5 --request PUT  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json" --data '{"login":"5-login", "pass":"5-pass", "phone":"5-phone"}'
```

#### Видалити користувача з id = 12 (DELETE запит)
```shell
curl http://localhost:8800/v1/api/users?id=12 --request DELETE  --header "Authorization: Bearer token_for_root1" --header "Content-Type: application/json"
```
