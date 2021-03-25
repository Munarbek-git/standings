<?php

namespace Tests\Feature\Services;

use App\Models\Division;
use App\Services\DivisionService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DivisionServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_get_all_division_from_base()
    {
        $divisions = factory(Division::class, 2)->create();

        $service = new DivisionService(new Division());

        $_divisions = $service->all();

        $this->assertEquals($divisions->count(), $_divisions->count());
    }
}
