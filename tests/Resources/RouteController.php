<?php

namespace Tests\Resources;

use Illuminate\Routing\Controller;
use JumpGate\ViewResolution\Traits\AutoResolvesViews;

class RouteController extends Controller
{
    use AutoResolvesViews;

    public function autoRoute()
    {
        return $this->view();
    }

    public function manualRoute()
    {
        return $this->view('test');
    }

    public function manualLayoutRoute()
    {
        return $this->view('test', 'testLayout');
    }
}
