# Frontier Router API SDK

PHP SDK for the Frontier Router

Query your router for information pertaining to your network, things like:

```php
$router = new \Frontier\Router(['ip' => '192.168.1.1','password' => 'admin']);
$ipAddress = $router->networkInfo('ipAddress');
var_dump($ipAddress); // 47.123.123.222
```

## Requirements

- Composer requirements:
    - php 7.3 and up
- Confirmed to work with the following routers:
    - FiOS-G1100 (UI: v1.0.210, Firmware: 01.04.00.10-FTR)

# Installation

```
composer require gjrdiesel/frontier-router
```

Then in php:

```php
require 'path/to/autoload.php';

use Frontier\Router;

$router = new Router(['ip'=>'','password'=>'']);
$devices = $router->devices();
```

Or with Laravel:

Fill out your .env

```dotenv
FRONTIER_ROUTER_IP=10.0.1.1
FRONTIER_ROUTER_PASSWORD=admin
```

Then use the facade:

```php
$ip = \Frontier\Router::networkInfo('ipAddress');
echo $ip;

$devices = \Frontier\Router::devices();
foreach($devices as $device){
    echo $device->ipAddress;
}
```

## Contributing

Contributions are welcome and will be fully credited. Please see CONTRIBUTING and CONDUCT for details.

## Security

If you discover any security related issues, please email knoxxbox@gmail.com instead of using the issue tracker.

## Changelog

Please see CHANGELOG for more information on what has changed recently.

## Credits

- Justin Reasoner
- All Contributors

## License

The MIT License (MIT). Please see LICENSE for more information.
