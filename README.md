# php-shop-logistics.ru-api

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

[![Coverage Status][ico-coverage]][link-coverage]
[![Sensiolabs_Medal][ico-code-quality-sensio]][link-code-quality-sensio]
[![Quality Score][ico-code-quality-scrutinizer]][link-code-quality-scrutinizer]

##php-shop-logistics.ru-api
PHP oop wrapper for shop-logistics.ru remote functions 

## Install

Via Composer

``` bash
$ composer require gennadyx/php-shop-logistics.ru-api
```

## Usage

``` php
use Gennadyx\ShopLogisticsRu\ApiClientBuilder;
use Gennadyx\ShopLogisticsRu\Environment;
use Gennadyx\ShopLogisticsRu\Api\Dictionary;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;

$client = ApiClientBuilder::create()
    ->withRequestFactory(new DiactorosMessageFactory())
    ->withStreamFactory(new DiactorosStreamFactory())
    ->withEncoder(function ($data) {
        $xml = '';
        //your logic here
        return $xml;
    })
    ->withEnvironment(Environment::PROD())
    ->withKey('your_key')
    ->build();

//or just build with default parameters
$client = ApiClientBuilder::create()->build();

/** @var Dictionary $dictionary */
$dictionary = $client->api('dictionary');

//call remote function
$cities = $dictionary->getCities();
//or
$states = $client->dictionary->getStates();//array

//if any error (http exception or other)
$metro = $client->dictionary->getMetro();
//$metro instance of \Gennadyx\ShopLogisticsRu\Response\Error with error code
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email dev@gennadyx.tech instead of using the issue tracker.

## Credits

- [Gennady Knyazkin][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/gennadyx/php-shop-logistics.ru-api.svg?style=flat
[ico-license]: https://img.shields.io/packagist/l/gennadyx/php-shop-logistics.ru-api.svg?style=flat
[ico-travis]: https://img.shields.io/travis/gennadyx/php-shop-logistics.ru-api/master.svg?style=flat
[ico-coverage]: https://img.shields.io/scrutinizer/coverage/g/gennadyx/php-shop-logistics.ru-api.svg?style=flat
[ico-code-quality-scrutinizer]: https://img.shields.io/scrutinizer/g/gennadyx/php-shop-logistics.ru-api.svg?style=flat
[ico-code-quality-sensio]: https://insight.sensiolabs.com/projects/6ede6306-c632-40ec-bffb-8e533a81ca55/mini.png
[ico-downloads]: https://img.shields.io/packagist/dt/gennadyx/php-shop-logistics.ru-api.svg?style=flat

[link-packagist]: https://packagist.org/packages/gennadyx/php-shop-logistics.ru-api
[link-travis]: https://travis-ci.org/gennadyx/php-shop-logistics.ru-api
[link-coverage]: https://scrutinizer-ci.com/g/gennadyx/php-shop-logistics.ru-api/code-structure
[link-code-quality-scrutinizer]: https://scrutinizer-ci.com/g/gennadyx/php-shop-logistics.ru-api
[link-code-quality-sensio]: https://insight.sensiolabs.com/projects/6ede6306-c632-40ec-bffb-8e533a81ca55
[link-downloads]: https://packagist.org/packages/gennadyx/php-shop-logistics.ru-api
[link-author]: http://gennadyx.tech
[link-contributors]: https://github.com/php-shop-logistics.ru-api/contributors
