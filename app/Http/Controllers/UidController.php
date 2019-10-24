<?php

namespace App\Http\Controllers;

use App\Record;
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

            $record=Record::whereId($record_id)->withCount('uids')->first();
            if ($record->uids_count >= $record->needLinks) {
                abort(418, "The right amount of id was recorded");
            }

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

    public function delete(Request $request, int $uid_id)
    {
        Uid::findOrFail($uid_id)->delete();
        return 'success';
    }

}
