<?php

namespace App\Generators;

use Illuminate\Support\Collection;

class FinalGameGenerator extends GeneratorGames
{
    /**
     * @param Collection $team_ids
     * @return array
     * @throws \Exception
     */
    protected function generateGamesData(Collection $team_ids)
    {
        $game_id = $this->gameService->createGameAndGetId();
        $isWin = (bool)random_int(0, 1);
        $game_array[$game_id][$team_ids[0]] = (int)$isWin;
        $game_array[$game_id][$team_ids[1]] = (int)!$isWin;

        return $game_array;
    }
}
