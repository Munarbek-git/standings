<?php

namespace Tests\Unit\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Generators\FinalGameGenerator;
use App\Services\GameService;
use PHPUnit\Framework\TestCase;

class FinalGameGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function should_generate_right_count_games_for_final()
    {
        $team_ids = collect(range(1, 2));

        $gameService = $this->getMockBuilder(GameService::class)
            ->getMock();
        $gameService->expects($this->any())
            ->method('createGameAndGetId')
            ->willReturnCallback(function (){
                return random_int(1000000000, 9999999999);
            });

        $generator = new FinalGameGenerator($gameService);
        $results = $generator->generate($team_ids);

        $this->assertEquals(1, count($results));
        foreach ($results as $teams) {
            $this->assertTrue(array_key_exists($team_ids[0], $teams));
            $this->assertTrue(array_key_exists($team_ids[1], $teams));
        }
    }

    /**
     * @test
     */
    public function should_throw_empty_teams_exception_final()
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

        $generator = new FinalGameGenerator($gameService);
        $generator->generate($teams);
    }
}
