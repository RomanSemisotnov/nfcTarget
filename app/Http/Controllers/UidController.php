<?php

namespace App\Http\Controllers;

use App\Uid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UidController extends Controller
{


    public function create(Request $request)
    {
        $uidValue = $request->input('value');
        $uid = Uid::whereValue($uidValue)->first();
        if ($uid === null) {
            $record_id = $request->input('record_id');
            Uid::create([
                'value' => $request->input('value'),
                'record_id' => $record_id
            ]);
            return DB::select("SELECT  (SELECT COUNT(*) from `uids` WHERE `record_id` = r.id) as countLinks 
            FROM `records` r WHERE r.id =" . $record_id);
        } else {
            abort(409, "This Uid value already exist");
        }
    }


}
