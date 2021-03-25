<?php


namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Collection;

class TeamService
{
    /**
     * @var Team
     */
    private $team;

    /**
     * DivisionService constructor.
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return array
     */
    public function getTeamsArray():array
    {
        $teams = $this->all();
        $array = [];
        foreach ($teams as $team) {
            $array[$team->id]['name'] = $team->name;
            $array[$team->id]['score'] = 0;
        }

        return $array;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->team->get();
    }

    /**
     * @return Collection
     */
    public function getDivisionsTeamsIds(): Collection
    {
        return $this->all()->map(function ($team) {
            return $team->id;
        });
    }
}
