@extends('layout')

@section('title')
    Réservation du court {{ $court }}
@stop

@section('content')
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            @include('playerReservation.form')
        </div>
    </div>
@stop