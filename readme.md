# Installation

# Set up
## Add Provider

`config/app.php`

```
'providers' => [
    ...
    JumpGate\ViewResolution\Providers\ViewServiceProvider::class,
    JumpGate\ViewResolution\Providers\AutoViewCollect::class,     // If Using debugbar
    ...
]
```
