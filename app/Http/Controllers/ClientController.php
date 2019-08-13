<?php

namespace App\Http\Controllers;

use App\Client;
use App\ParamVariable;
use App\QueryParam;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        return Client::all();
    }

    public function get(string $name)
    {
        $client = Client::whereName($name)->with('params.variables')->first();
        return $client;
    }

    public function store(Request $request)
    {
        $client = Client::create([
            'name' => $request->input('name'),
            'subdomain' => $request->input('subdomain')
        ]);
        for ($i = 1; $i <= 5; $i++) {
            $param=QueryParam::create([
                'client_id' => $client->id,
                'name' => '',
                'index_number' => $i
            ]);
            ParamVariable::create([
                'name' => 'all',
                'QueryParam_id' => $param->id
            ]);
        }
        return $client;
    }

    public function update(Request $request, int $client_id)
    {
        Client::findOrFail($client_id)->update($request->all());
        return 'success';
    }

}
