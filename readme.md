# Installation

# Set up
## Add Provider

`config/app.php`

```
'providers' => [
    ...
    JumpGate\ViewResolution\Providers\ViewServiceProvider::class,
    ...
]
```

# Debugbar

View resolution will collect details about how a view is resolved and add it to the debugbar package.  To use this do the 
following.

1. Install [debugbar](https://github.com/barryvdh/laravel-debugbar).
1. Publish the config file.
1. Edit the config file and add the following.

```
'collectors' => [
    ...
    'auto_views'      => true,  // Auto resolved view data
    ...
],
```

# Usage
