# Usage

- [Set Up](#set-up)
- [How It Works](#how-it-works)
- [Layouts](#layouts)
- [Views](#views)

<a name="set-up"></a>
## Set Up

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

> To remove some of the magic of this package, you can always call `return $this->view();` so your controller always returns 
something.

<a name="how-it-works"></a>
## How It Works

When the controller loads, it calls `ViewResolution()` and begins the process of figuring out what layout and view to use.

<a name="layouts"></a>
## Layouts

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

<a name="views"></a>
## Views

The view is determined by a number of factors.  The controller, the action and the prefix.  Assuming that no prefixes are
used, the view will be controller.action.  (ex: `HomeController@index` would become `home.index`).

The methods strip `Controller` from the controller name and any HTTP verb from the beginning of the action name (get, post, 
put, etc.).

If you are using prefixes, the methods will concat all the prefixes into a single dot notation string.  It will then remove
the controller name from the prefix if it finds it.  (ie: If you have a prefix as `admin.user` and the controller is 
`UserController`, it will remove `user` from the prefix).
  
> The controller name is only removed if it was the last prefix.

If no view is found using the prefix, it will drop off one part of the dot notation at a time trying to find a valid view.  
So if your prefix was `admin.user.dashboard` and your view was in `views/admin/user` it would still find it since it would 
drop `dashboard` after it didn't find an existing view.

```
Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'home'], function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', [
                'as'   => 'admin.home.index',
                'uses' => 'HomeController@index'
            ]);
        });
    });
});
```

In the above example, the package would look for a view at `views/admin/home/dashboard/index.blade.php`.  When it doesn't find 
one there, it will look at `views/admin/home/index.blade.php`.  Since the controller name matched the last prefix, it skipped 
looking in `views/admin/home/home/index.blade.php`.

If no view was found there, it would have looked for one last view in `views/home/index.blade.php`.  If none is found 
there, it will throw a ViewNotFound exception.
