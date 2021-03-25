<?php


namespace App\Services;

use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;

class DivisionService
{
    /**
     * @var Division
     */
    private $division;

    /**
     * @param Division $division
     */
    public function __construct(Division $division)
    {
        $this->division = $division;
    }

    /**
     * @return array
     */
    public function getDivisionArray():array
    {
        $divisions = $this->all();
        $array = [];
        foreach ($divisions as $division) {
            $array[$division->id] = $division->name;
        }

        return $array;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->division->get();
    }
}
