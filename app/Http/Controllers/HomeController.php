<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Game;
use App\Models\Team;
use App\Services\DivisionService;
use App\Services\GeneratorService;
use App\Services\PlayOffStageService;
use App\Services\ResultService;
use App\Services\TeamService;

class HomeController extends Controller
{
    /**
     * @var GeneratorService
     */
    private $generatorService;
    /**
     * @var TeamService
     */
    private $teamService;
    /**
     * @var ResultService
     */
    private $resultService;
    /**
     * @var DivisionService
     */
    private $divisionService;
    /**
     * @var PlayOffStageService
     */
    private $offStageService;

    public function __construct(
        GeneratorService $generatorService,
        TeamService $teamService,
        ResultService $resultService,
        DivisionService $divisionService,
        PlayOffStageService $offStageService
    )
    {
        $this->generatorService = $generatorService;
        $this->teamService = $teamService;
        $this->resultService = $resultService;
        $this->divisionService = $divisionService;
        $this->offStageService = $offStageService;
    }

    public function index()
    {
        $teams = $this->teamService->getTeamsArray();
        $divisions = $this->divisionService->getDivisionArray();
        $play_off_stages = $this->offStageService->getStageArray();
        $division_results = $this->resultService->getFormatedDivisionsResults();
        $play_off_results = $this->resultService->getPlayOffResults();

        return view('home.index',
            [
                'divisions' => $divisions,
                'teams' => $teams,
                'division_results' => $division_results,
                'play_off_results' => $play_off_results,
                'play_off_stages' => $play_off_stages
            ]);
    }

    public function generate()
    {
        $this->generatorService->generateData();

        return redirect()->route('home.index');
    }
}
