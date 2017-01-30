<?php

if (! function_exists('viewResolver')) {
    /**
     * Return the view builder instance.
     *
     * @return mixed
     */
    function viewResolver()
    {
        return app('viewResolver');
    }
}

if (! function_exists('checkDebugbar')) {
    /**
     * Return the ViewBuilder instance.
     *
     * @return mixed
     */
    function checkDebugbar()
    {
        return app()->environment('local') && app()->bound('debugbar');
    }
}
