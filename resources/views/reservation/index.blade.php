@extends('layout')

@section('title')
    Réservations
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <button type="button" class="btn btn-warning btn-outline dim" onclick="location.href='{{ route('sportHall.index') }}'">
                    Qui est disponible pour du jeu libre ?
                </button>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            @if((count($timeSlots) > 0 || count($courts) > 0) && $lastDayMonth)
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h1 class="text-center">
                            Réserver un court
                        </h1>
                    </div>
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-md-6 text-center text-navy">
                                <h2>Encore <span style="font-weight: bold;">{{ $courtSimpleAvailable }} créneaux</span> de simple libres</h2>
                                <h3>il reste {{ $nbSimpleMen + $nbSimpleWomen}} simples à jouer dont {{ $nbSimpleBooked }} déjà réservés</h3>
                                @if($nbSimpleMatchs != 0)
                                <h3>({{ $nbSimpleMatchs }} matchs ne sont pas encore déterminés)</h3>
                                @endif
                            </div>
                            <div class="col-md-6 text-center text-info">
                                <h2>Encore <span style="font-weight: bold;">{{ $courtDoubleAvailable }} créneaux</span> de double libres</h2>
                                <h3>il reste {{ $nbDoubleMen + $nbDoubleWomen + $nbMixte }} doubles à jouer dont {{ $nbDoubleBooked }} déjà réservés</h3>
                                @if($nbDoubleMatchs + $nbMixteMatchs!= 0)
                                <h3>({{ $nbDoubleMatchs + $nbMixteMatchs }} matchs ne sont pas encore déterminés)</h3>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped reservation">
                                <thead>
                                <tr>
                                    <th rowspan="{{ count($timeSlots) }}" class="text-center">Jour</th>
                                    <th class="text-center">Créneaux</th>
                                    @foreach($courts as $court)
                                        <th class="text-center">{{ $court }}</th>
                                    @endforeach
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($allDays as $day)
                                    <tr class="text-center">
                                        <td rowspan="{{ count($timeSlots) }}" style="background: #fbfcfc;" id="{{
                                        $day->format('Y-m-d') }}"
                                            class="{{ $day->format('Y-m-d') == \Carbon\Carbon::today()->format('Y-m-d') ? 'today' : '' }}">
                                            {!! $day->format('Y-m-d') == \Carbon\Carbon::today()->format('Y-m-d') ? ucfirst($day->format('l j F Y')) . '<br>Aujourd\'hui' : ucfirst($day->format('l j F Y')) !!}
                                        </td>
                                        <td>
                                            {{ $timeSlots[0] }}
                                        </td>
                                        @foreach($courts as $court)
                                            <td>
                                                @if($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] == 'simple' || $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] == 'double')
                                                    <div class="{{ $reservations[$day->format('Y-m-d')
                                                    ][$timeSlots[0]->id][$court->id]['owner'] ? 'text-danger' : "" }}">
                                                        {!! $reservations[$day->format('Y-m-d')
                                                        ][$timeSlots[0]->id][$court->id]['first_team'] !!}
                                                        <br> <span class="text-danger font-bold">VS</span> <br>
                                                        {!! $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['second_team'] !!}
                                                    </div>
                                                @elseif($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] == 'free')
                                                    <a href="{{ route('playerReservation.create', [$reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['day'], $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['court_id'], $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['timeSlot_id']]) }}"
                                                       class="text-resa">Réserver</a>
                                                @elseif($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] == 'admin')
                                                    <button type="button" class="btn btn-danger"
                                                            data-toggle="modal" data-target="#myModal">
                                                        {{ $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['name'] }}
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                                         aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"
                                                                        id="myModalLabel">{{ $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['name'] }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['content'] == null || $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['content'] == "")
                                                                        <p class="text-danger">Pas de
                                                                            renseignements
                                                                            supplémentaire</p>
                                                                    @else
                                                                        <p>
                                                                            {{ $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['content'] }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn
                                                                                btn-default"
                                                                            data-dismiss="modal">Fermer
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['user_id'] != null && ($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['user_id'] == $auth->id || $auth->hasRole('admin')) && $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] != 'admin')
                                                    <p>
                                                        <a href="{{ route('playerReservation.delete', $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['reservation_id']) }}"
                                                           class="text-danger"><span class="fa
                                                    fa-times"></span></a></p>
                                                @elseif($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['type'] == 'admin')
                                                    <p>
                                                        <a href="{{ route('adminReservation.delete', $reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$court->id]['reservation_id']) }}"
                                                           class="text-danger"><span class="fa
                                                    fa-times"></span></a></p>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                    @if(count($timeSlots) > 1)
                                        @foreach($timeSlots as $timeSlot)
                                            @if($timeSlot != $timeSlots[0])
                                                <tr class="text-center">
                                                    <td>{{ $timeSlot }}</td>
                                                    @foreach($courts as $court)
                                                        <td>
                                                            @if($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] == 'simple' || $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] == 'double')
                                                                <div class="{{ $reservations[$day->format('Y-m-d')
                                                                ][$timeSlot->id][$court->id]['owner'] ? "text-danger" : "" }}">
                                                                {!! $reservations[$day->format('Y-m-d')
                                                                ][$timeSlot->id][$court->id]['first_team'] !!} <br>
                                                                <span class="text-danger font-bold">VS</span> <br> {!!
                                                                $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['second_team'] !!}
                                                                </div>
                                                            @elseif($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] == 'free')
                                                                <a href="{{ route('playerReservation.create', [$reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['day'], $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['court_id'], $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['timeSlot_id']]) }}"
                                                                   class="text-resa">Réserver</a>
                                                            @elseif($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] == 'admin')
                                                                <button type="button" class="btn btn-danger"
                                                                        data-toggle="modal" data-target="#myModal">
                                                                    {{ $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['name'] }}
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="myModal" tabindex="-1"
                                                                     role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-label="Close"><span
                                                                                            aria-hidden="true">&times;</span>
                                                                                </button>
                                                                                <h4 class="modal-title"
                                                                                    id="myModalLabel">{{ $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['name'] }}</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                @if($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['content'] == null || $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['content'] == "")
                                                                                    <p class="text-danger">Pas de
                                                                                        renseignements
                                                                                        supplémentaire</p>
                                                                                @else
                                                                                    <p>
                                                                                        {{ $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['content'] }}
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn
                                                                                btn-default"
                                                                                        data-dismiss="modal">Fermer
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @endif
                                                            @if($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['user_id'] != null && ($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['user_id'] == $auth->id || $auth->hasRole('admin')) && $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] != 'admin')
                                                                <p>
                                                                    <a href="{{ route('playerReservation.delete', $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['reservation_id']) }}"
                                                                       class="text-danger"><span
                                                                                class="fa fa-times"></span></a></p>
                                                            @elseif($reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['type'] == 'admin')
                                                                <p>
                                                                    <a href="{{ route('adminReservation.delete', $reservations[$day->format('Y-m-d')][$timeSlot->id][$court->id]['reservation_id']) }}"
                                                                       class="text-danger"><span
                                                                                class="fa fa-times"></span></a></p>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <h1 class="text-danger text-center">
                    Pas de réservation disponible pour le moment
                </h1>
            @endif
        </div>
    </div>

@stop
