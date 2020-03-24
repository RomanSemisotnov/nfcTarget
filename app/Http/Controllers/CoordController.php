<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Client;
use App\ParamVariable;
use App\QueryParam;
use Illuminate\Http\Request;

class CoordController extends Controller
{

    public function save(Request $request){
        return \App\Coord::create([
            'first' => $request->first,
            'second' => $request->second
        ]);
    }

}
