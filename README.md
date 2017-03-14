# Cove

A minimalist PHP framework built modern standards. Makes use of:

- [auryn](https://github.com/rdlowrey/auryn) dependency injection
- [fast-route](https://github.com/nikic/FastRoute) regular expression router
- [PSR-7](http://www.php-fig.org/psr/psr-7/) HTTP messages
- [PSR-17](https://github.com/php-fig/fig-standards/blob/master/proposed/http-factory/http-factory-meta.md) HTTP factories

## Installation

```bash
composer require cove/cove
```

You will also need to install:

- a [`psr/http-message-implementation`](https://packagist.org/providers/psr/http-message-implementation)
- a [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation)

If you are not sure what that means:

```bash
composer require zendframework/zend-diactoros
composer require http-interop/http-factory-diactoros
```

## Usage

See [example](example) directory.

## License

MIT License.
