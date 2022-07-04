<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PerformanceScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get company list
        $companies = collect($this->fetchData('companies'))->keyBy("company_id");

        // get data/user list
        $data = collect($this->fetchData())->map(
            fn($user) => array_merge(
                $user, [
                    'company_name' => data_get($companies, data_get($user, 'company_id', 0) . '.company_name', 'n/a')
                ]
            )
        );

        return view('performance-script', compact(['data', 'companies']));
    }

    /**
     * Display a listing of the resource with the print method
     * 
     * Open terminal and use CLI:
     * php artisan tinker
     * app()->call('App\Http\Controllers\PerformanceScriptController@printData');
     */
    public function printData()
    {
        // get company list
        $companies = collect($this->fetchData('companies'))->keyBy("company_id");

        // get data/user list
        $data = collect($this->fetchData())->map(
            fn($user) => array_merge(
                $user, [
                    'company_name' => data_get($companies, data_get($user, 'company_id', 0) . '.company_name', 'n/a')
                ]
            )
        );

        $data->each(fn($dataValue) => print('"'.data_get($dataValue, 'id', 'n/a').'", "'.data_get($dataValue, 'name', 'n/a').'", "'.data_get($dataValue, 'title', 'n/a').'", "'.data_get($dataValue, 'company_name', 'n/a').'"' . PHP_EOL));

        if (count($companies) < 1) {
            print("Company info not found" . PHP_EOL);
        }
    }

    /**
     * Retrieve data from API
     * 
     * @param  string $key        for api in config
     * @param  int    $company_id for specific company id
     * @return array 
     */
    protected function fetchData(string $key = 'users', int $company_id = null)
    {
        $api = config("removify.api.$key", '');

        if ($key == 'company_by_id') {
            if ($company_id == null) {
                $api = config("removify.api.companies");
            } else {
                $api .= $company_id;
            }
        }

        try {
            // atempt to get response from API server
            $response = Http::withHeaders(
                [
                'x-token'=> 'removify',
                ]
            )->acceptJson()
                ->retry(3, 100) // retry up to 3 times if connection failed
                ->get($api);

            // check response status is 200 = successful()
            // double check with data returned from resource is TRUE
            if ($response->successful() && $response['success'] == true) {
                $data = $response['data'];

                if (is_array($data)) {
                    return $data;
                }
            }
        } catch(\Exception $e) {
            // do log here
            Logger($e);
        }

        // return empty array if anything went wrong
        return [];
    }
}
