# Cove Example

The front controller is located in `public/index.php`.

All controllers are classes with `__invoke` defined as:

```php
public function __invoke(ServerRequestInterface $request): ResponseInterface;
```
