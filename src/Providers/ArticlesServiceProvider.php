<?php

namespace Mixdinternet\Articles\Providers;

use Illuminate\Support\ServiceProvider;
use Mixdinternet\Articles\Article;

use Menu;

class ArticlesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setMenu();

        $this->setRoutes();

        $this->setRouterBind();

        $this->loadViews();

        $this->publish();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/maudit.php', 'maudit.alias');
    }

    protected function setMenu()
    {
        Menu::modify('adminlte-sidebar', function ($menu) {
            $menu->route('admin.articles.index', config('marticles.name', 'Artigos'), [], config('marticles.order', 20)
                , ['icon' => config('marticles.icon', 'fa fa-file-text'), 'active' => function () {
                    return checkActive(route('admin.articles.index'));
                }])->hideWhen(function () {
                return checkRule('admin.articles.index');
            });
        });

        Menu::modify('adminlte-permissions', function ($menu) {
            $menu->url('admin.articles', config('marticles.name', 'Artigos'), config('marticles.order', 20));
        });
    }

    protected function setRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $this->app->router->group(['namespace' => 'Mixdinternet\Articles\Http\Controllers'],
                function () {
                    require __DIR__ . '/../Http/routes.php';
                });
        }
    }

    protected function setRouterBind()
    {
        $this->app->router->bind('articles', function ($id) {
            $article = Article::find($id);
            if (!$article) {
                abort(404);
            }

            return $article;
        });
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mixdinternet/articles');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/mixdinternet/articles'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/marticles.php' => base_path('config/marticles.php'),
        ], 'config');
    }
}
