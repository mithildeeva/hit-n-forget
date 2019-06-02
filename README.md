# hit-n-forget
Php composer package to make http non-blocking requests and forget (no waiting for response)

**Usage**:

```php
<?php

$url = 'http://google.com?apple=ball';
$method = 'post';

# data in array will be sent as json with the required header.
$data = [
        'will_it_work' => 'no',
    ];
$headers = [
        'auth_token' => 'lol',
    ];

$request = new \HitNForget\Requests\Request(
                $url,
                $method,
                $data,
                $headers
            );

\HitNForget\Client::call($request)
                    ->getRequestGenerated();
```

How it works:
By making a socket connections using [fsockopen()](https://www.php.net/manual/en/function.fsockopen.php) and then closing it using [fclose()](https://www.php.net/manual/en/function.fclose.php).