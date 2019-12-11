<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordAnalyticsController extends Controller
{

    private $route = '/recordAnalytics/';

    public function all(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?from=' . $request->input('from') . '&to=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . $dateParam;

            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return $pathName . "<br>" . "Ошибка";
        }
    }

    public function get(Request $request, int $record_id)
    {
        try {
            $dateParam = '';
            if ($request->input('from') !== null)
                $dateParam = '?from=' . $request->input('from') . '&to=' . $request->input('to');

            $pathName = config('pathToAppMicroservices.analytics') . $this->route . $record_id . '/withUid' . $dateParam;
            return file_get_contents($pathName);
        } catch (\Exception  $e) {
            return $pathName . "<br>" . "Ошибка";
        }
    }

}
