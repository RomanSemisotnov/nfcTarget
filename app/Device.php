<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['name'];

    public function correctrequests()
    {
        return $this->hasMany(CorrectRequest::class);
    }

}
