<?php


namespace App\Services;


use App\Models\Division;
use App\Models\PlayOffStage;
use App\Models\Result;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResultService
{
    /**
     * @var Result
     */
    private $result;

    /**
     * ResultService constructor.
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getFormatedDivisionsResults()
    {
        $results = $this->getResults(Division::class);

        $array = [];

        foreach ($results as $result) {
            $array[$result->playable_id][$result->first_team_id][$result->seconds_team_id][0] = $result->first_team_goal;
            $array[$result->playable_id][$result->first_team_id][$result->seconds_team_id][1] = $result->second_team_goal;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function getPlayOffResults()
    {
        $results = $this->deleteDuplicateResults($this->getResults(PlayOffStage::class));

        $array = [];

        foreach ($results as $key => $result) {
            $array[$result->playable_id][$key]['first_team_id'] = $result->first_team_id;
            $array[$result->playable_id][$key]['seconds_team_id'] = $result->seconds_team_id;
            $array[$result->playable_id][$key]['match_result'] = $result->first_team_goal.":".$result->second_team_goal;
        }

        return $array;
    }

    /**
     * @param Collection $results
     * @return array
     */
    protected function deleteDuplicateResults(Collection $results): array
    {
        $teams_id = [];
        $new_results = [];

        foreach ($results as $result) {
            $teams_id[$result->playable_id][] = $result->first_team_id;
            if (!in_array($result->seconds_team_id, $teams_id[$result->playable_id])) {
                $new_results[] = $result;
            }
        }

        return $new_results;
    }

    /**
     * @param string $playble_type
     * @return \Illuminate\Support\Collection
     */
    protected function getResults(string $playble_type)
    {
        return DB::table('results as a')
            ->select(DB::raw('a.playable_id, `a`.`team_id` as first_team_id, `b`.`team_id` as seconds_team_id,  `a`.`goal` as first_team_goal, `b`.`goal` as second_team_goal'))
            ->leftJoin('results as b', function($join) {
                $join->on('a.game_id', '=', 'b.game_id');
                $join->on('a.team_id', '!=', 'b.team_id');
            })
            ->where('a.playable_type', $playble_type)
            ->orderBy('a.playable_id')
            ->orderBy('a.game_id')
            ->get();
    }

    /**
     * @param int $division_id
     * @return mixed
     */
    public function getWinnerTeamsForDivision(int $division_id)
    {
        return $this->result->select(DB::raw('team_id, sum(goal) as score'))
            ->where(['playable_type' => Division::class, 'playable_id' => $division_id])
            ->groupBy('team_id')
            ->orderBy('score', 'desc')
            ->take(4)
            ->get();
    }

    /**
     * @param array $where
     * @return mixed
     */
    public function getTeamIdsWithFilter(array $where)
    {
        $results =  $this->result->select('team_id')
            ->where($where)
            ->orderBy('game_id')
            ->get();

        return $results->map(function ($result) {
            return $result->team_id;
        });
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return $this->result->create($params);
    }
}
