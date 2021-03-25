<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Турнирная таблица</title>
</head>
<body>
<div>
    <div class="container">
        <div class="row">
            <div class="col-lg" style="margin-top: 40px;">
                <a href="{{ route('home.generate') }}">
                    <button type="button" class="btn btn-primary">Сгенерировать данные</button>
                </a>
            </div>
        </div>
        <div style="margin-top: 40px;">
            @foreach($division_results as $division_id => $games)
                <h1>{{ $divisions[$division_id] }}</h1>
                <div class="row">
                    <div class="col" style="border: 1px solid black">Teams</div>
                    @foreach($teams as $second_team_id => $second_team)
                        @if(array_key_exists($second_team_id, $games))
                            <div class="col" style="border: 1px solid black">{{ $second_team['name'] }}</div>
                        @endif
                    @endforeach
                    <div class="col" style="border: 1px solid black">Score</div>
                </div>
                @foreach($teams as $first_team_id => $first_team)
                    @if(array_key_exists($first_team_id, $games))
                        <div class="row" >
                            <div class="col" style="border: 1px solid black">{{ $first_team['name'] }}</div>
                            @foreach($teams as $second_team_id => $second_team['name'])
                                @if(array_key_exists($second_team_id, $games))
                                    @if(isset($games[$first_team_id][$second_team_id]))
                                        <div class="col" style="border: 1px solid black">
                                            {{ implode(':', $games[$first_team_id][$second_team_id]) }}
                                        </div>
                                        @if($games[$first_team_id][$second_team_id][0])
                                            @php
                                                $first_team['score'] = $first_team['score'] + 1;
                                            @endphp
                                        @endif
                                    @else
                                        <div class="col" style="border: 1px solid black; background: grey;">
                                        </div>
                                    @endif

                                @endif
                            @endforeach
                            <div class="col" style="border: 1px solid black">
                                {{ $first_team['score']  }}
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach

        </div>
        <div style="margin: 40px 0;">
            @foreach($play_off_results as $play_off_stage_id => $results)
                <div class="row">
                    <div><h1>{{ $play_off_stages[$play_off_stage_id] }}</h1></div>
                    @foreach($results as $result)
                        <div class="col" style="border: 1px solid black; text-align: center">
                            <div>{{ $teams[$result['first_team_id']]['name'] }}</div>
                            <div>{{ $teams[$result['seconds_team_id']]['name'] }}</div>
                            <hr>
                            <div>{{ $result['match_result'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
