<?php
/**
 * Created by PhpStorm.
 * User: russian_rave
 * Date: 7/31/2019
 * Time: 9:56 AM
 */

namespace App\Servicec;


use App\Client;

class ClientService
{

    public function getName(){
        return 'dodo'; // заглушка, переписать
    }

    public function getClientByName(){
        try{
            return Client::whereName($this->getName())->first();
        }catch(\Exception $e){
            abort(404,'Client not found');
        }
    }
}