@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('img/logo-dark.png') }}" width="100" data-auto-embed="attachment">
        @endcomponent
    @endslot

<p style="font-size: 15px">{{ $notification->message }}</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} fulcrum. All rights reserved.
        @endcomponent
    @endslot
@endcomponent