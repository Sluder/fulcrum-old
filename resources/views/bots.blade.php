@extends('layouts.app')

@section('title', 'Bots')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('partials.message')
            </div>
        </div>

        <trade-bots-dashboard
            trade_bots="{{ json_encode($bots) }}">
        </trade-bots-dashboard>
    </div>
@endsection
