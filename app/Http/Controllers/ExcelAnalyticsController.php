<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelAnalyticsController extends Controller
{

    public function get(Request $request)
    {
        if ($request->input('from') === null) {
            return file_get_contents("http://localhost:8080/analytics");
        } else {
            return file_get_contents("http://localhost:8080/analytics?from=" . $request->input('from') .
                "&to=" . $request->input('to'));
        }
    }

}
