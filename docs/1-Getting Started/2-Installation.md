# Installation

- [Composer](#composer)
- [Providers](#providers)
- [Configs](#configs)

<a name="composer"></a>
## Composer

`composer require jumpgate/view-resolution`


<a name="providers"></a>
## Providers

Add the following to `configs/app.php`.

```
'providers' => [
    ...
    JumpGate\ViewResolution\Providers\ViewServiceProvider::class,
    ...
]
```

<a name="configs"></a>
## Configs

This package comes with a configuration file with some default values set.  If you wish to override these values or use 
the config to assigne views to actions simply publish the config file locally.

`php artisan vendor:publish --provider="JumpGate\ViewResolution\Providers\ViewServiceProvider"`

This will create a `view-resolution.php` file in your `config/jumpgate` folder.
