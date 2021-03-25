<?php

namespace Tests\Unit\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Generators\DivisionGamesGenerator;
use App\Models\Division;
use App\Models\Team;
use App\Services\GameService;
use Tests\TestCase;

class DivisionGamesGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function should_generate_right_count_games_for_divisions()
    {
        $teams = factory(Team::class, 16)->make();
        $teams = $teams->map(function ($team) {
            return $team->id;
        });

        $gameService = $this->getMockBuilder(GameService::class)
                ->getMock();
        $gameService->expects($this->any())
                ->method('createGameAndGetId')
                ->willReturnCallback(function (){
                    return random_int(1000000000, 9999999999);
                });

        $generator = new DivisionGamesGenerator($gameService);
        $results = $generator->generate($teams);

        $this->assertEquals(Division::COUNTOFDIVISIONS, count($results));
        foreach ($results as $division_games) {
            $this->assertEquals(28, count($division_games));
        }
    }

    /**
     * @test
     */
    public function should_throw_empty_teams_exception_divisions()
    {
        $teams = collect([]);

        $gameService = $this->getMockBuilder(GameService::class)
            ->getMock();
        $gameService->expects($this->any())
            ->method('createGameAndGetId')
            ->willReturnCallback(function (){
                return random_int(1000000000, 9999999999);
            });

        $this->expectException(EmptyTeamsCollectionException::class);

        $generator = new DivisionGamesGenerator($gameService);
        $generator->generate($teams);
    }
}
