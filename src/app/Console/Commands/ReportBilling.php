<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:billing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report billing of the month';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $usersActives =  collect(DB::select("
        SELECT
        c.name AS `companies`, MONTH(CURDATE()) AS date, COUNT(DISTINCT u.id) AS `ativos`
       FROM
        users AS u
       LEFT JOIN companies AS c ON u.company_id = c.id
       INNER JOIN sessions AS s ON u.id = s.user_id
       WHERE c.name IS NOT NULL AND u.flag = 0
       GROUP BY
        c.name, date
        
        "));

        $usersInactives = collect(DB::select("
        SELECT 
        c.name AS `companies`, MONTH(CURDATE()) AS date, COUNT(DISTINCT u.id) AS `inativos` 
        FROM users AS u 
        LEFT JOIN companies AS c ON u.company_id = c.id 
        LEFT JOIN sessions AS s ON u.id = s.user_id 
        WHERE s.user_id IS NULL AND u.flag = 0
        GROUP BY
         c.name, date
        "));

        $companies = Company::all();


        foreach ($companies as $company) {

            if ($usersActives->where('companies', $company->name)->count() === 0) {
                $usersActives[] = (object) [
                    "companies" => $company->name,
                    "date" => $usersActives->first()->date,
                    "ativos" => 0
                ];
            }

            if ($usersInactives->where('companies', $company->name)->count() === 0) {
                $usersInactives[] = (object) [
                    "companies" => $company->name,
                    "date" => $usersInactives->first()->date,
                    "inativos" => 0
                ];
            }
        }

        foreach ($usersActives as $key => $userActive) {

            $userActive->ativos + $usersInactives[$key]->inativos;

            DB::table('billing_report')->insert([
                'company' => $userActive->companies,
                'month' => now(),
                'active' => $userActive->ativos,
                'inactive' => $usersInactives[$key]->inativos,
                'total' => $userActive->ativos + $usersInactives[$key]->inativos,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        DB::update("
        UPDATE users AS u SET flag = 1 WHERE DATE_FORMAT(u.deleted_at, '%m') = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH));
        ");
    }
}
