<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'formula',
        'ce_state',
        'gbc_state',
        'simple',
        'double',
        'mixte',
        'corpo_man',
        'corpo_woman',
        'corpo_mixte',
        't_shirt',
        'user_id',
        'season_id',
    ];

    protected $casts = [
        'simple'      => 'boolean',
        'double'      => 'boolean',
        'mixte'       => 'boolean',
        'corpo_man'   => 'boolean',
        'corpo_woman' => 'boolean',
        'corpo_mixte' => 'boolean',
        't_shirt'     => 'boolean',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function __toString()
    {
        return $this->user->__toString();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    public function teamsPlayerOne()
    {
        return $this->hasMany('App\Team', 'player_one');
    }

    public function teamsPlayerTwo()
    {
        return $this->hasMany('App\Team', 'player_two');
    }

    /******************/
    /*      Has       */
    /******************/

    public function hasSimple($simple)
    {
        return $this->simple === $simple;
    }

    public function hasDouble($double)
    {
        return $this->double === $double;
    }

    public function hasMixte($mixte)
    {
        return $this->mixte === $mixte;
    }

    public function hasCeState($ce_state)
    {
        return $this->ce_state === $ce_state;
    }

    public function hasGbcState($gbc_state)
    {
        return $this->gbc_state === $gbc_state;
    }

    public function hasCorpoMan($corpo_man)
    {
        return $this->corpo_man === $corpo_man;
    }

    public function hasCorpoWoman($corpo_woman)
    {
        return $this->corpo_woman === $corpo_woman;
    }

    public function hasCorpoMixte($corpo_mixte)
    {
        return $this->corpo_mixte === $corpo_mixte;
    }

    public function hasTShirt($t_shirt)
    {
        return $this->t_shirt === $t_shirt;
    }

    public function hasFormula($formula)
    {
        return $this->formula === $formula;
    }



    /******************/
    /*      Scope     */
    /******************/

    public function scopeWithSeason($query, $season_id)
    {
        $query->where('season_id', $season_id);
    }

    public function scopeWithUser($query, $user_id)
    {
        $query->where('user_id', $user_id);
    }

    public function scopeOrderByForname($query)
    {
        $query->orderBy('users.forname', 'asc');
    }

    public function scopePlayer($query, $type, $activeSeason, $auth)
    {
        if($type === 'double')
        {
            $query->select('users.name', 'users.forname', 'players.*')
                ->with('user')
                ->join('users', 'users.id', '=', 'players.user_id')
                ->withSeason($activeSeason->id)
                ->where('users.id', '!=', $auth->id)
                ->where('users.gender', $auth->gender == 'man' ? 'man' : 'woman')
                ->where('players.' . $type, true)
                ->orderByForname();
        }
        elseif($type === 'mixte')
        {
            $query->select('users.name', 'users.forname', 'players.*')
                ->with('user')
                ->join('users', 'users.id', '=', 'players.user_id')
                ->withSeason($activeSeason->id)
                ->where('users.id', '!=', $auth->id)
                ->where('users.gender', $auth->gender == 'man' ? 'woman' : 'man')
                ->where('players.' . $type, true)
                ->orderByForname();
        }
    }

    /******************/
    /*    Functions   */
    /******************/

    public function getTotalPrice($setting)
    {
        $totalPrice = $this->totalPrice($setting);

        return $totalPrice['formula'] + $totalPrice['t_shirt'];
    }

    public function totalPrice(Setting $setting)
    {
        $price['t_shirt'] = 0;
        $price['formula'] = 0;

        //calculate t-shirt but not corpo and competition player
        if ($this->hasTShirt(true) && ! $this->hasFormula('corpo') && ! $this->hasFormula('competition'))
        {
            $price['t_shirt'] += $setting->t_shirt_price;
        }

        //calculate leisure formula
        if ($this->hasFormula('leisure'))
        {
            $price['formula'] += $this->user->hasLectraRelation('external') ? $setting->leisure_external_price : $setting->leisure_price;
        }

        //calculate fun formula
        elseif ($this->hasFormula('fun'))
        {
            $price['formula'] += $this->user->hasLectraRelation('external') ? $setting->fun_external_price : $setting->fun_price;
        }

        //calculate performance formula
        elseif ($this->hasFormula('performance'))
        {
            $price['formula'] += $this->user->hasLectraRelation('external') ? $setting->performance_external_price : $setting->performance_price;
        }

        //calculate corpo formula
        elseif ($this->hasFormula('corpo'))
        {
            $price['formula'] += $this->user->hasLectraRelation('external') ? $setting->corpo_external_price : $setting->corpo_price;
        }

        //calculate competition formula
        elseif ($this->hasFormula('competition'))
        {
            $price['formula'] += $this->user->hasLectraRelation('external') ? $setting->competition_external_price : $setting->competition_price;
        }

        return $price;
    }

}