<?php


namespace App\Services;


use App\Exceptions\DataNotGenerated;
use App\Exceptions\GenerateResultsErrorException;
use App\Exceptions\GenerateQuaterFinalResultsErrorException;
use App\Generators\GeneratorFactory;
use App\Models\Division;
use App\Models\Game;
use App\Models\PlayableInterface;
use App\Models\PlayOffStage;
use App\Models\Result;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneratorService
{
    /**
     * @var ResultService
     */
    private $resultService;
    /**
     * @var TeamService
     */
    private $teamService;
    /**
     * @var DivisionService
     */
    private $divisionService;
    /**
     * @var GeneratorFactory
     */
    private $generatorFactory;

    public function __construct(ResultService $resultService, TeamService $teamService, DivisionService $divisionService, GeneratorFactory $generatorFactory)
    {
        $this->resultService = $resultService;
        $this->teamService = $teamService;
        $this->divisionService = $divisionService;
        $this->generatorFactory = $generatorFactory;
    }

    /**
     * @throws DataNotGenerated
     */
    public function generateData()
    {
        DB::beginTransaction();
        try {
            $this->clearTables();
            $this->generateTeams(16);
            $divisions = $this->divisionService->all();
            $this->generateGamesResult($divisions);
            $this->generateGamesForPlayOff($divisions);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new DataNotGenerated('Генерация данных не удалась.');
        }
    }

    /**
     * @param int $count
     * @return Collection
     */
    protected function generateTeams(int $count = 1): Collection
    {
        return factory(Team::class, $count)->create();
    }

    /**
     * @param Collection $divisions
     */
    protected function generateGamesResult(Collection $divisions)
    {
        $divisions_teams_ids = $this->teamService->getDivisionsTeamsIds();
        $result_data = $this->generatorFactory->setGenerator(GeneratorFactory::DIVISION)
            ->generate($divisions_teams_ids);

        foreach ($divisions as $key => $division) {
           $this->createPlayOffResult($result_data[$key], $division);
       }
    }

    /**
     * @param Collection $divisions
     */
    public function generateGamesForPlayOff(Collection $divisions)
    {
        $this->generateQuarterFinalGames($divisions);
        $this->generateSemiFinalGames();
        $this->generateGamesForThirdPlace();
        $this->generateFinalGames();
    }

    /**
     * @param Collection $divisions
     */
    protected function generateQuarterFinalGames(Collection $divisions)
    {
        $team_ids = [];
        for ($i = 0; $i < $divisions->count(); $i++) {
            $team_ids[$i] = $this->resultService->getWinnerTeamsForDivision($divisions[$i]->id);
            $team_ids[$i] = $team_ids[$i]->map(function ($team) {
                return $team->team_id;
            });
        }

        $games = $this->generatorFactory->setGenerator(GeneratorFactory::QUARTERFINAL)
            ->generate(collect($team_ids));

        $quater_final = PlayOffStage::where('name', PlayOffStage::QUARTERFINAL)->firstOrFail();

        $this->createPlayOffResult($games, $quater_final);
    }

    /**
     *
     */
    protected function generateSemiFinalGames()
    {
        $semi_final = PlayOffStage::where('name', PlayOffStage::SEMIFINAL)->firstOrFail();
        $quater_final = PlayOffStage::where('name', PlayOffStage::QUARTERFINAL)->firstOrFail();
        $team_ids = $this->resultService->getTeamIdsWithFilter([
            'playable_type' => PlayOffStage::class,
            'playable_id' => $quater_final->id,
            'goal' => 1
        ]);

        $games = $this->generatorFactory->setGenerator(GeneratorFactory::SEMIFINAL)
            ->generate(collect($team_ids));

        $this->createPlayOffResult($games, $semi_final);
    }

    /**
     *
     */
    protected function generateGamesForThirdPlace()
    {
        $thirdPlace = PlayOffStage::where('name', PlayOffStage::THIRDPLACE)->firstOrFail();
        $semi_final = PlayOffStage::where('name', PlayOffStage::SEMIFINAL)->firstOrFail();

        $team_ids = $this->resultService->getTeamIdsWithFilter([
            'playable_type' => PlayOffStage::class,
            'playable_id' => $semi_final->id,
            'goal' => 0
        ]);

        $games = $this->generatorFactory->setGenerator(GeneratorFactory::SEMIFINAL)
            ->generate(collect($team_ids));

        $this->createPlayOffResult($games, $thirdPlace);
    }

    /**
     *
     */
    protected function generateFinalGames()
    {
        $final = PlayOffStage::where('name', PlayOffStage::FINAL)->firstOrFail();
        $semi_final = PlayOffStage::where('name', PlayOffStage::SEMIFINAL)->firstOrFail();

        $team_ids = $this->resultService->getTeamIdsWithFilter([
            'playable_type' => PlayOffStage::class,
            'playable_id' => $semi_final->id,
            'goal' => 1
        ]);

        $games = $this->generatorFactory->setGenerator(GeneratorFactory::SEMIFINAL)
            ->generate(collect($team_ids));

        $this->createPlayOffResult($games, $final);
    }

    /**
     * @param array $games
     * @param PlayableInterface $playable
     */
    protected function createPlayOffResult(array $games, PlayableInterface $playable)
    {
        foreach ($games as $game_id => $teams) {
            foreach ($teams as $team_id => $goal) {
                $this->resultService->create([
                    'playable_id' => $playable->id,
                    'playable_type' => get_class($playable),
                    'game_id' => $game_id,
                    'team_id' => $team_id,
                    'goal' => $goal
                ]);
            }
        }
    }

    /**
     *
     */
    protected function clearTables()
    {
        Team::truncate();
        Game::truncate();
        Result::truncate();
    }

}
