<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.index')->only('index');
        $this->middleware('permission:users.store')->only('store');
        $this->middleware('permission:users.update')->only('update');
        $this->middleware('permission:users.destroy')->only('destroy');
        $this->middleware('permission:users.import')->only('import');
        $this->middleware('permission:users.changePassword')->only('changePassword');
        $this->middleware('permission:users.status')->only('status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $users = User::where('id', '!=', 1)->with('roles')->with('company')->get();
        $roles = Role::where('name', '!=', 'Administrador')->get();
        $companies = Company::all();

        return view('users.index', compact('users', 'roles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'username' => ['required', 'max:255', Rule::unique('users', 'username')->whereNull('deleted_at')],
            'email' => ['max:255', 'nullable', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'password' => ['required'],
        ];

        $messages = [
            'username.unique' => 'Este nome de usuário já foi registrado',
            'email.unique' => 'Este email já foi registrado',
        ];

        $request->validate($rules, $messages);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['created_by'] = auth()->user()->id;

        $role = Role::where('id', $request->role)->first();

        $user = User::create($data);

        $user->assignRole([$role->id]);

        return redirect()->route('users.index')->with('message', 'Usuário criado com sucesso');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $inputs = $request->all();

        $data = [];

        foreach ($inputs as $name => $input) {
            if ($input != null) {
                $data[$name] = $input;
            }
        }

        $user->fill($data);
        $user->save();

        $role = Role::where('id', $request->role)->first();
        $user->syncRoles($role->id);

        return redirect()->route('users.index')->with('message', 'Usuário editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('message', 'Usuário deletado com sucesso!');
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('file_bulk_registration')->store('import');

        $usersImport = new UsersImport();
        $usersImport->import($file);

        Storage::disk('local')->delete($file);

        return redirect()->route('users.index')->with('message', 'Usuários inseridos com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function changePassword(Request $request, User $user): RedirectResponse
    {
        $data['password'] = Hash::make($request->password);
        $user->fill($data);
        $user->save();

        return redirect()->route('users.index')->with('message', 'Senha editada com sucesso!');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function changeStatus(Request $request, User $user): JsonResponse
    {
        $status = $request->get('status');

        $user->fill([
            'status' => $status,
            'disabled_at' => $status ? null : now(),
        ]);

        $user->save();

        return response()->json(['success' => 'Status changed successfully']);
    }
}
