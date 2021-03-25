<?php


namespace App\Generators;


use Illuminate\Support\Collection;

class SemiFinalGamesGenerator extends GeneratorGames
{
    /**
     * @param Collection $team_ids
     * @return array
     * @throws \Exception
     */
    protected function generateGamesData(Collection $team_ids)
    {
        $game_array = [];

        for ($i = 0; $i < count($team_ids); $i = $i + 2) {
            $game_id = $this->gameService->createGameAndGetId();
            $isWin = (bool)random_int(0, 1);
            $game_array[$game_id][$team_ids[$i]] = (int)$isWin;
            $game_array[$game_id][$team_ids[$i + 1]] = (int)!$isWin;
        }

        return $game_array;
    }
}
