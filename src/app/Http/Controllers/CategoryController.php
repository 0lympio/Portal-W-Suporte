<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories.index')->only('index');
        $this->middleware('permission:categories.create')->only('create');
        $this->middleware('permission:categories.edit')->only('edit');
        $this->middleware('permission:categories.destroy')->only('destroy');
        $this->middleware('permission:categories.status')->only('status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $roleId = auth()->user()->roles->first()->id;

        $rootCategories = Category::leftJoin('permissions as p', 'categories.slug', '=', 'p.name')
            ->leftJoin('role_has_permissions as rh', 'p.id', '=', 'rh.permission_id')
            ->where('rh.role_id', $roleId)
            ->where('category_id', null)
            ->select('categories.*')
            ->with('children')
            ->get();

        $allCategories = [];

        foreach ($rootCategories as $category) {
            $this->getChildCategories($category, $allCategories);
        }

        return view('categories.index', ['categories' => $allCategories]);
    }

    public function getChildCategories($categories, &$array)
    {
        foreach ($categories->children as $category) {
            $this->getChildCategories($category, $array);
        }

        $array[] = $categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function store(Request $request, Role $role): RedirectResponse
    {
        $category = Category::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'isMenu' => isset($request->isMenu) ? 1 : 0,
            'status' => true,
            'icon' => $request->icon,
            'slug' => SlugService::createSlug(Category::class, 'slug', $request->name)
        ]);

        if (is_null($category->category_id)) {
            $permissionRole = DB::table('role_has_permissions as r')
                ->leftJoin('permissions as p', 'r.permission_id', '=', 'p.id')
                ->where('p.name', 'categories.create')
                ->get()
                ->pluck('role_id');

            $permission = Permission::create(['name' => $category->slug]);
            $permission->syncRoles($permissionRole);
        }

        return redirect()->route('categories.index')->with('message', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return Application|Factory|View
     */
    public function show($slug): View|Factory|Application
    {
        $category = Category::where('slug', $slug)->with('children')->with('posts')->first();

        return view('categories.show', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->all();

        $data['isMenu'] = isset($data['isMenu']) ? 1 : 0;
        $data['slug'] = SlugService::createSlug(Category::class, 'slug', $data['name']);

        $category->fill($data);

        $category->save();

        if (is_null($category->category_id)) {
            $permissionRole = DB::table('role_has_permissions as r')
                ->leftJoin('permissions as p', 'r.permission_id', '=', 'p.id')
                ->where('p.name', 'categories.create')
                ->get()
                ->pluck('role_id');

            $permission = Permission::create(['name' => $category->slug]);
            $permission->syncRoles($permissionRole);
        }

        return redirect()->route('categories.index')->with('message', 'Categoria editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('message', 'Categoria removida com sucesso');
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function changeStatus(Request $request, Category $category): JsonResponse
    {
        $category->fill(['status' => $request->status]);

        $category->save();

        return response()->json(['success' => 'Status changed successfully']);
    }
}
