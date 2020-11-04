@extends('layouts.app')

@section('title', 'Logs')

@section('content')
    <div class="container">
        <div class="panel">
            <div class="panel-header">
                <i class="fas fa-archive color-accent header-icon"></i> Bot {{ $bot->formattedId() }} Logs
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Message</th>
                        <th>Notified On</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_on->format('M d, Y h:ia') }}</td>
                        <td>{!! $log->message !!}</td>
                        <td>
                            @if($log->user_notified_on)
                                {{ $log->user_notified_on->format('M d, Y h:ia') }}
                            @else
                                <i>Not sent</i>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty text-center">
                        <td colspan="100"><i>No logs found</i></td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                <div class="pagination">
                    @if($logs->previousPageUrl() != "")
                        <a href="{{ $logs->previousPageUrl() }}" class="btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @if($logs->nextPageUrl() != "")
                        <a href="{{ $logs->nextPageUrl() }}" class="btn next"><i class="fas fa-chevron-right"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection