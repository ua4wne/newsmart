<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    protected $fillable = ['option_id','condition','val','action','text','state','step'];

    public function option()
    {
        return $this->belongsTo('App\Models\Option','rule_id','id');
    }
}
