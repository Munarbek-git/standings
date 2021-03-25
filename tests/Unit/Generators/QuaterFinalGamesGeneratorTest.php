<?php

namespace Tests\Unit\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Generators\QuaterFinalGamesGenerator;
use App\Services\GameService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class QuaterFinalGamesGeneratorTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function should_generate_right_games_for_quater_final()
    {
        $team_ids[] = collect(range(1,4));
        $team_ids[] = collect(range(5,8));

        $gameService = $this->getMockBuilder(GameService::class)
            ->getMock();
        $gameService->expects($this->any())
            ->method('createGameAndGetId')
            ->willReturnCallback(function (){
                return random_int(1000000000, 9999999999);
            });

        $generator = new QuaterFinalGamesGenerator($gameService);
        $results = $generator->generate(collect($team_ids));
        $i = 0;
        $j = $team_ids[1]->count() - 1;
        foreach ($results as $teams) {
            $this->assertTrue(array_key_exists($team_ids[0][$i], $teams));
            $this->assertTrue(array_key_exists($team_ids[1][$j - $i], $teams));
            $i++;
        }
    }

    /**
     * @test
     */
    public function should_throw_empty_teams_exception_quater_final()
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

        $generator = new QuaterFinalGamesGenerator($gameService);
        $generator->generate($teams);
    }
}
