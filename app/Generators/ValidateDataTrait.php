<?php

namespace App\Generators;

use App\Exceptions\EmptyTeamsCollectionException;
use App\Exceptions\GenerateResultsErrorException;
use Illuminate\Support\Collection;

trait ValidateDataTrait
{
    public function checkCountTeam(Collection $teams)
    {
        if (!$teams->count()) {
            throw new EmptyTeamsCollectionException('Вы передали пустой список команд.');
        }
    }

    public function checkResultData(array $data)
    {
        if (!count($data)) {
            throw new GenerateResultsErrorException('Не удалось сгенерировать данные');
        }
    }
}
