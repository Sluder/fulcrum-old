@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('img/fulcrum.png') }}" width="100" data-auto-embed="attachment">
        @endcomponent
    @endslot

    @if($decision === 'approved')
<p>Hello {{ $name }},</p>
<p>You were approved for fulcrum!</p>
<p>Before you begin trading, a few things for setup are required. Please use the email you requested access with to login and setup your profile.</p>

<table width="100%">
    <tr>
        <td style="text-align: center;">
            <a href="{{ route('login') }}" class="button" target="_blank" style="background-color: #3c3c3c;padding: 10px 35px;font-size: 16px;">
                Login
            </a>
        </td>
    </tr>
</table>
    @else
<p>Hello {{ $name }},</p>
<p>You were denied access to fulcrum.</p>
    @endif

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} fulcrum. All rights reserved.
        @endcomponent
    @endslot
@endcomponent