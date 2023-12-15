<?php
namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Blog\Listeners\SiteListenners;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        'detailProduct.customInput' => [
            'Modules\Blog\Listeners\SiteListenners@test'
        ]
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('test_view_render', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate(
                'blog::test.test'
            );
        });
    }
}