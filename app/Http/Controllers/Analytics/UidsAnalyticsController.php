<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UidsAnalyticsController extends Controller
{

    private $route = '/analytics/uids/';

    public function getOpenCount(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?startDate=' . $request->input('from') . '&endDate=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . '/openCount' . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

    public function getNotOpenCount(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?startDate=' . $request->input('from') . '&endDate=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . '/notOpenCount' . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

}
