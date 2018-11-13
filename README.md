# Redis
Брокер для [php-redis-client](https://github.com/cheprasov/php-redis-client).


## Конфигурация
|Имя|     Тип|       Описание| Значение по-умолчанию|
|:-------:|:---:|:--------------:|:---------------------:|
|server|string| Адрес сервера |127.0.0.1|
|port|int| Порт сервер |6379|

## Пример использования:

```php
$redis = $app->broker('Redis')->get();
$redis->set('foo', 'bar');
```
