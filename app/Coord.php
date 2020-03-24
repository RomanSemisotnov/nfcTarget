<?php


namespace App;
use Illuminate\Database\Eloquent\Model;

class Coord extends Model
{

    protected $table ='coords';

    protected $fillable = ['first', 'second', 'ip'];


}