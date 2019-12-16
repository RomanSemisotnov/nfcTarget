<?php

namespace App\Http\Controllers;

use App\Record;
use App\Uid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UidController extends Controller
{

    public function get(Request $request, string $uid_ids)
    {
        $uid = Uid::whereIn('id', explode(',', $uid_ids));

        if ($request->has('with')) {
            $relationships = explode(',', $request->input('with'));

            foreach ($relationships as $relationship) {
                $uid->with(["$relationship" => function ($query) use ($request) {
                    if ($request->has('from') && $request->has('to'))
                        $query->whereBetween('created_at', [$request->input('from'), $request->input('to')]);

                    $query->orderBy('id', 'desc');
                }]);
            }
        }

        $uid = $uid->get();

        if ($uid === null)
            abort(404, "Uid not found");

        return $uid;
    }

    public function create(Request $request)
    {
        $uidValue = $request->input('value');
        $uid = Uid::whereValue($uidValue)->first();
        if ($uid === null) {
            $record_id = $request->input('record_id');

            $record = Record::whereId($record_id)->withCount('uids')->first();
            if ($record->uids_count >= $record->needLinks) {
                abort(418, "The right amount of id was recorded");
            }

            Uid::create([
                'value' => $request->input('value'),
                'record_id' => $record_id,
                'patternlink_id' => $record->patternlink_id
            ]);
            return DB::select("SELECT  (SELECT COUNT(*) from `uids` WHERE `record_id` = r.id) as countLinks 
            FROM `records` r WHERE r.id =" . $record_id);
        } else {
            abort(409, "This Uid value already exist");
        }
    }

    public function update(Request $request, int $uid_id)
    {
        $uid = Uid::findOrFail($uid_id);
        $uid->update($request->all());
        return $uid;
    }

    public function delete(Request $request, int $uid_id)
    {
        Uid::findOrFail($uid_id)->delete();
        return 'success';
    }

}
