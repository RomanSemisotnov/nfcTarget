<?php

namespace App\Http\Controllers;

use App\Client;
use App\CorrectRequest;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{

    public function getExcel(Request $request)
    {
        $client = Client::findOrFail($request->input('client_id'));
        $start = $request->input('start');
        $end = $request->input('end');

        $client = Client::whereId($request->input('client_id'))->with(['params' => function ($query) use ($start, $end) {
            $query->with(['variables' => function ($query1) use ($start, $end) {
                $query1->withCount(['requests' => function ($query2) use ($start, $end) {
                    $query2->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
                }]);
            }]);
        }])->first();


        return $client;
    }

}