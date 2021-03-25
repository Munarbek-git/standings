<?php

namespace App\Generators;

use App\Services\GameService;
use Carbon\Exceptions\InvalidTypeException;
use Illuminate\Support\Collection;

class GeneratorFactory
{
    const DIVISION = "DIVISION";
    const QUARTERFINAL = "QUARTERFINAL";
    const SEMIFINAL = "SEMIFINAL";
    const FINAL = "FINAL";
    /**
     * @var GameService
     */
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @var GeneratorGamesInterface
     */
    protected $generator;

    public function setGenerator(string $type)
    {
        $this->generator = $this->createGenerator($type);
        return $this;
    }

    /**
     * @param string $type
     * @return GeneratorGames
     */
    protected function createGenerator(string $type): GeneratorGames
    {
        switch ($type) {
            case self::DIVISION:
                return new DivisionGamesGenerator($this->gameService);
            case self::QUARTERFINAL:
                return new QuaterFinalGamesGenerator($this->gameService);
            case self::SEMIFINAL:
                return new SemiFinalGamesGenerator($this->gameService);
            case self::FINAL:
                return new FinalGameGenerator($this->gameService);
        }

        throw new InvalidTypeException(sprintf("Генератор для типа \"%s\" не найдена", $type));
    }

    /**
     * @param Collection $team_ids
     * @return array
     */
    public function generate(Collection $team_ids):array
    {
        return $this->generator->generate($team_ids);
    }
}
