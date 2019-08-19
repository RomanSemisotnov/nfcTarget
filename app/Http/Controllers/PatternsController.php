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

    public function delete(Request $request, int $id)
    {
        PatternLink::findOrFail($id)->delete();
        return 'success';
    }

}
