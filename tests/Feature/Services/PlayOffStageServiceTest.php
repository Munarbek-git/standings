<?php

namespace Tests\Feature\Services;

use App\Models\Division;
use App\Models\PlayOffStage;
use App\Services\DivisionService;
use App\Services\PlayOffStageService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayOffStageServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_get_all_playoff_stages_from_base()
    {
        $stages = factory(PlayOffStage::class, 4)->create();

        $service = new PlayOffStageService(new PlayOffStage());

        $_stages = $service->all();

        $this->assertEquals($_stages->count(), $stages->count());
    }
}
