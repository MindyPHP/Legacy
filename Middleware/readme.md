# Middleware

Example configuration:

```php
...
    'middleware' => [
        ['class' => '\Mindy\Middleware\Middleware\AjaxRedirectMiddleware'],
        ['class' => '\Mindy\Middleware\Middleware\HtmlMinMiddleware']
    ]
...
```