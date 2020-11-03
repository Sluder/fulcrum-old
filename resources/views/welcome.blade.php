@extends('layouts.app')

@section('title', 'Welcome')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')
    <div class="header text-center">
        <i class="fab fa-fulcrum color-accent"></i>
        <h2>{{ env('APP_NAME') }}</h2>
        <h3>Automatic Robinhood Trading Bots</h3>
        <a href="#request-access" class="btn">Request Access</a>
    </div>

    <div class="particle-overlay">
        <div id="particles-js"></div>
    </div>

    <div class="container info-boxes">
        <h3 class="text-center">All-In-One Bot Trading</h3>
        <div class="divider"></div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="fas fa-robot color-accent"></i>
                    <p class="info-header">Automated Trading</p>
                    <p>Let bot algorithms find the buy & sell signals using built-in indicators or custom strategies</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="fas fa-bezier-curve color-accent"></i>
                    <p class="info-header">Conditional Signals</p>
                    <p>Set multiple buy & sell conditions to ensure your bots are as accurate as possible</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="fas fa-code color-accent"></i>
                    <p class="info-header">Custom Strategies</p>
                    <p>Code your own Python strategies for more flexible signaling</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="fas fa-dollar-sign  color-accent"></i>
                    <p class="info-header">Robinhood Markets</p>
                    <p>Invest in over 5,000 different US stocks through Robinhood</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="fas fa-power-off color-accent"></i>
                    <p class="info-header">Demo Mode</p>
                    <p>Test run your strategies using simulated trading, without investing real money</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box text-center">
                    <i class="far fa-envelope-open color-accent"></i>
                    <p class="info-header">Notifications</p>
                    <p>Get notifications if any issues occur, and get ordering updates from your trading bots</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="panel" id="request-access">
            <div class="panel-header">
                <i class="fas fa-key color-accent header-icon"></i> Request Access
                <small class="text-muted">Use an email you have used with Gmail. You will use Gmail to log in</small>
            </div>

            @include('partials.message')

            <form action="{{ route('request-access') }}" method="POST">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-5">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label for="email">Email</label>
                        <input type="email" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/particles.js') }}"></script>
    <script type="text/javascript">
        particlesJS.load("particles-js", '/js/particles.json');
    </script>
@endpush