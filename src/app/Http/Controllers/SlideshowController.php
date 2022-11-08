<?php

namespace App\Http\Controllers;

use App\Models\Slideshow;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlideshowController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $images = Upload::where('mimetype', 'like', 'image/%')->orderBy('created_at', 'desc')->get();

        $slides = DB::table('slideshow as s')
                    ->select('s.*', 'u.path')
                    ->join('uploads AS u', 's.upload_id', '=', 'u.id')
                    ->where('s.deleted_at', null)
                    ->where('status_id', 1)
                    ->get();

        return view('slideshow.index', compact('images', 'slides'));
    }

    /**
     * @return View|Factory|Application
     */
    public function create(): View|Factory|Application
    {
        $images = Upload::where('mimetype', 'like', 'image/%')->get();

        return view('slideshow.create', compact('images'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();

        if (!$data['image']) {
            return back()->with('message', 'Imagem para o novo destaque não foi selecionada');
        }

        $data['upload_id'] = Upload::where('path', $data['image'])->first()->id;

        Slideshow::create($data);

        return redirect()->route('slideshow.index')
            ->with('message', 'Imagem adicionada com sucesso!');
    }

    /**
     * @param Slideshow $slideshow
     * @return View|Factory|Application
     */
    public function edit(Slideshow $slideshow): View|Factory|Application
    {
        $images = Upload::where('mimetype', 'like', 'image/%')->get();
        $slideshow->path = $slideshow->upload->path;

        return view('slideshow.edit', compact('slideshow', 'images'));
    }

    /**
     * @param Request $request
     * @param Slideshow $slideshow
     * @return RedirectResponse
     */
    public function update(Request $request, Slideshow $slideshow): RedirectResponse
    {
        $slideshow->fill($request->all());
        $slideshow->save();

        return redirect()->route('slideshow.index')
            ->with('message', 'Imagem editada com sucesso!');
    }

    /**
     * Altera o tempo de exibição para todas as imagens
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function displayTime(Request $request): RedirectResponse
    {
        $displayTime = $request->displayTime;

        DB::table('slideshow')->where('deleted_at', null)->update(['duration' => $displayTime]);

        return redirect()->route('slideshow.index')
            ->with('message', "Tempo de exibição alterado para {$displayTime} segundos");
    }

    /**
     * @param Slideshow $slideshow
     * @return RedirectResponse
     */
    public function destroy(Slideshow $slideshow): RedirectResponse
    {
        $slideshow->delete();

        return redirect()->route('slideshow.index')->with('message', 'Slide deletado com sucesso!');
    }
}
