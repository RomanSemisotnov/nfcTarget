<?php

namespace App\Http\Controllers;

use App\Client;
use App\PatternLink;
use Illuminate\Http\Request;

class PatternsController extends Controller
{

    public function get(Request $request, int $client_id)
    {
        $links = PatternLink::whereClient_id($client_id);

        return $links->orderBy('id', 'desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $client = Client::whereId($request->input('client_id'))->with('params.variables')->first();
        $params = $request->input('params');

        $withToken = 'no';
        foreach ($params as $param) {
            if ($param === 'token')
                $withToken = 'yes';
        }

        for ($i = 0; $i < count($params); $i++) {
            if ($params[$i] !== 'token' && !in_array($params[$i], $client->params[$i]->variables->pluck('name')->toArray())) {
                abort(400, "Parametr " . ($i + 1) . " incorrect");
            }
        }

        $domain = "https://" . $client->name . ".nfc-u.ru/";
        foreach ($params as $param) {
            if ($param !== 'token') {
                $domain .= $param . '/';
            }
        }
        if ($domain[strlen($domain) - 1] === '/')
            $domain = substr($domain, 0, -1);

        return PatternLink::create([
            'value' => $domain,
            'withToken' => $withToken,
            'redirectTo' => $request->input('redirectTo'),
            'client_id' => $client->id
        ]);
    }

    public function delete(Request $request, int $id)
    {
        PatternLink::findOrFail($id)->delete();
        return 'success';
    }

}
