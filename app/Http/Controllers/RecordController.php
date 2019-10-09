<?php

namespace App\Http\Controllers;

use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{

    public function get(Record $request, int $client_id)
    {
        return Record::whereClient_id($client_id)->with('patternlink')->with('uids')->orderBy('id', 'desc')->get();
    }

    public function getActiveRecord()
    {
        return DB::select("SELECT r.needLinks, pat.value FROM `records` r 
        INNER JOIN `pattern_links` pat ON r.patternlink_id = pat.id WHERE r.isActive ='yes'");
        return Record::where([
            'isActive' => 'yes'
        ])->with('patternlink')->first();
    }

    public function create(Request $request)
    {
        return Record::create([
            'patternlink_id' => $request->input('pattern_id'),
            'needLinks' => $request->input('needLinks'),
            'client_id' => $request->input('client_id'),
            'isActive' => 'no'
        ]);
    }

    public function enable(Request $request, int $record_id)
    {
        $newStatus = $request->input('newStatus');
        if ($newStatus === 'no') {
            Record::whereId($record_id)->update(['isActive' => 'no']);
        } elseif ($newStatus === 'yes') {
            Record::where('isActive', 'yes')->update(['isActive' => 'no']);
            Record::whereId($record_id)->update(['isActive' => 'yes']);
        } else {
            abort(400, 'bad new status');
        }
        return 'success';
    }


    public function delete(Request $request, int $record_id)
    {
        return Record::whereId($record_id)->delete();
    }

}
