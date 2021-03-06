<div class="row">
    <div class="col-md-8">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                @if($player->exists)
                    <h1 class="text-center">Modification du joueur {{ $player }}</h1>
                @else
                    <h1 class="text-center">Inscription</h1>
                @endif
            </div>
            <div class="ibox-content">

                @if(!$player->exists)
                    {!! Form::open(['route' => ['player.store'], 'class' => 'form-horizontal']) !!}
                @else
                    {!! Form::open(['route' => ['player.update', $player->id], 'class' => 'form-horizontal']) !!}
                @endif

                @if($player->exists && $player->hasCeState('contribution_paid'))
                    <input name="formula" type="hidden" value="{{ $player->formula }}">
                    @endif
                    @if($player->exists && $player->hasCeState('contribution_paid') || $setting !== null && $setting->hasBuyTShirt(false))
                        <input name="t_shirt" type="hidden"
                               value="{{ $player->exists && $player->hasTShirt(true) ? '1' : '0' }}">
                @endif

                <p class="text-right"><i class="text-navy">* Champs obligatoires</i></p>

                <div class="form-group">
                    <div class="col-md-3">
                        {!! Form::label('formula', 'Formule :', ['class' => 'control-label']) !!}
                        <i class="text-navy">*</i>
                    </div>

                    <div class="col-md-9">
                        @if($setting->hasEnrollTournament(true))
                            {!! Form::select('formula', ['leisure' => 'Loisir', 'tournament' => 'Tournoi', 'fun' => 'Fun','performance' => 'Performance','corpo'=>'Corpo','competition' => 'Competition'],
                            $player->exists ? $player->formula : old('formula'),['class' => 'form-control', $player->exists && $player->hasCeState('contribution_paid') ? 'disabled' : 'required']) !!}
                        @else
                            {!! Form::select('formula', ['leisure' => 'Loisir', 'fun' => 'Fun','performance' => 'Performance','corpo'=>'Corpo','competition' => 'Competition'],
                            $player->exists ? $player->formula : old('formula'),['class' => 'form-control', $player->exists && $player->hasCeState('contribution_paid') ? 'disabled' : 'required']) !!}
                        @endif
                    </div>
                </div>

                <div class="form-group" id="t_shirt">
                    <div class="col-md-3">
                        {!! Form::label('t_shirt', 'T-shirt :', ['class' => 'control-label']) !!}
                        <i class="text-navy">({{ $setting->t_shirt_price }}€) *</i>
                    </div>

                    <div class="col-md-9">
                        <div class="radio-inline">
                            <label>
                                {!! Form::radio('t_shirt', '1', $player->exists ? $player->hasTShirt(true) ? true : false : false, [$player->exists && $player->hasCeState('contribution_paid') || $setting !== null && $setting->hasBuyTShirt(false) ? 'disabled' : 'required']) !!}
                                Oui
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                {!! Form::radio('t_shirt', '0', $player->exists ? $player->hasTShirt(false) ? true : false : true, [$player->exists && $player->hasCeState('contribution_paid') || $setting !== null && $setting->hasBuyTShirt(false) ? 'disabled' : 'required']) !!}
                                Non
                            </label>
                        </div>
                    </div>
                </div>

                <div class="panel panel-primary" id="championship-panel">
                    <div class="panel-heading">
                        <h3 class="text-center">
                            Championnat
                        </h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('simple', 'Simple :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('simple', '1', $player->exists ? $player->hasSimple(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('simple', '0', $player->exists ? $player->hasSimple(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('double', 'Double :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('double', '1', $player->exists ? $player->hasDouble(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('double', '0', $player->exists ? $player->hasDouble(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="double_partner">
                            <div class="col-md-3">
                                {!! Form::label('formula', 'Partenaire :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                {!! Form::select('double_partner', $listPartnerAvailable['double'], old('double_partner'), ['class' => 'chosen-select', 'style' => 'width: 100%;',
                                $player->exists && $player->hasDouble(true) ? 'required' : '']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('mixte', 'Mixte :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('mixte', '1', $player->exists ? $player->hasMixte(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('mixte', '0', $player->exists ? $player->hasMixte(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="mixte_partner">
                            <div class="col-md-3">
                                {!! Form::label('formula', 'Partenaire :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                {!! Form::select('mixte_partner', $listPartnerAvailable['mixte'], old('mixte_partner'), ['class' => 'chosen-select', 'style' => 'width: 100%;',
                                $player->exists && $player->hasMixte(true) ? 'required' : '']) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-success" id="corpo-panel">
                    <div class="panel-heading">
                        <h3 class="text-center">
                            Corpo
                        </h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('corpo_man', 'Homme :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_man', '1', $player->exists ? $player->hasCorpoMan(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_man', '0', $player->exists ? $player->hasCorpoMan(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('corpo_woman', 'Femme :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_woman', '1', $player->exists ? $player->hasCorpoWoman(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_woman', '0', $player->exists ? $player->hasCorpoWoman(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                {!! Form::label('corpo_mixte', 'Mixte :', ['class' => 'control-label']) !!}
                                <i class="text-navy">*</i>
                            </div>

                            <div class="col-md-9">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_mixte', '1', $player->exists ? $player->hasCorpoMixte(true) ? true : false : false, ['required']) !!}
                                        Oui
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio('corpo_mixte', '0', $player->exists ? $player->hasCorpoMixte(false) ? true : false : true, ['required']) !!}
                                        Non
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @if($auth->hasRole('admin'))
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="text-center">
                                Réservé aux administrateurs
                            </h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <div class="col-md-3">
                                    {!! Form::label('ce_state', 'Etat CE :', ['class' => 'control-label']) !!}
                                    <i class="text-navy">*</i>
                                </div>

                                <div class="col-md-9">
                                    {!! Form::select('ce_state', ['contribution_payable' => 'Cotisation à payer','contribution_paid' => 'Cotisation payée'],
                                    $player->exists ? $player->ce_state : old('ce_state'),['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    {!! Form::label('gbc_state', 'Etat GBC :', ['class' => 'control-label']) !!}
                                    <i class="text-navy">*</i>
                                </div>

                                <div class="col-md-9">
                                    {!! Form::select('gbc_state', ['non_applicable' => 'Non applicable','entry_must' => 'Dossier à remettre','valid' => 'Valide'],
                                    $player->exists ? $player->gbc_state : old('gbc_state'),['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

                <div class="form-group text-center">
                    @if($player->exists)
                        {!! Form::submit('Sauvegarder', ['class' => 'btn btn-primary']) !!}
                    @else
                        {!! Form::submit('S\'inscrire', ['class' => 'btn btn-primary']) !!}
                    @endif
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <div id="leisure" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule loisir</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->leisure_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership')) 
                            <h4 class="text-center"><strong>{{ $setting->leisure_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription gratuite</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                          aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="tournament" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule tournoi</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->leisure_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership'))
                            <h4 class="text-center"><strong>{{ $setting->leisure_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription gratuite</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                          aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="fun" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule fun</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->fun_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership'))
                            <h4 class="text-center"><strong>{{ $setting->fun_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription gratuite</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                          aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="performance" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule performance</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->performance_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership'))
                            <h4 class="text-center"><strong>{{ $setting->performance_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription non disponible</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-times-circle-o fa-2x text-danger"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="corpo" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule corpo</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->corpo_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership'))
                            <h4 class="text-center"><strong>{{ $setting->corpo_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription non disponible</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span> 2 inclus
                        </p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-times-circle-o fa-2x text-danger" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="competition" style="display:none">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-center">Formule competition</h3>
                    </div>
                    <div class="ibox-content">
                        @if($auth->hasLectraRelation('external') || $auth->hasLectraRelation('subcontractor'))
                            <h4 class="text-center"><strong>{{ $setting->competition_external_price }}€ + (cotisation ASL)</strong></h4>
                        @elseif(!$auth->hasLectraRelation('partnership'))
                            <h4 class="text-center"><strong>{{ $setting->competition_price }}€ + (cotisation ASL)</strong></h4>
                        @else
                            <h4 class="text-center"><strong>incription non disponible</strong></h4>
                        @endif

                        <p><strong>Jeu libre : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                              aria-hidden="true"></span></p>

                        <p><strong>Réservation de cours : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Championnat : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                         aria-hidden="true"></span></p>

                        <p><strong>Tournoi play-off : </strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                     aria-hidden="true"></span></p>

                        <p><strong>Entrainement : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span></p>

                        <p><strong>Corpo : </strong></strong><span class="fa fa-check-circle-o fa-2x text-success"
                                                                   aria-hidden="true"></span></p>

                        <p><strong>Soirée double : </strong><i class="text-info">Incluse</i></p>

                        <p><strong>Tournoi FFBAD : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span> 5 inclus
                        </p>

                        <p><strong>Entrainement compétition : </strong></strong><span
                                    class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></span></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@section('javascript')
    <script>

        function showPartner() {
            if ($('input[type=radio][name=double]:checked').attr('value') == 1) {
                $('#double_partner').fadeIn(200);
            }
            else {
                $('#double_partner').fadeOut(200);
            }
            if ($('input[type=radio][name=mixte]:checked').attr('value') == 1) {
                $('#mixte_partner').fadeIn(200);
            }
            else {
                $('#mixte_partner').fadeOut(200);
            }
        }

        function updateSelect() {

            var formula = $('select[name=formula]').val();

            if (formula == 'leisure') {
                $('#tournament').slideUp(400);
                $('#performance').slideUp(400);
                $('#corpo').slideUp(400);
                $('#competition').slideUp(400);
                $('#fun').slideUp(400, function () {
                    $('#leisure').slideDown(400);
                });
                $('#championship-panel').hide();

                $('#corpo-panel').hide();
                $('#t_shirt').show();
            }
            if (formula == 'tournament') {
                $('#fun').slideUp(400);
                $('#performance').slideUp(400);
                $('#corpo').slideUp(400);
                $('#competition').slideUp(400);
                $('#leisure').slideUp(400, function () {
                    $('#tournament').slideDown(400);
                });
                $('#championship-panel').show();
                $('#corpo-panel').hide();
                $('#t_shirt').show();
            }
            if (formula == 'fun') {
                $('#tournament').slideUp(400);
                $('#performance').slideUp(400);
                $('#corpo').slideUp(400);
                $('#competition').slideUp(400);
                $('#leisure').slideUp(400, function () {
                    $('#fun').slideDown(400);
                });
                $('#championship-panel').show();
                $('#corpo-panel').hide();
                $('#t_shirt').show();
            }
            if (formula == 'performance') {
                $('#tournament').slideUp(400);
                $('#fun').slideUp(400);
                $('#corpo').slideUp(400);
                $('#competition').slideUp(400);
                $('#leisure').slideUp(400, function () {
                    $('#performance').slideDown(400);
                });
                $('#championship-panel').show();
                $('#corpo-panel').hide();
                $('#t_shirt').show();
            }
            if (formula == 'corpo') {
                $('#tournament').slideUp(400);
                $('#performance').slideUp(400);
                $('#fun').slideUp(400);
                $('#competition').slideUp(400);
                $('#leisure').slideUp(400, function () {
                    $('#corpo').slideDown(400);
                });
                $('#championship-panel').show();
                $('#corpo-panel').show();
                $('#t_shirt').hide();
            }
            if (formula == 'competition') {
                $('#tournament').slideUp(400);
                $('#performance').slideUp(400);
                $('#corpo').slideUp(400);
                $('#fun').slideUp(400);
                $('#leisure').slideUp(400, function () {
                    $('#competition').slideDown(400);
                });
                $('#championship-panel').show();
                $('#corpo-panel').show();
                $('#t_shirt').hide();
            }
        }

        $(document).ready(function () {
            $('select[name=formula]').on('change', updateSelect);
            updateSelect();
            $('input[name=double]').on('change', showPartner);
            showPartner();
            $('input[name=mixte]').on('change', showPartner);
            showPartner();
        });
    </script>

    <script type="text/javascript">
        $(".chosen-select").chosen();
    </script>
@stop