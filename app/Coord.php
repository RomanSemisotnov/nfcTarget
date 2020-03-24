<?php


namespace App;
use Illuminate\Database\Eloquent\Model;

class Coord extends Model
{

    private $table ='coords';

    protected $fillable = ['first', 'second'];


}