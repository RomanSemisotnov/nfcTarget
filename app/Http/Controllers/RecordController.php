<?php

namespace App\Http\Controllers;

use App\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{


    public function get(Record $request, int $client_id)
    {
        return Record::whereClient_id($client_id)->with(['pattenrlink' => function ($query) {
            $query->withCount('uids');
        }])->orderBy('id', 'desc')->get();
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
