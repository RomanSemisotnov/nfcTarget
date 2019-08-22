<?php

namespace App\Http\Controllers;

use App\Client;
use App\ParamVariable;
use App\QueryParam;
use function foo\func;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        return Client::all();
    }

    public function get(Request $request, string $name)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $client = Client::whereName($name)->with(['params' => function ($query) use ($start, $end) {
            $query->with(['variables' => function ($query1) use ($start, $end) {
                $query1->withCount(['requests' => function ($query2) use ($start, $end) {
                    if($start && $end){
                        $query2->whereBetween('created_at', [$start.' 00:00:01', $end.' 23:59:59']);
                    }
                }]);
            }]);
        }])->first();
        return $client;
    }

    public function store(Request $request)
    {
        $client = Client::create([
            'name' => $request->input('name'),
            'subdomain' => $request->input('subdomain')
        ]);
        for ($i = 1; $i <= 3; $i++) {
            $param = QueryParam::create([
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
