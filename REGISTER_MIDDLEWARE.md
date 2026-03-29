# Register the CheckRole middleware

In Laravel 11, open: bootstrap/app.php
Add this inside the withMiddleware() call:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```
