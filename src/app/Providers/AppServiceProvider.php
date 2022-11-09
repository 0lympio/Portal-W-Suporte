<?php

namespace App\Providers;

use App\Http\Controllers\PostController;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slideshow;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (!auth()->user()) {
                return;
            }

            $roleId = auth()->user()->roles->first()->id;

            $categories = Category::leftJoin('permissions as p', 'categories.slug', '=', 'p.name')
                ->leftJoin('role_has_permissions as rh', 'p.id', '=', 'rh.permission_id')
                ->where('rh.role_id', $roleId)
                ->whereNull('category_id')
                ->where('isMenu', 1)
                ->where('status', 1)
                ->select('categories.*')
                ->with('children')
                ->get();

            $view->with('menus', $categories);

            $popups = [];

            $postsPopup = Post::where('popup', 1)->where('published_at', '<=', now())->where('status_id', 1)->get();

            foreach ($postsPopup as $post) {
                $views = $post->views->where('user_id', auth()->user()->id);
                if (sizeof($views) <= 0 || $views->first()->read == 0) {
                    $popups[] = $post;
                }
            }

            $view->with('popups', $popups);
        });

        view()->composer('layouts.app', function ($view) {
            if (!auth()->user()) {
                return;
            }

            if (session()->has('popup')) {
                session(['popup' => false]);
            } else {
                session(['popup' => true]);
            }
        });

        view()->composer('components.uploads.modal', function ($view) {
            $files = Upload::orderBy('created_at', 'desc')->get();

            $view->with('files', $files);
        });

        view()->composer('home', function ($view) {
            $slides = DB::table('slideshow as s')
                ->select('s.*', 'u.path')
                ->join('uploads AS u', 's.upload_id', '=', 'u.id')
                ->where('s.deleted_at', null)
                ->where('status_id', 1)
                ->get();

            $view->with('slides', $slides);
        });
    }
}
