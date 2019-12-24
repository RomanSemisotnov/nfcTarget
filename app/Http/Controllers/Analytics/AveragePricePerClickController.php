<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AveragePricePerClickController extends Controller
{

    private $route = '/analytics/averagePricePerClick/';

    public function get(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?startDate=' . $request->input('from') . '&endDate=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

}
