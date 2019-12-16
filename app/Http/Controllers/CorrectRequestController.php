<?php

namespace App\Http\Controllers;

use App\CorrectRequest;
use Illuminate\Http\Request;

class CorrectRequestController extends Controller
{

    public function update(Request $request, $request_id)
    {
        $correctRequest = CorrectRequest::findOrFail($request_id);
        $correctRequest->update($request->all());
        return $correctRequest;
    }

}
