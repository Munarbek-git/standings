<?php

namespace App\Generators;

use App\Models\Division;
use Illuminate\Support\Collection;

class DivisionGamesGenerator extends GeneratorGames
{
    /**
     * @param Collection $team_ids
     * @return array
     * @throws \Exception
     */
    protected function generateGamesData(Collection $team_ids)
    {
        $count_of_teams_for_one_division = ceil($team_ids->count()/Division::COUNTOFDIVISIONS);
        $divisions_teams_ids = $team_ids->shuffle()->chunk($count_of_teams_for_one_division);
        $game_array = [];

        foreach ($divisions_teams_ids as $key => $division_teams_ids) {
            while ($division_teams_ids->count()) {
                $_team_id = $division_teams_ids->first();
                $division_teams_ids->shift();

                foreach ($division_teams_ids as $division_team_id) {
                    $game_id = $this->gameService->createGameAndGetId();
                    $isWin = (bool)random_int(0, 1);
                    $game_array[$key][$game_id][$_team_id] = (int)$isWin;
                    $game_array[$key][$game_id][$division_team_id] = (int)!$isWin;
                }
            }
        }

        return $game_array;
    }
}
