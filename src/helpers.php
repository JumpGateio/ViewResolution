<?php

if (! function_exists('viewBuilder')) {
    /**
     * Return the ViewBuilder instance.
     *
     * @return mixed
     */
    function viewBuilder()
    {
        return app('viewBuilder');
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
        return app()->bound('debugbar');
    }
}
