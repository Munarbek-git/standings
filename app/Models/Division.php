<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model implements PlayableInterface
{
    const COUNTOFDIVISIONS = 2;

    protected $guarded = [];

    public function playable()
    {
        return $this->morphTo();
    }
}
