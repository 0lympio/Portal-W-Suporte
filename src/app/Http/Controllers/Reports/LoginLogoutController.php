<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LoginLogoutExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;

class LoginLogoutController extends Controller
{
    /**
     * @return View|Factory
     */
    public function index(): Factory|View
    {
        $users = User::where('username', '!=', 'admin')->get();
        $companies = Company::all();
        $lastMonth = now()->subDays(30)->startOfDay();
        $roles = Role::all();

        $data = $this->formatData(null, $lastMonth, now()->endOfDay(), null, null);

        return view('reports.login-logout', compact('data', 'companies', 'users', 'roles'));
    }

    public function filter(Request $request): JsonResponse
    {
        $profile = $request->get('profile');
        $company = $request->get('company');
        $status = $request->get('status');
        $startDate = new Carbon($request->get('startDate') . ' 00:00:00');
        $endDate = new Carbon($request->get('endDate') . ' 23:59:59');
        $users = $this->formatData($company, $startDate, $endDate, $status, $profile);

        return response()->json($users);
    }

    public function getData($company, Carbon $startDate, Carbon $endDate, $status, $profile)
    {
        $users = User::where('username', '!=', 'admin')->whereBetween('created_at', [$startDate, $endDate]);

        if ($company) {
            $users->where('company_id', $company);
        }

        if (!is_null($status)) {
            $users->where('status', $status);
        }

        if ($profile) {
            $users->leftJoin('model_has_roles as mhr', 'mhr.model_id', 'users.id')
                ->where('mhr.role_id', $profile);
        }

        return $users->get();
    }

    public function formatData($company, $startDate, $endDate, $status, $profile)
    {
        $users = $this->getData($company, $startDate, $endDate, $status, $profile);
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'name' => Str::of($user->name)->explode(' ')->first() . " " . Str::of($user->last_name)->explode(' ')->last(),
                'username' => $user->username,
                'status' =>   $user->status === 1 ? 'Ativo' : 'Inativo',
                'created_at' => $user->created_at,
                'profile' => $user->roles[0]->name ?? '',
                'company' => $user->company->name ?? '',
            ];
        }

        return $data;
    }

    public function getDataForExport($company, $status, $profile, $startDate, $endDate)
    {
        $company = $company ? "and c.id = {$company}" : "";
        $status = !is_null($status) ? "and u.status = {$status}" : "";
        $profile = $profile ? "and r.id = {$profile}" : "";
        $startDate = $startDate ? "{$startDate} 00:00:00" : now()->subDays(30)->startOfDay();
        $endDate = $endDate ? "{$endDate} 23:59:59" : now()->endOfDay();

        $query = DB::select(
            "select
                CONCAT(u.name, ' ', u.last_name) as 'name',
                u.username,
                case when u.status = 0 then 'inativo' else 'ativo' end as status,
                u.created_at,
                u.disabled_at,
                r.name as profile,
                c.name as company,
                DATE_FORMAT(s.created_at, '%d/%m/%Y') as 'date',
                DATE_FORMAT(min(s.created_at), '%H:%i:%S') as 'login',
                DATE_FORMAT(max(s.created_at), '%H:%i:%S') as 'logout',
                TIMEDIFF(max(s.created_at), min(s.created_at)) as 'totalTimeLogged',
                0 as daysLogged,
                (select u2.username from users as u2 where u2.id = u.created_by) as 'created_by'
            from
                sessions s
                inner join users u on s.user_id = u.id
                inner join companies c on u.company_id = c.id
                left join model_has_roles mhr on mhr.model_id = u.id
                left join roles r on r.id = mhr.role_id
                where u.deleted_at is null
                {$company}
                {$status}
                {$profile}
                and s.created_at between '{$startDate}' and '{$endDate}'
            group BY
                date,
                u.username,
                name,
                u.status,
                u.created_at,
                u.disabled_at,
                profile,
                company,
                created_by"
        );

        return collect($query);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $this->getDataForExport(
            $request->get('company'),
            $request->get('status'),
            $request->get('profile'),
            $request->get('startDate'),
            $request->get('endDate')
        );

        return Excel::download(new LoginLogoutExport(collect($data)), 'usuarios.xlsx');
    }
}
