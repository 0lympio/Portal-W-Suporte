<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'Owner')->get();
        $companies = Company::get();
        return view('auth.register', compact(['roles','companies']));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'username' => ['required', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'role_id' => ['required'],
        ];

        $messages = [
            'username.unique' => 'Este usuário já foi registrado',
            'email.unique' => 'Este e-mail já foi registrado',
        ];

        $request->validate($rules, $messages);
        $request->password = Hash::make($request->password);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
