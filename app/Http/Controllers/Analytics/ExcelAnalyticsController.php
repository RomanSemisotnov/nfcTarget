<?php

namespace App\Http\Controllers\Analytics;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExcelAnalyticsController extends Controller
{

    public function get(Request $request, string $record_ids)
    {
        try {
            $httpClient = new \GuzzleHttp\Client(['base_uri' => config('pathToAppMicroservices.analytics')]);
            $response = $httpClient->request('GET', '/analytics/excel/' . $record_ids);

            return response($response->getBody(), 200)
                ->header("Content-Disposition", "attachment; filename=analytics.xlsx");
        } catch (RequestException $e) {

            if ($e->getCode() === 0) {
                return abort(400, 'Сервер недоступен');
            }

            if ($e->hasResponse()) {
                return abort($e->getCode(), $e->getResponse()->getBody()->getContents());
            }

            return abort($e->getCode(), "Указание о ошибке отсутствует");
        }

    }

}
