<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PostsExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PostsReportController extends Controller
{
    /**
     * Retorna para a view os dados dos últimos 7 dias
     *
     * @return View|Factory
     */
    public function index(): Factory|View
    {
        $categories = Category::whereNull('category_id')->where('slug', '<>', 'feed-de-noticias')->get();
        $data = $this->getData();

        return view('reports.posts', compact('data', 'categories'));
    }

    public function getData(Request $request = null): array
    {
        $posts = Post::all();

        $data = [];

        foreach ($posts as $post) {
            $published_at = (new Carbon($post->published_at))->format('d/m/Y H:i:s');
            $disabled_at = $post->disabled_at !== null
                ? (new Carbon($post->disabled_at))->format('d/m/Y H:i:s')
                : null;

            $data[] = [
                'title' => $post->title,
                'published_at' => $published_at,
                'disabled_at' => $disabled_at,
                'type' => $post->extras['type'],
                'views' => $post->views->count(),
                'readings' => $post->views->where('read', 1)->count(),
                'user' => $post->user->name,
            ];
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $request->data;

        return Excel::download(new PostsExport(collect($data)), 'postagens.xlsx');
    }
}
