<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.index')->only('index');
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.edit')->only(['edit', 'update']);
        $this->middleware('permission:roles.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $roles = Role::where('id', '!=', 5)->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $permissions = DB::select('select p.* from permissions as p where p.name not in (select c.slug from categories as c) order by p.name');
        $roleId = auth()->user()->roles->first()->id;

        $categories = Category::leftJoin('permissions as p', 'categories.slug', '=', 'p.name')
            ->leftJoin('role_has_permissions as rh', 'p.id', '=', 'rh.permission_id')
            ->where('rh.role_id', $roleId)
            ->select('categories.*')
            ->get();

        return view('roles.create', compact('permissions', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);


        $selectedCategories = $request->get('categories') ? array_keys($request->get('categories')) : [];
        $permissions = $request->get('permission') ? array_keys($request->get('permission')) : [];

        $categories = Permission::whereIn('name', $selectedCategories)->get();

        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions([...$permissions, ...$categories->pluck('id')]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return Application|Factory|View
     */
    public function show(Role $role): View|Factory|Application
    {
        $rolePermissions = $role->permissions;

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return Application|Factory|View
     */
    public function edit(Role $role): View|Factory|Application
    {
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $permissions = DB::select('select p.* from permissions as p where p.name not in (select c.slug from categories as c) order by p.name');

        $roleId = auth()->user()->roles->first()->id;

        $categories = Category::leftJoin('permissions as p', 'categories.slug', '=', 'p.name')
            ->leftJoin('role_has_permissions as rh', 'p.id', '=', 'rh.permission_id')
            ->where('rh.role_id', $roleId)
            ->select('categories.*')
            ->get();

        return view('roles.edit', compact('role', 'rolePermissions', 'permissions', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Role $role, Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $selectedCategories = $request->get('categories') ? array_keys($request->get('categories')) : [];
        $permissions = $request->get('permission') ? array_keys($request->get('permission')) : [];

        $role->update($request->only('name'));
        $categories = Permission::whereIn('name', $selectedCategories)->get();

        $role->syncPermissions([...$permissions, ...$categories->pluck('id')]);

        return redirect()->route('roles.index')
            ->with('message', 'Perfil editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('message', 'Perfil removido com sucesso');
    }
}
