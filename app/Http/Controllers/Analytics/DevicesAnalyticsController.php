<?php

namespace App\Http\Controllers\Analytics;

use App\Uid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DevicesAnalyticsController extends Controller
{

    public function get(Request $request, int $record_id)
    {

        $onlyAndroidUidCount = Uid::whereRecord_id($record_id)
            ->whereHas('correctrequests', function ($query) {
                $query->whereHas('device', function ($query2) {
                    $query2->whereIn('name', config('devices.android'));
                });
            })
            ->whereDoesntHave('correctrequests', function ($query) {
                $query->whereHas('device', function ($query2) {
                    $query2->whereIn('name', config('devices.ios'));
                });
            })->count();

        $onlyIosUidCount = Uid::whereRecord_id($record_id)
            ->whereHas('correctrequests', function ($query) {
                $query->whereHas('device', function ($query2) {
                    $query2->whereIn('name', config('devices.ios'));
                });
            })
            ->whereDoesntHave('correctrequests', function ($query) {
                $query->whereHas('device', function ($query2) {
                    $query2->whereIn('name', config('devices.android'));
                });
            })->count();

        $iosAndAndroidUidCount = Uid::whereRecord_id($record_id)->whereHas('correctrequests', function ($query) {
            $query->whereHas('device', function ($query2) {
                $devices = array_merge(config('devices.ios'), config('devices.android'));
                $query2->whereIn('name', $devices);
            });
        })->count();

        return [
            'onlyAndroidUidCount' => $onlyAndroidUidCount,
            'onlyIosUidCount' => $onlyIosUidCount,
            'androidAndIosUidCount' => $iosAndAndroidUidCount,
        ];
    }
}
