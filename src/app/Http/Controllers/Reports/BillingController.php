<?php

namespace App\Http\Controllers\Reports;

use App\Exports\BillingExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BillingController extends Controller
{
    /**
     * Retorna para a view os dados dos últimos 7 dias
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $companies = Company::all();
        $lastMonth = Carbon::create(now()->year, now()->subMonth()->month, 1, 0, 0, 0);

        $data = $this->getData(null, $lastMonth, now());

        return view('reports.billing', compact('data', 'companies'));
    }

    public function filter(Request $request)
    {
        $company = $request->get('company');
        $startDate = new Carbon($request->get('startDate') . ' 00:00:00');
        $endDate = new Carbon($request->get('endDate') . ' 23:59:59');

        $data = $this->getData($company, $startDate, $endDate);

        return response()->json($data);
    }


    public function getData($company, Carbon $startDate, Carbon $endDate)
    {
        $builder = DB::table('billing_report')->whereBetween('month', [$startDate, $endDate]);

        if ($company) {
            $builder->where('company', $company);
        }

        return $builder->get();
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $request->data;

        return Excel::download(new BillingExport(collect($data)), 'faturamento.xlsx');
    }
}
