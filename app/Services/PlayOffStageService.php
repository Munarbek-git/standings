<?php


namespace App\Services;


use App\Models\PlayOffStage;

class PlayOffStageService
{
    /**
     * @var PlayOffStage
     */
    private $playOffStage;

    public function __construct(PlayOffStage $playOffStage)
    {
        $this->playOffStage = $playOffStage;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->playOffStage->get();
    }

    public function getStageArray()
    {
        $stages = $this->all();
        $array = [];
        foreach ($stages as $stage) {
            $array[$stage->id] = $stage->name;
        }

        return $array;
    }
}
