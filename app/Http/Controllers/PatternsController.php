<?php

namespace App\Http\Controllers;

use App\Client;
use App\CorrectRequest;
use App\PatternLink;
use Illuminate\Http\Request;

class PatternsController extends Controller
{

    public function get(Request $request, int $client_id)
    {
        $links = PatternLink::whereClient_id($client_id);

        if ($request->input('needParam') !== '')
            $links->where('value', 'like', '%' . $request->input('needParam') . '%');

        return $links->with(['uids' => function($query){
            $query->withCount('correctrequests');
        }])->orderBy('id', 'desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $client = Client::whereId($request->input('client_id'))->with('params.variables')->first();
        $params = $request->input('params');

        for ($i = 0; $i < count($params); $i++) {
            if (!in_array($params[$i], $client->params[$i]->variables->pluck('name')->toArray())) {
                abort(400, "Parametr " . ($i + 1) . " incorrect");
            }
        }

        $domain = "http://" . $client->subdomain . ".nfc-u.ru/";
        foreach ($params as $param) {
            $domain .= $param . '/';
        }

        return PatternLink::create([
            'value' => $domain,
            'redirectTo' => $request->input('redirectTo'),
            'client_id' => $client->id
        ]);
    }

    public function update(Request $request, int $link_id)
    {
        $link = PatternLink::whereId($link_id)->with('uids.correctrequests')->first();

        if($request->input('redirectTo') !== $link->redirectTo){
            $correctrequests= $link->uids->pluck('correctrequests');

            $correctrequest_ids=[];
            foreach($correctrequests as $mass){
                foreach($mass as $correctrequest){
                    $correctrequest_ids[]=$correctrequest->id;
                }
            }
            CorrectRequest::whereIn('id', $correctrequest_ids)->delete();
        }

        $link->update($request->all());

        return $link;
    }

    public function delete(Request $request, int $id)
    {
        PatternLink::findOrFail($id)->delete();
        return 'success';
    }

}
