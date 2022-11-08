<?php

namespace App\Http\Controllers\Reports;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UsersReportController extends Controller
{
    /**
     * Retorna para a view os dados dos últimos 7 dias
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $companies = Company::all();
        $data = $this->getData();

        return view('reports.users', compact('data', 'companies'));
    }

    /**
     * @param Request|null $request
     * @return array
     */
    public function getData(Request $request = null): array
    {
        $users = User::where('username', '!=', 'admin')->get();

        $data = [];
        foreach ($users as $key => $user) {
            $data[] = [
                'name' => $user->name,
                'username' => $user->username,
                'profile' => $user->roles[0]->name,
                'status' => $user->status ? 'Ativo' : 'Inativo',
            ];
        }

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $request->data;

        return Excel::download(new UsersExport(collect($data)), 'usuários.xlsx');
    }
}
