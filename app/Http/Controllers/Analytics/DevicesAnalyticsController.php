<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DevicesAnalyticsController extends Controller
{

    private $route = '/analytics/devices/';

    public function all(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?from=' . $request->input('from') . '&to=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . '/all' . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

    public function get(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?from=' . $request->input('from') . '&to=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

    public function getRating(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?from=' . $request->input('from') . '&to=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . '/rating' . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return abort(500, $pathName);
        }
    }

}
