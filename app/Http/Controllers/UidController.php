<?php

namespace App\Http\Controllers;

use App\Uid;
use Illuminate\Http\Request;

class UidController extends Controller
{


    public function create(Request $request)
    {
        return Uid::create([
            'value' => $request->input('value'),
            'record_id' => $request->input('record_id')
        ]);
    }


}
