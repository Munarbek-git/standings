<?php

namespace Tests\Unit\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Generators\SemiFinalGamesGenerator;
use App\Services\GameService;
use PHPUnit\Framework\TestCase;

class SemiFinalGamesGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function should_generate_right_count_games_for_semi_final()
    {
        $team_ids = collect(range(1, 4));

        $gameService = $this->getMockBuilder(GameService::class)
            ->getMock();
        $gameService->expects($this->any())
            ->method('createGameAndGetId')
            ->willReturnCallback(function (){
                return random_int(1000000000, 9999999999);
            });

        $generator = new SemiFinalGamesGenerator($gameService);
        $results = $generator->generate($team_ids);

        $this->assertEquals(2, count($results));
        $i = 0;
        foreach ($results as $teams) {
            $this->assertTrue(array_key_exists($team_ids[$i], $teams));
            $this->assertTrue(array_key_exists($team_ids[$i + 1], $teams));
            $i = $i + 2;
        }
    }

    /**
     * @test
     */
    public function should_throw_empty_teams_exception_semi_final()
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

        $generator = new SemiFinalGamesGenerator($gameService);
        $generator->generate($teams);
    }
}
