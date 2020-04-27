# Redis
Брокер для [php-redis-client](https://github.com/cheprasov/php-redis-client).


## Конфигурация
|Имя|     Тип|       Описание| Значение по-умолчанию|
|:-------:|:---:|:--------------:|:---------------------:|
|server|string| Адрес сервера |127.0.0.1|

## Пример использования:

```php
$redis = $app->factory('Redis');
$redis->set('foo', 'bar');
```
