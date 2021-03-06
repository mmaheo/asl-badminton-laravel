@extends('layout')

@section('title')
    Disponibilités
@stop

@section('content')


    <div class="row">
      {!! Form::open(['route' => ['availability.index', $formule], 'class' => 'form-horizontal']) !!}
        <div class="col-md-12">
            @if((count($timeSlots) > 0 || count($opponents) > 0) && $lastDayMonth)
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h1 class="text-center">
                            Vos disponibilités et celles de vos adversaires
                        </h1>
                        <a href="/availability/index/all" class="btn btn-default   " style="  }}">All <span class="fa fa-bookmark"></span></a>
                        <a href="/availability/index/simple" class="btn btn-warning   " style="  }}">Simple <span class="fa fa-bookmark"></span></a>
                        <a href="/availability/index/double" class="btn btn-info   " style="  }}">Double <span class="fa fa-bookmark"></span></a>
                        <a href="/availability/index/mixte" class="btn btn-danger   " style="  }}">Mixte <span class="fa fa-bookmark"></span></a>
                    </div>
                    <div class="ibox-content">
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                @foreach($allDays as $index => $day)
                                  @if ($index == 0 || $day->dayOfWeek == 1)
                                    <thead>
                                      <tr>
                                          <th class="text-center" style="padding:3px">Jour</th>
                                          <th class="text-center" style="padding:3px">Créneaux</th>
                                          @foreach($opponents as $opponent)
                                              <th class="text-center" style="padding:3px">{{ $opponent }}</th>
                                          @endforeach
                                      </tr>
                                    </thead>
                                  @endif
                                    <tr class="text-center">
                                        <td rowspan="{{ count($timeSlots) }}" style="padding:3px;background: #fbfcfc;" id="{{
                                        $day->format('Y-m-d') }}"
                                            class="{{ $day->format('Y-m-d') == \Carbon\Carbon::today()->format('Y-m-d') ? 'today' : '' }}">
                                            {!! $day->format('Y-m-d') == \Carbon\Carbon::today()->format('Y-m-d') ? ucfirst($day->format('l j F Y')) . '<br>Aujourd\'hui' : ucfirst($day->format('l j F Y')) !!}
                                        </td>
                                        <td style="padding:3px">
                                            {{ $timeSlots[0] }}
                                        </td>
                                        @foreach($opponents as $index => $opponent)
                                            <td style="padding:3px">
                                                @if($reservations[$day->format('Y-m-d')][$timeSlots[0]->id][$opponent]['type'] == 1)
                                                  @if($index == $userId)
                                                       <div class="form-group">
                                                         <span class="btn btn-primary btn-rounded btn-sm">{!! Form::checkbox($index ."__" . $day->format('Y-m-d') ."__".$timeSlots[0]->id, 1, true) !!}</span>
                                                      </div>
                                                  @else
                                                      <span class="btn btn-primary btn-rounded btn-sm">Dispo</span>
                                                  @endif
                                                @else
                                                  @if($index == $userId)
                                                       <div class="form-group">
                                                         <span class="btn btn-danger btn-sm">{!! Form::checkbox($index ."__" . $day->format('Y-m-d') ."__".$timeSlots[0]->id, 1, false) !!}</span>
                                                      </div>
                                                  @else
                                                      <span class="btn btn-danger btn-sm">Non</span>
                                                  @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                    @if(count($timeSlots) > 1)
                                        @foreach($timeSlots as $timeSlot)
                                            @if($timeSlot != $timeSlots[0])
                                                <tr class="text-center">
                                                    <td style="padding:3px">{{ $timeSlot }}</td>
                                                    @foreach($opponents as $index => $opponent)
                                                        <td style="padding:3px">
                                                            @if($reservations[$day->format('Y-m-d')][$timeSlot->id][$opponent]['type'] == 1)
                                                                @if($index == $userId)
                                                                    <div class="form-group">
                                                                      <span class="btn btn-primary btn-rounded btn-sm">{!! Form::checkbox($index ."__" . $day->format('Y-m-d') ."__". $timeSlot->id, 1, true) !!}</span>
                                                                   </div>
                                                               @else
                                                                   <span class="btn btn-primary btn-rounded btn-sm">Dispo</span>
                                                               @endif
                                                            @else
                                                                @if($index == $userId)
                                                                    <div class="form-group">
                                                                      <span class="btn btn-danger btn-sm">{!! Form::checkbox($index ."__" . $day->format('Y-m-d') ."__". $timeSlot->id, 1, false) !!}</span>
                                                                   </div>
                                                               @else
                                                                   <span class="btn btn-danger btn-sm">Non</span>
                                                               @endif
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
        <div class="form-group text-center">
            {!! Form::submit('Mise à jour de vos dispos', ['class' => 'btn btn-primary']) !!}
        </div>
      {!! Form::close() !!}
    </div>

@stop
