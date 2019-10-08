<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{


    protected $fillable = [
        'patternlink_id', 'needLinks', 'isActive', 'client_id'
    ];


    public function pattenrlink()
    {
        return $this->belongsTo(PatternLink::class, 'patternlink_id');
    }


}
