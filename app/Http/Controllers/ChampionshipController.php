<?php

namespace App\Http\Controllers;

use App\ChampionshipPool;
use App\ChampionshipRanking;
use App\Helpers;
use App\Http\Requests\ChampionshipStoreRequest;
use App\Period;
use App\Season;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Jenssegers\Date\Date;

class ChampionshipController extends Controller
{

    /**
     * @param $router
     */
    public static function routes($router)
    {
        //championship create
        $router->get('create', [
            'middleware' => 'settingExists',
            'uses'       => 'ChampionshipController@create',
            'as'         => 'championship.create',
        ]);

        //championship store
        $router->post('store', [
            'middleware' => 'settingExists',
            'uses'       => 'ChampionshipController@store',
            'as'         => 'championship.store',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $period = new Period();

        $activeSeason = Season::active()->first();

        $setting = Helpers::getInstance()->setting();

        if ($activeSeason !== null)
        {
            $lastedPeriod = Period::lasted($activeSeason->id, 'championship')->first();

            if ($lastedPeriod === null)
            {
                $teams = [];
                if ($setting->hasChampionshipSimpleWoman(true))
                {
                    $teams['simple']['man'] = $this->getSimpleTeams($activeSeason->id, 'man', true);
                    $teams['simple']['woman'] = $this->getSimpleTeams($activeSeason->id, 'woman', true);
                }
                else
                {
                    $teams['simple'] = array_merge($this->getSimpleTeams($activeSeason->id, 'man', true),
                        $this->getSimpleTeams($activeSeason->id, 'woman', true));
                    sort($teams['simple']);
                }

                if ($setting->hasChampionshipDoubleWoman(true))
                {
                    $teams['double']['man'] = $this->getDoubleTeams($activeSeason->id, 'double', 'man', true);
                    $teams['double']['woman'] = $this->getDoubleTeams($activeSeason->id, 'double', 'woman', true);
                }
                else
                {
                    $teams['double'] = array_merge($this->getDoubleTeams($activeSeason->id, 'double', 'man', true),
                        $this->getDoubleTeams($activeSeason->id, 'double', 'woman', true));

                    sort($teams['double']);
                }

                $teams['mixte'] = $this->getDoubleTeams($activeSeason->id, 'mixte', '', true);

                return view('championship.create', compact('period', 'teams', 'setting'));
            }
            else
            {
                $teams = [];
                $teams['simple'] = Team::select('users.name', 'users.forname', 'users.state', 'users.ending_holiday',
                    'users.ending_injury')
                    ->join('players', 'players.id', '=', 'teams.player_one')
                    ->join('users', 'players.user_id', '=', 'users.id')
                    ->join('championship_rankings', 'teams.id', '=', 'championship_rankings.team_id')
                    ->join('championship_pools', 'championship_rankings.championship_pool_id', '=',
                        'championship_pools.id')
                    ->where('championship_pools.id', $lastedPeriod->id)
                    ->where('teams.season_id', $activeSeason->id)
                    ->where('teams.simple_man', true)
                    ->whereNotNull('teams.player_one')
                    ->whereNull('teams.player_two')
                    ->where('teams.enable', true)
                    ->orWhere(function ($query) use ($lastedPeriod, $activeSeason)
                    {
                        $query->where('championship_pools.id', $lastedPeriod->id)
                            ->where('teams.season_id', $activeSeason->id)
                            ->where('teams.simple_woman', true)
                            ->whereNotNull('teams.player_one')
                            ->whereNull('teams.player_two')
                            ->where('teams.enable', true);
                    })
                    ->orderBy('championship_pools.number', 'asc')
                    ->orderBy('championship_rankings.rank', 'asc')
                    ->get();

                dd($teams['simple']);
            }
        }

        return redirect()->route('season.index')->with('error', "Le championnat ne peut pas être créé car il n'y a
        pas de saison active !");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipStoreRequest $request)
    {

        $season = Season::active()->first();

        $setting = Helpers::getInstance()->setting();
        $championshipSimpleWoman = $setting->hasChampionshipSimpleWoman(true) ? true : false;
        $championshipDoubleWoman = $setting->hasChampionshipDoubleWoman(true) ? true : false;

        if ($season !== null)
        {
            $period = Period::create([
                'start'     => $request->start,
                'end'       => $request->end,
                'season_id' => $season->id,
                'type'      => 'championship',
            ]);

            $pools = [];

            if ($championshipSimpleWoman)
            {
                $pools['simple_man'] = [];
                $pools['simple_woman'] = [];

                if ($request->exists('pool_number_simple_man'))
                {
                    foreach ($request->pool_number_simple_man as $team_id => $pool_number)
                    {
                        $pools['simple_man'][$pool_number][] = $team_id;
                    }
                }

                if ($request->exists('pool_number_simple_woman'))
                {
                    foreach ($request->pool_number_simple_woman as $team_id => $pool_number)
                    {
                        $pools['simple_woman'][$pool_number][] = $team_id;
                    }
                }
            }
            else
            {
                $pools['simple'] = [];
                if ($request->exists('pool_number_simple'))
                {
                    foreach ($request->pool_number_simple as $team_id => $pool_number)
                    {
                        $pools['simple'][$pool_number][] = $team_id;
                    }
                }
            }

            if ($championshipDoubleWoman)
            {
                $pools['double_man'] = [];
                $pools['double_woman'] = [];
                if ($request->exists('pool_number_double_man'))
                {
                    foreach ($request->pool_number_double_man as $team_id => $pool_number)
                    {
                        $pools['double_man'][$pool_number][] = $team_id;
                    }
                }

                if ($request->exists('pool_number_double_woman'))
                {
                    foreach ($request->pool_number_double_woman as $team_id => $pool_number)
                    {
                        $pools['double_woman'][$pool_number][] = $team_id;
                    }
                }
            }
            else
            {
                $pools['double'] = [];
                if ($request->exists('pool_number_double'))
                {
                    foreach ($request->pool_number_double as $team_id => $pool_number)
                    {
                        $pools['double'][$pool_number][] = $team_id;
                    }
                }
            }

            $pools['mixte'] = [];
            if ($request->exists('pool_number_mixte'))
            {
                foreach ($request->pool_number_mixte as $team_id => $pool_number)
                {
                    $pools['mixte'][$pool_number][] = $team_id;
                }
            }

            if($championshipSimpleWoman)
            {
                foreach (['simple_man', 'simple_woman'] as $gender)
                {
                    foreach ($pools[$gender] as $pool_number => $teams_id)
                    {
                        $pool = ChampionshipPool::create([
                            'number' => $pool_number,
                            'type'   => $gender,
                            'period_id' => $period->id,
                        ]);

                        foreach ($teams_id as $team_id)
                        {
                            ChampionshipRanking::create([
                                'match_to_play'        => count($pools[$gender][$pool_number]) - 1,
                                'team_id'              => $team_id,
                                'championship_pool_id' => $pool->id,
                            ]);
                        }
                    }
                }
            }
            else
            {
                foreach ($pools['simple'] as $pool_number => $teams_id)
                {
                    $pool = ChampionshipPool::create([
                        'number' => $pool_number,
                        'type'   => 'simple',
                        'period_id' => $period->id,
                    ]);

                    foreach ($teams_id as $team_id)
                    {
                        ChampionshipRanking::create([
                            'match_to_play'        => count($pools['simple'][$pool_number]) - 1,
                            'team_id'              => $team_id,
                            'championship_pool_id' => $pool->id,
                        ]);
                    }
                }
            }

            if($championshipDoubleWoman)
            {
                foreach (['double_man', 'double_woman'] as $gender)
                {
                    foreach ($pools[$gender] as $pool_number => $teams_id)
                    {
                        $pool = ChampionshipPool::create([
                            'number' => $pool_number,
                            'type'   => $gender,
                            'period_id' => $period->id,
                        ]);

                        foreach ($teams_id as $team_id)
                        {
                            ChampionshipRanking::create([
                                'match_to_play'        => count($pools[$gender][$pool_number]) - 1,
                                'team_id'              => $team_id,
                                'championship_pool_id' => $pool->id,
                            ]);
                        }
                    }
                }
            }
            else
            {
                foreach ($pools['double'] as $pool_number => $teams_id)
                {
                    $pool = ChampionshipPool::create([
                        'number' => $pool_number,
                        'type'   => 'double',
                        'period_id' => $period->id,
                    ]);

                    foreach ($teams_id as $team_id)
                    {
                        ChampionshipRanking::create([
                            'match_to_play'        => count($pools['double'][$pool_number]) - 1,
                            'team_id'              => $team_id,
                            'championship_pool_id' => $pool->id,
                        ]);
                    }
                }
            }

            foreach ($pools['mixte'] as $pool_number => $teams_id)
            {
                $pool = ChampionshipPool::create([
                    'number' => $pool_number,
                    'type'   => 'mixte',
                    'period_id' => $period->id,
                ]);

                foreach ($teams_id as $team_id)
                {
                    ChampionshipRanking::create([
                        'match_to_play'        => count($pools['mixte'][$pool_number]) - 1,
                        'team_id'              => $team_id,
                        'championship_pool_id' => $pool->id,
                    ]);
                }
            }

            return redirect()->route('home.index')->with('success', "Le championnat vient d'être créé !");
        }

        return redirect()->route('season.index')->with('error', "Le championnat ne peut pas être créé car il n'y a
        pas de saison active !");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getSimpleTeams($season_id, $gender, $new)
    {
        $simpleTeams = Team::select('users.name', 'users.forname', 'users.state', 'users.ending_holiday',
            'users.ending_injury', 'teams.id')
            ->join('players', 'players.id', '=', 'teams.player_one')
            ->join('users', 'players.user_id', '=', 'users.id')
            ->whereNotNull('teams.player_one')
            ->whereNull('teams.player_two')
            ->where('teams.simple_' . $gender, true)
            ->where('teams.enable', true)
            ->where('teams.season_id', $season_id)
            ->orderBy('users.forname', 'asc')
            ->orderBy('users.name', 'asc')
            ->get();

        $playersSimple = [];
        foreach ($simpleTeams as $index => $simpleTeam)
        {
            if ($new)
            {
                $playersSimple[$index]['rank'] = 'new';
            }
            $playersSimple[$index]['id'] = $simpleTeam->id;
            $playersSimple[$index]['name'] = Helpers::getInstance()->getTeamName($simpleTeam->forname,
                $simpleTeam->name);
            $playersSimple[$index]['state'] = $simpleTeam->state;
            $playersSimple[$index]['ending_holiday'] = Date::createFromFormat('Y-m-d', $simpleTeam->ending_holiday)
                ->format('l j F Y');
            $playersSimple[$index]['ending_injury'] = Date::createFromFormat('Y-m-d', $simpleTeam->ending_injury)
                ->format('l j F Y');;
        }

        return $playersSimple;
    }

    private function getDoubleTeams($season_id, $type, $gender, $new)
    {
        $doubleTeams = Team::select('userOne.name as nameOne', 'userOne.forname as fornameOne',
            'userOne.state as stateOne', 'userOne.ending_holiday as ending_holidayOne',
            'userOne.ending_injury as ending_injuryOne', 'userTwo.name as nameTwo', 'userTwo.forname as fornameTwo',
            'userTwo.state as stateTwo', 'userTwo.ending_holiday as ending_holidayTwo',
            'userTwo.ending_injury as ending_injuryTwo', 'teams.id')
            ->allDoubleOrMixteActiveTeams($type, $gender, $season_id)
            ->orderBy('userOne.forname', 'asc')
            ->orderBy('userOne.name', 'asc')
            ->get();

        $players = [];

        foreach ($doubleTeams as $index => $doubleTeam)
        {
            if ($new)
            {
                $players[$index]['rank'] = 'new';
            }

            $players[$index]['id'] = $doubleTeam->id;
            $players[$index]['name'] = Helpers::getInstance()->getTeamName($doubleTeam->fornameOne,
                $doubleTeam->nameOne, $doubleTeam->fornameTwo, $doubleTeam->nameTwo);
            $players[$index]['stateOne'] = $doubleTeam->stateOne;
            $players[$index]['stateTwo'] = $doubleTeam->stateTwo;
            $players[$index]['ending_holidayOne'] = Date::createFromFormat('Y-m-d',
                $doubleTeam->ending_holidayOne)->format('l j F Y');
            $players[$index]['ending_holidayTwo'] = Date::createFromFormat('Y-m-d',
                $doubleTeam->ending_holidayTwo)->format('l j F Y');
            $players[$index]['ending_injuryOne'] = Date::createFromFormat('Y-m-d',
                $doubleTeam->ending_injuryOne)->format('l j F Y');
            $players[$index]['ending_injuryTwo'] = Date::createFromFormat('Y-m-d',
                $doubleTeam->ending_injuryTwo)->format('l j F Y');
        }

        return $players;
    }
}
