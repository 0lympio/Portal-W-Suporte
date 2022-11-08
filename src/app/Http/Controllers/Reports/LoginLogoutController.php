<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LoginLogoutExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoginLogoutController extends Controller
{
    /**
     * Retorna para a view os dados dos últimos 7 dias
     *
     * @return View|Factory
     */
    public function index(): Factory|View
    {
        $users = User::all();
        $companies = Company::all();
        $data = $this->getData();

        return view('reports.login-logout', compact('data', 'users', 'companies'));
    }

    public function filter(Request $request): JsonResponse
    {
        $data = $this->getData($request);

        return response()->json(['data' => $data]);
    }

    public function getData(Request $request = null): array
    {
        $dateStart = Carbon::now()->subDays(7);
        $dateEnd = new Carbon();
        $users = User::where('id', '!=', 1)->get();

        if ($request) {
            $start = $request->query('start');
            $end = $request->query('end');

            if ($start !== 'last_7_days') {
                $dateStart = new Carbon($start . ' 00:00:00');
                $dateEnd = new Carbon($end . ' 23:59:59');
            }
        }

        $data = [];

        foreach ($users as $key => $user) {
            $userSessions = $user->sessions->whereBetween('created_at', [$dateStart, $dateEnd])->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            });

            foreach ($userSessions as $session) {
                $entry = new Carbon($session->first()->created_at);
                $exit = new Carbon($session->last()->created_at);

                $data[] = [
                    'name' => $user->name,
                    'username' => $user->username,
                    'company' => $user->company->name ?? null,
                    'date' => $exit->format('d/m/Y'),
                    'entry' => $entry->format('G:i:s'),
                    'exit' => $exit->format('G:i:s'),
                ];
            }
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

        return Excel::download(new LoginLogoutExport(collect($data)), 'login_logout.xlsx');
    }
}
