<?php

namespace App\Services;

use App\Models\Game;

class GameService
{
    public function createGameAndGetId()
    {
        return factory(Game::class)->create()->id;
    }
}
