<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayOffStage extends Model implements PlayableInterface
{
    const QUARTERFINAL = "Quarter Final";
    const SEMIFINAL = "Semi Final";
    const THIRDPLACE = "Game for third place";
    const FINAL = "Final";

    protected $guarded = [];
}
