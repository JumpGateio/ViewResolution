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

# Getting Started
To start using this package all you have to do is add the `JumpGate\ViewResolution\Traits\AutoResolvesViews` trait to your 
controller and use it.

```
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JumpGate\ViewResolution\Traits\AutoResolvesViews;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AutoResolvesViews;
}
```

Next, in any method on the controller call `$this->view()` to start the auto resolver.

```
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JumpGate\ViewResolution\Traits\AutoResolvesViews;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AutoResolvesViews;

    public function welcome()
    {
        $this->view();
    }
}
```

In the above case, the package will look for a view in the root view folder named `welcome.blade.php`.

> To remove some of the magic of this package, you can always call `return $this->view();`

# Layouts
This package uses layouts to handle the view resolution.  A layout must always be used to add a view into.  The package 
expects two layouts to exist (they can be the same file).  A default layout used for standard requests and an ajax layout 
used for ajax requests.  There are a few ways the package gives you to determine which layout to use.

1. In `config/jumpgate/view-resolution.php` there is a `load_layout` option.  If this is true the default layout included 
with the package will be used for both default and ajax requests..
    - Optionally, you can publish the config and change the values in `layout_options` and the package will use views instead.
1. When calling `$this->view();` in your controller, you can specify a layout as the second parameter.  The layout provided 
will be used in both default and ajax calls.
1. On any controller you can define a set of layout options to tell the package what to use.  An example is given below.

```
    protected $layoutOptions = [
        'default' => 'layouts.default',
        'ajax'    => 'layouts.ajax',
    ];
```
