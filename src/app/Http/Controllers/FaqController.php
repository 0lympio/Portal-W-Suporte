<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    /**
     * @return View|Factory
     */
    public function index(): Factory|View

    {

        $faqs = Category::where('slug', 'fique-on')->with('children')->with('posts')->first()->posts->where('status_id', 1)->sortByDesc('created_at');

        return view('faqs.index', ['faqs' => $faqs]);
    }
}
