<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExcelAnalyticsController extends Controller
{

    public function get(Request $request, string $record_ids)
    {
        return $record_ids;
    }

}
