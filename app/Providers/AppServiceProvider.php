<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
//use App\Repositories\CategoryRepository;
use App\Policies\ { AlbumPolicy, ImagePolicy, UserPolicy };
use App\Models\ { Image, User, Album };
use App\Repositories\ { CategoryRepository, AlbumRepository };

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->admin;
        });
       
        if (request ()->server ("SCRIPT_NAME") !== 'artisan') {
            view ()->share ('categories', resolve(CategoryRepository::class)->getAll());
            view ()->composer('layouts.app', function ($view)
            {
                if(auth()->check()) {
                    $albums = resolve (AlbumRepository::class)->getByUser(auth()->id());
                    if($albums->isNotEmpty()) {
                        $view->with('albums', $albums);
                    }
                }
            });

            Blade::if ('maintenance', function () {
                return auth ()->check () && auth ()->user ()->admin && app()->isDownForMaintenance();
            });
        }
         Blade::if ('adminOrOwner', function ($id) {
            return auth ()->check () && (auth ()->id () === $id || auth ()->user ()->admin);
         });

    }
    protected $policies = [
        Image::class => ImagePolicy::class,
        User::class => UserPolicy::class,
        Album::class => AlbumPolicy::class,
    ];
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    
         
}
