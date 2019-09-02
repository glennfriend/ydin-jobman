## First Install

### install redis
- 請自行安裝

### install from composer
```
composer require "ydin/jobman:0.1.0"
```

### install for command line
```
composer config "repositories.jobman" path "packages/System/Jobman"
composer require "ydin/jobman:dev-master"
```

### vi .env
```
QUEUE_DRIVER=redis
REDIS_HOST=______redis.______.______.______.______.cache.amazonaws.com
```

### publish
```
php artisan vendor:publish --tag="jobman"
php artisan vendor:publish --tag="horizon-assets" --tag="horizon-provider" 
``` 

### vi app/Http/Middleware/VerifyCsrfToken.php
```
    # 這項是選擇性修改
    protected $except = [
        'horizon/*'
    ];
```

### vi config/database.php
```
    'redis' => [

         'client' => 'predis',
 
         'default' => [
             'host' => env('REDIS_HOST', '127.0.0.1'),
             'password' => env('REDIS_PASSWORD', null),
             'port' => env('REDIS_PORT', 6379),
             'database' => 0,
         ],
 
+        'horizon' => [
+            'host'      => env('REDIS_HOST', '127.0.0.1'),
+            'password'  => env('REDIS_PASSWORD', null),
+            'port'      => env('REDIS_PORT', 6379),
+            'database'  => 0,
+        ],
+
     ],
 
 ];
```

### horizon problem
- composer install error message
    - "laravel/horizon v3.2.8 requires ext-pcntl * -> the requested PHP extension pcntl is missing from your system"
- 如果在 circleci 發生以上的問題, 可以編輯 composer.json 如下
```
{
    "config": {
        "platform": {
            "ext-pcntl": "7.3.4",
            "ext-posix": "7.3.4"
        }
    },
}
```
- 版本可以用指令查看
    - composer show --platform

### queue 存在哪裡?
- 資料存在 redis
- "不需要" 建立 laravel "jobs" table
- 要建立 laravel "failed_jobs" table

