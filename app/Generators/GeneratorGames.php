<?php

namespace App\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Exceptions\GenerateResultsErrorException;
use App\Services\GameService;
use Illuminate\Support\Collection;

abstract class GeneratorGames
{
    use ValidateDataTrait;
    /**
     * @var GameService
     */
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @param Collection $team_ids
     * @return mixed
     * @throws EmptyTeamsCollectionException
     * @throws GenerateResultsErrorException
     */
    public function generate(Collection $team_ids)
    {
        $this->checkCountTeam($team_ids);

        $game_array = $this->generateGamesData($team_ids);

        $this->checkResultData($game_array);

        return $game_array;
    }

    abstract protected function generateGamesData(Collection $team_ids);
}
