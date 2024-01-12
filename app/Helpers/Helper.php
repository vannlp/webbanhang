<?php

use App\Helpers\CartHelper;
use App\Helpers\Core;
use App\Helpers\ViewRenderEventManager;
use App\Models\Cart;

if(!function_exists('core')) {
    function core(): Core {
        return new Core();
    }
}

if(!function_exists('view_render_event')) {
    function view_render_event($eventName, $params = null)
    {
        app()->singleton(ViewRenderEventManager::class);

        $viewEventManager = app()->make(ViewRenderEventManager::class);

        $viewEventManager->handleRenderEvent($eventName, $params);

        return $viewEventManager->render();
    }
}

if(!function_exists('cart')) {
    function cart()  {
        return app()->make(CartHelper::class);
    }
}