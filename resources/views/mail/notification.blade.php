@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('img/fulcrum.png') }}" width="150" data-auto-embed="attachment">
        @endcomponent
    @endslot

<p><b>Message from trading bot #{{ $trade_bot_id }}</b></p>
<p>{{ $message }}</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} fulcrum. All rights reserved.
        @endcomponent
    @endslot
@endcomponent